<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\SelectedAddon;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    public function paymentOrderGenerate(Request $request)
    {
        try {
            $id = $_GET['cart_id'];
            $cart = Cart::where('id', $id)->where('is_remove_from_cart', 0)->where('is_paid', 0)->first();

            if ($cart) {
                $total_amount = $total_amount = $cart->assignSubject->sum('amount');

                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                $orderData = [
                    'receipt'         => now()->timestamp,
                    'amount'          => $total_amount * 100, // 39900 rupees in paise
                    'currency'        => 'INR'
                ];

                $razorpayOrder = $api->order->create($orderData);

                $order = [
                    'user_id' => auth()->user()->id,
                    'board_id' => $cart->board_id,
                    'assign_class_id' => $cart->assign_class_id,
                    'is_full_course_selected' => $cart->is_full_course_selected,
                    'rzp_order_id' => $razorpayOrder['id'],
                    'payment_status' => "unpaid",
                ];

                 Order::create($order);
                $data = [
                    'razorpayOrderId' => $razorpayOrder['id'],
                    'razorpayKey' => env('RAZORPAY_KEY'),
                    'cart_id' => $cart->id,
                    'amount' => $total_amount,
                ];
                $data = [
                    "code" => 200,
                    "message" => "Order generated successfully,Continue for Checkout.",
                    "data" => $data


                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 400,
                    "message" => "Invalid cart data.",
                    "data" => []


                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => $th->getMessage(),


            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function paymentVerification(Request $request)
    {
        try {
            $cart_id = $request->cart_id;
            $razorpay_order_id =  $request->razorpay_order_id;
            $razorpay_payment_id = $request->razorpay_payment_id;

            $cart = Cart::find($cart_id);

            $order=Order::where('rzp_order_id',$razorpay_order_id)->first();
            $order_update_data=[
                'payment_status'=>"paid",
                'rzp_payment_id'=>$razorpay_payment_id,
            ];
            $order_update=$order->update($order_update_data);
            $cart_assign_subjects = $cart->assignSubject;
            foreach ($cart_assign_subjects as $key => $cart_assign_subject) {

                $cart_assign_subject_update = $cart_assign_subject->update(['order_id' => $order->id]);

                SelectedAddon::where('user_id', auth()->user()->id)->where('cart_id', $request->cart_id)->where('payment_status', 'pending')->update([
                    'order_id' =>   $order->id,
                    'payment_status' => 'paid'
                ]);
            }
            $cart_update = $cart->update(['is_paid' => 1, 'is_remove_from_cart' => 1]);

            

            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Payment Done Successfully.",


            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => $th,


            ];
            return response()->json(['status' => 1, 'result' => $data]);
        }
    }
}
