<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerfication;
use App\Models\Cart;
use App\Models\Order;
use App\Models\TimeTable;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;

class UserController extends Controller
{
    public function index()
    {
        try {
            $user_details = UserDetails::select('name', 'email', 'phone', 'education', 'gender', 'image', 'address')->where('user_id', auth()->user()->id)->first();
            $cart = Cart::select('id', 'user_id', 'is_full_course_selected', 'assign_class_id', 'board_id', 'is_paid', 'is_remove_from_cart')
                ->with(['assignClass:id,class', 'board:id,exam_board', 'assignSubject:id,cart_id,assign_subject_id,amount', 'assignSubject.subject:id,subject_name'])
                ->where('user_id', auth()->user()->id)
                ->where('is_paid', 0)
                ->where('is_remove_from_cart', 0)
                ->where('is_buy', 0)
                ->get()->count();
            $result = ["user_details" => $user_details, "cart_total_count" => $cart];
            if (!$user_details = null) {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "Your Details",
                    "result" => $result,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 0,
                    "message" => "No record found",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
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
    public function updateDetails(Request $request)
    {
        try {
            $user_details = UserDetails::where('user_id', auth()->user()->id)->first();
            $data = [
                'education' => $request->education,
                'name' => $request->name,
                'address' => $request->address,
            ];
            $user_details->update($data);

            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "User Details updated successfully",

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
    public function profileUpdate(Request $request)
    {
        try {

            $image = $request->file('image');

            $validator = Validator::make($request->all(), [
                'image' => 'required|mimes:jpg,png|max:2048',
            ]);

            if ($validator->fails()) {

                return response()->json(['error' => $validator->errors()], 401);
            }
            if ($file = $request->file('image')) {
                $new_imgage_name = time() . '-' . auth()->user()->name . '.' . $image->extension();
                $image_path = $image->move(public_path('files/profile'), $new_imgage_name);
                $path_name = 'files/profile/' . $new_imgage_name;
                UserDetails::where('user_id', auth()->user()->id)->update(['image' => $path_name]);
                $data = [
                    "code" => 200,
                    "message" => "Profile photo uploaded",
                    "path" => $path_name,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong.",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function allCourses(Request $request)
    {
        try {
            $carts = Cart::select('id', 'user_id', 'is_full_course_selected', 'assign_class_id', 'board_id', 'is_paid', 'is_remove_from_cart')
                ->with(['assignClass:id,class', 'board:id,exam_board,logo', 'assignSubject:id,cart_id,assign_subject_id,amount', 'assignSubject.subject:id,subject_name'])
                ->where('user_id', auth()->user()->id)
                ->where('is_paid', 1)
                ->where('is_remove_from_cart', 1)
                ->orderBy('updated_at', 'DESC')
                ->get();

            if (!$carts->isEmpty()) {
                $all_courses = [];

                foreach ($carts as $key => $cart) {
                    $subject = [];
                    foreach ($cart->assignSubject as $key => $assign_subject) {
                        $subject[] = $assign_subject->subject->subject_name;
                    }


                    $course_details = [
                        'id' => $cart->id,
                        'user_id' => $cart->user_id,
                        'type' => $cart->is_full_course_selected,
                        'board' => $cart->board->exam_board,
                        'board_logo' => $cart->board->logo,
                        'class_name' => $cart->assignClass->class,
                        'total_subject' => $cart->assignSubject->count(),
                        'total_amount' => $cart->assignSubject->sum("amount"),
                        'cart_subject_details' => $subject,
                    ];
                    $all_courses[] = $course_details;
                }
                $data = [
                    "code" => 200,
                    "message" => "Courses Details",
                    "courses" => $all_courses,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "message" => "No data found",
                    "courses" => [],

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",
                "all_subjects" => [],

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function allSubject(Request $request)
    {
        try {

            $id = $_GET['cart_id'];

            $cart = Cart::select('id', 'user_id', 'is_full_course_selected', 'assign_class_id', 'board_id', 'is_paid', 'is_remove_from_cart')
                ->with(['assignClass:id,class', 'board:id,exam_board', 'assignSubject'])
                ->where('id', $id)
                ->where('is_paid', 1)
                ->where('is_remove_from_cart', 1)
                ->first();
            if (!$cart == null) {
                $cart_total_amount = $cart->assignSubject->sum("amount");
                $subject_details = [];
                foreach ($cart->assignSubject as $key => $assignSubject) {
                    $subject = [
                        'id' => $assignSubject->subject->id,
                        'name' => $assignSubject->subject->subject_name,
                        'total_lesson' => $assignSubject->subject->lesson->count(),
                        'image' => $assignSubject->subject->image,
                        'total_video' => subjectTotalVideo($assignSubject->subject->id),
                        'total_document' => subjectTotalDocument($assignSubject->subject->id),
                        'total_article' => subjectTotalArticle($assignSubject->subject->id)
                    ];
                    $subject_details[] = $subject;
                }
                $cart_details = [
                    'id' => $cart->id,
                    'user_id' => $cart->user_id,
                    'type' => $cart->is_full_course_selected,
                    'board' => $cart->board->exam_board,
                    'class_name' => $cart->assignClass->class,
                    'total_amount' => $cart_total_amount,
                    'cart_subject_details' => $subject_details,

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
                return response()->json(['status' => 0, 'result' => $data]);
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
    public function resetPasswordForWeb(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            if (Hash::check($request->old_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();

                $data = [
                    "code" => 200,
                    "message" => "Password changed Successfully ",

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "message" => "Password does not match. ",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function sendOtpForgotPassword(Request $request)
    {

        try {
            $istype = $request->type;
            if ($istype == 1) {
                $user = User::where('phone', $request->phone)->where('verify_otp', 1)->first();
                if ($user) {
                    $otp = rand(100000, 999999);
                    $user->update(['otp' => $otp]);
                    $otpsend = otpSendForgotPassword($request->phone, $otp);

                    $data = [
                        "user_id" => $user->id,
                        "otp" => $otp,
                        "code" => 200,
                        "message" => "Verification code send to your registered mobile number.",

                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
                $data = [
                    "code" => 400,
                    "message" => "Record not found.",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
            } else {

                $user = User::where('email', $request->email)->where('verify_otp', 1)->first();

                if ($user != null) {
                    $otp = rand(100000, 999999);
                    $user->update(['otp' => $otp]);
                    $details = [
                        'otp' => $otp,

                    ];

                    Mail::to($request->email)->send(new OtpVerfication($details));

                    // check for failures
                    if (Mail::failures()) {
                        $data = [
                            "user_id" => $user->id,
                            "code" => 200,
                            "message" => "Mail not sent",
                        ];

                        return response()->json(['status' => 0, 'result' => $data]);
                    }

                    $data = [
                        "user_id" => $user->id,
                        "code" => 200,
                        "message" => "Verification code send to your registered Email address.",

                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $data = [
                        "code" => 400,
                        "message" => "Record not found.",

                    ];
                    return response()->json(['status' => 0, 'result' => $data]);
                }
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong.",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function verifyOtpForgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'user_id' => 'required',
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()]);
            }


            if (checkemail($request->user_id)) {
                $user = user::where('email', $request->user_id)->first();
            } else {

                $user = user::where('phone', $request->user_id)->first();
            }

            if ($user) {
                if ($user->otp == $request->otp) {
                    $data = [
                        "user_id" => $user->id,
                        "code" => 200,
                        "message" => "Account verified successfully please enter your new password.",

                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $data = [
                        "code" => 400,
                        "message" => "OTP mismatch.",

                    ];
                    return response()->json(['status' => 0, 'result' => $data]);
                }
            }
            $data = [
                "code" => 400,
                "message" => "Record not found.",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong.",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function resetForgotPassword(Request $request)
    {


        try {
            if (checkemail($request->user_id)) {
                $user = user::where('email', $request->user_id)->first();
            } else {

                $user = user::where('phone', $request->user_id)->first();
            }
            if (Hash::check($request->old_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();

                $data = [
                    "code" => 200,
                    "message" => "Password changed Successfully ",

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "message" => "Old Password does not match. ",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }








        try {
            $user = User::find($request->user_id);
            if ($user) {
                $user->update(['password' => Hash::make($request->password)]);


                $data = [
                    "code" => 200,
                    "message" => "Password changed Successfully ",

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
            $data = [
                "code" => 200,
                "message" => "Account not found. ",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function purchaseHistory(Request $request)
    {
        try {

            $purchase_history = Order::with('board', 'assignClass', 'assignSubject')->where('user_id', auth()->user()->id)->where('payment_status', 'paid')->orderBy('created_at', 'DESC')->get();
            if ($purchase_history->count() > 0) {
                $purchase_history_data = [];
                foreach ($purchase_history as $key => $purchase_history_item) {
                    if ($purchase_history_item->is_full_course_selected == 1) {
                        $course_type = "Full Course";
                    } else {
                        $course_type = "Custom package";
                    }
                    $subjects = [];
                    foreach ($purchase_history_item->assignSubject as $key => $subject) {
                        $subject_data = [
                            'id' => $subject->subject->id,
                            'name' => $subject->subject->subject_name,
                        ];
                        $subjects[] = $subject_data;
                    }
                    $result = [
                        'id' => $purchase_history_item->id,
                        'board' => $purchase_history_item->board->exam_board,
                        'class' => $purchase_history_item->assignClass->class,
                        'course_type' => $course_type,
                        'subjects' => $subjects,
                        'total_amount' => number_format($purchase_history_item->assignSubject->sum('amount') ??
                            '00', 2, '.', ''),
                        'created_at' => $purchase_history_item->updated_at->format('d-M-Y'),
                    ];
                    $purchase_history_data[] = $result;
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "Purchase History",
                        "result" => $purchase_history_data,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "result" => $purchase_history,
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
    public function purchaseHistoryInvoice(Request $request)
    {

        try {
            $id = $_GET['order_id'];

            $purchase_history = Order::with('board', 'assignClass', 'assignSubject')->where('id', $id)->first();

            if ($purchase_history->is_receipt_generated == 0) {
                $receipt_no = reciptGenerate($purchase_history->id);
                $user_details = $purchase_history->user->userDetail;
                if ($user_details->is_above_eighteen == 1) {
                    $user_name = $user_details->name;
                } else {
                    $user_name = $user_details->parent_name;
                }
                if ($purchase_history->is_full_course_selected == 0) {
                    $package_type = "Custom Package";
                } else {
                    $package_type = "Full Package";
                }
                $user = [
                    'receipt_no' => $receipt_no,
                    'user_name' => $user_name,
                    'mobile' => $user_details->phone,
                    'email' => $user_details->email
                ];
                $course_details = [
                    'created_at' => $purchase_history->created_at,
                    'board' => $purchase_history->board->exam_board,
                    'class' => $purchase_history->assignClass->class,
                    'subjects' => $purchase_history->assignSubject,
                    'package_type' => $package_type,
                    'total_amount' =>  number_format($purchase_history->assignSubject->sum('amount') ?? '00', 2, '.', ''),
                ];
                $pdf = PDF::loadView('common.receipt', [
                    'user_details' => $user,
                    'course_details' => $course_details
                ]);
                $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
                $pdf->setPaper('A4', 'portrait');
                Storage::put('public/pdf/' . auth()->user()->name . '_' . date('d-m-Y-H-i-s') . '_' . $id . '.pdf', $pdf->output());
                $file_path = 'storage/pdf/' . auth()->user()->name . '_' . date('d-m-Y-H-i-s') . '_' . $id . '.pdf';
                $update_data = [
                    'is_receipt_generated' => 1,
                    'receipt_no' => $receipt_no,
                    'receipt_url' => $file_path,
                ];
                $purchase_history->update($update_data);

                // $generated_pdf= $pdf->download(auth()->user()->name.'_'.date('d-m-Y-H-i-s') . '_' . $id.'.pdf')->getOriginalContent();

                $invoice_url = $file_path;
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "Course Receipt",
                    "invoice_url" => $invoice_url,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $invoice_url = $purchase_history->receipt_url;
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "Course Receipt",
                    "invoice_url" => $invoice_url,
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
    public function timeTableDisplay()
    {
        try {
            $time_tables = TimeTable::whereHas('assignSubject', function ($q) {
                $q->whereHas('assignOrder', function ($query) {
                    $query->whereHas('order', function ($qu) {
                        $qu->where('user_id', auth()->user()->id);
                    });
                });
            })->where('is_activate',1)->orderBy('created_at', 'DESC')->get();
            if ($time_tables->count() > 0) {
                $time_table = [];
                foreach ($time_tables as $key => $item) {
                    $data = [
                        'board' => $item->board->exam_board,
                        'class' => $item->assignClass->class,
                        'subject' => $item->assignSubject->subject_name,
                        'Link' => $item->zoom_link,
                        'class_date' => $item->date,
                        'class_time' => $item->time,
                        'status' => $item->is_activate,
                    ];
                    $time_table[] = $data;
                }
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "All Time-Table",
                    "result" => $time_table,

                ];
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Recored Found",
                    "result" => $time_tables,

                ];
            }


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

    public function resetForgotPasswordForWeb(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'user_id' => 'required',
                'otp' => 'required',
                'password' => 'min:6|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'min:6'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
            }

            // $dec_id = Crypt::decrypt($request->user_id);
            $dec_id = $request->user_id;
            $user = User::find($dec_id);

            // If no user found
            if (!$user) {
                return response()->json(['status' => 0, 'message' => 'User not found']);
            }

            // Verify otp
            if ($user->otp != $request->otp) {
                return response()->json(['status' => 0, 'message' => 'OTP not match']);
            }

            // Update otp
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $data = [
                "code" => 200,
                "message" => "Password reset successful",
            ];

            // Return success
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 0, 'message' => $th->getMessage()]);
        }
    }
}
