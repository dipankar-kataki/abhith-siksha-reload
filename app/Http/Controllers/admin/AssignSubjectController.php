<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\ConvertVideoForResolution;
use App\Models\AssignClass;
use App\Models\AssignSubject;
use App\Models\Board;
use App\Models\CartOrOrderAssignSubject;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\LessonAttachmentTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AssignSubjectController extends Controller
{
    public function allSubjects()
    {

        $class_details =  AssignClass::with('boards')->where('is_activate', 1)->get();

        $assign_subject = AssignSubject::with('assignClass', 'boards')->orderBy('created_at', 'DESC')->get();



        return view('admin.course-management.subjects.index')->with(['subjects' => $assign_subject, 'classes' => $class_details]);
    }
    public function store(Request $request)
    {

        try {

            $validate = Validator::make(
                $request->all(),
                [
                    'subjectName' => 'required',
                    'assignedClass' => 'required',
                    'subject_amount' => 'required|integer|min:100|digits_between:3,7',
                    'description' => 'required',
                    'why_learn' => 'required',
                    'requirements' => 'required',
                    'image_url' => 'mimes:jpg,png,jpeg|max:1024',
                    'video_thumbnail_image_url' => 'mimes:jpg,png,jpeg|max:1024',
                    'video_url' => 'mimes:mp4,webm,mov',


                ],
                [
                    'subjectName.required' => 'Subject name is required',
                    'assignedClass.required' => 'Subject class is required',
                    'subject_amount.required' => 'Amount filed is required',
                    'subject_amount.digits_between' => 'Please insert a valid amount',
                    'subject_amount.min' => 'Please insert a valid amount',
                    'description.required' => 'Subject descripttion filed can not be null',
                    'why_learn.required' => 'Why will students learn this subject filed can not be null',
                    'requirements.required' => 'Requirements filed can nit be null',
                    'image_url.max' => "Maximum file size to upload is 1MB (1024 KB). If you are uploading a photo, try to reduce its resolution to make it under 1MB",
                    'image_url.mimes' => " Subject cover picture only supports jpg, png and jpeg file type",
                    'video_thumbnail_image_url.max' => "Maximum file size to upload is 1MB (1024 KB). If you are uploading a photo, try to reduce its resolution to make it under 1MB",
                    'video_thumbnail_image_url.mimes' => "Subject promo video thumbnail only supports jpg, png and jpeg file type",
                    'video_url.mimes' => "Video URL supports mp4,webm,mov",
                ]
            );

            if ($validate->fails()) {
                return response()->json(['status' => 0, 'message' => $validate->errors()->toArray()]);
            }

            $split_assignedClass = str_split($request->assignedClass);

            $assignedClass = $request->assignedClass;
            $assignedBoard = $request->assignedBoard;

            // Check request ID for update
            if ($request->subject_id == null) {
                $is_in_assignsubject = AssignSubject::where('subject_name', ucfirst($request->subjectName))->where('assign_class_id', $assignedClass)->where('board_id', $assignedBoard)->first();
                if ($is_in_assignsubject) {
                    return response()->json(['status' => 2, 'message' => "'$request->subjectName'.'already active'"]);
                }
            }

            // Check same subject on same board
            // $getAllSubjects = AssignSubject::where('subject_name', ucfirst($request->subjectName))->where('board_id', $request->assignedBoard)->first();
            // if () {
            //     return response()->json(['status'=> 0, 'message' => "'$request->subjectName'.'already exists'"]);
            // }

            // Condition ends here

            $document = $request->file('image_url');
            $lessonVideo = $request->file('video_url');
            $videoThumbnailImageUrl = $request->file('video_thumbnail_image_url');
            $name_slug = Str::slug($request->subjectName);
            if (!empty($document)) {
                $image_path = LessonAttachmentTrait::uploadAttachment($document, "image", $name_slug); //lesson image store
                $image_path = $image_path;
            } else {
                $image_path = '/files/subject/placeholder.jpg';
                $image_path = $image_path;
                // if ($request->subject_id == null) {
                //     $image_path = '/files/subject/placeholder.jpg';
                //     $image_path = $image_path;
                // } else {
                //     $assign_subject = AssignSubject::with('subjectAttachment')->where('id', $request->subject_id)->first();
                //     $image_path = $assign_subject->subjectAttachment->img_url;
                // }
            }

            if (!empty($lessonVideo)) {
                $video_path = LessonAttachmentTrait::uploadAttachment($lessonVideo, "video", $name_slug);
                $video_path = $video_path;

                if (!empty($videoThumbnailImageUrl)) {
                    $video_thumbnail_image_url_path = LessonAttachmentTrait::uploadAttachment($videoThumbnailImageUrl, "image", $name_slug); //lesson image store
                    $video_thumbnail_image_url_path = $video_thumbnail_image_url_path;
                } else {
                    $video_thumbnail_image_url_path = '/files/subject/placeholder.jpg';
                    $video_thumbnail_image_url_path = $video_thumbnail_image_url_path;
                    // if ($request->subject_id == null) {
                    //     $video_thumbnail_image_url_path = '/files/subject/placeholder.jpg';
                    //     $video_thumbnail_image_url_path = $video_thumbnail_image_url_path;
                    // } else {
                    //     $assign_subject = AssignSubject::with('subjectAttachment')->where('id', $request->subject_id)->first();
                    //     $video_thumbnail_image_url_path = $assign_subject->subjectAttachment->video_thumbnail_image;
                    // }
                }
            } else {
                if ($request->subject_id == null) {
                    $video_path = null;
                    $video_thumbnail_image_url_path = null;
                } else {
                    $assign_subject = AssignSubject::with('subjectAttachment')->where('id', $request->subject_id)->first();
                    $video_path = $assign_subject->attachment_origin_url;
                    // $video_thumbnail_image_url_path = $assign_subject->subjectAttachment->video_thumbnail_image;
                    if (!empty($videoThumbnailImageUrl)) {
                        $video_thumbnail_image_url_path = LessonAttachmentTrait::uploadAttachment($videoThumbnailImageUrl, "image", $name_slug); //lesson image store
                        $video_thumbnail_image_url_path = $video_thumbnail_image_url_path;
                    } else {
                        $video_thumbnail_image_url_path = '/files/subject/placeholder.jpg';
                        $video_thumbnail_image_url_path = $video_thumbnail_image_url_path;

                    }
                }
            }
            $subject_name = strtolower($request->subjectName);
            $data = [
                'subject_name' => ucfirst($subject_name),
                'image' =>  $image_path,
                'teacher_id' => $request->teacher_id,
                'subject_amount' => $request->subject_amount,
                'assign_class_id' => $assignedClass,
                'board_id' => $assignedBoard,
                'is_activate' => 0, //initially subject not active
                'description' => $request->description,
                'why_learn' => $request->why_learn,
                'requirements' => $request->requirements,
            ];

            if ($request->subject_id == null) {
                $assign_subject = AssignSubject::create($data);
            } else {
                $assign_subject = AssignSubject::find($request->subject_id);
                $assign_subject->update($data);
                if ($request->has('subject_amount')) {
                    $cart_subjects = CartOrOrderAssignSubject::where('assign_subject_id', $request->subject_id)->update(['amount' => $request->subject_amount]);
                }
            }


            // $video_path=str_replace("public/", "",$video_path);
            $data_attachment = [
                'subject_lesson_id' =>  $assign_subject->id,
                'img_url' => $image_path,
                'attachment_origin_url' => $video_path,
                'video_thumbnail_image' => $video_thumbnail_image_url_path,
                'type' => 1,

            ];
            if ($request->subject_id == null) {
                $lesson_attachment = LessonAttachment::create($data_attachment);
            } else {
                $assign_subject = AssignSubject::with('subjectAttachment')->where('id', $request->subject_id)->first();
                $assign_subject->subjectAttachment->update($data_attachment);
            }
            if ($request->subject_id == null) {
                return response()->json(['status' => 1, 'message' => 'Subject added successfully.']);
            } else {
                return response()->json(['status' => 1, 'message' => 'Subject updated successfully.']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => $th]);
        }
    }

    public function create()
    {
        $boards =  Board::where('is_activate', 1)->get();

        $teachers = $students = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'Teacher');
            }
        )->get();


        return view('admin.course-management.subjects.create')->with(['subject' => null, 'boards' => $boards, 'teachers' => $teachers]);
    }
    public function edit($id)
    {
        $class_details =  AssignClass::with('boards')->where('is_activate', 1)->get();
        $boards =  Board::where('is_activate', 1)->get();
        $assign_subject = AssignSubject::with('assignClass', 'boards')->where('is_activate', 1)->orderBy('created_at', 'DESC')->get();
        $teachers = $students = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'Teacher');
            }
        )->get();

        $subject_id = Crypt::decrypt($id);
        $subject = AssignSubject::with('subjectAttachment')->where('id', $subject_id)->first();
        $classBoard = $subject->assign_class_id . $subject->board_id;

        return view('admin.course-management.subjects.edit')->with(['subject' => $subject, 'subjects' => $assign_subject, 'classes' => $class_details, 'teachers' => $teachers, 'classBoard' => $classBoard, 'boards' => $boards]);
    }
    public function view($subject_id)
    {
        try {
            $subject = AssignSubject::where('id', Crypt::decrypt($subject_id))->first();
            $assign_teachers = $subject->assignTeacher;

            return view('admin.course-management.subjects.view')->with(['subject' => $subject, 'assignTeachers' => $assign_teachers]);
        } catch (\Throwable $th) {
            Toastr::error("Something went wrong", '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }
    public function assignSubjectLesson($lesson_id)
    {

        $lesson = Lesson::where('id', Crypt::decrypt($lesson_id))->first();


        return view('admin.course-management.subjects.lesson')->with(['lesson' => $lesson]);
    }
    public function findSubject(Request $request)
    {
        $subjects = AssignSubject::where('board_id', $request->board_id)->where('assign_class_id', $request->class_id)->where('is_activate', 1)->where('published', 1)->select('id', 'subject_name')->orderBy('created_at', 'DESC')->get();
        if ($subjects->count() > 0) {
            $data = [
                'code' => 200,
                'subjects' => $subjects
            ];
            return response()->json($data);
        }else{
            $data = [
                'code' => 400,
                'subjects' => $subjects
            ];
            return response()->json($data);
        }
    }
}
