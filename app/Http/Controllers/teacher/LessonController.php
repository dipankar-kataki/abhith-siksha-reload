<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LessonController extends Controller
{
    public function index(){
        $board_details = Board::where('is_activate', 1)->get();
        $all_lessons = Lesson::with(['assignClass', 'board', 'topics', 'subTopics', 'assignSubject'=>function($query){
                    $query->where('teacher_id',auth()->user()->id);
        }])->where('parent_id', null)->get();

        return view('teacher.lesson.index')->with(['boards' => $board_details, 'all_lessons' => $all_lessons]);
    }
    public function view($lesson_id){
        try {
            $lesson_id=Crypt::decrypt($lesson_id);
            $lesson=Lesson::with('topics')->where('id',$lesson_id)->first();
           return view('teacher.lesson.view')->with(['lesson'=>$lesson]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function topicView($topic_id){
        try {
            $topic_id=Crypt::decrypt($topic_id);
            $topic=Lesson::with('subTopics')->where('id',$topic_id)->first();
            return view('teacher.lesson.topic.view')->with(['topic'=>$topic]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
