<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\ReportBlog;

class ReportBlogController extends Controller
{
    public function reportBlog(Request $request){
        $blogId = $request->blogId;
        $reason_of_report = $request->reason_of_report;


        $getBlog = Blog::where('id', $blogId)->first();
        $insertReportedBlog = ReportBlog::where('blogs_id',$blogId)->first();

        $reasons = [];
        $new_array_reason = [];
        array_push($reasons,$reason_of_report);

        if($insertReportedBlog != null){
            $new_array_reason =  array_merge($reasons, $insertReportedBlog->report_reason);
        }

        if($insertReportedBlog == null){
            ReportBlog::create([
                'blogs_id' => $blogId,
                'report_count' => 1,
                'report_reason' => $reasons ,
                'is_activate' => $getBlog->is_activate,
            ]);
        }
        else{
            ReportBlog::where('blogs_id' ,$blogId)->update([
                'report_count' => $insertReportedBlog->report_count + 1,
                'report_reason' => $new_array_reason
            ]);
        }
        return response()->json(['success' => 'Blog reported successfully.']);
    }


    public function getReportedBlog(Request $request){
        $reportedBlogs = ReportBlog::orderBy('created_at','DESC')->simplePaginate(10);
        return view('admin.report.reported-blog')->with(['reportedBlogs' => $reportedBlogs]);
    }

    public function removeReportedBlog(Request $request){
        ReportBlog::where('blogs_id' ,$request->blog_id)->update([ 'is_activate' => $request->active, ]);
        Blog::where('id' ,$request->blog_id)->update([ 'is_activate' => $request->active, ]);

        if($request->active == 0){
            return response()->json(['success' => 'Blog visibility updated from show to hide']);
        }else{
            return response()->json(['success' => 'Blog visibility updated from hide to show']);
        }
    }
}
