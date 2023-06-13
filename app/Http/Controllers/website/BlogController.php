<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Common\Activation;
use Illuminate\Support\Facades\Crypt;

class BlogController extends Controller
{
    //

    protected function details(Request $request){
        $blog_id = Crypt::decrypt($request->id);

        $blog = Blog::find($blog_id);

        return view('website.blog.bloddetails', \compact('blog'));
    }

    protected function getBlog()
    {
        $blogs = Blog::where('is_activate', Activation::Activate)->orderBy('id','DESC')->paginate(4);

        return view('website.blog.blog',\compact('blogs'));
    }

    public function createBlog(Request $request){
        $blogName = $request->blogName;
        $blogPic = $request->pic;
        $blogDescription = $request->description;

        $request->validate([
            'blogName' => 'required',
            'pic' => 'required',
            'description'  => 'required',
            'blog_category' => 'required'
        ]);

        if (isset($blogPic) && !empty($blogPic)) {
            $new_name = date('d-m-Y-H-i-s') . '_' . $blogPic->getClientOriginalName();
            // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
            $blogPic->move(public_path('/files/blog/image/'), $new_name);
            $file = 'files/blog/image/' . $new_name;
        }

        $create = Blog::create([
            'name' => $blogName,
            'blog_image' => $file,
            'blog' => $blogDescription,
            'category' => $request->blog_category,
            'is_activate' => 0,
        ]);

        if($create){
            return response()->json(['blogMessage' => 'Blog created successfully']);
        }else{
            return response()->json(['blogErrorMessage' => 'Oops!, Something went wrong']);
        }

    }
}
