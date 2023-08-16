<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddonApiController extends Controller
{
    public function getClassRelatedAddons(Request $request){

        $validator = Validator::make($request->all(), [
            'class_id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()], 400);
        }else{
            try{
                $get_addons =  Addon::where('class_id', $request->class_id)->where('status', 1)->get();
                $data = [
                    "code" => 200,
                    "message" => "Addons fetched successfully.",
                    "addons" => $get_addons
                ];
                return response()->json(['status' => 1, 'result' => $data]);
            }catch(\Exception $e){
                $data = [
                    "code" => 500,
                    "message" => "Something went wrong.",
                ];
    
                return response()->json(['status' => 0, 'result' => $data]);
            }
        }
    }
}
