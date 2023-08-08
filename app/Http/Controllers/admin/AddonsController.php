<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddonsController extends Controller
{
    public function getCreateAddonPage(){
        $get_subjects = AssignSubject::with('assignClass', 'boards')->where('is_activate', 1)->get();
        return view('admin.addon.get-create-addon-page')->with(['subjects' => $get_subjects]);
    }

    public function createAddon(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'price' => 'required',
            'addonFile' => 'required|mimes:jpeg,png,jpg,pdf|max:3048',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'Oops '.$validator->errors()->first(), 'data' => null, 'status' => 0]);
        }else{
            try{

                if($request->hasFile('addonFile')){
                    $file = time() . '.' . $request->addonFile->extension();
                    $fileName = null;
                    if($request->addonFile->extension() == 'pdf'){
                        $request->addonFile->move(public_path('files/addons/pdf/'), $file);
                        $fileName = 'files/addons/pdf/'. $file;
                    }else{
                        $request->addonFile->move(public_path('files/addons/image/'), $file);
                        $fileName = 'files/addons/image/'. $file;
                    }
                    
                }

                Addon::create([
                    'subject_id' => $request->subject_id,
                    'board_id' => $request->boardId == 'undefined' ? null : $request->boardId,
                    'class_id' => $request->classId == 'undefined' ? null : $request->classId,
                    'name' => $request->name,
                    'type' => $request->type,
                    'price' => $request->price,
                    'file_path' => $fileName
                ]);

                return response()->json(['message' => 'Great! Addon Created Succesfully.', 'data' => null, 'status' => 1]);
            }catch(\Exception $e){
                return response()->json(['message' => 'Oops! Something Went Wrong.', 'data' => null, 'status' => 0]);
            }
        }
    }

    public function addonList(){
        $addonList = Addon::with('assignSubject', 'assignClass', 'boards')->orderBy('created_at', 'desc')->get();
        return view('admin.addon.list')->with(['addonList' => $addonList]);
    }

    public function changeStatus(Request $request){
        try{
            Addon::where('id', $request->id)->update([
                'status' => $request->status
            ]);
            return response()->json(['message' => 'Great! Status Change Successfully.', 'data' => null, 'status' => 1]);
        }catch(\Exception $e){
            return response()->json(['message' => 'Oops! Something Went Wrong.', 'data' => null, 'status' => 0]);
        }
    }
}
