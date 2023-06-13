<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        try {
            $banner = Banner::select('id','name', 'banner_image', 'description','is_activate','created_at')->where('is_activate', 1)->orderBy('id', 'DESC')->limit(5)->get();
            $result = ["banner" => $banner];
            if (!$banner->isEmpty()) {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all banners",
                    "result" => $result,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "result" => $result,
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
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
