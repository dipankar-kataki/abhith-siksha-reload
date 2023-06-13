<?php
use App\Http\Controllers\teacher\CourseController;
use App\Http\Controllers\teacher\LessonController;
use App\Http\Controllers\teacher\StudentController;
use App\Http\Controllers\teacher\TeacherController;
use App\Http\Controllers\website\WebsiteAuthController;
use App\Http\Controllers\admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('login',[WebsiteAuthController::class,'viewLogin'])->name('teacher.login');
Route::post('signup', [WebsiteAuthController::class, 'signup'])->name('teacher.signup');
Route::post('verify-otp', [WebsiteAuthController::class, 'verifyOtp'])->name('teacher.verifyOtp');
Route::post('complete-signup', [WebsiteAuthController::class, 'teacherSignup'])->name('teacher.completeSignup');
Route::post('login', [WebsiteAuthController::class, 'login'])->name('teacher.auth.login');
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('teacher.dashboard')->middleware('admin');
    Route::prefix('apply')->group(function () {
        Route::post('/', [TeacherController::class, 'store'])->name('teacher.store');
        Route::get('/application',[TeacherController::class,'index'])->name('teacher.application');
        Route::get('/application/{userdetails_id}',[TeacherController::class,'details'])->name('teacher.application.view');
    });

    Route::prefix('course')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('teacher.course');
        Route::get('/create', [CourseController::class, 'create'])->name('teacher.course.create');
        Route::get('/view/{subject_id}', [CourseController::class, 'view'])->name('teacher.course.view');
        Route::get('/details/{subject_id}', [CourseController::class, 'details'])->name('teacher.course.details');
        Route::get('/subjects', [CourseController::class, 'mySubject'])->name('teacher.subject.index');
        Route::get('{lesson_id}/{user_id}', [CourseController::class, 'topicWiseReport'])->name('teacher.course.management.lesson.topic.report');
        Route::get('/mcq-attempt/{set_id}/{user_id}', [CourseController::class, 'mcqAttemptReport'])->name('teacher.view.mcq.attempt');

    });
    Route::prefix('lesson')->group(function () {
        Route::get('/', [LessonController::class, 'index'])->name('teacher.lesson');
        Route::get('/{lesson_id}',[LessonController::class,'view'])->name('teacher.lesson.view');
        Route::prefix('topic')->group(function () {
            Route::get('/', [LessonController::class, 'topicView'])->name('teacher.topic.view');
        });
    });
    Route::prefix('student')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('teacher.student.index');
        Route::get('/{subject_id}', [StudentController::class, 'subjectWiseStudent'])->name('teacher.subject.student');
        Route::get('/report/{subject_id}/{student_id}', [StudentController::class, 'subjectWiseStudentReport'])->name('teacher.subject.student.report');

    });

});
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('preview')->group(function () {
        Route::get('/{type}', [CourseController::class, 'preview'])->name('teacher.course.preview');
    });

});
