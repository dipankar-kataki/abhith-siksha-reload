<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddonsController extends Controller
{
    public function getCreateAddonPage(){
        return view('admin.addon.get-create-addon-page');
    }

    public function createAddon(Request $request){

    }
}
