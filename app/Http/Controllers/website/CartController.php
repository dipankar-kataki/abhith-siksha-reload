<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\SelectedAddon;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Chapter;
use App\Models\Order;
use App\Models\AssignSubject;
use App\Models\CartOrOrderAssignSubject;
use App\Models\Subject;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Crypt;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class CartController extends Controller
{
    public function index(Request $request)
    {
        try {
            $cart = [];
            $countCartItem = 0;
            $price = [];
            if (Auth::check()) {
                $carts = Cart::with('board', 'assignClass')->where('user_id', Auth::user()->id)->where('is_paid', 0)->where('is_remove_from_cart', 0)->where('is_buy', 0)->get();
            } else {
                return redirect()->route('website.login');
            }
        } catch (\Throwable $th) {
            return redirect()->back();
        }

        return view('website.cart.cart')->with(['carts' => $carts]);
    }
    public function cartDetails($cart_id)
    {
        try {

            if (Auth::check()) {
                $cart = Cart::with('board', 'assignClass', 'assignSubject')->where('id', Crypt::decrypt($cart_id))->first();
                $all_subjects = $cart->assignSubject;
                $totalPrice = $cart->assignSubject->sum('amount');
            }
            return view('website.cart.cart-details')->with(['cart' => $cart, 'all_subjects' => $all_subjects, 'countPrice' => $totalPrice]);
        } catch (\Throwable $th) {
            Toastr::error('Something went wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }
    public function addToCart(Request $request)
    {

        // dd('Add To Cart Check Response', $request->all());
        try {

            if (!Auth::check()) {

                Toastr::success('please login for add the package!', '', ["positionClass" => "toast-top-right"]);
                return redirect()->route('website.login');
            }

            $board_id = $request->board_id;
            $class_id = $request->class_id;
            $course_type = $request->course_type;

            if($request->subjects != null){
                if ($course_type == 1) {
                    $all_subjects = AssignSubject::where(['board_id' => $board_id, 'assign_class_id' => $class_id, 'is_activate' => 1])->get();
                } else {
                    $all_subjects = AssignSubject::whereIn('id', $request->subjects)->get();
                }
            }
            


        //    dd('Addons Total Amount', $total_addons_amount);

            if ($request->buynow == 1 ) {
                $cart = Cart::create([
                    'user_id' => auth()->user()->id,
                    'board_id' => $board_id, //board_id
                    'assign_class_id' => $class_id, //class_id
                    'is_full_course_selected' => $course_type,
                    'is_buy' => $request->buynow,
                    'is_addons_selected' => $request->is_addons_selected
                ]);
                foreach ($all_subjects as $key => $subject) {
                    
                    $data = [
                        'cart_id' => $cart->id,
                        'assign_subject_id' => $all_subjects[$key]['id'],
                        'amount' => $all_subjects[$key]['subject_amount'],
                        'type' => 1,
                    ];

                    $assign_subject = CartOrOrderAssignSubject::create($data);
                }

                /**********************  For Selected Addons *******************/

                if( ($request->is_addons_selected == 1) && (!($request->addons == null)) ){
                    $get_addons = Addon::whereIn('id', $request->addons)->get();
                    foreach($get_addons as $key => $addon){
                        $data = [
                            'user_id' => auth()->user()->id,
                            'cart_id' => $cart->id,
                            'addon_id' => $addon->id,
                            'payment_status' => 'pending'
                        ];

                        $check_if_addon_already_selected = SelectedAddon::where('cart_id', $cart->id)->where('addon_id', $addon->id)->where('payment_status', 'pending')->exists();
                        if(!$check_if_addon_already_selected){
                            SelectedAddon::create($data);
                        } 
                    }
                }
                

                /**********************  End For Selected Addons *******************/


                $user_detail = UserDetails::where('user_id', Auth::user()->id)->first();
                $total_amount = totalAmountCart($cart->id);

                $get_selected_addons = SelectedAddon::with('selectedAddon')->where('cart_id', $cart->id)->where('payment_status', 'pending')->get();

                


                /**********************  For Razorpay  *************************/
                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                $orderData = [
                    'receipt'         => now()->timestamp,
                    'amount'          => $total_amount * 100, // 39900 rupees in paise
                    'currency'        => 'INR'
                ];

                $razorpayOrder = $api->order->create($orderData);

                $checkout_params = [
                    "key"               => env('RAZORPAY_KEY'),
                    "amount"            => ($total_amount * 100),
                    "name"              => "Abhith Shiksha",
                    "image"             => "https://cdn.razorpay.com/logos/FFATTsJeURNMxx_medium.png",
                    "prefill"           => [
                        "name"              => Auth::user()->name . Auth::user()->lastname,
                        "email"             => Auth::user()->email,
                        "contact"           => auth()->user()->phone,
                    ],
                    "theme"             => [
                        "color"             => "#528FF0"
                    ],
                    "order_id"          =>  $razorpayOrder['id'],
                ];


                Order::create([
                    'user_id' => Auth::user()->id,
                    'board_id' => $cart->board_id,
                    'assign_class_id' => $cart->assign_class_id,
                    'rzp_order_id' => $razorpayOrder['id'],
                    'payment_status' => 'pending',
                    'order_no' => orderNo()

                ]);
                return view('website.cart.checkout')->with(['cart' => $cart, 'get_selected_addons' => $get_selected_addons, 'countPrice' => $total_amount, 'checkoutParam' => $checkout_params]);
            }

            $cart = Cart::with('assignSubject')->where([['user_id', '=', Auth::user()->id], ['assign_class_id', '=', $class_id], ['board_id', '=', $board_id], ['is_paid', '=', 0], ['is_remove_from_cart', '=', 0], ['is_full_course_selected', '=', $course_type], ['is_buy', '=', $request->buynow]])->first();
            
            if ($cart) {

                $assignSubjectAlreadyInCart = CartOrOrderAssignSubject::where('cart_id', $cart['id'])->whereIn('assign_subject_id', $request->subjects)->get();
                
                if ($assignSubjectAlreadyInCart->count() == count($request->subjects)) {
                    Toastr::error('Package already in Cart!', '', ["positionClass" => "toast-top-right"]);
                    return redirect()->back();
                } else {

                    if ($assignSubjectAlreadyInCart->count() < count($request->subjects)) {
                        foreach ($all_subjects as $key => $subject) {
                            $subject_already_store = CartOrOrderAssignSubject::where('cart_id', $cart['id'])->where('assign_subject_id', $all_subjects[$key]['id'])->first();


                            if ($subject_already_store) {
                                $subject_already_store->update(['amount', '=', $all_subjects[$key]['subject_amount']]);
                            } else {
                                $data = [
                                    'cart_id' => $cart['id'],
                                    'assign_subject_id' => $all_subjects[$key]['id'],
                                    'amount' => $all_subjects[$key]['subject_amount'],
                                    'type' => 1,
                                ];
                                $assign_subject = CartOrOrderAssignSubject::create($data);
                            }
                        }
                        
                        Toastr::success('Subjects was successfully added to your cart.', '', ["positionClass" => "toast-top-right"]);
                        return redirect()->back();
                    }
                }
            }
            $cartstore = Cart::create([
                'user_id' => auth()->user()->id,
                'board_id' => $board_id, //board_id
                'assign_class_id' => $class_id, //class_id
                'is_full_course_selected' => $course_type,
                'is_buy' => $request->buynow
            ]);

            foreach ($all_subjects as $key => $subject) {
                $data = [
                    'cart_id' => $cartstore['id'],
                    'assign_subject_id' => $all_subjects[$key]['id'],
                    'amount' => $all_subjects[$key]['subject_amount'],
                    'type' => 1,
                ];

                $assign_subject = CartOrOrderAssignSubject::create($data);
            }
            Toastr::success('Subjects was successfully added to your cart.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
            Toastr::error('Something want wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }
    public function removeCart($cart_id)
    {
        try {
            $cart = Cart::find(Crypt::decrypt($cart_id));
            $cart->delete();
            $cart->assignSubject()->delete();
            Toastr::success('Cart item remove successfully.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Something want wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            if (Auth::check()) {
                $cart = Cart::find($request->cart_id);
                $cart->update(['is_remove_from_cart' => 1]);
                $cart->assignSubject()->delete();
                return response()->json(['message' => 'Item removed successfully']);
            } else {
                return response()->json(['message' => ' Something want wrong']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => ' Something want wrong']);
        }
    }
}
