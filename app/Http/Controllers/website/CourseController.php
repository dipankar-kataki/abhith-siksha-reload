<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Common\Activation;
use App\Models\AssignClass;
use App\Models\AssignSubject;
use App\Models\Board;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Cart;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\MultipleChoice;
use App\Models\Set;
use App\Models\Question;
use App\Models\SubjectLessonVisitor;
use Illuminate\Support\Facades\Crypt;
use PhpParser\Node\Expr\Assign;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $class_id = null;
        $board_details = Board::where('is_activate', 1)->get();
        $subject_details = AssignSubject::with('assignClass', 'boards')->where('is_activate', 1)->where('published', 1)->orderBy('created_at', 'DESC')->get();

        if ($request->has('assignedBoard') && $request->has('class_id')) {
            $class_id = $request->has('class_id');
            $subject_details =  AssignSubject::with('assignClass', 'boards')->where('assign_class_id', $request->class_id)->where('board_id', $request->assignedBoard)->where('is_activate', 1)->where('published', 1)->orderBy('created_at', 'DESC')->get();
        } elseif ($request->has('assignedBoard')) {
            $class_id = $request->has('class_id');
            $subject_details =  AssignSubject::with('assignClass', 'boards')->where('board_id', $request->assignedBoard)->where('is_activate', 1)->where('published', 1)->orderBy('created_at', 'DESC')->get();
        }


        return view('website.course.course')->with(['boards' => $board_details, 'subjects' => $subject_details, 'class_id' => $class_id]);
    }



    public function coursePackageFilter(Request $request)
    {
        try {

            $board_id = $request->assignedBoard;
            $assign_class_id = $request->class_id;
            $data = [
                'board_id' => $board_id,
                'class_id' => $assign_class_id,
            ];
            $board = Board::find($board_id);
            $assign_subject = AssignSubject::find($assign_class_id);
            if (($board_id == null) || ($assign_class_id == null)) {

                $datas['code'] = 500;
                $datas['message'] = 'Whoop! Something went wrong.';
                return response()->back()->json(['datas' => $datas]);
            } else {

                $all_subjects = AssignSubject::where(['board_id' => $board_id, 'assign_class_id' => $assign_class_id, 'is_activate' => 1])->get();


                if ($all_subjects != null) {
                    $total_amount = $all_subjects->sum('subject_amount');
                    $datas =
                        [
                            'all_subjects' => $all_subjects,
                            'total_amount' => $total_amount,
                        ];
                    $data =
                        [
                            'code' => 200,
                            'message' => 'All filtering data are Display',
                            'datas' => $datas,
                        ];
                    return response()->json(['data' => $data]);
                } else {
                    $data =
                        [
                            'code' => 500,
                            'message' => 'Sorry! subject is not available for this time.',

                        ];
                    return response()->back()->json(['datas' => $data]);
                }

                $data =
                    [
                        'code' => 500,
                        'message' => 'Sorry! subject is not available for this time.',

                    ];
                return response()->back()->json(['datas' => $data]);
            }
        } catch (\Throwable $th) {
            return response()->back()->json(['message' => 'Whoops! Something went wrong. Failed to Find Packages.', 'status' => 2]);
        }
    }
    public function enrollPackage($subject_id)
    {


        try {

            $subject = AssignSubject::find(Crypt::decrypt($subject_id));
            $subject_id = $subject->id;
            $subjects = AssignSubject::with('review')->select('id', 'subject_name', 'image', 'subject_amount', 'subject_amount')->where('board_id', $subject->board_id)->where('assign_class_id', $subject->assign_class_id)->where('is_activate', 1)->where('published', 1)->get();
            $total_subject = $subjects->count();
            $board = Board::find($subject->board_id);
            $Assignclass = AssignClass::find($subject->assign_class_id);
            $total_amount = 0;
            foreach ($subjects as $key => $subjectDetail) {
                if (subjectAlreadyPurchase($subjectDetail->id) == 1) {
                    $total_amount = $total_amount + 0;
                } else {
                    $total_amount = $total_amount + $subjectDetail->subject_amount;
                }
            }

            $all_subject = [];
            foreach ($subjects as $key => $subjectDetail) {
                if ($subjectDetail->review->count() > 0) {
                    $total_rating = $subjectDetail->review()->count() * 5;
                    $rating_average = $subjectDetail->review()->sum('rating') / $total_rating * 5;
                } else {
                    $rating_average = "No reviews yet";
                }

                $data = [
                    'id' => $subjectDetail->id,
                    'subject_name' => $subjectDetail->subject_name,
                    'image' => $subjectDetail->image,
                    'subject_amount' => $subjectDetail->subject_amount,
                    'rating' => $subjectDetail,
                    'already_purchase' => subjectAlreadyPurchase($subjectDetail->id),

                ];
                $all_subject[] = $data;
            }




            $data = [
                'subjects' => $all_subject,
                'total_amount' => $total_amount,
                'board' => $board,
                'assignclass' => $Assignclass,
                'subjectamount' => $subject->subject_amount,
            ];

            return view('website.course.enroll', compact('data', 'subject_id', 'total_subject'));
        } catch (\Throwable $th) {
            //throw $th;
        }











        // return view('website.course.filter-course', compact('board', 'class', 'subjects', 'total_amount'));
    }
    public function  subjectDetails($subject_id)
    {

        $subject_id = Crypt::decrypt($subject_id);

        $subject = AssignSubject::with(['lesson' => function ($query) {
            $query->with('lessonAttachment');
        }, 'subjectAttachment', 'assignClass', 'boards'])->where('id', $subject_id)->first();
        $lesson = $subject->lesson->first();
        $topicDocuments = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 1)->where('assign_subject_id', $subject_id)->where('status',1)->get();
        $topicVideos = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 2)->where('assign_subject_id', $subject_id)->where('status',1)->get();
        $topicArticles = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 3)->where('assign_subject_id', $subject_id)->where('status',1)->get();
        $mcq_questions = Lesson::with('activeSets')->where('id', $lesson->id)->where('assign_subject_id', $subject_id)->first();
        $next_lesson_id = Lesson::where('id', '>', $lesson->id)->where('parent_id', null)->orderBy('id')->where('assign_subject_id', $subject_id)->where('status',1)->first();
        if ($next_lesson_id == null) {
            $next_lesson_id = false;
        } else {
            $next_lesson_id = true;
        }
        $previous_lesson_id = false;

        // $previous_lesson_id = Lesson::where('id', '<', $lesson->id)->orderBy('id','desc')->first()->id;
        // $next_lesson_id = Lesson::where('id', '>', $lesson->id)->orderBy('id')->first()->id;
        return view('website.my_account.lesson_details', compact('lesson', 'topicDocuments', 'topicVideos', 'topicArticles', 'mcq_questions', 'next_lesson_id', 'previous_lesson_id'));
    }
    public function getLessonDetails($lesson_id, $type)
    {

        try {

            $lesson = Lesson::find(Crypt::decrypt($lesson_id));

            if ($type == 1) {
                $lesson = Lesson::where('id', '<', $lesson->id)->where('assign_subject_id', $lesson->assign_subject_id)->where('parent_id', null)->orderBy('id', 'desc')->first();
            } else {
                $lesson = Lesson::where('id', '>', $lesson->id)->where('assign_subject_id', $lesson->assign_subject_id)->where('parent_id', null)->orderBy('id')->first();
            }
            $topicDocuments = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 1)->where('assign_subject_id', $lesson->assign_subject_id)->where('status',1)->get();
            $topicVideos = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 2)->where('assign_subject_id', $lesson->assign_subject_id)->where('status',1)->get();
            $topicArticles = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 3)->where('assign_subject_id', $lesson->assign_subject_id)->where('status',1)->get();
            $mcq_questions = Lesson::with('Sets')->where('id', $lesson->id)->where('assign_subject_id', $lesson->assign_subject_id)->first();
            $next_lesson_id = Lesson::where('id', '>', $lesson->id)->where('parent_id', null)->where('assign_subject_id', $lesson->assign_subject_id)->orderBy('id')->first();

            $previous_lesson_id = Lesson::where('id', '<', $lesson->id)->where('parent_id', null)->where('assign_subject_id', $lesson->assign_subject_id)->orderBy('id', 'desc')->first();
            if ($next_lesson_id == null) {
                $next_lesson_id = false;
            } else {
                $next_lesson_id = true;
            }
            if ($previous_lesson_id == null) {
                $previous_lesson_id = false;
            } else {
                $previous_lesson_id = true;
            }

            return view('website.my_account.lesson_details', compact('lesson', 'topicDocuments', 'topicVideos', 'topicArticles', 'mcq_questions', 'next_lesson_id', 'previous_lesson_id'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function video($id)
    {
        $data = LessonAttachment::where('id', Crypt::decrypt($id))->with('lesson')->first();
        $lesson_id = $data->lesson->id;
        $subject_id = $data->lesson->assign_subject_id;
        $user_id = auth()->user()->id;

        return view('website.my_account.video', compact('data', 'lesson_id', 'subject_id', 'user_id'));
    }
    public function LessonVideoWatchTime(Request $request)
    {
        try {
            $lesson_id = $request->lesson_id;
            $subject_id = $request->subject_id;
            $user_id = $request->user_id;
            $lesson = Lesson::find($lesson_id);
            $total_video_duration = $lesson->lessonAttachment->video_duration;
            $data = [
                'subject_id' => $subject_id,
                'lesson_subject_id' => $lesson_id,
                'visitor_id' => $user_id,
                'type' => 2,
                'video_watch_time' => $total_video_duration,
                'total_video_duration' => $total_video_duration,
            ];
            $subjectlessonvisitor = SubjectLessonVisitor::create($data);
            $returnData = [
                "code" => 200,
                "status" => 1,
                "message" => "Data stored successfully",
                "data" => $subjectlessonvisitor,

            ];
            return response()->json(['status' => 1, 'result' => $returnData]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function LessonVideoWatchTimeUpdate(Request $request)
    {
        try {

            $start_time = $request->pause_time;
            $end_time = $request->play_time;
            $subject_lesson_visitor_id = $request->subject_lesson_visitor_id;
            $subject_lesson_visitor = SubjectLessonVisitor::find($subject_lesson_visitor_id);

            if ($subject_lesson_visitor->video_watch_time == $subject_lesson_visitor->total_video_duration) {
                $previous_time_duration = "00:00:00";
            } else {
                $previous_time_duration = $subject_lesson_visitor->video_watch_time;
            }
            $second_time = timeDifference($end_time, $start_time);

            $data = [
                'video_watch_time' => addTime($previous_time_duration, $second_time),
                'subject_lesson_visitor_id' => $subject_lesson_visitor_id,
            ];
            $subject_lesson_visitor = SubjectLessonVisitor::find($subject_lesson_visitor_id);
            $subject_lesson_visitor->update($data);
            return response()->json($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
