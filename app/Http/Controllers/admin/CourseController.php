<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Carbon\Carbon;
use App\Models\Chapter;
use App\Common\Activation;
use App\Common\BadWords;
use Illuminate\Support\Facades\Crypt;

class CourseController extends Controller
{
    //

    protected function index()
    {
        $course = Course::orderBy('id', 'DESC')->paginate(10);

        return view('admin.course.course', \compact('course'));
    }

    protected function create(Request $request)
    {
       
        // return response($request->course_video_thumbnail);
        # code...
        $this->validate($request,[
            'name' => 'required',
            'subject_id' => 'required',
            'duration' => 'required',
            'publish_date' => 'required',
            'publish_time' => 'required',
            'data' => 'required',

        ],[
            'name.required' => 'Course name is required',
            'subject_id.required' => 'Select subject',
            'duration.required' => 'Duration is required',
            'publish_date.required' => 'Publish date is required',
            'publish_time.required' => 'Publish time is required',
            'data.required' => 'Description is required',
        ]);


        if (Carbon::parse($request->publish_date)->format('Y-m-d') == Carbon::today()->format('Y-m-d')) {
            $publishTime = Carbon::parse($request->publish_time)->format('H:i');
            $presentTime = Carbon::now()->format('H:i');
            if ($publishTime < $presentTime) {
                return response()->json(["status"=>2,'error'=>'Publish Time can\'t be lesser then Present Time']);
            } else {
                $document = $request->pic;
                $video = $request->video;
                $imgFile = '';
                $videoFile = '';
                // $videoThumbnailFile = '';
                $checkCourseName = Course::where('name','like','%'.$request->name.'%')->where('subject_id',$request->subject_id)->exists();
                if( $checkCourseName == true){
                    return response()->json(['error' => 'Oops! Chapter name already exists under this course name', 'status' => 2]);
                }else{
                    
                    if ( $request->hasFile('pic') &&  $request->hasFile('video') ) {
                        $new_img_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                        $document->move(public_path('/files/course/'), $new_img_name);
                        $imgFile = 'files/course/' . $new_img_name;
    
                        $new_video_name = date('d-m-Y-H-i-s') . '_' . $video->getClientOriginalName();
                        $video->move(public_path('/files/course/courseVideo/'), $new_video_name);
                        $videoFile = 'files/course/courseVideo/' . $new_video_name;
                    }else if( $request->hasFile('pic') ){
                        $new_img_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                        $document->move(public_path('/files/course/'), $new_img_name);
                        $imgFile = 'files/course/' . $new_img_name;
                    }else{
                        return response()->json(["status"=>2,'error'=>'Image or Video is required']);
                    }

                    Course::create([
                        'name' => $request->name,
                        'subject_id' => $request->subject_id,
                        'course_pic' => $imgFile,
                        'course_video' => $videoFile,
                        'durations' => $request->duration.' '.$request->duration_type,
                        'publish_date' => Carbon::parse($request->publish_date.$request->publish_time)->format('Y-m-d H:i:s'),
                        'time' => Carbon::parse($request->publish_time)->format('H:i:s'),
                        'description' =>  \ConsoleTVs\Profanity\Builder::blocker($request->data, BadWords::badWordsReplace)->strictClean(false)->filter(),
                    ]);
                    return response()->json(['status'=>1]);
                }
            }
        } else {

            $checkCourseName = Course::where('name','like','%'.$request->name.'%')->where('subject_id',$request->subject_id)->exists();
            if( $checkCourseName == true){
                return response()->json(['error' => 'Oops! Chapter name already exists under this course name', 'status' => 2]);
            }else{
                $document = $request->pic;
                $video = $request->video;
                // $video_thumbnail = $request->course_video_thumbnail;


                if ( $request->hasFile('pic') &&  $request->hasFile('video') ) {
                    $new_img_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                    $document->move(public_path('/files/course/'), $new_img_name);
                    $imgFile = 'files/course/' . $new_img_name;

                    $new_video_name = date('d-m-Y-H-i-s') . '_' . $video->getClientOriginalName();
                    $video->move(public_path('/files/course/courseVideo/'), $new_video_name);
                    $videoFile = 'files/course/courseVideo/' . $new_video_name;
                }else if(  $request->hasFile('pic') ){
                    $new_img_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                    $document->move(public_path('/files/course/'), $new_img_name);
                    $imgFile = 'files/course/' . $new_img_name;
                }else{
                    return response()->json(["status"=>2,'error'=>'Image or Video is required']);
                }

                Course::create([
                    'name' => $request->name,
                    'subject_id' => $request->subject_id,
                    'course_pic' => $imgFile,
                    'course_video' => $videoFile,
                    'durations' => $request->duration.' '.$request->duration_type,
                    'publish_date' => Carbon::parse($request->publish_date.$request->publish_time)->format('Y-m-d H:i:s'),
                    'time' => Carbon::parse($request->publish_time)->format('H:i:s'),
                    'description' =>  \ConsoleTVs\Profanity\Builder::blocker($request->data, BadWords::badWordsReplace)->strictClean(false)->filter(),

                ]);
                return response()->json(['status'=>1]);
            }
        }
    }

    protected function ckeditorImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;

            $request->file('upload')->move(public_path('files/course/ckImage'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('files/course/ckImage/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    protected function editCourse(Request $request)
    {
        $course_id = Crypt::decrypt($request->id);

        $course = Course::find($course_id);

        return view('admin.course.edit', \compact('course'));
    }

    protected function edit(Request $request)
    {
        $course_id = Crypt::decrypt($request->id);
        $document = $request->pic;
        $course = Course::where('id', $course_id)->first();

        if ($document->getClientOriginalName() == 'blob') {
            $course->name = $request->name;
            $course->subject_id = $request->subject_id;
            $course->durations=  $request->duration;
            $course->publish_date = Carbon::parse($request->publish_date.$request->publish_time)->format('Y-m-d H:i:s');
            $course->time = Carbon::parse($request->publish_time)->format('H:i:s');
            $course->description = $request->data;
            $course->save();
        } else {
            if (isset($document) && !empty($document)) {
                $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
                $document->move(public_path('/files/course/'), $new_name);
                $file = 'files/course/' . $new_name;
            }
            $course->name = $request->name;
            $course->subject_id = $request->subject_id;
            $course->durations=  $request->duration;
            $course->course_pic = $file;
            $course->publish_date = Carbon::parse($request->publish_date.$request->publish_time)->format('Y-m-d H:i:s');
            $course->time = Carbon::parse($request->publish_time)->format('H:i:s');
            $course->description = \ConsoleTVs\Profanity\Builder::blocker($request->data, BadWords::badWordsReplace)->strictClean(false)->filter();
            $course->save();
        }

        return response()->json(['status'=>1, 'message' => 'Course edited successfully']);
    }

    protected function active(Request $request)
    {
        $course = Course::find($request->catId);
        $course->is_activate = $request->active;
        $course->save();
    }

    protected function chapterPrice(Request $request)
    {
        # code...
        $course_id = Crypt::decrypt($request->id);
        $Total_price = Course::where([['is_activate',Activation::Activate],['id',$course_id]])->with('priceList')->first();
        // dd($Total_price['priceList']);
        $price = [];
        foreach ($Total_price['priceList'] as $key => $value) {
            # code...
            $price [] = $value->price;
        }
        $final_price = array_sum($price);
        return view('admin.course.view', \compact('Total_price','final_price'));
    }
}
