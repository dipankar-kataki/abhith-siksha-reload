<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\AssignSubject;
use App\Models\Cart;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\UserDetails;
use App\Models\User;
use App\Models\Order;
use App\Models\SubjectLessonVisitor;
use App\Models\UserPracticeTest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserDetailsController extends Controller
{

    public function myAccount(Request $request){
        if(Auth::check()){
            $user_details = UserDetails::with('user')->where('email',Auth::user()->email)->first();
            $purchase_history = Order::with('board','assignClass','assignSubject')->where('user_id',Auth::user()->id)->where('payment_status','paid')->orderBy('updated_at','DESC')->get();
         
        }
       
        return view('website.my_account.my_account')->with(['user_details' => $user_details, 'purchase_history' => $purchase_history]);
    }


    public function userDetails(Request $request){

        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $education = $request->education;
        $gender = $request->gender;
        $user_id = Auth::user()->id;
        $address=$request->address;
        $user_details = UserDetails::where('user_id',Auth::user()->id )->exists();

        if($user_details == true){
            UserDetails::where('user_id', Auth::user()->id)
                        ->update([
                            'name' => $name,'email' => $email, 'phone' => $phone, 'education' => $education, 'gender' => $gender,'address'=>$address
                        ]);
        }else{
            UserDetails::create([
                'name' => $name,
                'email'  => $email,
                'phone' => $phone,
                'education' => $education,
                'gender' => $gender,
                'user_id' => $user_id,
                'address'=>$address
            ]);
        }
        User::where('email', Auth::user()->email)->update(['name' => $name,'email' => $email]);
        
        return response()->json(['message' => 'Profile details updated']);
    }

    public function uploadPhoto(Request $request){
        
        $image = $request->file('image');

        $request->validate([
            'image' => 'required | mimes:jpg,jpeg,png'
        ]);
        $new_imgage_name = time().'-'.Auth::user()->lastname.Auth::user()->name.'.'.$image->extension();
        $image_path = $image->move(public_path('files/profile'), $new_imgage_name);
        $path_name = 'files/profile/' . $new_imgage_name;
        UserDetails::where('email', Auth::user()->email)->update(['image' => $path_name]);
        return response()->json(['message' => 'Profile photo uploaded']);
    }

    public function updatePassword(Request $request){
        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required',
            'confirmPassword' => 'required'
        ]);
        
        $user_details = User::where('email',Auth::user()->email)->first();

        if(Hash::check($currentPassword, $user_details->password)) {
            User::where('email', Auth::user()->email)->update(['password' => Hash::make($newPassword)]);
            return response()->json(['message' => 'Password updated' , 'status' => 1]);
        }else{
            return response()->json(['message' => 'Existing Password Not matched', 'status' => 2]);
        }
    }
    public function mySubjects($order_id){
        try {
           
           $order=Order::find($order_id);
           if($order->is_full_course_selected==1){
              $subjects=AssignSubject::where('assign_class_id',$order->assign_class_id)->where('board_id',$order->board_id)->get();
           }
           return view('website.my_account.my_subject',compact('subjects','order'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function myLesson($order_id,$subject_id){
        try {
            
               $order=Order::find($order_id);
               $subject=AssignSubject::where('assign_class_id',$order->assign_class_id)->where('board_id',$order->board_id)->where('id',$subject_id)->first();
               $all_lessons=Lesson::where('board_id',$order->board_id)->where('assign_class_id',$order->assign_class_id)->where('assign_subject_id',$subject_id)->where('parent_id',null)->get();
               return view('website.my_account.my_lesson',compact('all_lessons','order','subject'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function myLessonDetails($lesson_id){
        try {
           
            $lesson_id = Crypt::decrypt($lesson_id);
              
            $lesson=Lesson::with(['topics','subTopics'])->where('id',$lesson_id)->first();
            
            return view('website.my_account.my_lesson_details',compact('lesson'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function displayAttachment(Request $request){
        try {
           $lesson=Lesson::with('lessonAttachment')->where('id',$request->lesson_id)->first();
           visitorRecord($lesson->id,2);
           return response()->json($lesson);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function myPerformance(){
        try {
            $carts = Cart::with(['assignClass', 'board', 'assignSubject' => function ($q) {
                $q->with(['subject' => function ($qu) {
                    $qu->with(['lesson' => function ($query) {
                        $query->with('topics');
                    }]);
                }]);
            }])
                ->where('user_id', auth()->user()->id)
                ->where('is_paid', 1)
                ->where('is_remove_from_cart', 1)
                ->get();
            $subjects = [];
            $total_video_count = 0;
            $total_watch_video_count = 0;
            $all_subjects = [];
            foreach ($carts as $key => $cart) {

                foreach ($cart->assignSubject as $key => $assign_subject) {
                    $all_subjects[] = [
                        'id' => $assign_subject->subject->id,
                        'name' => $assign_subject->subject->subject_name,
                        'board' => $assign_subject->subject->boards->exam_board,
                        'class' => $assign_subject->subject->assignClass->class,
                    ];
                    $subjects[] = [

                        'total_video' => $total_video_count + subjectTotalVideo($assign_subject->subject->id),
                        'total_watch_video' => $total_watch_video_count + subjectTotalWatchVideo($assign_subject->subject->id),
                    ];
                }
            }
            $total_video = 0;
            $total_watch_video = 0;
            foreach ($subjects as $key => $subject) {
                $total_video = $total_video + $subject['total_video'];
                $total_watch_video = $total_watch_video + $subject['total_watch_video'];
            }

            if ($total_watch_video == 0) {
                $subject_progress = [
                    'watched_percentage' => 0,
                    'not_watched_percentage' => 100,
                    'subject_progress' => 0,
                ];
            } else {
                $watched_percentage = $total_watch_video ;
                $not_watched_percentage = ($total_video - $total_watch_video);
                $subject_progress=($total_video - $total_watch_video) / $total_video * 100;
                $total_watched=($watched_percentage/($watched_percentage+$not_watched_percentage))*100;
                $subject_progress = [
                    'watched_percentage' => round($watched_percentage),
                    'not_watched_percentage' => round($not_watched_percentage),
                    'subject_progress' => round($watched_percentage),
                    'total'=>round($total_watched),
                ];
            }


            // // $all_watched_video=SubjectLessonVisitor::where('visitor_id',auth()->user()->id)->groupBy(date('D',strtotime('created_at')))->get();
            // $all_watched_video_day_wises = SubjectLessonVisitor::query()
            //     ->where('visitor_id', auth()->user()->id)
            //     ->get()
            //     ->groupBy(function ($item) {
            //         return $item->created_at->format('D');
            //     });

            $date = Carbon::now()->subDays(7);

            $all_watched_video_day_wises = SubjectLessonVisitor::where('created_at', '>=', $date)->get();


            $total_mon_day_watch_video = 0;
            $total_mon_day_test = 0;
            $total_tue_day_watch_video = 0;
            $total_tue_day_test = 0;
            $total_wed_day_watch_video = 0;
            $total_wed_day_test = 0;
            $total_thu_day_watch_video = 0;
            $total_thu_day_test = 0;
            $total_fri_day_watch_video = 0;
            $total_fri_day_test = 0;
            $total_sat_day_watch_video = 0;
            $total_sat_day_test = 0;
            $total_sun_day_watch_video = 0;
            $total_sun_day_test = 0;





            $mon_day_watch_video = $tue_day_watch_video = $wed_day_watch_video = $thu_day_watch_video = $fri_day_watch_video = $sat_day_watch_video = $sun_day_watch_video = [];

            foreach ($all_watched_video_day_wises as $key => $all_watched_video_day_wise) {
                if ($all_watched_video_day_wise->created_at->format('D') == "Mon") {
                    $mon_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
                if ($all_watched_video_day_wise->created_at->format('D') == "Tue") {
                    $tue_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
                if ($all_watched_video_day_wise->created_at->format('D') == "Wed") {
                    $wed_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
                if ($all_watched_video_day_wise->created_at->format('D') == "Thu") {
                    $thu_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
                if ($all_watched_video_day_wise->created_at->format('D') == "Fri") {
                    $fri_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
                if ($all_watched_video_day_wise->created_at->format('D') == "Sat") {
                    $sat_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
                if ($all_watched_video_day_wise->created_at->format('D') == "Sun") {
                    $sun_day_watch_video[] = $all_watched_video_day_wise->video_watch_time;
                }
            }

            $user_practice_tests = UserPracticeTest::where('created_at', '>=', $date)->get();
            // $total_rating = $subject->review()->count() * 5;
            // $rating_average = round($subject->review()->sum('rating') / $total_rating * 5);

            $mon_day_practice_test = $tue_day_practice_test = $wed_day_practice_test = $thu_day_practice_test = $fri_day_practice_test = $sat_day_practice_test = $sun_day_practice_test = [];

            foreach ($user_practice_tests as $key => $user_practice_test) {
                if ($user_practice_test->created_at->format('D') == "Mon") {
                    $mon_day_practice_test[] = $user_practice_test->total_duration;
                }
                if ($user_practice_test->created_at->format('D') == "Tue") {
                    $tue_day_practice_test[] = $user_practice_test->total_duration;
                }
                if ($user_practice_test->created_at->format('D') == "Wed") {
                    $wed_day_practice_test[] = $user_practice_test->total_duration;
                }
                if ($user_practice_test->created_at->format('D') == "Thu") {
                    $thu_day_practice_test[] = $user_practice_test->total_duration;
                }
                if ($user_practice_test->created_at->format('D') == "Fri") {
                    $fri_day_practice_test[] = $user_practice_test->total_duration;
                }
                if ($user_practice_test->created_at->format('D') == "Sat") {
                    $sat_day_practice_test[] = $user_practice_test->total_duration;
                }
                if ($user_practice_test->created_at->format('D') == "Sun") {
                    $sun_day_practice_test[] = $user_practice_test->total_duration;
                }
            }

            if (!$mon_day_watch_video == []) {
                $total_mon_day_watch_video = totalTime($mon_day_watch_video);
            }
            if (!$tue_day_watch_video == []) {
                $total_tue_day_watch_video = totalTime($tue_day_watch_video);
            }

            if (!$wed_day_watch_video == []) {
                $total_wed_day_watch_video = totalTime($wed_day_watch_video);
            }
            if (!$thu_day_watch_video == []) {
                $total_thu_day_watch_video = totalTime($thu_day_watch_video);
            }
            if (!$fri_day_watch_video == []) {
                $total_fri_day_watch_video = totalTime($fri_day_watch_video);
            }
            if (!$sat_day_watch_video == []) {
                $total_sat_day_watch_video = totalTime($sat_day_watch_video);
            }
            if (!$sun_day_watch_video == []) {
                $total_sun_day_watch_video = totalTime($sun_day_watch_video);
            }
            //test duration
            if (!$mon_day_practice_test == []) {
                $total_mon_day_test = totalTime($mon_day_practice_test);
            }
            if (!$tue_day_practice_test == []) {
                $total_tue_day_test = totalTime($tue_day_practice_test);
            }

            if (!$wed_day_practice_test == []) {
                $total_wed_day_test = totalTime($wed_day_practice_test);
            }
            if (!$thu_day_practice_test == []) {
                $total_thu_day_test = totalTime($thu_day_practice_test);
            }
            if (!$fri_day_practice_test == []) {
                $total_fri_day_test = totalTime($fri_day_practice_test);
            }
            if (!$sat_day_practice_test == []) {
                $total_sat_day_test = totalTime($sat_day_practice_test);
            }
            if (!$sun_day_practice_test == []) {
                $total_sun_day_test = totalTime($sun_day_practice_test);
            }
            $time_spent = [
                'Mon' => $total_mon_day_watch_video + $total_mon_day_test,
                'Tue' => $total_tue_day_watch_video + $total_tue_day_test,
                'Wed' => $total_wed_day_watch_video + $total_wed_day_test,
                'Thu' => $total_thu_day_watch_video + $total_thu_day_test,
                'Fri' => $total_fri_day_watch_video + $total_fri_day_test,
                'Sat' => $total_sat_day_watch_video + $total_sat_day_test,
                'Sun' => $total_sun_day_watch_video + $total_sun_day_test,
            ];

            // MCQ test performance

            $user_practice_tests = UserPracticeTest::with('userPracticeTestAnswer')->where('user_id', auth()->user()->id)->get();
            if ($user_practice_tests->count() > 0) {
                foreach ($user_practice_tests as $key => $user_practice_test) {
                    $practice_test_duration[] = $user_practice_test->total_duration;
                }

                $total_correct = $user_practice_tests->sum('total_correct_count');
                $total_attempted = $user_practice_tests->sum('total_attempts');

                $total_duration_in_sec = totalTime($practice_test_duration);
                $mcq_performance = [
                    'test_attempted' => $user_practice_tests->count(),
                    'total_correct' => $total_correct,
                    'total_attempted' => $total_attempted,
                    'accuracy' => ($total_correct / $total_attempted) * 100,
                    'total_duration' => gmdate('H:i:s', $total_duration_in_sec),

                ];
            } else {
                $mcq_performance = [
                    'test_attempted' => 0,
                    'total_correct' => 0,
                    'total_attempted' => 0,
                    'accuracy' => 0,
                    'total_duration' => gmdate('H:i:s', 0),

                ];
            }
            $data = [
                'all_subjects' => $all_subjects,
                'subject_progress' => $subject_progress,
                'time_spent' => $time_spent,
                'mcq_performance' => $mcq_performance
            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }

}
