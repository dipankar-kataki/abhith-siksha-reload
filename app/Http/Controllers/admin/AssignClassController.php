<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AssignClass;
use App\Models\AssignSubject;
use App\Models\Board;
use App\Models\TeacherAssignToSubject;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AssignClassController extends Controller
{
    public function allClasses()
    {
        $board_details = Board::where('is_activate', 1)->orderBy('created_at', 'DESC')->get();
        $assigned_classes_details = AssignClass::with('boards')->orderBy('created_at', 'DESC')->get();
        return view('admin.course-management.classes.class')->with(['boards' => $board_details, 'assignedClass' => $assigned_classes_details]);
    }

    public function assignClass(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'assignedClass' => 'required',
                'board' => 'required',
            ],
            [
                'assignedClass.required' => 'Please select class first',
                'board.required' => 'Please select board'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => 'Whoop! Something went wrong.', 'error' => $validator->errors()->first()]);
        } else {
            $is_class_assigned_already = AssignClass::where('class', $request->assignedClass)->where('board_id', $request->board)->exists();
            if ($is_class_assigned_already) {
                return response()->json(['message' => 'Whoops! Class already assigned with the board.', 'status' => 2]);
            } else {
                $create = AssignClass::create([
                    'class' => $request->assignedClass,
                    'board_id' => $request->board
                ]);

                if ($create) {
                    return response()->json(['message' => 'Class assigned successfully', 'status' => 1]);
                } else {
                    return response()->json(['message' => 'Whoops! Something went wrong. Failed to assign class.', 'status' => 2]);
                }
            }
        }
    }
    public function updateClassStatus(Request $request)
    {
        try {
            $assigned_class = AssignClass::find($request->class_id);
            if ($assigned_class->is_activate == 0) {
                $updated_status = 1;
            } else {
                $updated_status = 0;
            }
            $assigned_class->update(['is_activate' => $updated_status]);
            if ($request->status == 1) {
                return response()->json(['message' => 'Status Change Form  Deactive To Active Successfully', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Status  Change Form Active to Deactive Successfully', 'status' => 1]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Whoops! Something went wrong. Failed to update status', 'status' => 2]);
        }
    }
    public function assignTeacher(Request $request)
    {

        $subject_id =Crypt::decrypt($request->subject_id);
        $assign_subject = AssignSubject::find($subject_id);
        $board_id = $assign_subject->board_id;
        $class_id = $assign_subject->assign_class_id;
        $teachers = UserDetails::where('assign_board_id', $board_id)->where('assign_class_id', $class_id)->where('assign_subject_id', $subject_id)->select('id', 'name')->where('status',2)->get();
        if ($teachers->count() > 0) {
            $data = [
                'code' => 200,
                'teachers' => $teachers,
            ];
            return response()->json($data);
        } else {
            $data = [
                'code' => 400,
                'teachers' => $teachers,
            ];
            return response()->json($data);
        }
    }
    public function assignTeacherToSubject(Request $request){
        try {
            $subject_id=Crypt::decrypt($request->subject_id);
            $is_already_assign=TeacherAssignToSubject::where('user_id',$request->teacher_id)->where('assign_subject_id',$subject_id)->first();
            if($is_already_assign){
                $data=[
                    'code'=>201,
                    'msg'=>'Teacher already assigned for this subject'
                ];
                return response()->json($data);
            }
            $data=[
                'user_id'=>$request->teacher_id,
                'assign_subject_id'=>$subject_id,
                'status'=>1,
            ];
            $assign_teacher=TeacherAssignToSubject::create($data);

            $data=[
                'code'=>200,
                'msg'=>'Teacher assign successfully.'
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            $data=[
                'code'=>400,
                'msg'=>'Something went wrong.'
            ];
            return response()->json($data);
        }
    }
    public function changeAssignTeacherStatus(Request $request){
        $update = TeacherAssignToSubject::where('id', $request->assign_id)->update([
            'status' => $request->active
        ]);



        if ($update) {
            if ($request->active == 0) {
                return response()->json(['message' => 'Status changed from Active to inactive', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Status changed from hide to show', 'status' => 1]);
            }
        } else {
            return response()->json(['message' => 'Whoops! Something went wrong. Failed to update status', 'status' => 2]);
        }
    }
}
