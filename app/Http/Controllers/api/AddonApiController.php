<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddonApiController extends Controller
{
    public function getClassRelatedAddons(Request $request){

        // $validator = Validator::make($request->all(), [
        //     'class_id' => 'required'
        // ]);

        if($_GET['class_id'] == null ){
            return response()->json(['error' => 'Class Id is required.'], 400);
        }else{
            try{
                $check_addon_class_exists = Addon::where('class_id', $request->class_id)->exists();
                if(!$check_addon_class_exists){
                    return response()->json(['error' => 'Addons not found.'], 400);
                }else{
                    $get_addons =  Addon::where('class_id', $request->class_id)->where('status', 1)->get();
                    $data = [
                        "code" => 200,
                        "message" => "Addons fetched successfully.",
                        "addons" => $get_addons
                    ];
                    return response()->json(['status' => 1, 'result' => $data]);
                }
                
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
