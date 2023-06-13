<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function saveContactDetails(Request $request){
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $message = $request->message;

        
        $create = Enquiry::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'message' => $message,
            'date_of_enquiry' => date('Y-m-d'),
            'marked_as_contacted' => 0,
            'type'=>2,
        ]);
        if($create){
            return response()->json(['status' => 1, 'message' => 'Thank you for contacting us. Our customer support will contact you shortly.']);
        }else{
            return response()->json(['status' => 2 ,'message' => 'Something went wrong while enquiring']);
        }
    }
    public function getContactDetails(){
        $details = Enquiry::orderBy('created_at','desc')->where('type',2)->get();
        return view('admin.contact.index')->with('details',$details);
    }
}
