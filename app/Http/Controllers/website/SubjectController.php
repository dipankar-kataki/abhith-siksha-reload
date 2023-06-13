<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Review;
use App\Models\Set;
use App\Models\UserPracticeTest;
use App\Models\UserPracticeTestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SubjectController extends Controller
{
    public function subjectDetails($subject_id)
    {

        $subject_id = Crypt::decrypt($subject_id);
        $subject = AssignSubject::with('lesson', 'subjectAttachment')->where('id', $subject_id)->first();
        $lessons = $subject->lesson;

        $reviews = Review::with([
            'user' => function ($q) {
                $q->with('userDetail');
            }
        ])->where('subject_id', $subject_id)->where('is_visible', 1)->get();
        if (!$reviews->isEmpty()) {
            $all_reviews = [];
            $total_rating = $reviews->count() * 5;
            $rating_average = $reviews->sum('rating') / $total_rating * 5;
            foreach ($reviews as $key => $review) {
                $review = [
                    'user_name' => $review->user->userDetail->name,
                    'image' => $review->user->userDetail->image,
                    'rating' => $review->rating,
                    'review' => $review->review,

                ];
                $all_reviews[] = $review;
            }
            $total_review = $reviews->count();
        } else {
            $reviews = null;
            $total_review = 0;
            $rating_average = 0;
        }

        return view('website.user.lesson', compact('lessons', 'subject', 'reviews', 'total_review', 'rating_average'));
    }
    public function subjectMCQ($subject_id)
    {
        try {
            $subject_id = Crypt::decrypt($subject_id);
            $subject = AssignSubject::with('lesson', 'subjectAttachment', 'sets')->where('id', $subject_id)->first();
            return view('website.my_account.mcq', compact('subject'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function mcqStart($set_id)
    {
        try {
            $set_id = Crypt::decrypt($set_id);
            $set = Set::with('activequestion')->where('id', $set_id)->first();
            $total_question = $set->activequestion->count();
            $start = true;

            return view('website.my_account.mcq_start', compact('set', 'start', 'total_question'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function mcqResult(Request $request)
    {

        $id = $request->get('id');
        $user_practice_test = UserPracticeTest::with('userPracticeTestAnswer')->where('id', $id)->first();
        $attempted_question = $user_practice_test->userPracticeTestAnswer->count();
        $correct_attempted = $user_practice_test->correctAnswer->count();
        $analysis_on_attempted_question = ($correct_attempted / $attempted_question) * 100;
        $data = [

            'set_title' => $user_practice_test->set->set_name,
            'total_question' => $user_practice_test->total_active_question,
            'attempted_question' => $attempted_question,
            'correct_attempted' => $correct_attempted,
            'incorrect_attempted' => $user_practice_test->incorrectAnswer->count(),
            'analysis_on_attempted_question' => number_format((float)$analysis_on_attempted_question, 2, '.', ''),
        ];

        return view('website.my_account.mcq_result', compact('data'));
    }
    public function mcqAnalysis($id)
    {
        $practice_test_id = Crypt::decrypt($id);
        $user_practice_test = UserPracticeTest::with('userPracticeTestAnswer')->where('id', $practice_test_id)->first();
        $attempted_question = $user_practice_test->userPracticeTestAnswer->count();
        $correct_attempted = $user_practice_test->correctAnswer->count();
        $analysis_on_attempted_question = ($correct_attempted / $attempted_question) * 100;
        $data = [

            'set_title' => $user_practice_test->set->set_name,
            'total_question' => $user_practice_test->total_active_question,
            'attempted_question' => $attempted_question,
            'correct_attempted' => $correct_attempted,
            'incorrect_attempted' => $user_practice_test->incorrectAnswer->count(),
            'analysis_on_attempted_question' => number_format((float)$analysis_on_attempted_question, 2, '.', ''),
        ];
        return view('website.my_account.mcq_analysis', compact('data'));
    }
    public function topicDetails($topic_id)
    {
        try {
            $lesson = Lesson::find(Crypt::decrypt($topic_id));
            $topicDocuments = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 1)->get();
            $topicVideos = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 2)->get();
            $topicArticles = Lesson::with('lessonAttachment')->where('parent_id', $lesson->id)->where('type', 3)->get();
            $mcq_questions = Lesson::with('Sets')->where('id', $lesson->id)->first();

            return view('website.my_account.lesson_details', compact('lesson', 'topicDocuments', 'topicVideos', 'topicArticles', 'mcq_questions'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function mcqGetQuestion(Request $request)
    {
        try {
            $set_id = $request->set_id;
            $page = $request->page;
            $type = $request->type;
            $last = $request->last;
            $user_practice_test_store_id = $request->user_practice_test_store_id;
            $question_id = $request->question_id;


            $set_question = Set::with(['question', 'activequestion'])->where('id', $set_id)->first();
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
                if ($type == "start") {
                    $user_practice_test = [
                        'user_id' => auth()->user()->id,
                        'set_id' => $set_question->id,
                        'start_time' => date('Y-m-d H:i:s'),
                        'total_active_question' => $set_question->activequestion->count(),

                    ];

                    $user_practice_test_store = UserPracticeTest::create($user_practice_test);
                }
                if ($type == "next") {

                    $question = Question::find($request->question_id);

                    if ($question->correct_answer == $request->question_answer) {
                        $is_correct = 1;
                    } else {
                        $is_correct = 0;
                    }
                    $data = [
                        'user_practice_test_id' => $request->user_practice_test_store_id,
                        'question_id' => $question_id,
                        'answer' => $question->correct_answer,
                        'user_answer' => $request->question_answer,
                        'is_correct' => $is_correct
                    ];

                    $user_pract_test_answer = UserPracticeTestAnswer::create($data);
                }

                if ($type == "skip" && $page == ($last + 1)) {

                    $user_practice_tests = UserPracticeTest::with('userPracticeTestAnswer')->where('set_id', $set_id)->where('user_id', auth()->user()->id)->where('id', '!=', $user_practice_test_store_id)->get();

                    if ($user_practice_tests) {

                        foreach ($user_practice_tests as $key => $user_practice_test) {

                            $user_practice_test->delete();
                            if ($user_practice_test->userPracticeTestAnswer != null) {
                                $user_practice_test->userPracticeTestAnswer()->delete();
                            }
                        }
                    }

                    $user_practice_test = UserPracticeTest::find($request->user_practice_test_store_id);

                    $end_time = date('Y-m-d H:i:s');
                    $start_time = $user_practice_test->start_time;
                    $total_duration = timeDifference($start_time, $end_time);
                    $update_data = [
                        'end_time' => $end_time,
                        'total_duration' => $total_duration,
                    ];
                    $user_practice_test->update($update_data);
                    $question = Question::find($request->question_id);
                    if ($question->correct_answer == $request->question_answer) {
                        $is_correct = 1;
                    } else {
                        $is_correct = 0;
                    }

                    $data = [
                        'user_practice_test_id' => $request->user_practice_test_store_id,
                        'question_id' => $question->id,
                        'answer' => $question->correct_answer,
                        'user_answer' => $request->question_answer,
                        'is_correct' => $is_correct
                    ];

                    $user_pract_test_answer = UserPracticeTestAnswer::create($data);
                    $update_user_practice_test_store =
                        [
                            'total_attempts' => $user_practice_test->UserPracticeTestAnswer->count(),
                            'total_correct_count' => $user_practice_test->correctAnswer->count(),
                        ];
                    $user_practice_test->update($update_user_practice_test_store);
                }

                $all_questions = $set_question->question()->paginate(1);

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
                if ($type == "start") {
                    $user_practice_test_store_id = $user_practice_test_store->id;
                } else {
                    $user_practice_test_store_id = $request->user_practice_test_store_id;
                }
                $result = [
                    'set_name' => $set_question->set_name,
                    'total_question' => $set_question->question->count(),
                    'mcq_question' => $data,
                    'page' => $page,
                    'user_practice_test_store' => $user_practice_test_store_id,
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
            return response()->json(['status' => 0, 'result' => $th]);
        }
    }
    public function finalSubmit(Request $request)
    {
        try {


            $user_practice_test = UserPracticeTest::find($request->user_practice_test_store_id);

            $end_time = date('Y-m-d H:i:s');
            $start_time = $user_practice_test->start_time;
            $total_duration = timeDifference($start_time, $end_time);
            $update_data = [
                'end_time' => $end_time,
                'total_duration' => $total_duration,
            ];
            $user_practice_test->update($update_data);
            if ($request->question_answer != null) {
                $question = Question::find($request->question_id);
                if ($question->correct_answer == $request->question_answer) {
                    $is_correct = 1;
                } else {
                    $is_correct = 0;
                }
                $data = [
                    'user_practice_test_id' => $request->user_practice_test_store_id,
                    'question_id' => $question->id,
                    'answer' => $question->correct_answer,
                    'user_answer' => $request->question_answer,
                    'is_correct' => $is_correct
                ];

                $user_pract_test_answer = UserPracticeTestAnswer::create($data);
            }



            $update_user_practice_test_store =
                [
                    'total_attempts' => $user_practice_test->UserPracticeTestAnswer->count(),
                    'total_correct_count' => $user_practice_test->correctAnswer->count(),
                ];
            $user_practice_test->update($update_user_practice_test_store);
            if ($user_practice_test->UserPracticeTestAnswer->count() > 0) {
                $data = [
                    'code' => 200,
                    'user_practice_test_id' => $user_practice_test->id,
                ];
            } else {
                $data = [
                    'code' => 400,
                    'user_practice_test_id' => null,
                ];
            }

            return response()->json($data);
        } catch (\Throwable $th) {
            $data = [
                'code' => 400,

            ];
            return response()->json($data);
        }
    }
    public function countTotalAttempt(Request $request)
    {
        try {
            $user_practice_test = UserPracticeTest::find($request->user_practice_test);
            if ($user_practice_test->total_attempts == 0) {
                $data = [
                    'code' => 401,
                ];
                return response()->json($data);
            } else {
                $data = [
                    'code' => 200,
                ];
                return response()->json($data);
            }
        } catch (\Throwable $th) {
            $data = [
                'code' => 400,

            ];
            return response()->json($data);
        }
    }
}
