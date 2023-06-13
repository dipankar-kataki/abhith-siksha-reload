<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Common\Activation;
use Illuminate\Support\Facades\Crypt;
use Brian2694\Toastr\Facades\Toastr;

class GalleryController extends Controller
{
    //
    protected function index()
    {
        $gallerries = Gallery::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.master.gallery.gallery', \compact('gallerries'));
    }

    protected function create(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'pic' => 'required',


        ], [
            'name.required' => 'Gallery name is required',
            'pic.required' => 'Picture required',

        ]);

        $document = $request->pic;
        if (isset($document) && !empty($document)) {
            $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
            // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
            $document->move(public_path('/files/gallery/'), $new_name);
            $file = 'files/gallery/' . $new_name;
        }

        Gallery::create([
            'name' => $request->name,
            'gallery' => $file
        ]);

        return response()->json(['status' => 1, 'message' => 'Gallery created successfully']);
    }

    protected function active(Request $request)
    {
        $banner = Gallery::find($request->catId);
        $banner->is_activate = $request->active;
        $banner->save();

        return response()->json(['status' => 1]);
    }

    protected function editGallery(Request $request)
    {
        $gallery_id = \Crypt::decrypt($request->id);

        $gallery = Gallery::find($gallery_id);

        return view('admin.master.gallery.edit', \compact('gallery'));
    }

    protected function edit(Request $request)
    {
        $gallery_id = \Crypt::decrypt($request->id);
        $document = $request->pic;
        $gallery = Gallery::where('id', $gallery_id)->first();

        if ($document->getClientOriginalName() == 'blob') {
            $gallery->name = $request->name;
            $gallery->save();
        } else {
            if (isset($document) && !empty($document)) {
                $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
                $document->move(public_path('/files/gallery/'), $new_name);
                $file = 'files/gallery/' . $new_name;
            }
            $gallery->name = $request->name;
            $gallery->gallery = $file;
            $gallery->save();
        }

        return response()->json(['status' => 1, 'message' => 'Gallery details updated successfully']);
    }
    public function deleteGallery($id)
    {

        try {
            $gallery = Gallery::find(Crypt::decrypt($id));
            $gallery->delete();
            Toastr::success('Gallery Image deleted successfully', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Something went wrong.', '', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }
}
