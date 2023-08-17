<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AssignClass;
use App\Models\SelectedAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddonApiController extends Controller
{
    public function getClassRelatedAddons(Request $request){

        // $validator = Validator::make($request->all(), [
        //     'class_id' => 'required'
        // ]);

        if( ($_GET['class'] == null) || ($_GET['board_id']== null) ){
            return response()->json(['error' => 'Required Parameters missing.'], 400);
        }else{
            try{
                $check_class_exists = AssignClass::where('class', $request->class)->where('board_id', $request->board_id)->first();
                if($check_class_exists == null){
                    return response()->json(['error' => 'Addons not found.'], 400);
                }else{
                    $get_addons =  Addon::where('class_id', $check_class_exists->id)->where('status', 1)->get();
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

    public function getSelectedAddon(){
        try{

            $get_selected_addon = SelectedAddon::where('user_id', auth()->user()->id)->where('payment_status', 'paid')->get();
            $data = [
                "code" => 200,
                "message" => "Addons fetched successfully.",
                "selected_addons" => $get_selected_addon
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
