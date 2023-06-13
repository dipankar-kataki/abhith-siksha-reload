<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserDetails;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getUserAddress(){
        try {
            $user_details=UserDetails::where('user_id',auth()->user()->id)->first();
        
            if ($user_details) {
                $address=$user_details->address;
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "User address",
                    "address" => $address,
    
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            } else {
                $data = [
                    "code" => 200,
                    "status" => 1,
                    "message" => "No record found",
                    "address"=>null
    
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }
        } catch (\Throwable $th) {
            $data = [
                "code" => 400,
                "status" => 0,
                "message" => "Something went wrong",
                "address"=>null

            ];
            return response()->json(['status' => 0, 'result' => $data]);
        }
       
    }
}
