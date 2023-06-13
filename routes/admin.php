<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\admin\SubjectController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\ChapterController;
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\BoardController;
use App\Http\Controllers\admin\AssignClassController;
use App\Http\Controllers\admin\AssignSubjectController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EnquiryController;
use App\Http\Controllers\admin\GalleryController;
use App\Http\Controllers\admin\MultipleChoiceController;
use App\Http\Controllers\admin\EnrolledController;
use App\Http\Controllers\admin\TimeTableController;
use App\Http\Controllers\admin\LessonController;
use App\Http\Controllers\teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\teacher\StudentController;
use App\Http\Controllers\teacher\TeacherController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\website\ContactController;
use App\Http\Middleware\IsAdmin;
use App\Models\AssignSubject;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('admin');
Route::group(['middleware' => ['auth']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('log.out');


    //     return view('admin.dashboard.dashboard');
    // })->name('admin.dashboard')->middleware('admin');

    /* ------------------------------- COURSE ------------------------------------ */
    // Route::prefix('course')->group(function () {

    //     /* ------------------------------- COURSE ------------------------------------ */
    //     Route::get('',[CourseController::class,'index'])->name('admin.get.course');
    //     Route::view('create', 'admin.course.create')->name('admin.create.course');
    //     Route::post('creating',[CourseController::class,'create'])->name('admin.creating.course');
    Route::post('ckeditorImage', [CourseController::class, 'ckeditorImage'])->name('admin.course.upload');
    //     Route::get('edit/{id}',[CourseController::class,'editCourse'])->name('admin.edit.course');
    //     Route::post('editing',[CourseController::class,'edit'])->name('admin.editing.course');
    //     Route::post('active',[CourseController::class,'active'])->name('admin.active.course');
    //     Route::get('price/{id}',[CourseController::class,'chapterPrice'])->name('admin.price.course');

    // });

    Route::prefix('course-management')->group(function () {
        Route::prefix('board')->group(function () {
            Route::get('', [BoardController::class, 'allBoard'])->name('admin.course.management.board.all');
            Route::post('add-board', [BoardController::class, 'addBoard'])->name('admin.course.management.board.add');
            Route::post('update-board-status', [BoardController::class, 'updateBoardStatus'])->name('admin.course.management.board.update.status');
            Route::post('update-board', [BoardController::class, 'updateBoard'])->name('admin.course.management.board.update');
        });
        Route::prefix('teacher')->group(function () {
            Route::get('', [TeacherController::class, 'index'])->name('admin.teacher.all');
            Route::get('/{teacher_id}', [TeacherController::class, 'details'])->name('admin.teacher.details');
            Route::get('approved/{userdetails_id}', [TeacherController::class, 'approvedApplication'])->name('approved.teacher');
            Route::post('reject', [TeacherController::class, 'rejectApplication'])->name('reject.teacher');
        });
        //all new assign teacher
        Route::prefix('class')->group(function () {
            Route::get('', [AssignClassController::class, 'allClasses'])->name('admin.course.management.class.all');
            Route::post('assign', [AssignClassController::class, 'assignClass'])->name('admin.course.management.class.assign');
            Route::post('assign-teacher', [AssignClassController::class, 'assignTeacher'])->name('admin.course.assign.teacher');
            Route::post('assign-teacher-to-subject', [AssignClassController::class, 'assignTeacherToSubject'])->name('admin.teacher.tosubject');
            Route::post('update-assign-teacher-status', [AssignClassController::class, 'changeAssignTeacherStatus'])->name('admin.course.management.assignteacher.update.status');
            Route::post('update-board-status', [AssignClassController::class, 'updateClassStatus'])->name('admin.course.management.class.update.status');
        });

        Route::prefix('subject')->group(function () {
            Route::get('/', [AssignSubjectController::class, 'allSubjects'])->name('admin.course.management.subject.all');
            Route::get('create', [AssignSubjectController::class, 'create'])->name('admin.course.management.subject.create');
            Route::get('edit/{subject_id}', [AssignSubjectController::class, 'edit'])->name('admin.course.management.subject.edit');
            Route::post('store', [AssignSubjectController::class, 'store'])->name('admin.course.management.subject.store');
            Route::get('view/{subject_id}', [AssignSubjectController::class, 'view'])->name('admin.course.management.subject.view');
            Route::post('assign', [AssignSubjectController::class, 'assignSubject'])->name('admin.course.management.subject.assign');
            Route::get('lesson/{lesson_id}', [AssignSubjectController::class, 'assignSubjectLesson'])->name('admin.course.management.lesson.topic.display');
            Route::post('published', [SubjectController::class, 'published'])->name('admin.published.subject');
            Route::get('active/{subject_id}', [SubjectController::class, 'active'])->name('admin.active.subject');
            Route::post('/get-subjects',[AssignSubjectController::class,'findSubject'])->name('find.subject');
            Route::get('/student/{subject_id}', [StudentController::class, 'subjectWiseStudent'])->name('admin.subject.student');
            Route::get('/report/{subject_id}/{student_id}', [StudentController::class, 'subjectWiseStudentReport'])->name('admin.subject.student.report');
            Route::get('{lesson_id}/{user_id}', [TeacherCourseController::class, 'topicWiseReport'])->name('admin.course.management.lesson.topic.report');
            Route::get('/mcq-attempt/{set_id}/{user_id}', [TeacherCourseController::class, 'mcqAttemptReport'])->name('admin.view.mcq.attempt');
        });
        Route::prefix('lesson')->group(function () {
            Route::get('all', [LessonController::class, 'index'])->name('admin.course.management.lesson.all');
            Route::get('create/{subject_id}', [LessonController::class, 'create'])->name('admin.course.management.lesson.create');
            Route::post('store', [LessonController::class, 'store'])->name('admin.course.management.lesson.store');
            Route::get('edit/{lesson_id}', [LessonController::class, 'edit'])->name('admin.course.management.lesson.edit');





            // Route::post('store/file', [LessonController::class,'storeFile'])->name('admin.course.management.lesson.storefile');
            Route::get('{lesson_id}', [LessonController::class, 'topicCreate'])->name('admin.course.management.lesson.topic.create'); //add Recources work
            Route::post('topic/store', [LessonController::class, 'topicStore'])->name('admin.course.management.lesson.topic.store'); //store Recources work
            Route::get('view/{lesson_id}', [LessonController::class, 'resourceView'])->name('admin.course.management.lesson.view'); // view resources
            Route::get('edit/{lesson_id}', [LessonController::class, 'resourceEdit'])->name('admin.course.management.lesson.edit'); //edit video resources
            Route::post('update', [LessonController::class, 'resourceupdate'])->name('admin.course.management.lesson.update'); //update video resources
            Route::post('update-lesson', [LessonController::class, 'updateLesson'])->name('admin.course.management.lesson.update.name'); //update video resources
            /* ------------------------------- Multiple Choice Questions ------------------------------------ */
            Route::prefix('multiple-choice')->group(function () {
                Route::get('multiple-choice-question', [MultipleChoiceController::class, 'index'])->name('admin.index.multiple.choice');
                Route::get('add-multiple-choice', [MultipleChoiceController::class, 'addMultipleChoice'])->name('admin.add.multiple.choice');
                // Route::post('insert-multiple-choice',[MultipleChoiceController::class,'insertMultipleChoice'])->name('admin.insert.multiple.choice');
                Route::post('is-activate-multiple-choice', [MultipleChoiceController::class, 'isActivateMultipleChoice'])->name('admin.is.activate.multiple.choice');

                Route::post('insert-mcq-question', [MultipleChoiceController::class, 'insertQuestions'])->name('admin.insert.mcq.question');
                Route::get('view-mcq-question/{id}', [MultipleChoiceController::class, 'viewMcq'])->name('admin.view.mcq.question');
                Route::get('status/{lesson_id}', [MultipleChoiceController::class, 'statusChange'])->name('admin.mcq.status');
                Route::get('status-set/{set_id}', [MultipleChoiceController::class, 'mcqSetStatusChange'])->name('admin.mcq.set.status');

            });



            Route::get('subtopic/{lesson_slug}/{topic_slug}', [LessonController::class, 'subTopicCreate'])->name('admin.course.management.lesson.subtopic.create');
            Route::get('attachment/{lesson_id}/{url_type}', [LessonController::class, 'displayAttachment'])->name('admin.course.management.lesson.attachment');
            Route::get('preview/{attachement_id}', [LessonController::class, 'previewStatusChange'])->name('admin.preview.lesson');
            Route::get('status/{lesson_id}', [LessonController::class, 'lessonStatusChange'])->name('admin.lesson.status');
        });
    });

    /* ------------------------------- CHAPTER ------------------------------------ */
    Route::prefix('chapter')->group(function () {

        /* ------------------------------- CHAPTER ------------------------------------ */
        Route::get('{id}', [ChapterController::class, 'index'])->name('admin.get.chapter');
        // Route::view('create', 'admin.course.create')->name('admin.create.course');
        Route::post('creating', [ChapterController::class, 'create'])->name('admin.creating.chapter');
        Route::post('editChapter', [ChapterController::class, 'editChapter'])->name('admin.edit.chapter');
        Route::post('changeChapterVisibility', [ChapterController::class, 'changeChapterVisibility'])->name('admin.change.visibility.chapter');
    });

    /* ------------------------------- Master ------------------------------------ */
    Route::prefix('master')->group(function () {

        // /* ------------------------------- COURSE ------------------------------------ */
        // Route::prefix('subject')->group(function () {
        //     Route::get('',[SubjectController::class,'index'])->name('admin.get.subject');
        Route::view('create', 'admin.master.subjects.create')->name('admin.create.subject');
        Route::post('creating', [SubjectController::class, 'create'])->name('admin.creating.subject');
        //     Route::post('active',[SubjectController::class,'active'])->name('admin.active.subject');
        //     Route::get('edit/{id}',[SubjectController::class,'editSubject'])->name('admin.edit.subject');
        //     Route::post('editing',[SubjectController::class,'edit'])->name('admin.editing.subject');

        // });

        /* ------------------------------- Banner ------------------------------------ */
        Route::prefix('banner')->group(function () {
            Route::get('', [BannerController::class, 'index'])->name('admin.get.banner');
            Route::view('create', 'admin.master.banner.create')->name('admin.create.banner');
            Route::post('creating', [BannerController::class, 'create'])->name('admin.creating.banner');
            Route::post('active', [BannerController::class, 'active'])->name('admin.active.banner');
            Route::get('edit/{id}', [BannerController::class, 'editBanner'])->name('admin.edit.banner');
            Route::get('delete/{id}', [BannerController::class, 'deleteBanner'])->name('admin.delete.banner');
            Route::post('editing', [BannerController::class, 'edit'])->name('admin.editing.banner');
        });

        /* ------------------------------- Blog ------------------------------------ */
        Route::prefix('blog')->group(function () {
            // Route::get('',[BlogController::class,'index'])->name('admin.get.blog');
            Route::get('blog/{id?}', [BlogController::class, 'index'])->name('admin.get.blog.by.id');
            Route::view('create', 'admin.master.blog.create')->name('admin.create.blog');
            Route::post('creating', [BlogController::class, 'create'])->name('admin.creating.blog');
            Route::post('ckeditorImage', [BlogController::class, 'ckeditorImage'])->name('upload');
            Route::post('active', [BlogController::class, 'active'])->name('admin.active.blog');
            Route::get('edit/{id}', [BlogController::class, 'editBlog'])->name('admin.edit.blog');
            Route::post('editing', [BlogController::class, 'edit'])->name('admin.editing.blog');
            Route::get('view/{id}', [BlogController::class, 'viewBlog'])->name('admin.read.blog');
        });

        /* ------------------------------- Gallery ------------------------------------ */
        Route::prefix('gallery')->group(function () {
            Route::get('', [GalleryController::class, 'index'])->name('admin.get.gallery');
            Route::view('create', 'admin.master.gallery.create')->name('admin.create.gallery');
            Route::post('creating', [GalleryController::class, 'create'])->name('admin.creating.gallery');
            Route::post('active', [GalleryController::class, 'active'])->name('admin.active.gallery');
            Route::get('edit/{id}', [GalleryController::class, 'editGallery'])->name('admin.edit.gallery');
            Route::get('delete/{id}', [GalleryController::class, 'deleteGallery'])->name('admin.delete.gallery');
            Route::post('editing', [GalleryController::class, 'edit'])->name('admin.editing.gallery');
        });
    });




    /* ------------------------------- Enrolled Students ------------------------------------ */
    Route::prefix('enrolled')->group(function () {
        Route::get('all', [EnrolledController::class, 'getEnrolledStudents'])->name('admin.get.enrolled.students');
        Route::get('pending', [EnrolledController::class, 'getPendingStudents'])->name('admin.get.enrolled.pending');
        Route::get('students', [EnrolledController::class, 'getRegisterdStudents'])->name('admin.registered.students');
    });

    /* ------------------------------- Enquiry ------------------------------------ */
    Route::prefix('enquiry')->group(function () {
        Route::get('get-enquiry-details', [EnquiryController::class, 'getEnquiryDetails'])->name('admin.get.enquiry.details');
        Route::post('mark-enquiry', [EnquiryController::class, 'markEnquiry'])->name('admin.mark.enquiry');
    });

    Route::prefix('contact')->group(function () {
        Route::get('get-contact-details', [ContactController::class, 'getContactDetails'])->name('website.get.contact.details');

    });

    /* ------------------------------- Time Table ------------------------------------ */
    Route::prefix('time-table')->group(function () {
        Route::get('view-time-table', [TimeTableController::class, 'adminViewTimeTable'])->name('admin.view.time.table');
        Route::get('create-time-table', [TimeTableController::class, 'adminCreateTimeTable'])->name('admin.create.time.table');
        Route::post('save-time-table', [TimeTableController::class, 'saveTimeTable'])->name('admin.save.time.table');
        Route::post('change-visibility-time-table', [TimeTableController::class, 'changeVisibility'])->name('admin.change.visibility.time.table');
    });

        /* ------------------------------- Testimonial ------------------------------------ */
        Route::prefix('testimonial')->group(function () {
            Route::get('', [TestimonialController::class, 'index'])->name('admin.testimonial.index');
            Route::get('add', [TestimonialController::class, 'add'])->name('admin.testimonial.add');
            Route::post('submit', [TestimonialController::class, 'submit'])->name('admin.testimonial.submit');
            Route::post('delete', [TestimonialController::class, 'delete'])->name('admin.testimonial.delete');
        });
});
Route::get('course-management/subject/demovideo/{subject_id}', [SubjectController::class, 'getDemoVideo'])->name('admin.subject.promovideo');

/* ------------------------------- Enquiry Not Authenticated ------------------------------------ */
Route::prefix('enquiry')->group(function () {
    Route::post('save-enquiry-details', [EnquiryController::class, 'saveEnquiryDetails'])->name('website.save.enquiry.details');
});
