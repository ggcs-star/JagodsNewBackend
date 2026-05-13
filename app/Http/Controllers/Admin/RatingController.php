<?php

namespace App\Http\Controllers\Admin;
use App\Enums\Status;
use App\Enums\RatingStatus;
use App\Http\Controllers\BackendController;
use App\Models\RestaurantRating;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class RatingController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Ratings';

        $this->middleware(['permission:rating']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data= RestaurantRating::latest()->get();
        return view('admin.rating.index', $this->data);
    }

    public function update(Request $request, $id)
    {
        $rating         = RestaurantRating::findOrFail($id);
        $rating->status = $rating->status == 10 ? RatingStatus::ACTIVE : RatingStatus::INACTIVE;
        $rating->save();

        return redirect(route('admin.rating.index'))->withSuccess('The Data Updated Successfully');
    }

    public function destroy($id)
    {
        $rating = RestaurantRating::findOrFail($id);
        $rating->delete();

        return redirect()->back()->withSuccess('The Rating Deleted Successfully');
    }

    public function getRating(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->status) && (int) $request->status) {
                $ratings = RestaurantRating::where(['status' => $request->status])->latest()->get();
            } else {
                $ratings = RestaurantRating::latest()->get();
            }

            $i           = 1;
            $ratingArray = []; 

            if (!blank($ratings)) {
                foreach ($ratings as $rating) {
                    $ratingArray[$i]                 = $rating;
                    $ratingArray[$i]['user_name']     = $rating->user->name;
                    $ratingArray[$i]['restaurant_name']   = Str::limit($rating->restaurant->name, 30);
                    $ratingArray[$i]['rating']       = number_format($rating->rating, 1);
                    $ratingArray[$i]['review']       = Str::limit($rating->review, 30);
                    $ratingArray[$i]['setID']        = $i;
                    $i++;
                }
            }

            return Datatables::of($ratingArray)
                ->addColumn('action', function ($rating) {
                    
                    $button_array['delete'] = ['route' => route('admin.rating.delete', $rating),'permission' => 'rating'];
                    
                    return action_button($button_array);
                })
                ->escapeColumns([])
                ->make(true);
        }
    }
}
