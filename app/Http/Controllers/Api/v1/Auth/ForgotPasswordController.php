<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
            public function sendResetLinkEmail(Request $request)
        {
            try {
        
                Log::info('Forgot Password API called.', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);
        
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|exists:users,email'
                ]);
        
                if ($validator->fails()) {
        
                    Log::warning('Forgot Password Validation Failed.', [
                        'email' => $request->email,
                        'errors' => $validator->errors()->toArray()
                    ]);
        
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation error',
                        'errors'  => $validator->errors()
                    ], 422);
                }
        
                Log::info('Validation passed. Sending reset link.', [
                    'email' => $request->email
                ]);
        
                $response = Password::sendResetLink([
                    'email' => $request->email
                ]);
        
                Log::info('Password::sendResetLink response received.', [
                    'email' => $request->email,
                    'response' => $response
                ]);
        
                if ($response === Password::RESET_LINK_SENT) {
        
                    Log::info('Reset link successfully sent.', [
                        'email' => $request->email
                    ]);
        
                    return response()->json([
                        'success' => true,
                        'message' => 'Password reset link sent to your email.'
                    ], 200);
                }
        
                Log::error('Unable to send reset link.', [
                    'email' => $request->email,
                    'response' => $response
                ]);
        
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to send reset link.'
                ], 400);
        
            } catch (\Exception $e) {
        
                Log::error('Forgot Password Exception Occurred.', [
                    'email' => $request->email ?? null,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
        
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later demo.'
                ], 500);
            }
        }

}
