<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddonsController extends Controller
{
    public function getCreateAddonPage(){
        return view('admin.addon.get-create-addon-page');
    }

    public function createAddon(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'price' => 'required',
            'addonFile' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'Oops '.$validator->errors()->first(), 'data' => null, 'status' => 0]);
        }else{
            try{
                return response()->json(['mesage' => 'Great! Addon Created Succesfully.', 'data' => $request->all(), 'status' => 1]);
            }catch(\Exception $e){
                return response()->json(['mesage' => 'Oops! Something Went Wrong.', 'data' => null, 'status' => 0]);
            }
        }
    }
}
