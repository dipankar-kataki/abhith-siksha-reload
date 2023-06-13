<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Common\Role;
use App\Common\Type;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;

class AuthController extends Controller
{
  //
  public function index()
  {
    return view('admin.auth.login');
  }
  protected function customLogin(Request $request)
  {
    try {


      $request->validate([
        'email' => 'required',
        'password' => 'required',
      ]);

      if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

        if (Auth::user()->type_id == 1) {
          return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->type_id == 3) {
          return redirect()->route('admin.dashboard');
        } else {
          return redirect()->back()->withErrors(['Credentials doesn\'t match with our record'])->withInput($request->input());
        }
      } else {
        return redirect()->back()->withErrors(['Credentials doesn\'t match with our record'])->withInput($request->input());
      }
      return redirect()->back();
    } catch (\Throwable $th) {
      //throw $th;
    }
  }

  protected function login()
  {
    # code...
    return redirect(route('login'));
  }

  protected function logout()
  {
    if (Auth::user()->type_id == 3) {
      Auth::logout();
      return redirect()->route('website.becomeTeacher');
    } else {
      Auth::logout();
      return redirect()->route('login');
    }
  }

  public function userLogin()
  {
    return view('admin.auth.login');
  }
}
