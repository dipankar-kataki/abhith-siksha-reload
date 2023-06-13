<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\Subject;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    //
    protected function index()
    {
        $subjects = Subject::orderBy('id', 'DESC')->paginate(10);

        return view('admin.master.subjects.subjects', \compact('subjects'));
    }

    protected function create(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ],
            ['name.required' => 'Subject Name is required']
        );
        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }
        Subject::create([
            'name' => $request->name
        ]);
        $request->session()->flash('subject_created', 'Subject created successfully');
        return \redirect()->back();
    }

    protected function published(Request $request)
    {
        $subject = AssignSubject::find($request->subjectId);
        $subject_content=Lesson::where('assign_subject_id',$subject->id)->where('parent_id','!=',null)->get();
        if(($subject_content->count()==0)){
            return response()->json(['status' => 0, 'message' => 'Please add lesson content before published the subject']);
        }
        $subject->published = $request->published;
        $subject->save();


        if ($request->published == 0) {
            return response()->json(['status' => 1, 'message' => 'Subject publicity changed from published to not published']);
        } else {
            $subject = AssignSubject::find($request->subjectId);
            $subject->is_activate = 1;
            $subject->save();
            return response()->json(['status' => 1, 'message' => 'Subject publicity changed from not published to published']);
        }
    }

    protected function editSubject(Request $request)
    {
        $subject_id = \Crypt::decrypt($request->id);
        $subject = Subject::find($subject_id);

        return view('admin.master.subjects.edit', \compact('subject'));
    }

    protected function edit(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $subject_id = \Crypt::decrypt($request->id);
        $subject = Subject::find($subject_id);
        $subject->name = $request->name;
        $subject->save();
        $request->session()->flash('subject_update_message', 'Subject name updated successfully');

        return redirect()->back();
    }
    public function getDemoVideo($lesson_id)
    {
        try {
            $lesson = Lesson::with('lessonAttachment')->where('id', $lesson_id)->first();
            $all_lessons = Lesson::with('lessonAttachment')->whereHas('lessonAttachment', function ($query) {
                $query->where('preview', 1);
            })->where('assign_subject_id', $lesson->assign_subject_id)->where('type', 2)->get();
            $result = [
                'lesson' => $lesson,
                'all_lessons' => $all_lessons,
            ];

            return response()->json(['status' => 1, 'result' => $result]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'result' => []]);
        }
    }
    public function active($subject_id)
    {
        try {
            $assignSubject = AssignSubject::find(Crypt::decrypt($subject_id));
            if ($assignSubject->is_activate == 0) {
                $assignSubject->update(['is_activate' => 1]);
                Toastr::success('Subject activate changed from inactivate to activate', '', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            } else {

                $assignSubject->update(['is_activate' => 0]);
                if($assignSubject->published==1){
                    $assignSubject->update(['published' => 0]);
                }
                Toastr::success('Subject activate changed from activate to inactivate', '', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
