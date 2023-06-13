<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Common\BadWords;
use Illuminate\Support\Facades\Crypt;


class BlogController extends Controller
{
    //
    protected function index(Request $request, $id = null)
    {

        $blog_id = 0;
        if($id != null){
            $blog_id = Crypt::decrypt($id);
        }

        $blogs = Blog::orderBy('id','DESC')->paginate(10);
        return view('admin.master.blog.blog', \compact('blogs', 'blog_id'));
    }

    protected function ckeditorImage(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;

            $request->file('upload')->move(public_path('files/blog/ckImage'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('files/blog/ckImage/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    protected function create(Request $request){

        $this->validate($request,[
            'name' => 'required',
            'data' => 'required',
            'pic' => 'required',

        ],[
            'name.required' => 'Blog name is required',
            'data.required' => 'Description is required',
            'pic.required' => 'Picture required',
        ]);

        $blog = $request->data;
        $document = $request->pic;
        if (isset($document) && !empty($document)) {
            $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
            // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
            $document->move(public_path('/files/blog/image/'), $new_name);
            $file = 'files/blog/image/' . $new_name;
        }

        Blog::create([
            'name' => $request->name,
            'blog_image' => $file,
            'blog' => \ConsoleTVs\Profanity\Builder::blocker($blog, BadWords::badWordsReplace)->strictClean(false)->filter(),
            'category' => $request->blog_category
        ]);

        return response()->json(['status'=>1,'message' => 'Blog created successfully']);
    }

    protected function active(Request $request) {
        $blog = Blog::find($request->catId);
        $blog->is_activate = $request->active;
        $blog->save();

        if($request->active == 0){
            return response()->json(['status'=>1,'message' => 'Blog visibility changed from show to hide']);
        }else{
            return response()->json(['status'=>1,'message' => 'Blog visibility changed from hide to show']);
        }
    }

    protected function viewBlog(Request $request)
    {
        # code...
        $blog_id = Crypt::decrypt($request->id);
        $blog = Blog::find($blog_id);

        return view('admin.master.blog.read', \compact('blog'));
    }

    protected function editBlog(Request $request){
        $main_id = Crypt::decrypt($request->id);

        $blog = Blog::find($main_id);
        
        return view('admin.master.blog.edit',\compact('blog'));

    }

    protected function edit(Request $request){

        $blog_id = Crypt::decrypt($request->id);
        $document = $request->pic;
        $blog = Blog::where('id', $blog_id)->first();

        if ($document->getClientOriginalName() == 'blob') {
            $blog->name = $request->name;
            $blog->blog = \ConsoleTVs\Profanity\Builder::blocker($request->data , BadWords::badWordsReplace)->strictClean(false)->filter();
            $blog->category = $request->blog_category;
            $blog->save();
        } else {
            if (isset($document) && !empty($document)) {
                $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
                $document->move(public_path('/files/blog/image/'), $new_name);
                $file = 'files/blog/image/' . $new_name;
            }
            $blog->name = $request->name;
            $blog->blog_image = $file;
            $blog->blog = \ConsoleTVs\Profanity\Builder::blocker($request->data , BadWords::badWordsReplace)->strictClean(false)->filter();
            $blog->category = $request->blog_category;
            $blog->save();
        }

        return response()->json(['status'=>1, 'message' => 'Blog edited successfully']);
    }
}
