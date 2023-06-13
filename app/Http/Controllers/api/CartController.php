<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Cart;
use App\Models\CartOrOrderAssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {

        $carts = Cart::select('id', 'user_id', 'is_full_course_selected', 'assign_class_id', 'board_id', 'is_paid', 'is_remove_from_cart')
            ->with(['assignClass:id,class', 'board:id,exam_board', 'assignSubject:id,cart_id,assign_subject_id,amount', 'assignSubject.subject:id,subject_name'])
            ->where('user_id', auth()->user()->id)
            ->where('is_paid',0)
            ->where('is_remove_from_cart',0)
            ->where('is_buy',0)
            ->get();
         
        if ($carts->count() > 0) {
            $cart_items = [];
            foreach ($carts as $key => $cart) {

                $subject_tmp = '';

                foreach ($cart->assignSubject as $key => $assignSubject) {

                    $subject_tmp .= ucfirst(strtolower($assignSubject->subject->subject_name)) . ',';
                }

                $subject_tmp = trim($subject_tmp, ',');
                $cart = [
                    'id' => $cart->id,
                    'course_type' => $cart->is_full_course_selected??"1",
                    'assign_class' => $cart->assignClass->class,
                    'board' => $cart->board->exam_board,
                    'cart_total_amount' => $cart->assignSubject->sum("amount"),
                    'assign_subject' => $subject_tmp,
                    'total_subject'=>$cart->assignSubject->count(),

                ];
                $cart_items[] = $cart;
            }

            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "All cart items",
                "carts" => $cart_items,

            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } else {
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Your cart is empty",
                "carts" => $carts,
            ];
            return response()->json(['status' => 1, 'result' => $data]);
        }
    }
    public function store(Request $request)
    {
        try {
            
            $all_subjects = $request->subjects;
            if ($all_subjects == null) {
                $data = [
                    "code" => 400,
                    "message" => "Please select subject",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
            }
            $subject = AssignSubject::find($all_subjects[0]);
            $board_id = $subject->board_id;
            $class_id = $subject->assign_class_id;
            $course_type = $request->course_type;
            $isBuy = $request->is_buy;
            // $already_in_cart = Cart::where('user_id', auth()->user()->id)->where('board_id', $board_id)->where('assign_class_id', $class_id)->where('is_full_course_selected', 1)->get();
            // // if ($already_in_cart->count() > 0) {
            // //     $data = [
            // //         "code" => 400,
            // //         "status" => 0,
            // //         "message" => "Subjects already in cart",

            // //     ];
            // //     return response()->json(['status' => 0, 'result' => $data]);
            // // }

            $cart_check = Cart::whereHas('assignSubject', function ($q) use ($all_subjects) {
                $q->whereIn('assign_subject_id', $all_subjects);
            })->where('board_id', $board_id)->where('assign_class_id', $class_id)->where('is_remove_from_cart',0)->where('user_id',auth()->user()->id)->where('is_buy',$request->is_buy)->first();
            
            
            
            if ($cart_check) {
                if($cart_check->is_full_course_selected==$request->course_type && $cart_check->assignSubject()->count()==count($all_subjects) && $request->is_buy==0){
                    $data = [
                        "code" => 400,
                        "status" => 0,
                        "message" => "Same Subjects already on your cart.",
        
        
                    ];
                    return response()->json(['status' => 0, 'result' => $data]);

                }
                $cart_check->assignSubject()->delete();
                $cart_check->update(['is_remove_from_cart'=>1]);
             
            }

            $cart = Cart::create([
                'user_id' => auth()->user()->id,
                'board_id' => $board_id, //board_id
                'assign_class_id' => $class_id, //class_id
                'is_full_course_selected' => $course_type,
                'is_buy' => $isBuy
            ]);

            foreach ($all_subjects as $key => $subject) {


                $subject = AssignSubject::find($all_subjects[$key]);

                $data = [
                    'cart_id' => $cart->id,
                    'assign_subject_id' => $subject->id,
                    'amount' => $subject->subject_amount,
                    'type' => 1,
                ];
                $assign_subject = CartOrOrderAssignSubject::create($data);
            }
            if ($request->is_buy == 1) {
                $message = "Continue for buy subject";
            } else {
                $message = "Subjects was successfully added to your cart";
            }
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => $message,
                "cart_id" => $cart->id,
            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",


            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function remove(Request $request)
    {
        try {
            $id = $_GET['cart_id'];
            $cart = Cart::find($id);
            $cart->update(['is_remove_from_cart' => 1]);
            $cart->assignSubject()->delete();

            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Subjects was successfully removed from your cart",

            ];

            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function cartDetails(Request $request)
    {
        try {

            $id = $_GET['cart_id'];

            $cart = Cart::select('id', 'user_id', 'is_full_course_selected', 'assign_class_id', 'board_id', 'is_paid', 'is_remove_from_cart')
                ->with(['assignClass:id,class', 'board:id,exam_board', 'assignSubject:id,cart_id,assign_subject_id,amount', 'assignSubject.subject:id,subject_name,image'])
                ->where('id', $id)
                ->where('is_paid', 0)
                ->where('is_remove_from_cart', 0)
                ->first();
                
            if (!$cart == null) {
                $cart_total_amount = totalAmountCart($cart->id);
                $cart_subjects=[];
                foreach($cart->assignSubject as $key=>$assign_subject){
                    $is_available=0;
                    if(subjectStatus($assign_subject->subject->id)==3){
                        $is_available=1;
                    }

                    $data = [
                       'id'=>$assign_subject->id,
                       'cart_id'=>$assign_subject->cart_id,
                       'assign_subject_id'=>$assign_subject->subject->id,
                       'amount'=>$assign_subject->amount,
                       'subject_name'=>$assign_subject->subject->subject_name,
                       'image'=>$assign_subject->subject->image,
                       'is_available'=>$is_available,
                       
                    ];
                    $cart_subjects[]=$data;
                }

                $cart_details = [
                    'id' => $cart->id,
                    'user_id' => $cart->user_id,
                    'type' => $cart->is_full_course_selected,
                    'board' => $cart->board->exam_board,
                    'class_name' => $cart->assignClass->class,
                    'total_amount' => $cart_total_amount,
                    'cart_subject_details' => $cart_subjects,

                ];

                $data = [
                    "code" => 200,
                    "message" => "Cart Details",
                    "carts" => $cart_details,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "message" => "No data found",

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
}
