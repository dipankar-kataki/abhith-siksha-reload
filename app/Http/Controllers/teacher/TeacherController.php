<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\UserDetails;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{

    public function store(Request $request)
    {
        try {

            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    // 'total_experience_year' => 'numeric',
                    // 'total_experience_month' => 'numeric',
                    'education' => 'required|string',
                    'board_id' => 'required',
                    'assign_class_id' => 'required',
                    'assign_subject_id' => 'required',
                    'hslc_percentage' => 'required|numeric|min:10|max:100',
                    'hs_percentage' => 'required|numeric|min:10|max:100',
                    'resume' => 'required|mimes:pdf',
                    'current_ctc'=>'numeric|between:0,9999999999.99',
                    'teacherdemovideo' => 'required',
                ],
                [
                    'name.required' => 'Subject Name is required',
                    'email.required' => 'Email is required',
                    'phone.required' => 'Phone number is required',
                    'gender.required' => 'gender is required',
                    'dob.required' => 'DOB is required',
                    // 'total_experience_year.numeric' => 'Insert a valide total experience year',
                    // 'total_experience_month.numeric' => 'Insert a valide total experience year',
                    'education.required' => 'Education filed is required',
                    'board_id.required' => 'Board is required',
                    'assign_class_id' => 'Class is required',
                    'assign_subject_id' => 'Subject is required',
                    'hslc_percentage' => 'HSCL percentage is required',
                    'hslc_percentage.numeric' => 'Please insert a valid data in HSLC percentage filed',
                    'hslc_percentage.min' => 'HSLC percentage must be in between 10 to 100',
                    'hslc_percentage.max' => 'HSLC percentage must be in between 10 to 100',
                    'hs_percentage' => 'HS percentage is required',
                    'hs_percentage.numeric' => 'Please insert a valid data in HS percentage filed',
                    'hs_percentage.min' => 'HS percentage must be in between 10 to 100',
                    'hs_percentage.max' => 'HS percentage must be in between 10 to 100',
                    'resume.required' => 'Please upload your Resume',
                    'resume.mimes'=>'Resume should be in .pdf formate',
                    'teacherdemovideo.required' => 'Please upload a demo video which and video duration must be less then 5 minutes.',
                    'current_ctc.numeric'=>'Please insert a valid CTC',
                    ]
            );
            if ($validate->fails()) {
                return response()->json(['status' => 0, 'message' => $validate->errors()->toArray()]);
            }

            $resume = $request->resume;
            $teacherdemovideo = $request->teacherdemovideo;
            if (!empty($resume)) {

                $new_name = date('d-m-Y-H-i-s') . '_' . $resume->getClientOriginalName();
                // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
                $resume->move(public_path('/files/teacher/resume/'), $new_name);
                $resume_url = 'files/teacher/resume/' . $new_name;
            }
            if (!empty($teacherdemovideo)) {
                $new_name = date('d-m-Y-H-i-s') . '_' . $teacherdemovideo->getClientOriginalName();
                // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
                $teacherdemovideo->move(public_path('/files/teacher/demovideo/'), $new_name);
                $teacherdemovideo_url = 'files/teacher/demovideo/' . $new_name;
            }
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'total_experience_year' => $request->total_experience_year,
                'total_experience_month' => $request->total_experience_month,
                'education' => $request->education,
                'assign_board_id' => $request->board_id,
                'assign_class_id' => $request->assign_class_id,
                'assign_subject_id' => $request->assign_subject_id,
                'hslc_percentage' => $request->hslc_percentage,
                'hs_percentage' => $request->hs_percentage,
                'current_organization' => $request->current_organization,
                'current_designation' => $request->current_designation,
                'current_ctc' => $request->current_ctc,
                'resume_url' => url('') . '/' . $resume_url,
                'teacherdemovideo_url' => url('') . '/' . $teacherdemovideo_url,
                'status' => 1,
                'user_id' => auth()->user()->id,
            ];
            $user_details = UserDetails::where('user_id', auth()->user()->id)->where('status', '!=', 0)->first();
            if ($user_details) {
                $user_details = UserDetails::where('user_id', auth()->user()->id)->first();
                $user_details->update($data);
                return response()->json(['status' => 1, 'message' => 'Application updated successfully.']);
            } else {
                UserDetails::create($data);
                return response()->json(['status' => 1, 'message' => 'Application submitted successfully.']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => $th->getMessage()]);
        }
    }
    public function index()
    {
        if (auth()->user()->hasRole('Teacher')) {
            $applications = UserDetails::with('user')->where('status', '!=', 0)->where('user_id', auth()->user()->id)->get();
        } else {
            $applications = UserDetails::with('user')->where('status', '!=', 0)->get();
        }


        return view('admin.teacher.index', compact('applications'));
    }
    public function details($teacher_id)
    {
        try {

            $user_details = UserDetails::with('user')->where('id', Crypt::decrypt($teacher_id))->first();
            $resume = pathinfo(public_path($user_details->resume_url));
            $resume_extension = $resume['extension'];
            return view('admin.teacher.application', compact('user_details', 'resume_extension'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function approvedApplication($user_detail_id)
    {
        try {

            $data = [
                'status' => 2,
                'referral_id' => teacherReferralId(),
            ];
            $user_details = UserDetails::find(Crypt::decrypt($user_detail_id));
            $user_details->update($data);
            Toastr::success('Application approved sccessfully.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Something went wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function rejectApplication(Request $request)
    {
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'teacher_id' => 'required'
                ],
                [
                    'teacher_id.required' => 'ID not found'
                ]
            );

            // If error
            if ($validate->fails()) {
                return response()->json(['status' => 0, 'message' => $validate->errors()->first()]);
            }

            // If success
            UserDetails::find(Crypt::decrypt($request->teacher_id))->update([
                'status' => 3
            ]);

            return response()->json(['status' => 1, 'message' => 'Teacher rejected']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 0, 'message' => $th->getMessage()]);
        }
    }
}
