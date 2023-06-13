<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class GalleryController extends Controller
{
    public function index()
    {
        try {
            $gallerries = Gallery::select('name','gallery','is_activate','created_at')->where('is_activate',1)->orderBy('created_at', 'DESC')->get();
            if ($gallerries->count()>0) {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all gallery data",
                    "result" => $gallerries,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No pictures found",
                    "result" => $gallerries,
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
    public function testapi(Request $request){
        try {
            
            $validator = Validator::make($request->all(), [
                'title' => 'string|required',
                'description' => 'string|required',
            ]);
            if ($validator->fails()) {
                $data = [
                    "code" => 400,
                    "message" => $validator->errors(),

                ];
                return response()->json(['status' => 0, 'result' => $data]);
            }
            $uploaded_data=[
                'title'=>$request['title'],
                'description'=>$request['description'],
            ];
            $data = [
                "code" => 200,
                "message" => "Note uploaded successfully",
                "data"=>$uploaded_data,
            
            ];
            return response()->json(['status' => 1, 'result' => $data]);
        } catch (\Throwable $th) {
            $data = [
                "code" => 200,
                "status" => 0,
                "message" => "something went wrong",

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
    }
}
