<?php

use App\Models\AssignSubject;
use App\Models\Cart;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Order;
use App\Models\SubjectLessonVisitor;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserPracticeTest;
use Illuminate\Routing\Route;

function attachmenetPath($path)
{
    return base_path() . $path;
}
//store visitor details
function  visitorRecord($subject_lesson_id, $type)
{
    if (auth()->check()) {
        if ($type == 1) {
            $subject = AssignSubject::find($subject_lesson_id);
            $subject_lesson_id = $subject->id;
            $teacher_id = $subject->assignTeacher->id;
        } else {
            $lesson = Lesson::find($subject_lesson_id);
            $subject_lesson_id = $lesson->id;
            $teacher_id = $lesson->assignSubject->assignTeacher->id;
        }

        $is_available = SubjectLessonVisitor::where([
            'lesson_subject_id' => $subject_lesson_id,
            'teacher_id' => $teacher_id,
            'visitor_id' => auth()->user()->id,
            'type' => $type
        ])->first();

        if ($is_available != null) {
            $is_available->update(['total_visit' => $is_available->total_visit + 1]);
        } else {
            $data = [
                'lesson_subject_id' => $subject_lesson_id,
                'teacher_id' => $teacher_id,
                'visitor_id' => auth()->user()->id,
                'total_visit' => 1,
                'type' => $type,
            ];

            SubjectLessonVisitor::create($data);
        }
    } else {
        return false;
    }
}
function dateFormat($dateTime, $format = "d-m-Y")
{
    if ($dateTime == "0000-00-00" || $dateTime == "0000-00-00 00:00:00") {
        return " ";
    }
    $date = strtotime($dateTime);
    if (date('d-m-Y', $date) != '01-01-1970') {
        return date($format, $date);
    } else {
        return " ";
    }
}
function subjectTotalResource($subject_id, $type)
{
    if ($type == "content") {
        return Lesson::where('assign_subject_id', $subject_id)->get()->count();
    } elseif ($type == "image") {
        return LessonAttachment::whereHas('lesson')->where('img_url', '!=', null)->get()->count();
    } else {
        return LessonAttachment::whereHas('lesson')->where('origin_video_url', '!=', null)->get()->count();
    }
}
function lessonTotalVisite($lesson_id)
{
    $total_visit = SubjectLessonVisitor::where('lesson_subject_id', $lesson_id)->where('visitor_id', auth()->user()->id)->where('type', 2)->first();
    return $total_visit->total_visit;
}
function getPrefix($request)
{
    return  $request->route()->getPrefix();
}
function getAssignSubjects()
{
    if (auth()->check()) {
        $assign_subject = AssignSubject::with('assignClass', 'boards')->where('is_activate', 1)->where('assign_class_id', auth()->user()->userDetail->assign_class_id)->where('board_id', auth()->user()->userDetail->board_id)->limit(4)->orderBy('created_at', 'DESC')->get();
        if ($assign_subject->count() > 0) {
            return  $assign_subject;
        } else {
            return AssignSubject::with('assignClass', 'boards')->where('is_activate', 1)->limit(4)->get();
        }
    } else {
        return AssignSubject::with('assignClass', 'boards')->where('is_activate', 1)->limit(4)->get();
    }
}

function isTeacherApply()
{
    $user_details = UserDetails::where('user_id', auth()->user()->id)->where('status', '!=', 0)->count();
    if ($user_details == 0) {
        return false;
    } else {
        return true;
    }
}
function userFirstName()
{
    $words = explode(" ", auth()->user()->name);

    return $words[0];
}
function teacherReferralId()
{

    $referralId         = 'ABHITHSIKSHA' . date('dmY') . '/' . random_int(10000000, 99999999);
    return $referralId;
}
function reciptGenerate($id)
{
    $reciptId         = 'ABHITHSIKSHA/' . $id;
    return $reciptId;
}
function getlessonAttachment($lesson_id)
{
    $lesson = Lesson::with('lessonAttachment')->where('id', $lesson_id)->first();
    if ($lesson->type == 1) {
        $url_name = $lesson->lessonAttachment->img_url;
        $type = 1;
        $extension = pathinfo($url_name, PATHINFO_EXTENSION);
        return $data = [
            'url_name' => $url_name,
            'type' => $type,
            'extension' => $extension,
        ];
    }
}
function otpSend($phone, $otp)
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
        return false;
    } else {
        return true;
    }
}

function otpSendForgotPassword($phone, $otp)
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
        return false;
    } else {
        return true;
    }
}
function isUserBuy($subject_id)
{

    $isBuy = Order::whereHas("assignSubject", function ($q) use ($subject_id) {
        $q->where('assign_subject_id', $subject_id);
    })->where("user_id", auth()->user()->id)->get();
    if ($isBuy->isEmpty()) {
        return false;
    } else {
        return true;
    }
}
function timeDifference($from, $to)
{
    $to   = new DateTime($to);
    $from = new DateTime($from);
    $diff = $to->diff($from);
    return $diff->format('%H:%I:%S');
}
function addTime($first, $second)
{
    $secs = strtotime($second) - strtotime("00:00:00");
    return  date("H:i:s", strtotime($first) + $secs);
}
function isPracticeTestPlayed($set_id)
{
    $user_practice_tests = UserPracticeTest::where('user_id', auth()->user()->id)->where('set_id', $set_id)->first();
    if ($user_practice_tests) {
        return 1;
    } else {
        return 0;
    }
}
function getPracticeTestId($set_id)
{
    $user_practice_tests = UserPracticeTest::where('user_id', auth()->user()->id)->where('set_id', $set_id)->where('end_time', '!=', null)->latest('created_at')->first();
    if ($user_practice_tests) {
        return $user_practice_tests->id;
    } else {
        return 0;
    }
}
function subjectTotalVideo($subject_id)
{
    $total_video = Lesson::where('assign_subject_id', $subject_id)->where('type', 2)->get()->count();
    return $total_video;
}
function subjectTotalArticle($subject_id)
{
    $total_article = Lesson::where('assign_subject_id', $subject_id)->where('type', 3)->get()->count();
    return $total_article;
}
function subjectTotalDocument($subject_id)
{
    $total_document = Lesson::where('assign_subject_id', $subject_id)->where('type', 1)->get()->count();
    return $total_document;
}
function lessonTotalVideo($parent_id)
{
    $total_video = Lesson::where('parent_id', $parent_id)->where('type', 2)->get()->count();
    return $total_video;
}
function lessonTotalArticle($parent_id)
{
    $total_article = Lesson::where('parent_id', $parent_id)->where('type', 3)->get()->count();
    return $total_article;
}
function lessonTotalDocument($parent_id)
{
    $total_document = Lesson::where('parent_id', $parent_id)->where('type', 1)->get()->count();
    return $total_document;
}
function lessonTopicFindById($parent_id)
{
    $total_lesson = Lesson::where('parent_id', $parent_id)->get()->count();
    return $total_lesson;
}
function subjectTotalWatchVideo($subject_id)
{
    $total_video = SubjectLessonVisitor::where('visitor_id', auth()->user()->id)->where('subject_id', $subject_id)->distinct('lesson_subject_id')->count('lesson_subject_id');
    return $total_video;
}
function subjectAlreadyPurchase($subject_id)
{

    $isBuy = Order::whereHas("assignSubject", function ($q) use ($subject_id) {
        $q->where('assign_subject_id', $subject_id);
    })->where("user_id", auth()->user()->id)->first();

    if ($isBuy == null) {
        return 0;
    } else {
        return 1;
    }
}
function subjectAlreadyInCart($subject_id)
{

    $subject = AssignSubject::find($subject_id);
    $board_id = $subject->board_id;
    $class_id = $subject->assign_class_id;

    $cart_check = Cart::where('board_id', $board_id)->where('assign_class_id', $class_id)->where('is_remove_from_cart', 0)->where('user_id', auth()->user()->id)->first();
    if ($cart_check) {
        $subject_in_cart = $cart_check->assignSubject->where('assign_subject_id', $subject_id)->first();
        if ($subject_in_cart) {
            return 1;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
function checkemail($str)
{
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}
function totalTime($times)
{

    $time_seconds = 0;
    foreach ($times as $key => $time) {
        $time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);

        sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);

        $time_seconds = $time_seconds + $hours * 3600 + $minutes * 60 + $seconds;
    }

    return $time_seconds;
}
function converToSec($time)
{
    $time_seconds = 0;
    $time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $time);

    sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);

    $time_seconds = $time_seconds + $hours * 3600 + $minutes * 60 + $seconds;
    return $time_seconds;
}

function ifBannerActive($current_route)
{
    if ($current_route == "admin.get.banner" || $current_route == "admin.create.banner" || $current_route == "admin.creating.banner" || $current_route == "admin.active.banner" || $current_route == "admin.edit.banner" || $current_route == "admin.editing.banner") {
        return true;
    } else {
        return false;
    }
}
function ifBlogActive($current_route)
{
    if ($current_route == "admin.get.blog.by.id" || $current_route == "admin.create.blog" || $current_route == "admin.creating.blog" || $current_route == "upload" || $current_route == "admin.active.blog" || $current_route == "admin.edit.blog" || $current_route == "admin.editing.blog" || $current_route == "admin.read.blog") {
        return true;
    } else {
        return false;
    }
}
function ifGalleryActive($current_route)
{
    if ($current_route == "admin.get.gallery" || $current_route == "admin.create.gallery" || $current_route == "admin.creating.gallery" || $current_route == "admin.edit.gallery" || $current_route == "admin.editing.gallery") {
        return true;
    } else {
        return false;
    }
}
function ifSubjectActive($current_route)
{
    
    if ($current_route == "admin.course.management.subject.all" || $current_route == "admin.course.management.subject.create" || $current_route == "admin.course.management.subject.edit" || $current_route == "admin.course.management.subject.store" || $current_route == "admin.course.management.subject.view" || $current_route == "admin.course.management.subject.assign" || $current_route == "admin.course.management.lesson.topic.display" || $current_route == "admin.published.subject" || $current_route == "admin.active.subject" || $current_route == "admin.course.management.lesson.all" || $current_route == "admin.course.management.lesson.create" || $current_route == "admin.course.management.lesson.topic.create" || $current_route == "admin.course.management.lesson.view" || $current_route == "admin.course.management.lesson.create") {
        return true;
    } else {
        return false;
    }
}
function ifClassActive($current_route)
{
    
    if ($current_route == "admin.course.management.class.all") {
        return true;
    } else {
        return false;
    }
}
function ifExamBoardActive($current_route)
{
    if ($current_route == "admin.course.management.board.all") {
        return true;
    } else {
        return false;
    }
}
function subjectStatus($subject_id)
{
    $isBuy = Order::whereHas("assignSubject", function ($q) use ($subject_id) {
        $q->where('assign_subject_id', $subject_id);
    })->where("user_id", auth()->user()->id)->first();

    $isSubjectActive = AssignSubject::where('is_activate', 1)->where('published', 1)->where('id', $subject_id)->first();
    if ($isSubjectActive && (!$isBuy)) { //subject activate and not buy
        return 3;
    } elseif ($isBuy) {
        return 1;
    } else {
        return 2;
    }
}
function totalAmountCart($cart_id)
{

    $cart = Cart::with('board', 'assignClass', 'assignSubject')->where('id', $cart_id)->first();
    $all_subjects = $cart->assignSubject;

    $total = 0;

    foreach ($all_subjects as $key => $all_subject) {

        if (subjectStatus($all_subject->assign_subject_id) == 3) {
            $total = $total + $all_subject->amount;
        }
    }
    return $total;
}
function totalCartItem()
{
    $cart = Cart::where('is_remove_from_cart', 0)->where('user_id', auth()->user()->id)->where('is_buy', 0)->get();
    return $cart->count();
}
function orderNo()
{
    $order_count = Order::count() + 1;
    $order_no         = date('dmY') . '/' . $order_count;
    return $order_no;
}
function videoWatchTime($user_id, $subject_id, $lesson_id)
{
    $video_watch_time = SubjectLessonVisitor::where('visitor_id', $user_id)->where('subject_id', $subject_id)->where('lesson_subject_id', $lesson_id)->first();
    if ($video_watch_time) {
        return $video_watch_time->video_watch_time;
    } else {
        return "00:00:00";
    }
}
