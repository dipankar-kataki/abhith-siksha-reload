<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Common\Activation;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\Course;
use Carbon\Carbon;
use App\Models\Chapter;
use App\Models\Testimonial;
use Brian2694\Toastr\Facades\Toastr;

class DashboardController extends Controller
{
    //
    protected function index()
    {
        
        $banner = Banner::where('is_activate', Activation::Activate)->take(6)->orderBy('id', 'DESC')->get();
        $blogs = Blog::where('is_activate', Activation::Activate)->take(3)->orderBy('id', 'DESC')->get();
        $testimonial = Testimonial::latest()->limit(3)->get();
        // $gallery = Gallery::where('is_activate',Activation::Activate)->take(4)->orderBy('id','DESC')-get();
        $publishCourse = [];
        $upComingCourse = [];
        $price = [];

        $courses = Course::where('is_activate', Activation::Activate)->with('priceList')->orderBy('id', 'DESC')->get();

        foreach ($courses as $key => $value) {
            # code...
            $price = [];
            $publishDate = Carbon::parse($value->publish_date)->format('Y-m-d') ;
            $Today = Carbon::today()->format('Y-m-d');
            if ($publishDate < $Today) {
                $chapters = Chapter::where([['course_id', $value->id],['is_activate',Activation::Activate]])->get();
                foreach ($chapters as $key => $value2) {
                    # code...
                    $price [] = $value2->price;
                }
                $final_price = array_sum($price);
                $published['final_price']=$final_price;
                $published['id']=$value->id;
                $published['name']=$value->name;
                $published['course_pic']=$value->course_pic;
                $published['duration']=$value->durations;
                $published['publish_date']=$value->publish_date;
                $publishCourse[] = $published;
            } elseif ($publishDate == $Today) {
                //    dd('Not Today', $value->publish_date);
                $publishTime = Carbon::parse($value->publish_date)->format('H:i');
                $presentTime = Carbon::now()->format('H:i');
                if ($publishTime < $presentTime) {
                    $chapters = Chapter::where([['course_id', $value->id],['is_activate',Activation::Activate]])->get();
                    foreach ($chapters as $key => $value2) {
                        # code...
                        $price [] = $value2->price;
                    }
                    $final_price = array_sum($price);
                    $published['final_price']=$final_price;
                    $published['id']=$value->id;
                    $published['name']=$value->name;
                    $published['course_pic']=$value->course_pic;
                    $published['duration']=$value->durations;
                    $published['publish_date']=$value->publish_date;
                    $publishCourse[] = $published;
                } else {
                    $chapters = Chapter::where([['course_id', $value->id],['is_activate',Activation::Activate]])->get();
                    foreach ($chapters as $key => $value2) {
                        # code...
                        $price [] = $value2->price;
                    }
                    $final_price = array_sum($price);
                    $upcoming['final_price']=$final_price;
                    $upcoming['id']=$value->id;
                    $upcoming['name']=$value->name;
                    $upcoming['course_pic']=$value->course_pic;
                    $upcoming['duration']=$value->durations;
                    $upcoming['publish_date']=$value->publish_date;
                    $upComingCourse[] = $upcoming;
                }
            } elseif ($publishDate > $Today) {
                // dd('GRATER Today', $value->publish_date);
                $chapters = Chapter::where([['course_id', $value->id],['is_activate',Activation::Activate]])->get();
                foreach ($chapters as $key => $value2) {
                    # code...
                    $price [] = $value2->price;
                }
                $final_price = array_sum($price);
                $upcoming['final_price']=$final_price;
                $upcoming['id']=$value->id;
                $upcoming['name']=$value->name;
                $upcoming['course_pic']=$value->course_pic;
                $upcoming['duration']=$value->durations;
                $upcoming['publish_date']=$value->publish_date;
                $upComingCourse[] = $upcoming;
            }
        }
        // dd($publishCourse);
        return view('website.home',compact('banner', 'blogs', 'testimonial', 'upComingCourse', 'publishCourse'));
    }
}
