<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Board;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Set;
use App\Models\SubjectLessonVisitor;
use App\Models\UserPracticeTest;
use App\Models\UserPracticeTestAnswer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function findSubject(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'board' => 'required',
                'standard' => 'required',
            ]);
            if ($validator->fails()) {
                $data = [
                    "code" => 400,
                    "status" => 0,
                    "message" => $validator->errors(),

                ];
                return response()->json(['status' => 0, 'result' => $data]);
            }
            //get all subject
            // $subjects = AssignSubject::with('review')->select('id', 'subject_name', 'image', 'subject_amount', 'subject_amount')->where('board_id',2)->where('assign_class_id', 3)->where('is_activate', 1)->where('published', 1)->get();
            $subjects = AssignSubject::with('review')->whereHas('boards', function ($query) use ($request) {
                $query->where('exam_board', $request->board);
            })->whereHas('assignClass', function ($query) use ($request) {
                $query->where('class', $request->standard);
            })->select('id', 'subject_name', 'image', 'subject_amount', 'subject_amount')->where('is_activate', 1)->where('published', 1)->orderBy('created_at', 'DESC')->get();

            // calculate total amount
            $total_amount = 0;
            foreach ($subjects as $key => $subject) {
                if (subjectAlreadyPurchase($subject->id) == 1) {
                    $total_amount = $total_amount + 0;
                } else {
                    $total_amount = $total_amount + $subject->subject_amount;
                }
            }
            $all_subject = [];
            foreach ($subjects as $key => $subject) {
                if ($subject->review->count() > 0) {
                    $total_rating = $subject->review()->count() * 5;
                    $rating_average =  round($subject->review()->sum('rating') / $total_rating * 5);
                } else {
                    $rating_average = "No reviews yet";
                }

                $data = [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'image' => $subject->image,
                    'subject_amount' => $subject->subject_amount,
                    'rating' => $rating_average,
                    'already_purchase' => subjectAlreadyPurchase($subject->id),
                ];
                $all_subject[] = $data;
            }




            $data = [
                'subjects' => $all_subject,
                'total_amount' => $total_amount,
            ];
            if (!$subjects->isEmpty()) {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all board",
                    "result" => $data,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "result" => $data,
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
    public function subjectDetails(Request $request)
    {
        try {
            $id = $_GET['subject_id'];
            $subject = AssignSubject::select('id', 'subject_name', 'subject_amount', 'assign_class_id', 'board_id', 'description', 'why_learn', 'created_at')->with(['assignClass:id,class', 'boards:id,exam_board', 'lesson', 'lesson.topics', 'subjectAttachment'])->where('id', $id)->first();
            if ($subject->review->count() > 0) {
                $total_rating = $subject->review()->count() * 5;
                $rating_average = round($subject->review()->sum('rating') / $total_rating * 5);
            } else {
                $rating_average = "No reviews yet";
            }
            $subject_promo_video = $subject->subjectAttachment->attachment_origin_url;
            if ($subject_promo_video != null) {
                $attachment_type = "video";
                $subject_attachment = $subject->subjectAttachment->attachment_origin_url;
            } else {
                $attachment_type = "image";
                $subject_attachment = $subject->image;
            }
            $subject_attachment = [
                'attachment_type' => $attachment_type,
                'attachment' => $subject_attachment,


            ];
            $total_lesson = $subject->lesson->count();
            $total_topic = Lesson::where('id', $id)->where('parent_id', null)->count();
            $total_image_pdf = Lesson::where('assign_subject_id', $id)->where('type', 1)->where('status', 1)->get()->count();
            $total_video = Lesson::where('assign_subject_id', $id)->where('type', 2)->where('status', 1)->get()->count();
            $total_article = Lesson::where('assign_subject_id', $id)->where('type', 3)->where('status', 1)->get()->count();
            $subject_details = [
                'id' => $subject->id,
                'subject_name' => $subject->subject_name,
                'subject_amount' => $subject->subject_amount,
                'description' => $subject->description,
                'why_learn' => $subject->why_learn,
                'created_at' => $subject->created_at,
                'board_id' => $subject->boards->id,
                'board_name' => $subject->boards->exam_board,
                'class_id' => $subject->assignClass->id,
                'class_name' => $subject->assignClass->class,
                'total_lesson' => $total_lesson,
                'total_topic' => $total_topic,
                'total_image_pdf' => $total_image_pdf,
                'total_video' => $total_video,
                'total_article' => $total_article,
                'rating' => $rating_average,
                'already_purchase' => subjectAlreadyPurchase($subject->id),
                'already_incart'=>subjectAlreadyInCart($subject->id),

            ];



            if (!$subject == null) {
                $result = [

                    'subject_details' => $subject_details,
                    'subject_attachment' => $subject_attachment,

                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all board",
                    "result" => $result,

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
    public function LessonDetails(Request $request)
    {
        try {
            $id = $_GET['subject_id'];

            $lessons = Lesson::select('id', 'name', 'assign_class_id', 'board_id', 'assign_subject_id', 'created_at')->with(['assignClass:id,class', 'board:id,exam_board', 'assignSubject:id,subject_name', 'topics', 'lessonAttachment'])->where('assign_subject_id', $id)->where('parent_id', null)->paginate(15);
            $total_lesson = Lesson::select('id', 'name', 'assign_class_id', 'board_id', 'assign_subject_id', 'created_at')->with(['assignClass:id,class', 'board:id,exam_board', 'assignSubject:id,subject_name', 'topics', 'lessonAttachment'])->where('assign_subject_id', $id)->where('parent_id', null)->get()->count();
            $lessonData = [];
            $lessons->getCollection()->transform(function ($lesson) {
                $pdf = 0;
                $img = 0;

                $topic = $lesson->topics->count();
                $total_image_pdfs = Lesson::with(['lessonAttachment'])->where('type', 1)->where('status', 1)->where('parent_id', $lesson->id)->get();
                if ($total_image_pdfs != null) {
                    foreach ($total_image_pdfs as $key => $data) {
                        $ext = pathinfo($data->lessonAttachment->img_url, PATHINFO_EXTENSION);
                        if ($ext == "pdf") {
                            $pdf += 1;
                        } else {
                            $img += 1;
                        }
                    }
                }
                $total_video = Lesson::with(['lessonAttachment'])->where('type', 2)->where('status', 1)->where('parent_id', $lesson->id)->get()->count();
                $total_article = Lesson::with(['lessonAttachment'])->where('type', 3)->where('status', 1)->where('parent_id', $lesson->id)->get()->count();
                $subject_content =
                    [
                        'total_pdf' => $pdf,
                        'total_image' => $img,
                        'total_video' => $total_video,
                        'total_article' => $total_article,
                        'total_mcq_set'=>$lesson->activeSets->count(),
                    ];

                $lesson = [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'board_id' => $lesson->board->id,
                    'board_name' => $lesson->board->exam_board,
                    'class_id' => $lesson->assignClass->id,
                    'class_name' => $lesson->assignClass->class,
                    'subject_id' => $lesson->assignSubject->id,
                    'subject_name' => $lesson->assignSubject->subject_name,
                    'total_content' => $subject_content,

                ];
                return $lessonData[] = $lesson;
            });
            // foreach ($lessons as $key => $lesson) {

            // }

            if ($lessons->count() > 0) {

                $data = [
                    "code" => 200,
                    "message" => "All Lesson",
                    "total_lesson" => $total_lesson,
                    "result" => $lessons,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "message" => "No record found",
                    "total_lesson" => $total_lesson,
                    "result" => $lessons,
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
    public function LessonContentDetails(Request $request)
    {
        try {
            $id = $_GET['lesson_id'];
            $page = $_GET['page'];
            $sub_topic_content = [];
            $lesson = Lesson::with(["lessonAttachment", "subTopics"])->where('parent_id', $id)->where('type', 3)->where('status', 1)->paginate();

            if ($lesson != null) {

                $topic_content = [];

                foreach ($lesson as $key => $topic) {


                    $topic_content_data =
                        [
                            'id' => $topic->id,
                            'title' => $topic->name,
                            'content' => $topic->content ?? null,
                            'preview'=>$topic->preview,
                            'purchase'=>subjectAlreadyPurchase($topic->assign_subject_id),
                        ];


                    if ($topic->subTopics->where('type', 3)) {

                        foreach ($topic->subTopics->where('type', 3) as $key => $sub_topic) {


                            $sub_topic_content =
                                [
                                    'id' => $sub_topic->id,
                                    'title' => $sub_topic->name,
                                    'content' => $sub_topic->content ?? null,
                                    'preview'=>$sub_topic->preview,
                                    'purchase'=>subjectAlreadyPurchase($sub_topic->assign_subject_id),

                                ];
                            $topic_content[] = $sub_topic_content;
                        }
                    }

                    $topic_content[] = $topic_content_data;
                }
                $array = array_filter($topic_content, function ($x) {
                    return !empty($x);
                });
                if (sizeof($array) > 0) {
                    $all_videos = [];
                    foreach ($array as $key => $data) {
                        $all_contents[] = $data;
                    }
                    $count = sizeof($array);
                    $content_details = [
                        'contents' => $all_contents,
                        'total_contents' => $count,

                    ];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "All Videos",
                        "result" => $content_details,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $content_details = [
                        'contents' => [],
                        'total_contents' => 0,

                    ];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "No Records found",
                        "result" => $content_details,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
            } else {
                $content_details = [
                    'contents' => [],
                    'total_contents' => 0,

                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Records found",
                    "result" => $content_details,
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
    public function LessonVideoDetails(Request $request)
    {
        try {
            $id = $_GET['lesson_id'];
            $page = $_GET['page'];
            $sub_topic_video = [];
            $lesson = Lesson::with(["lessonAttachment", "subTopics"])->where('parent_id', $id)->where('type', 2)->where('status', 1)->paginate();

            if ($lesson != null) {

                $topic_video = [];

                foreach ($lesson as $key => $topic) {


                    $topic_video_data =
                        [
                            'id' => $topic->id,
                            'title' => $topic->name,
                            'video_thumbnail_image' => $topic->lessonAttachment->video_thumbnail_image ?? null,
                            'original_video_path' => $topic->lessonAttachment->attachment_origin_url ?? null,
                            'video_size_480' => $topic->lessonAttachment->video_resize_480 ?? null,
                            'video_size_720' => $topic->lessonAttachment->video_resize_720 ?? null,
                            'video_duration' => $topic->lessonAttachment->video_duration ?? "00:00:00",
                            'preview' => $topic->preview,
                            'purchase'=>subjectAlreadyPurchase($topic->assign_subject_id),
                        ];


                    if ($topic->subTopics->where('type', 2)) {

                        foreach ($topic->subTopics->where('type', 2) as $key => $sub_topic) {


                            $sub_topic_video =
                                [
                                    'id' => $sub_topic->id,
                                    'title' => $sub_topic->name,

                                    'original_video_path' => $sub_topic->lessonAttachment->attachment_origin_url ?? null,
                                    'video_size_480' => $sub_topic->lessonAttachment->video_resize_480 ?? null,
                                    'video_size_720' => $sub_topic->lessonAttachment->video_resize_720 ?? null,
                                    'video_duration' => $topic->lessonAttachment->video_duration ?? "00:00:00",
                                    'preview' => $sub_topic->preview,
                                    'purchase'=>subjectAlreadyPurchase($sub_topic->assign_subject_id),
                                ];
                            $topic_video[] = $sub_topic_video;
                        }
                    }
                    $topic_video[] = $topic_video_data;
                }
                $array = array_filter($topic_video, function ($x) {
                    return !empty($x);
                });
                if (sizeof($array) > 0) {
                    $all_videos = [];
                    foreach ($array as $key => $data) {
                        $all_videos[] = $data;
                    }
                    $count = sizeof($array);
                    $video_details = [
                        'user_id'=>auth()->user()->id,
                        'videos' => $all_videos,
                        'total_videos' => $count,

                    ];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "All Videos",
                        "result" => $video_details,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $video_details = [
                        'videos' => [],
                        'total_videos' => 0,
                        'user_id'=>auth()->user()->id,
                    ];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "No Records found",
                        "result" => $video_details,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
            } else {
                $video_details = [
                    'videos' => [],
                    'total_videos' => 0,
                    'user_id'=>auth()->user()->id,
                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Records found",
                    "result" => $video_details,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something want wrong.",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function LessonPdfDetails(Request $request)
    {
        try {
            $id = $_GET['lesson_id'];
            $page = $_GET['page'];
            $sub_topic_pdf = [];
            $lesson = Lesson::with(["lessonAttachment", "subTopics"])->where('parent_id', $id)->where('parent_id', '!=', null)->where('type', 1)->where('status', 1)->paginate();

            if ($lesson != null) {

                $topic_pdf = [];

                foreach ($lesson as $key => $topic) {

                    $path = basename($topic->lessonAttachment->img_url);
                    $topic_pdf_data =
                        [
                            'id' => $topic->id,
                            'title' => $topic->name,
                            'pdf_url' => $topic->lessonAttachment->img_url ?? null,
                            'pdf_name' => $path,
                            'preview'=>$topic->preview,
                            'purchase'=>subjectAlreadyPurchase($topic->assign_subject_id),
                        ];


                    if ($topic->subTopics->where('type', 1)) {

                        foreach ($topic->subTopics->where('type', 1) as $key => $sub_topic) {

                            $sub_topic_pdf =
                                [
                                    'id' => $sub_topic->id,
                                    'title' => $sub_topic->name,
                                    'pdf_url' => $sub_topic->lessonAttachment->img_url ?? null,
                                    'preview'=>$sub_topic->preview,
                                    'purchase'=>subjectAlreadyPurchase($sub_topic->assign_subject_id),
                                ];
                            $topic_pdf[] = $sub_topic_pdf;
                        }
                    }
                    $topic_pdf[] = $topic_pdf_data;
                }

                $array = array_filter($topic_pdf, function ($x) {
                    return !empty($x);
                });
                if (sizeof($array) > 0) {
                    $all_pdfs = [];
                    foreach ($array as $key => $data) {
                        $all_pdfs[] = $data;
                    }
                    $count = sizeof($array);
                    $pdf_details = [
                        'pdfs' => $all_pdfs,
                        'total_pdfs' => $count,

                    ];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "All Videos",
                        "result" => $pdf_details,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $pdf_details = [
                        'pdfs' => [],
                        'total_pdfs' => 0,

                    ];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "No Records found",
                        "result" => $pdf_details,
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
            } else {
                $pdf_details = [
                    'pdfs' => [],
                    'total_pdfs' => 0,

                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Records found",
                    "result" => $pdf_details,
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
    public function LessonTopicsDetails(Request $request){
        try {
            $id = $_GET['lesson_id'];
            $lesson=Lesson::find($id);
            $topics=$lesson->topics;
            $lessonTopics=[];
            if($topics){
                   foreach($lesson->topics as $key=>$topic){
                    if($topic->type==1){
                      $type="docs";
                    }elseif($topic->type==2){
                        $type="videos";
                    }else{
                        $type="articles";
                    }
                    $data=[
                        'title' => $topic->name,
                        'preview'=>$topic->preview,
                        'type'=>$type,
                    ];
                    $lessonTopics[] = $data;
                   }
                   $data = [
                    "code" => 200,
                    "message" => "All Topics",
                    "result"=>$lessonTopics,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }else{
                $data = [
                    "code" => 200,
                    "message" => "No record found",
                    "result"=>$lessonTopics,
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
    public function LessonTopics(Request $request)
    {
        try {
            $id = $_GET['lesson_id'];
            $page = $_GET['page'];

            $lesson = Lesson::with(['topics:parent_id,name', 'subTopics'])->where('parent_id', $id)->first();

            if ($lesson->topics) {
                $lesson_topic = $lesson->topics()->where('status',1)->paginate(5);
                $topics = [];
                foreach ($lesson_topic as $key => $topic) {
                    $sub_topic_count = $topic->subTopics()->where('status',1)->count();
                    $topic = [
                        'id' => $topic->id,
                        'name' => $topic->name,
                        'sub_topic_count' => $sub_topic_count

                    ];
                    $topics[] = $topic;
                }



                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "All Topics",
                    "result" => $topics,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Data Available",

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
    public function LessonMCQ(Request $request)
    {
        try {
            $id = $_GET['lesson_id'];

            $mcq_sets = Lesson::with('activeSets')->where('id', $id)->first();

            if (!$mcq_sets->activeSets->isEmpty()) {
                $all_mcq_set = [];
                foreach ($mcq_sets->activeSets as $key => $mcq_set) {
                    $data = [
                        'id' => $mcq_set->id,
                        'name' => $mcq_set->set_name,
                        'total_question' => $mcq_set->activequestion->count(),
                        'is_played' => isPracticeTestPlayed($mcq_set->id),
                    ];
                    $all_mcq_set[] = $data;
                }

                $data = [
                    'code' => 200,
                    'status' => 1,
                    'message' => "All MCQ Set",
                    'result' => $all_mcq_set,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "result" => [],
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
    public function LessonMcqQuestion(Request $request)
    {
        try {

            $set_id = $_GET['set_id'];
            $page = $_GET['page'];
            $set_question = Set::with('activequestion')->where('id', $set_id)->first();
            if (!$set_question) {
                $result = [
                    'set_name' => null,
                    'total_question' => 0,
                    'mcq_question' => [],
                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Record Found",
                    "result" => $result,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
            if (!$set_question->activequestion->isEmpty()) {
                $all_questions = $set_question->activequestion()->paginate(1);
                $options = [];
                foreach ($all_questions as $key => $question) {

                    $options[] = $question->option_1;
                    $options[] = $question->option_2;
                    $options[] = $question->option_3;
                    $options[] = $question->option_4;
                    $data = [
                        'id' => $question->id,
                        'question' => $question->question,
                        'options' => $options,
                        'correct_answer' => $question->correct_answer,

                    ];
                }

                $result = [
                    'set_name' => $set_question->set_name,
                    'total_question' => $set_question->activequestion->count(),
                    'mcq_question' => $data,
                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "All MCQ Questions",
                    "result" => $result,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $result = [
                    'set_name' => null,
                    'total_question' => 0,
                    'mcq_question' => [],
                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Record Found",
                    "result" => $result,
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
    public function startMcq(Request $request)
    {
        try {

            $set_id = $request->set_id;
            $start_time = $request->start_time;
            $end_time = $request->endtime;
            $total_duration = timeDifference($start_time, $end_time);
            $findSet = Set::with('activequestion')->where('id', $set_id)->first();
            $total_question = $findSet->activequestion->count();
            $answers = $request->answers;
            $user_practice_test = UserPracticeTest::where('set_id', $set_id)->where('user_id', auth()->user()->id)->first();
            if ($user_practice_test) {
                $user_practice_test->delete();
                $user_practice_test->userPracticeTestAnswer()->delete();
            }
            $user_practice_test = [
                'user_id' => auth()->user()->id,
                'set_id' => $findSet->id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_duration' => $total_duration,
            ];

            $user_practice_test_store = UserPracticeTest::create($user_practice_test);
            foreach ($answers as $key => $answer) {

                $is_correct = 0;
                $question = Question::find($answer['question_id']);
                if ($question->correct_answer == $answer['user_answer']) {
                    $is_correct = 1;
                }
                $data = [
                    'user_practice_test_id' => $user_practice_test_store->id,
                    'question_id' => $question->id,
                    'answer' => $question->correct_answer,
                    'user_answer' => $answer['user_answer'],
                    'is_correct' => $is_correct
                ];
                $user_pract_test_answer = UserPracticeTestAnswer::create($data);
            }
            $user_practice_test = UserPracticeTest::with('userPracticeTestAnswer')->where('id', $user_practice_test_store->id)->first();
            $update_user_practice_test_store =
                [
                    'total_attempts' => $user_practice_test->UserPracticeTestAnswer->count(),
                    'total_correct_count' => $user_practice_test->correctAnswer->count(),
                ];
            $user_practice_test->update($update_user_practice_test_store);
            $data = ['user_practice_test_id' => $user_practice_test->id];
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Practice Test submitted successfully",
                "result" => $data,
            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",
                "result" => [],
            ];
            return response()->json(['status' => 1, 'result' => $th]);
        }
    }
    public function practiceTestReport(Request $request)
    {
        try {
            $id = $_GET['id'];
            $user_practice_test = UserPracticeTest::with('userPracticeTestAnswer')->where('id', $id)->first();


            $attempted_question = $user_practice_test->userPracticeTestAnswer->count();
            $correct_attempted = $user_practice_test->correctAnswer->count();
            $analysis_on_attempted_question = ($correct_attempted / $attempted_question) * 100;
            $data = [

                'set_title' => $user_practice_test->set->set_name,
                'total_question' => $user_practice_test->set->activequestion->count(),
                'attempted_question' => $attempted_question,
                'correct_attempted' => $correct_attempted,
                'incorrect_attempted' => $user_practice_test->incorrectAnswer->count(),
                'analysis_on_attempted_question' => number_format((float)$analysis_on_attempted_question, 2, '.', ''),
            ];
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Practice Test Result",
                "result" => $data,
            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",
                "result" => [],
            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function LessonVideoWatchTime(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'lesson_id' => 'required',
                'video_start_time' => 'required',
                'video_end_time' => 'required'
            ]);

            if ($validator->fails()) {

                return response()->json(['status' => 0, 'result' => $validator->errors()]);
            }

            $lesson_id = $request->lesson_id;
            $lesson = Lesson::with('lessonAttachment')->where('id', $lesson_id)->first();
            $video_start_time = $request->video_start_time;
            $video_ending_time = $request->video_end_time;
            $total_video_duration = $lesson->lessonAttachment->video_duration;
            $total_watch_duration = timeDifference($video_ending_time, $video_start_time);


            $data = [
                'subject_id' => $lesson->assign_subject_id,
                'lesson_subject_id' => $lesson_id,
                'teacher_id' => $lesson->teacher_id,
                'visitor_id' => auth()->user()->id,
                'type' => 2,
                'video_watch_time' => $total_watch_duration,
                'total_video_duration' => $total_video_duration,

            ];
            $subjectlessonvisitor = SubjectLessonVisitor::create($data);
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "Data stored successfully",

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
    public function getBoard(){
        try {
            $board_details = Board::where('is_activate',1)->orderBy('created_at', 'DESC')->select('id','exam_board')->get();
            if ($board_details) {

                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "All boards",
                    "board" => $board_details,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "board"=>null

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",
                "board"=>null

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function getSuggestedClass(){
        try {

            //get all subject
            // $subjects = AssignSubject::with('review')->select('id', 'subject_name', 'image', 'subject_amount', 'subject_amount')->where('board_id',2)->where('assign_class_id', 3)->where('is_activate', 1)->where('published', 1)->get();
            $subjects = AssignSubject::with('review','assignClass', 'boards')->where('assign_class_id',auth()->user()->userDetail->assign_class_id)->where('board_id',auth()->user()->userDetail->board_id)->select('id', 'subject_name', 'image', 'subject_amount', 'subject_amount')->where('is_activate', 1)->where('published', 1)->orderBy('created_at', 'DESC')->get();
             if($subjects->count()>0){
                $subjects=$subjects;
             }else{
                $subjects = AssignSubject::with('review','assignClass', 'boards')->select('id', 'subject_name', 'image', 'subject_amount', 'subject_amount')->where('is_activate', 1)->where('published', 1)->orderBy('created_at', 'DESC')->get();
             }
            // calculate total amount

            if (!$subjects->isEmpty()) {
                $total_amount = 0;
                foreach ($subjects as $key => $subject) {
                    if (subjectAlreadyPurchase($subject->id) == 1) {
                        $total_amount = $total_amount + 0;
                    } else {
                        $total_amount = $total_amount + $subject->subject_amount;
                    }
                }
                $all_subject = [];
                foreach ($subjects as $key => $subject) {
                    if ($subject->review->count() > 0) {
                        $total_rating = $subject->review()->count() * 5;
                        $rating_average =  round($subject->review()->sum('rating') / $total_rating * 5);
                    } else {
                        $rating_average = "No reviews yet";
                    }

                    $data = [
                        'id' => $subject->id,
                        'subject_name' => $subject->subject_name,
                        'image' => $subject->image,
                        'subject_amount' => $subject->subject_amount,
                        'rating' => $rating_average,
                        'already_purchase' => subjectAlreadyPurchase($subject->id),
                    ];
                    $all_subject[] = $data;
                }




                $data = [
                    'subjects' => $all_subject,
                    'total_amount' => $total_amount,
                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all board",
                    "result" => $data,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    'subjects' => null,
                    'total_amount' => null,
                ];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "result" => $data,
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
}
