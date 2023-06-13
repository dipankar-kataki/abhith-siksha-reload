<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Common\Activation;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\AssignClass;
use App\Models\AssignSubject;
use App\Models\Board;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function index()
    {
        # code...
        // $courses = Course::where('is_activate',Activation::Activate)->paginate(10);
        $publishCourse = [];
        $upComingCourse = [];
        $price = [];

        $courses = Course::where('is_activate', Activation::Activate)->with('priceList')->orderBy('id', 'DESC')->get();

        foreach ($courses as $key => $value) {
            # code...
            $price = [];
            $publishDate = Carbon::parse($value->publish_date)->format('Y-m-d');
            $Today = Carbon::today()->format('Y-m-d');
            if ($publishDate < $Today) {
                //  dd('less today', $value->publish_date);
                $chapters = Chapter::where([['course_id', $value->id], ['is_activate', Activation::Activate]])->get();
                foreach ($chapters as $key => $value2) {
                    # code...
                    $price[] = $value2->price;
                }
                $final_price = array_sum($price);
                $published['final_price'] = $final_price;
                $published['id'] = $value->id;
                $published['name'] = $value->name;
                $published['course_pic'] = $value->course_pic;
                $published['duration'] = $value->durations;
                $published['publish_date'] = $value->publish_date;
                $publishCourse[] = $published;
            } elseif ($publishDate == $Today) {
                //    dd('Not Today', $value->publish_date);
                $publishTime = Carbon::parse($value->publish_date)->format('H:i');
                $presentTime = Carbon::now()->format('H:i');
                if ($publishTime < $presentTime) {
                    $chapters = Chapter::where([['course_id', $value->id], ['is_activate', Activation::Activate]])->get();
                    foreach ($chapters as $key => $value3) {
                        # code...
                        $price[] = $value3->price;
                    }
                    $final_price = array_sum($price);
                    $published['final_price'] = $final_price;
                    $published['id'] = $value->id;
                    $published['name'] = $value->name;
                    $published['course_pic'] = $value->course_pic;
                    $published['duration'] = $value->durations;
                    $published['publish_date'] = $value->publish_date;
                    $publishCourse[] = $published;
                } else {
                    $upcoming['id'] = $value->id;
                    $upcoming['name'] = $value->name;
                    $upcoming['course_pic'] = $value->course_pic;
                    $upcoming['duration'] = $value->durations;
                    $upcoming['publish_date'] = $value->publish_date;
                    $upComingCourse[] = $upcoming;
                }
            } elseif ($publishDate > $Today) {
                // dd('GRATER Today', $value->publish_date);
                $upcoming['id'] = $value->id;
                $upcoming['name'] = $value->name;
                $upcoming['duration'] = $value->durations;
                $upcoming['course_pic'] = $value->course_pic;
                $upcoming['publish_date'] = $value->publish_date;
                $upComingCourse[] = $upcoming;
            }
        }
        $subjects = Subject::where('is_activate', Activation::Activate)->get();

        $response = [
            'subjects' => $subjects,
            'publishCourse' => $publishCourse,
            'upcomingCourse' => $upComingCourse
        ];
        // dd($publishCourse);
        return response()->json(['response' => $response, 'message' => 'Data fetch successfully']);
    }
    public function findClass(Request $request)
    {

        $board = AssignClass::where(['board_id' => $request->board_id, 'is_activate' => 1])->get();
        return response()->json($board);
    }
    public function findBoardClassSubject(Request $request)
    {
        $subject = AssignSubject::where(['board_id' => $request->board_id, 'assign_class_id' => $request->class_id])->get();
        return response()->json($subject);
    }
    public function allCourses()
    {
        try {

            // $courses = AssignSubject::select('id', 'subject_name', 'image', 'subject_amount', 'assign_class_id', 'board_id', 'is_activate', 'published')->with('assignClass:id,class', 'boards:id,exam_board')->with('review:subject_id,rating')->where('is_activate', 1)->where('published', 1)->where('assign_class_id', auth()->user()->userDetail->assign_class_id)->where('board_id', auth()->user()->userDetail->board_id)->limit(4)->get();

            $courses= AssignSubject::with('assignClass','boards')->where('is_activate',1)->where('published',1)->where('assign_class_id',auth()->user()->userDetail->assign_class_id)->where('board_id',auth()->user()->userDetail->assign_board_id)->limit(4)->get();

            // if($data->count()>0){
            //     $courses = $data;
            // }else{
            //     $courses = AssignSubject::with('assignClass','boards')->where('is_activate',1)->limit(4)->get();
            // }


            if ($courses->count()>0) {

                $all_courses = [];
                foreach ($courses as $key => $course) {

                    if ($course->review->count() > 0) {
                        $total_rating = $course->review()->count() * 5;
                        $rating_average = round($course->review()->sum('rating') / $total_rating * 5);
                    } else {
                        $rating_average = "No reviews yet";
                    }
                    $data = [
                        "id" => $course->id,
                        "subject_name" => $course->subject_name,
                        "image" => $course->image,
                        "subject_amount" => $course->subject_amount,
                        "assign_class_id" => $course->assign_class_id,
                        "board_id" => $course->board_id,
                        "assign_class" => $course->assignClass,
                        "boards" => $course->boards,
                        "rating" => $rating_average,

                    ];
                    $all_courses[] = $data;
                }

                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all courses",
                    "result" => $all_courses,

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
    public function allUpcommingCourses()
    {
        try {
            $courses = AssignSubject::select('id', 'subject_name', 'image', 'subject_amount', 'assign_class_id', 'board_id')->with('assignClass:id,class', 'boards:id,exam_board')->with('review:subject_id,rating')->where('is_activate', 1)->where('published', 0)->limit(4)->get();

            if ($courses) {

                $all_courses = [];
                foreach ($courses as $key => $course) {
                    if (subjectAlreadyPurchase($course->id) == 0) {
                        if ($course->review->count() > 0) {
                            $total_rating = $course->review()->count() * 5;
                            $rating_average = $course->review()->sum('rating') / $total_rating * 5;
                        } else {
                            $rating_average = "No reviews yet";
                        }
                        $data = [
                            "id" => $course->id,
                            "subject_name" => $course->subject_name,
                            "image" => $course->image,
                            "subject_amount" => $course->subject_amount,
                            "assign_class_id" => $course->assign_class_id,
                            "board_id" => $course->board_id,
                            "assign_class" => $course->assignClass,
                            "boards" => $course->boards,
                            "rating" => $rating_average,

                        ];
                        $all_courses[] = $data;
                    }
                }
                if (count($all_courses) == 0) {
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "No record found",
                        "result" => $all_courses,

                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "all courses",
                        "result" => $all_courses,

                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
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
    public function findAllClass(Request $request)
    {
        try {
            $board_name = $_GET['board_name'];
            $board = Board::where('exam_board', $board_name)->where('is_activate', 1)->first();

            if ($board) {
                $assign_class = AssignClass::select('id', 'class', 'board_id')->where('board_id', $board->id)->where('is_activate', 1)->get();
                if ($assign_class) {
                    $all_class = [];
                    $all_class[0] = "Select class";
                    foreach ($assign_class as $key => $board) {

                        $all_class[$key + 1] = $board->class;
                    }
                    $result = ["all_class" => $all_class];
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "all Class",
                        "result" => $result,

                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
            }
            $result = ["all_class" => []];
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "No Recored Found",
                "result" => $result,

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
    public function getAllClass(Request $request)
    {
        try {
            $board_id = $_GET['board_id'];

            $assign_class = AssignClass::select('id', 'class', 'board_id')->where('board_id', $board_id)->where('is_activate', 1)->get();



            if ($assign_class->count()>0) {
                $all_class = [];
                $all_class[0]['id'] =0;
                $all_class[0]['name'] ="Select Class";
                foreach ($assign_class as $key => $class) {
                    $class = [
                        'id' => $class->id,
                        'name' => $class->class,
                    ];
                    $all_class[] = $class;
                }
                $result = ["all_class" => $all_class];
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all Class",
                    "result" => $result,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }

            $result = ["all_class" => []];
            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "No Recored Found",
                "result" => $result,

            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $th]);
        }
    }
}
