<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MultipleChoice;
use App\Models\Set;
use App\Models\Question;
use App\Models\Subject;
use App\Imports\QuestionImport;
use App\Models\AssignSubject;
use App\Models\Board;
use App\Models\Lesson;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;


class MultipleChoiceController extends Controller
{
    public function index(Request $request)
    {
        $getMultipleChoice = Set::with('assignSubject')->paginate(10);

        return view('admin.multiple-choice.multiple-choice')->with('getMultipleChoice', $getMultipleChoice);
    }

    public function addMultipleChoice(Request $request)
    {
        $boards = Board::where('is_activate', 1)->get();
        return view('admin.multiple-choice.add-multiple-choice', compact('boards'));
    }

    public function insertQuestions(Request $request)
    {
        try {
          
            $setName = $request->setName;
            $board_id = $request->subject_id;
            $assign_class_id = $request->assign_class_id;
            $assign_subject_id = $request->assign_subject_id;
            $questionFile = $request->questionExcel;

            $checkIfSetExists = Set::where('board_id', $board_id)->where('assign_class_id', $assign_class_id)->where('assign_subject_id', $assign_subject_id)->where('set_name', $setName)->where('is_activate', 1)->exists();

            $subject = AssignSubject::where('id',$assign_subject_id)->first();

            if ($checkIfSetExists == true) {
                return back()->with(['error' => $setName . ' ' . 'already active with subject ' . $subject->name . '. Please select another subject or deactive the currently active Set']);
            } else {


                if ($request->hasFile('questionExcel')) {
                   
                    $subject_id = $subject->id;
                    $board_id = $subject->board_id;
                    $assign_class_id = $subject->assign_class_id;
                    $new_excel_name = date('d-m-Y-H-i-s') . '_' . $questionFile->getClientOriginalName();
                    $questionFileExtension = $questionFile->getClientOriginalExtension();

                    if ($questionFileExtension != 'xlsx') {
                        return back()->with(['error' => 'Not a valid excel file.']);
                    } else {
                        $questionFile = $request->file('questionExcel');

                        $questionFile = $request->file('questionExcel')->store('imports');
                        $import = new QuestionImport($setName, $subject_id, $board_id, $assign_class_id);
                        $import->import($questionFile);

                        return back()->withStatus('File uploaded successfully');
                    }
                } else {
                    return back()->with(['error' => $setName . ' ' . 'already exists with subject ' . $subject->name . '. Please select another subject.']);
                }
            }
        } catch (\Throwable $th) {
           dd($th);
        }
    }


    public function viewMcq(Request $request, $id)
    {

        $mcq_set_id = Crypt::decrypt($id);

        $details = Question::with('set')->where('set_id', $mcq_set_id)->get();
        $set=Set::find($mcq_set_id);
        $lesson=Lesson::find($set->lesson_id);
        return view('admin.multiple-choice.edit-multiple-choice')->with(['lesson' => $lesson, 'details' => $details]);
    }


    // public function updateMcqQuestion(Request $request){
    //     $set_id = $request->set_id;
    //     $question = $request->question;
    //     $option1 = $request->option1;
    //     $option2 = $request->option2;
    //     $option3 = $request->option3;
    //     $option4 = $request->option4;
    //     $correct_answer = $request->correct_answer;

    //     foreach($question as $key =>$value){
    //         foreach($option1 as $key1 => $value1){
    //             foreach($option2 as $key2 => $value2){
    //                 foreach($option3 as $key3 => $value3){
    //                     foreach($option4 as $key4 => $value4){
    //                         foreach($correct_answer as $key5 => $value5){
    //                             if($key == $key1 && $key1 == $key2 && $key2 == $key3 && $key3 == $key4 && $key4 == $key5){
    //                                 $data['question'] = $value;
    //                                 $data['option_1'] = $value1;
    //                                 $data['option_2'] = $value2;
    //                                 $data['option_3'] = $value3;
    //                                 $data['option_4'] = $value4;
    //                                 $data['correct_answer'] = $value5;
    //                                 $insertingData[] = $data;
    //                             }
    //                         }
    //                     } 
    //                 }
    //             } 
    //         } 
    //     }

    //     MultipleChoice::insert($insertingData);
    //     return back()->withSuccess('Mcq details updated successfully');
    // }


    // public function insertMultipleChoice(Request $request){
    //     $subject_id = $request->subject_id;
    //     $question = $request->question;
    //     $option1 = $request->option1;
    //     $option2 = $request->option2;
    //     $option3 = $request->option3;
    //     $option4 = $request->option4;
    //     $correct_answer = $request->correct_answer;

    //     foreach($question as $key =>$value){
    //         foreach($option1 as $key1 => $value1){
    //             foreach($option2 as $key2 => $value2){
    //                 foreach($option3 as $key3 => $value3){
    //                     foreach($option4 as $key4 => $value4){
    //                         foreach($correct_answer as $key5 => $value5){
    //                             if($key == $key1 && $key1 == $key2 && $key2 == $key3 && $key3 == $key4 && $key4 == $key5){
    //                                 $data['subject_id'] = $subject_id;
    //                                 $data['question'] = $value;
    //                                 $data['option_1'] = $value1;
    //                                 $data['option_2'] = $value2;
    //                                 $data['option_3'] = $value3;
    //                                 $data['option_4'] = $value4;
    //                                 $data['correct_answer'] = $value5;
    //                                 $data['is_activate'] = 1;
    //                                 $insertingData[] = $data;
    //                             }
    //                         }
    //                     } 
    //                 }
    //             } 
    //         } 
    //     }

    //     MultipleChoice::insert($insertingData);
    //     return back()->withSuccess(['message' => "MCQ's Added Successfully"]);
    // }


    public function isActivateMultipleChoice(Request $request)
    {
        Set::where('id', $request->mcq_id)->update(['is_activate' => $request->active,]);
        Question::where('set_id', $request->mcq_id)->update(['is_activate' => $request->active,]);
        return response()->json(['message' => 'MCQ visibility status updated successfully']);
    }


    public function startMcq(Request $request, $id)
    {
        $startMcq = MultipleChoice::where('subject_id', $id)->simplePaginate(5);
        return back()->with('startMcq',  $startMcq);
    }


    public function checkIsCorrectMcq(Request $request)
    {
        $subject_id = $request->subject_id;
        $setId = $request->setId;

        $checkMcq = Question::where('set_id',  $setId)->get();
        $correctAnswer = [];
        $selectedAnswer = $request->mcArray;

        // return response( $selectedAnswer);

        // $correctAnswerArray = [];
        // foreach( $checkMcq as $item){
        //     array_push($correctAnswerArray, $item->correct_answer);
        // }
        // $output = array_intersect( $selectedAnswer,$correctAnswerArray);

        return response()->json(['selectedAnswer' =>  $selectedAnswer, 'checkMcq' => $checkMcq, 'setId' => $setId]);
    }
    public function statusChange($question_id){
        try {
            $question = Question::find(Crypt::decrypt($question_id));
            if ($question->is_activate == 0) {
                $question->update(['is_activate' => 1]);
                Toastr::success('Question status update from InActive to Active successfully.', '', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            } else {
                $question->update(['is_activate' => 0]);
                Toastr::success('Question status update from Active to InActive successfully.', '', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error('Something went wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function mcqSetStatusChange($set_id)
    {
        try {
            $set = Set::find(Crypt::decrypt($set_id));
            if ($set->is_activate == 0) {
                $set->update(['is_activate' => 1]);
                Toastr::success('Resource status update from Inactive to Active successfully.', '', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            } else {
                $set->update(['is_activate' => 0]);
                Toastr::success('Resource status update from Active to Inactive successfully.', '', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Toastr::error('Something went wrong.', '', ["positionClass" => "toast-top-right"]);
            // dd($th->getMessage());
            return redirect()->back();
        }
    }
}
