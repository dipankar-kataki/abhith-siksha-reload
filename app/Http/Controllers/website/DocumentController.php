<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function privacyPolicy(){
        return view('layout.website.include.privacy_policy');
    }

    public function termsAndCondition(){
        return view('layout.website.include.terms_and_condition');
    }

    public function cancellationAndRefund(){
        return view('layout.website.include.cancellation_and_refund');
    }

    
}
