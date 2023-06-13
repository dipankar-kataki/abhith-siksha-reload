<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Order;
use App\Common\Activation;
use App\Mail\TimeTableMail;
use App\Models\AssignClass;
use App\Models\AssignSubject;
use App\Models\Board;
use App\Models\TimeTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TimeTableController extends Controller
{

    public function websiteViewTimeTable(Request $request)
    {
        
        $time_tables=TimeTable::whereHas('assignSubject',function($q){
            $q->whereHas('assignOrder',function($query){
                $query->whereHas('order',function($qu){
                    $qu->where('user_id',auth()->user()->id);
                });
            });

        })->where('is_activate',1)->orderBy('created_at', 'DESC')->get();
      
        

        return view('website.time-table.time-table')->with(['time_tables' =>  $time_tables]);
    }



    public function adminViewTimeTable(Request $request)
    {
        $getTimeTables = TimeTable::orderBy('created_at', 'DESC')->get();
        return view('admin.time-table.view-time-table')->with(['getTimeTables' => $getTimeTables]);
    }

    public function adminCreateTimeTable(Request $request)
    {
        // $class_details =  AssignClass::with('boards')->where('is_activate', 1)->orderBy('id', 'DESC')->get();
        $boards =  Board::orderBy('id', 'DESC')->get();
        // $chapters = [];
        // if($request->ajax()){
        //         $chapters = Chapter::where('course_id', $request->course_id)->get();
        //     return response()->json(['chapter' => $chapters]);
        // }

        return view('admin.time-table.add-time-table')->with(['boards' => $boards]);
    }

    public function saveTimeTable(Request $request)
    {

        try {
            $board = $request->board;
            $class = $request->class;
            $subject = $request->subject;
            $zoom_link = $request->zoom_link;
            $add_time = $request->add_time;
            $add_date = $request->add_date;
            $validate = Validator::make(
                $request->all(),
                [
                    'board' => 'required',
                    'class' => 'required',
                    'subject' => 'required',
                    'zoom_link' => 'required|max:100',
                    'add_time' => 'required',
                    'add_date' => 'required',
                ],
                [
                    'board.required' => 'Board name is required',
                    'class.required' => 'Class class is required',
                    'subject.required' => 'Subject filed is required',
                    'zoom_link.required' => 'Please insert a valid Zoom Link',
                    'add_time.required' => 'Add time is required',
                    'add_date.required' => 'Add date is required',

                ]
            );
           
            

            if ($validate->fails()) {
                return response()->json(['status' => 0, 'message' => $validate->errors()->first()]);
            }


            $create = TimeTable::create([
                'board_id' => $board,
                'assign_class_id' => $class,
                'assign_subject_id' => $subject,
                'time' => $add_time,
                'date' => $add_date,
                'zoom_link' => $zoom_link,
                'is_activate' => Activation::Activate,

            ]);
            if ($create) {
                $subject=AssignSubject::find($request->subject);
                $orders=$subject->assignOrder;
                if($orders->count()>0){
                     foreach($orders as $key=>$order){
                       
                         $email=$order->order->user->userDetail->email;
                           
                        $details = [
                            'name' => $order->order->user->userDetail->name,
                            'subject_name'=>$subject->subject_name,
                            'board'=>$subject->boards->exam_board,
                            'class'=>$subject->assignClass->class,
                            'date'=>$create->date,
                            'time'=> $create->time,
                            'link'=>$create->zoom_link
            
                        ];
                       
                        Mail::to($email)->send(new TimeTableMail($details));
                     }
                }

                return response()->json(['status' => 1, 'message' => 'Time Table created successfully']);
            } else {
                return response()->json(['status' => 0, 'message' => 'Oops! Something went wrong']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => $th]);
        }
    }


    public function changeVisibility(Request $request)
    {
        try {
            $timeTable = $request->timeTable;
            $status = $request->active;
            
            $timetable=TimeTable::find($timeTable);
            $timetable->update([
                'is_activate' =>  $status
            ]);
            if ($status == 0) {
                return response()->json(['status' => 1,'message' => 'Time-Table visibility updated from show to hide']);
            } else {
                return response()->json(['status' => 1,'message' => 'Time-Table visibility updated from hide to show']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 0,'message' => 'Time-Table visibility updated from hide to show']);
        }
       
    }
}
