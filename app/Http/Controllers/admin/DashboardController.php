<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
  public function index()
  {
   
    $boards = Board::where('is_activate', 1)->get();
    $total_student = User::where('type_id', 2)->where('verify_otp', 1)->get()->count();
    $total_teacher = User::where('type_id', 3)->where('verify_otp', 1)->get()->count();
  
    return view('admin.dashboard.dashboard', compact('boards','total_student','total_teacher'));
  }
}
