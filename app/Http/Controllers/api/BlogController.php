<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        try {
            $page = $_GET['page'];

            $blogs = Blog::orderBy('id', 'DESC')->paginate(5);
           
            if ($blogs->count() > 0) {
                $all_blogs = [];
                foreach ($blogs as $key => $blog) {
                    $data = [
                        'id' => $blog->id,
                        'name' => $blog->name,
                        'image' => $blog->blog_image,
                        'blog' => $blog->blog,
                        'date' => dateFormat($blog->created_at, 'F d, Y'),
                        'category' => $blog->category

                    ];
                    $all_blogs[] = $data;
                }
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "All Topics",
                    "result" => $all_blogs,
                ];
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No Recored Found",
                    "result" => $blogs,

                ];
            }
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
}
