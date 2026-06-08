<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use App\Models\Report;
use App\Models\Address;
use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Enums\RatingStatus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\RestaurantRating;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use Illuminate\Support\Facades\File;
use App\Http\Resources\v1\MeResource;
use App\Http\Services\ComplaintService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Requests\Api\PasswordUpdateRequest;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Http\Services\OtpService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
class MeController extends Controller
{
    use ApiResponse;
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->middleware('auth:api');
        $this->otpService = $otpService;
    }
    public function action(Request $request)
    {
        try {
            $data = new MeResource($request->user());
        } catch (\Exception $e) {
            return response()->json([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);
        }

        return $this->successResponse($data);
    }

    public function refresh()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Token not provided',
            ], 401);
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 401,
                'message' => $e->getMessage(),
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            "token_type" => "bearer",
            'expires_in' => config('jwt.ttl') * 3600000000000,
        ], 200);
    }

    public function update(Request $request)
    {
        $profile = auth()->user();

        if (blank($profile)) {
            return response()->json(['status' => 401, 'message' => 'Unauthorized access.'], 401);
        }

        $validator = new ProfileUpdateRequest($profile->id);
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors()], 422);
        }

        $firstName = '';
        $lastName = '';
        if ($request->has('name')) {
            $parts = $this->splitName($request->get('name'));
            $firstName = $parts[0];
            $lastName = $parts[1];
        }

        $newEmail = $request->get('email');
        $newPhone = $request->get('phone');

        $isSensitiveChange = ($newEmail !== $profile->email) || ($newPhone !== $profile->phone);

        if ($isSensitiveChange) {

            $tempToken = Str::uuid()->toString();

            $updateData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $newEmail,
                'phone' => $newPhone,
                'address' => $request->get('address'),
                'username' => $request->username ?? $profile->username,
                'device_id' => $request->header('X-Device-ID')
            ];

            Cache::put("profile_update_{$tempToken}", $updateData, now()->addMinutes(10));

            $tempUser = clone $profile;
            $tempUser->email = $newEmail; 
            $tempUser->phone = $newPhone; 

            $result = $this->otpService->generateAndSend(
                $tempUser,
                'profile_update', 
                $request->header('X-Device-ID'),
                $request->ip()
            );

            if (!$result['status']) {
                return response()->json(['status' => 400, 'message' => 'Failed to send OTP to new contact details.'], 400);
            }

            return response()->json([
                'status' => 202,
                'requires_otp' => true,
                'temp_token' => $tempToken,
                'message' => 'OTP sent to your new contact details. Please verify to confirm changes.'
            ], 202);
        }

        return $this->performDirectUpdate($profile, $request, $firstName, $lastName);
    }


    private function performDirectUpdate($profile, $request, $firstName, $lastName)
    {
        $profile->first_name = $firstName;
        $profile->last_name = $lastName;
        $profile->address = $request->get('address');

        if ($request->username) {
            $profile->username = $request->username;
        }
        $profile->save();

        if ($profile->address != $request->get('address') && !empty($request->get('address'))) {
            Address::create([
                'label' => AddressType::HOME,
                'address' => $request->get('address'),
                'label_name' => trans('address_types.' . AddressType::HOME),
                'user_id' => $profile->id,
            ]);
        }

        if ($request->file('image')) {
            $profile->media()->delete();
            $profile->addMedia($request->file('image'))->toMediaCollection('user');
        }

        return response()->json([
            'status' => 200,
            'message' => 'Successfully Updated Profile',
        ], 200);
    }



    private function splitName($name)
    {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));
        return [$first_name, $last_name];
    }

    public function changePassword(Request $request)
    {
        $validator = new PasswordUpdateRequest();
        $validator = Validator::make($request->all(), $validator->rules());

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        $profile = auth()->user();
        $profile->password = bcrypt($request->get('password'));
        $profile->save();

        return response()->json([
            'status' => 200,
            'message' => 'Successfully Updated Password',
        ], 200);
    }

    public function device(Request $request)
    {
        $validator = Validator::make($request->all(), ['device_token' => 'required']);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();
        $user->device_token = $request->device_token;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Successfully device updated',
        ], 200);
    }

    public function review($id)
    {
        $ratingReview = RestaurantRating::where(['user_id' => auth()->user()->id, 'restaurant_id' => $id])->first();

        if (blank($ratingReview)) {
            return response()->json([
                'status' => 401,
                'message' => 'Review not found.',
            ], 401);
        }


        return response()->json([
            'status' => 200,
            'data' => $ratingReview,
        ], 200);
    }

    public function saveReview(Request $request)
    {
        // 🚨 NAYI LINE: Validation check hone se pehle logged-in user ki ID request me daal do
        $request->merge([
            'user_id' => auth()->id()
        ]);

        $validator = Validator::make($request->all(), $this->reviewValidateArray());
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        // 💡 OPTIMIZATION: updateOrCreate check karega ki pehle se record hai ya nahi.
        // Agar hai, toh update karega. Agar nahi hai, toh naya create kar dega.
        RestaurantRating::updateOrCreate(
            [
                // Yeh conditions search karegi
                'user_id' => auth()->id(), 
                'restaurant_id' => $request->restaurant_id
            ],
            [
                // Agar mila ya naya banana hua, toh in values ko save karegi
                'rating' => $request->rating,
                'review' => $request->review,
                'status' => RatingStatus::ACTIVE,
            ]
        );

        return response()->json([
            'status' => 200,
            'message' => 'Your rating successfully saved.',
        ], 200);
    }

    public function reviewValidateArray()
    {
        return [
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|max:500',
            'user_id' => 'required|numeric',
            'restaurant_id' => 'required|numeric',
        ];
    }

    public function reportCheck($id)
    {
        $report = Report::where('order_id', $id)->first();
        if (blank($report)) {
            return response()->json([
                'status' => 200,
                'isNew' => true,
                'message' => 'No reports yet.',
            ], 200);
        } else {
            return response()->json([
                'status' => 200,
                'isNew' => false,
                'message' => trans('report_statues_frontend.' . $report->status),
            ], 200);
        }
    }
    public function storeReport(ReportRequest $request)
    {
        $report = app(ComplaintService::class)->storeReport($request);
        if ($report) {
            return response()->json([
                'status' => 200,
                'message' => 'Reported successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 502,
                'message' => 'Something\'s Wrong !',
            ], 200);
        }
    }
}
