<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class StudentController extends Controller
{
  public function index()
  {
    $assign_subjects = AssignSubject::with(['assignOrder' => function ($query) {
      $query->with(['order' => function ($q) {
        $q->with('user');
      }]);
    }])->where('teacher_id', auth()->user()->id)->get();
    // $orders=Order::with(['user','assignSubject'=>function($query){
    //     $query->with(['subject'=>function($q){
    //         $q->where('teacher_id',auth()->user()->id);
    //     }]);
    // }])->get();

    return view('teacher.student.index', compact('assign_subjects'));
  }
  // Display all enrolled student for a subject
  public function subjectWiseStudent($subject_id)
  {
  
    $assign_orders = $this->getAssignOrder($subject_id);
    return view('teacher.student.index', compact('assign_orders'));
  }
  public function subjectWiseStudentReport($subject_id, $student_id)
  {
    try {
      $subject = AssignSubject::find(Crypt::decrypt($subject_id));
      $user=User::find(Crypt::decrypt($student_id));
    
      return view('teacher.student.report')->with(['subject'=>$subject,'user'=>$user]);
    } catch (\Throwable $th) {
      //throw $th;
    }
  }
  public function getAssignOrder($subject_id)
  {
    $assign_subjects = AssignSubject::with(['assignOrder' => function ($query) {
      $query->with(['order' => function ($q) {
        $q->with('user');
      }]);
    }])->where('id', Crypt::decrypt($subject_id))->first();
    return $assign_subjects->assignOrder;
  }
}
