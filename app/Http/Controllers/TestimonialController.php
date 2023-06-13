<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    //
    public function index()
    {
        $data['data'] = Testimonial::latest()->get();
        return view('admin.testimonial.index')->with($data);
    }

    public function add()
    {
        return view('admin.testimonial.add');
    }

    public function submit(Request $request)
    {
        // dd($request->input());
        $request->validate([
            'name' => 'required|max:100',
            'qualification' => 'required|max:100',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:1024',
            'message' => 'required|max:300',
        ]);

        try {
            $file = '';
            $document = $request->image;
            if (isset($document) && !empty($document)) {
                $new_name = time() . '.' . $document->extension();
                $document->move(public_path('/files/testimonial/'), $new_name);
                $file = 'files/testimonial/' . $new_name;
            }

            $create = Testimonial::create([
                'name' => $request->name,
                'qualification' => $request->qualification,
                'image' => $file,
                'message' => $request->message
            ]);

            return redirect()->route('admin.testimonial.index')->with(['alert-type' => 'success', 'message' => 'Testimonial added successfully']);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'id' => 'required',
                ],
                [
                    'id.required' => 'ID mismatch',
                ]
            );

            if ($validate->fails()) {
                return response()->json(['status' => 0, 'message' => $validate->errors()->first()]);
            }

            $dec_id = Crypt::decrypt($request->id);
            $testimonial = Testimonial::find($dec_id);
            if($testimonial->image != ''){
                File::delete($testimonial->image);
            }
            $testimonial->delete();
            return response()->json(['status' => 1, 'message' => 'Testimonial deleted successfully']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 0, 'message' => $th->getMessage()]);
        }
    }
}
