<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Lesson;
use App\Models\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use PDF;
use URL;

class UserCourseController extends Controller
{
    public function displayUserSubjects($order_id)
    {
        try {

            $order_id = Crypt::decrypt($order_id);
            $order = Order::find($order_id);

            $subjects = $order->assignSubject;
            return view('website.user.subject', compact('subjects', 'order'));
        } catch (\Throwable $th) {

            return redirect()->back();
            //throw $th;
        }
    }
    public function myLesson($order_id, $subject_id)
    {
        try {


            $order_id = Crypt::decrypt($order_id);
            $subject_id = Crypt::decrypt($subject_id);
            $order = Order::find($order_id);
            visitorRecord($subject_id, $type = 1); //visitor record store after login
            $subject = AssignSubject::where('assign_class_id', $order->assign_class_id)->where('board_id', $order->board_id)->where('id', $subject_id)->first();
            $lessons = Lesson::where('board_id', $order->board_id)->where('assign_class_id', $order->assign_class_id)->where('assign_subject_id', $subject_id)->where('parent_id', null)->get();

            return view('website.user.lesson', compact('lessons', 'order', 'subject'));
        } catch (\Throwable $th) {

            return redirect()->back();
        }
    }
    public function receiptGenerate($id)
    {
        try {

            $purchase_history = Order::with(['board', 'assignClass', 'assignSubject'])->where('id', $id)->first();

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

                // Return view for debug
                // return view('common.receipt')->with(['course_detatils' => $course_detatils, 'user_details' => $user_details]);

                $pdf = PDF::loadView('common.receipt', [
                    'user_details' => $user,
                    'course_details' => $course_details
                ]);
                $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
                $pdf->setPaper('A4', 'portrait');
                $url_temp=auth()->user()->name . '_' . date('d-m-Y-H-i-s') . '_' . $id . '.pdf';
                Storage::put('public/pdf/' . $url_temp, $pdf->output());
                $file_path = 'storage/pdf/' . $url_temp;
                $update_data = [
                    'is_receipt_generated' => 1,
                    'receipt_no' => $receipt_no,
                    'receipt_url' => $file_path,
                ];
                $purchase_history->update($update_data);

                // $generated_pdf= $pdf->download(auth()->user()->name.'_'.date('d-m-Y-H-i-s') . '_' . $id.'.pdf')->getOriginalContent();

                return $pdf->download($url_temp);
            } else {

                return response()->download(public_path($purchase_history->receipt_url));
            }
        } catch (\Throwable $th) {
            Toastr::error('Something went wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }
}
