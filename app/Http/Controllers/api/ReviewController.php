<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ReviewController extends Controller
{
    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                
                'subject_id'=>'required',
            ]);

            if ($validator->fails()) {

                return response()->json(['error' => $validator->errors()], 401);
            }
            $data=[
                'user_id'=>auth()->user()->id,
                'subject_id'=>$request->subject_id,
                'rating'=>$request->rating,
                'review'=>$request->review,
            ];
            $review=Review::create($data);
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Thank you for your review.",

            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong.",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function index(Request $request){
        try {
            $subject_id=$_GET['subject_id'];
            $reviews=Review::with(['user'=>function($q){
                $q->with('userDetail');
            }
            ])->where('subject_id',$subject_id)->where('is_visible',1)->get();
            if(!$reviews->isEmpty()){
                $all_reviews=[];
                $total_rating=$reviews->count()*5;
                $rating_average=$reviews->sum('rating') / $total_rating * 5;
                foreach($reviews as $key=>$review){
                    $review=[
                        'user_name'=>$review->user->userDetail->name,
                        'image'=>$review->user->userDetail->image,
                        'rating'=>$review->rating,
                        'review'=>$review->review,
    
                    ];
                    $all_reviews[]=$review;
                }
                $data=[
                    "code" => 200,
                    "message"=>"All reviews",
                    "reviews"=>$all_reviews,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }else{
                $data = [
                    "code" => 200, 
                    "message" => "No review found",
                    'reviews'=>[],
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400, 
                "message" => "Something went wrong",
                'reviews'=>[],
            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
        
    }
}
