<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Common\Activation;
use App\Common\Type;
use App\Mail\OtpVerfication;
use App\Models\Board;
use App\Models\MobileAndEmailVerification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WebsiteAuthController extends Controller
{
    public function signup(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|numeric',

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()]);
            }


            $name = $request->name;
            $email = $request->email;
            $phone = $request->phone;

            if (getPrefix($request) == "api") {
                $type = Type::User;
            } elseif (getPrefix($request) == "teacher") {
                $type = Type::Teacher;
            } else {
                $type = Type::User;
            }

            $check_user_active = User::where([['email', $email], ['type_id', $type], ['phone', $phone], ['verify_otp', 1], ['is_activate', 1]])->exists();
            if ($check_user_active) {
                return response()->json(['message' => 'Oops! User already exists', 'status' => 0]);
            } else {

                $otp = rand(100000, 999999);

                $check_otp_sent_but_user_not_verified = User::where([['email', $email], ['type_id', $type], ['phone', $phone], ['verify_otp', 0], ['is_activate', 0]])->exists();
                $check_otp_verified_but_user_not_activate = User::where([['email', $email], ['type_id', $type], ['phone', $phone], ['verify_otp', 1], ['is_activate', 0]])->exists();
                if ($check_otp_sent_but_user_not_verified) {
                    User::where([['email', $email], ['type_id', $type], ['phone', $phone], ['verify_otp', 0], ['is_activate', 0]])->update([
                        'otp' => $otp
                    ]);

                    $this->otpSend($phone, $otp);
                    return response()->json(['message' => 'OTP sent successfully', 'status' => 1]);
                } else if ($check_otp_verified_but_user_not_activate) {
                    return response()->json(['message' => 'Oops! User already exists', 'status' => 0]);
                } else {

                    $create = User::create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'otp' => $otp,
                        'type_id' => $type,
                        'is_activate' => 0
                    ]);


                    $user = User::where('email', $email)->first();

                    $userDetails = UserDetails::create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'user_id' => $user->id,
                    ]);

                    $this->otpSend($phone, $otp);

                    if ($create && $userDetails) {
                        return response()->json(['message' => 'OTP sent successfully', 'status' => 1]);
                    } else {
                        return response()->json(['message' => 'Oops! Something went wrong', 'status' => 0]);
                    }
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Oops! Something went wrong', 'status' => 0]);
        }
    }



    public function otpSend($phone, $otp)
    {
        $isError = 0;
        $errorMessage = true;

        //Your message to send, Adding URL encoding.
        $message = urlencode("<#> Use $otp as your verification code. The OTP expires within 10 mins. Do not share it with anyone. -regards Abhith Siksha");


        //Preparing post parameters
        $postData = array(
            'authkey' => '19403ARfxb6xCGLJ619221c6P15',
            'mobiles' => $phone,
            'message' => $message,
            'sender' => 'ABHSKH',
            'DLT_TE_ID' => 1207164006513329391,
            'route' => 4,
            'response' => 'json'
        );

        $url = "http://login.yourbulksms.com/api/sendhttp.php";

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ));
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //get response
        $output = curl_exec($ch);
        //Print error if any
        if (curl_errno($ch)) {
            $isError = true;
            $errorMessage = curl_error($ch);
        }
        curl_close($ch);
        if ($isError) {
            return array('error' => 1, 'message' => $errorMessage);
        } else {
            return array('error' => 0);
        }
    }





    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'otp' => 'required|numeric',

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()]);
            }

            $details = User::where([['email', $request->email], ['phone', $request->phone], ['verify_otp', 0], ['is_activate', 0]])->first();
            if ($request->otp == $details->otp) {
                $details->verify_otp = 1;
                $details->save();
                return response()->json(['message' => 'OTP verified successfully', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Whoops! Invalid OTP', 'status' => 0]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Whoops! Something Went Wrong', 'status' => 0]);
        }
    }


    public function completeSignup(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'password' => 'required',
                'name' => 'required',
                'is_above_eighteen' => 'required',
                'board_id' => 'required',
                'class_id' => 'required'

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()]);
            }
            if ($request->is_above_eighteen == 0) {
                $validator = Validator::make($request->all(), [

                    'parent_name' => 'required'

                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()]);
                }
            }
            $parent_name = null;
            if ($request->is_above_eighteen == 0) {
                $parent_name = $request->parent_name;
            }
            $is_mobile_verified = MobileAndEmailVerification::where('mobile', $request->phone)->where('mobile_email_verification', 1)->first();
            $is_email_verified = MobileAndEmailVerification::where('email', $request->email)->where('mobile_email_verification', 1)->first();
            $user_mobile_in_use = User::where('phone', $request->phone)->where('is_activate', 1)->first();
            $user_email_in_use = User::where('email', $request->phone)->where('is_activate', 1)->first();
            if ($is_mobile_verified == null) {
                return response()->json(['message' => 'Please verify your mobile number', 'status' => 0]);
            }
            if ($is_email_verified == null) {
                return response()->json([
                    'status' => 0,
                    'message' => "Please verify your email address",
                ]);
            }
            if ($user_mobile_in_use) {
                return response()->json([
                    'status' => 0,
                    'message' => "This mobile number already exists",
                ]);
            }
            if ($user_email_in_use) {
                return response()->json([
                    'status' => 0,
                    'message' => "This email address already exists",
                ]);
            }

            $details = User::where('email', $request->email)->where('phone', $request->phone)->where('verify_otp', 1)->where('is_activate', 1)->first();

            if ($details == null) {
                if (getPrefix($request) == "teacher") {
                    $roles = 3;
                } else {
                    $roles = 2;
                }
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'otp' => 00000,
                    'verify_otp' => 1,
                    'type_id' => 2,
                    'password' => Hash::make($request->password),
                    'is_active' => 1,
                ];

                $user = User::create($data);
                $assign_role = $user->assignRole(2);
                $token = $user->createToken('auth_token')->plainTextToken;
                $userDetails = UserDetails::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'user_id' => $user->id,
                    'assign_board_id' => $request->board_id,
                    'assign_class_id' => $request->class_id,
                    'parent_name' => $request->parent_name,
                    'is_above_eighteen' => $request->is_above_eighteen,
                ]);
                return response()->json(['message' => 'Signed up successfully', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Already registered user.', 'status' => 0]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th, 'status' => 0]);
        }
    }
    public function teacherSignup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'password' => 'required',
                'name' => 'required',


            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()]);
            }


            $is_mobile_verified = MobileAndEmailVerification::where('mobile', $request->phone)->where('mobile_email_verification', 1)->first();
            $is_email_verified = MobileAndEmailVerification::where('email', $request->email)->where('mobile_email_verification', 1)->first();
            $user_mobile_in_use = User::where('phone', $request->phone)->where('is_activate', 1)->first();
            $user_email_in_use = User::where('email', $request->phone)->where('is_activate', 1)->first();
            if ($is_mobile_verified == null) {
                return response()->json(['message' => 'Please verify your mobile number', 'status' => 0]);
            }
            if ($is_email_verified == null) {
                return response()->json([
                    'status' => 0,
                    'message' => "Please verify your email address",
                ]);
            }
            if ($user_mobile_in_use) {
                return response()->json([
                    'status' => 0,
                    'message' => "This mobile number already exists",
                ]);
            }
            if ($user_email_in_use) {
                return response()->json([
                    'status' => 0,
                    'message' => "This email address already exists",
                ]);
            }

            $details = User::where('email', $request->email)->where('phone', $request->phone)->where('verify_otp', 1)->where('is_activate', 1)->first();

            if ($details == null) {
                if (getPrefix($request) == "teacher") {
                    $roles = 3;
                } else {
                    $roles = 2;
                }
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'otp' => 00000,
                    'verify_otp' => 1,
                    'type_id' => $roles,
                    'password' => Hash::make($request->password),
                    'is_active' => 1,
                ];

                $user = User::create($data);
                $assign_role = $user->assignRole($roles);
                $token = $user->createToken('auth_token')->plainTextToken;
                $userDetails = UserDetails::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'user_id' => $user->id,

                ]);
                return response()->json(['message' => 'Signed up successfully', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Already registered user.', 'status' => 0]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th, 'status' => 0]);
        }
    }

    public function login(Request $request)
    {

        try {


            if (getPrefix($request) == "api") {
                $type = Type::User;
            } elseif ($request->type == 3) {
                $type = 3;
            } else {
                $type = 2;
            }


            if (getPrefix($request) == "api") {
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required',

                ]);

                if ($validator->fails()) {
                    return response()->json(['code' => 400, 'status' => 0, 'message' => $validator->errors()]);
                }

                if (!Auth::attempt($request->only('email', 'password'))) {
                    return response()->json([
                        'code' => 401, 'status' => 0,
                        'message' => 'Invalid login details'
                    ]);
                }

                $user = User::where('email', $request['email'])->select('id', 'email', 'phone', 'name', 'is_activate', 'created_at')->first();

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'code' => 200,
                    'status' => 1,
                    'message' => "Signed in successfully",
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            } else {
                $email = $request->email;
                $password = $request->password;

                $request->validate([
                    'email' => 'required',
                    'password' => 'required'
                ]);

                if (Auth::attempt(['email' => $request->email,  'password' => $request->password, 'is_activate' => Activation::Activate])) {
                    if(auth()->user()->type_id==3){
                        return redirect()->route('teacher.dashboard');
                        Toastr::success('Signed in successfully.', '', ["positionClass" => "toast-top-right", "timeOut" => 2000]);
                    }
                    elseif (auth()->user()->type_id==2) {
                        return redirect()->route('website.dashboard');
                        Toastr::success('Signed in successfully.', '', ["positionClass" => "toast-top-right", "timeOut" => 2000]);
                    }
                    else{
                        return redirect()->back()->withErrors(['Credentials doesn\'t match with our record'])->withInput($request->input());
                    }

                } else {
                    return redirect()->back()->withErrors(['Credentials doesn\'t match with our record'])->withInput($request->input());
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['code' => 500, 'message' => 'Whoops! Something Went Wrong', 'status' => 0]);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (getPrefix($request) == "api") {

                auth()->user()->tokens()->delete();

                return [
                    'message' => 'Tokens Revoked'
                ];
                return response()->json(['message' => 'Loged out successfully.', 'status' => 1]);
            } else {
                Auth::logout();
                Toastr::success('Logged out successfully.', '', ["positionClass" => "toast-top-right", "timeOut" => 2000]);
                return redirect('');
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong.', 'status' => 0]);
        }
    }
    public function viewLogin(Request $request)
    {
        if ($request->route()->getPrefix() == "teacher") {
            $prefix = "teacher";
            return view('website.auth.teacherlogin', compact('prefix'));
        } else {
            $prefix = "user";
            $boards = Board::where('is_activate', 1)->get();
            return view('website.auth.login', compact('prefix', 'boards'));
        }
    }
    public function mobileSignUp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'phone' => 'required|numeric',
                'email' => 'required|email',
                'is_above_eighteen' => 'required',
                'assign_class_id' => 'required',
                'board_id' => 'required',


            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
            }
            if ($request->is_above_eighteen == 0) {
                $validator = Validator::make($request->all(), [

                    'parent_name' => 'required'

                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'message' => $validator->errors()]);
                }
            }
            $parent_name = null;
            if ($request->is_above_eighteen == 0) {
                $parent_name = $request->parent_name;
            }
            $is_mobile_verified = MobileAndEmailVerification::where('mobile', $request->phone)->where('mobile_email_verification', 1)->first();
            $is_email_verified = MobileAndEmailVerification::where('email', $request->email)->where('mobile_email_verification', 1)->first();
            $user_mobile_in_use = User::where('phone', $request->phone)->where('is_activate', 1)->first();
            $user_email_in_use = User::where('email', $request->email)->where('is_activate', 1)->first();
            if ($is_mobile_verified == null) {
                return response()->json([
                    'status' => 0,
                    'message' => "Please verify your mobile number",
                ]);
            }
            if ($is_email_verified == null) {
                return response()->json([
                    'status' => 0,
                    'message' => "Please verify your email address",
                ]);
            }
            if ($user_mobile_in_use) {
                return response()->json([
                    'status' => 0,
                    'message' => "This mobile number already exists",
                ]);
            }
            if ($user_email_in_use) {
                return response()->json([
                    'status' => 0,
                    'message' => "This email address already exists",
                ]);
            }
            if ($request->has('parent_name')) {
                $parent_name = $request->parent_name;
            }
            $data = [

                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'otp' => 00000,
                'verify_otp' => 1,
                'type_id' => 2,
                'password' => Hash::make($request->password),
                'is_active' => 1,

            ];
            $user = User::create($data);
            $assign_role = $user->assignRole(2);
            $token = $user->createToken('auth_token')->plainTextToken;
            $userDetails = UserDetails::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
                'parent_name' => $parent_name,
                'assign_class_id' => $request->assign_class_id,
                'board_id' => $request->board_id,
                'assign_board_id' => $request->board_id,
                'assign_class_id' => $request->class_id,
                'parent_name' => $request->parent_name,
                'is_above_eighteen' => $request->is_above_eighteen,
            ]);
            $user = User::where('email', $request['email'])->select('id', 'email', 'phone', 'name', 'is_activate', 'created_at')->first();
            return response()->json([
                'status' => 1,
                'message' => "Signed up successfully",
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Whoops! Something Went Wrong', 'status' => 0]);
        }
    }
    public function sendMobileOtp(Request $request)
    {
        try {
            if ($request->has('phone')) {
                $validator = Validator::make($request->all(), [
                    'phone' => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'message' => $validator->errors()]);
                }
                $user = User::where('phone', $request->phone)->where('is_activate', 1)->first();

                if ($user != null) {
                    $data = [
                        "code" => 400,
                        "status" => 0,
                        "message" => "Oops! User already exists",
                    ];
                    return response()->json(['status' => 0, 'result' => $data]);
                }

                $phone = $request->phone;
                $otp = rand(100000, 999999);

                $mobile_email_verification_data = MobileAndEmailVerification::where('mobile', $phone)->first();

                $mobileEmailVerificationData = [
                    'mobile' => $phone,
                    'mobile_email_otp' => $otp

                ];
                if ($mobile_email_verification_data != null) {
                    $mobile_email_verification_data->update($mobileEmailVerificationData);
                } else {
                    MobileAndEmailVerification::create($mobileEmailVerificationData);
                }

                $otpsend = otpSend($phone, $otp);
                $data = [
                    'otp' => $otp,
                    'phone' => $phone,
                ];
                if ($otpsend) {
                    $data = [
                        "code" => 200,
                        "status" => 1,
                        "message" => "OTP sent successfully",
                        'data' => $data


                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                } else {
                    $data = [
                        "code" => 400,
                        "status" => 0,
                        "message" => "Something went wrong",

                    ];
                    return response()->json(['status' => 0, 'result' => $data]);
                }
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
    public function verifyMobileOtp(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'phone' => 'required|numeric',
                'otp' => 'required|numeric',

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()]);
            }

            $mobile_email_verification_data = MobileAndEmailVerification::where('mobile', $request->phone)->where('mobile_email_otp', $request->otp)->first();

            if ($mobile_email_verification_data) {
                $mobile_email_verification_data->update(['mobile_email_verification' => 1]);
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "Your Mobile Number Verified successfully",

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 400,
                    "status" => 0,
                    "message" => "OTP verification Mismatch",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
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
    public function sendEmailOtp(Request $request)
    {
        try {

            $user = User::where('email', $request->email)->where('is_activate', 1)->first();

            if ($user != null) {
                $data = [
                    "code" => 400,
                    "status" => 0,
                    "message" => "Oops! User already exists",
                ];
                return response()->json(['status' => 0, 'result' => $data]);
            }
            $email = $request->email;
            $otp = rand(100000, 999999);

            $mobile_email_verification_data = MobileAndEmailVerification::where('email', $email)->first();

            $data = [
                'mobile_email_otp' => $otp,
                'email' => $email,
                'mobile_email_verification' => 0,
            ];
            $details = [
                'otp' => $otp,

            ];
            if ($mobile_email_verification_data != null) {
                $mobile_email_verification_data->update($data);
            } else {
                MobileAndEmailVerification::create($data);
            }

            Mail::to($request->email)->send(new OtpVerfication($details));


            $data = [
                "code" => 200,
                "status" => 1,
                "message" => "OTP sent successfully",
                'data' => $data


            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            // dd($th);
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
    public function verifyEmailOtp(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp' => 'required|numeric',

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()]);
            }

            $mobile_email_verification_data = MobileAndEmailVerification::where('email', $request->email)->where('mobile_email_otp', $request->otp)->first();

            if ($mobile_email_verification_data) {
                $mobile_email_verification_data->update(['mobile_email_verification' => 1]);
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "Your Email address Verified successfully",

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 400,
                    "status" => 0,
                    "message" => "OTP verification Mismatch",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
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
    public function userLogout()
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json(['message' => 'Logged out successfully.', 'status' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong.', 'status' => 0]);
        }
    }
}
