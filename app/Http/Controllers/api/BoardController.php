<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(){
        try {
           $board=Board::where('is_activate',1)->get();
           $result = ["banner" => $board];
            if (!$board->isEmpty()) {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "all board",
                    "result" => $result,

                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 0,
                    "message" => "No record found",

                ];
                return response()->json(['status' => 0, 'result' => $data]);
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
