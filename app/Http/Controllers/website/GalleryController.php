<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Common\Activation;

class GalleryController extends Controller
{
    //
    protected function index() {
        $gallery = Gallery::where('is_activate',Activation::Activate)->orderBy('id', 'DESC')->paginate(9);
        return view('website.gallery.gallery',compact('gallery'));
    }
}
