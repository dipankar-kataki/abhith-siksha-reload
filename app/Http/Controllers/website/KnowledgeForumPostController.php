<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeForumPost;
use App\Models\UserDetails;
use App\Common\BadWords;
use Illuminate\Support\Facades\Auth;
use App\Common\Activation;

class KnowledgeForumPostController extends Controller
{
    public function addKnowledgeQuestion(Request $request){
       
        $question = $request->question;
        $description = $request->description;
        $link  = $request->link;

        $request->validate([
            'question' => 'required',
            'description' => 'required',
        ]);

        $user_detail = UserDetails::where('user_id', Auth::user()->id)->first();
        
        $create = KnowledgeForumPost::create([
            'question' =>  \ConsoleTVs\Profanity\Builder::blocker($question, BadWords::badWordsReplace)->strictClean(false)->filter(),
            'description' =>  \ConsoleTVs\Profanity\Builder::blocker($description, BadWords::badWordsReplace)->strictClean(false)->filter(),
            'links' => $link,
            'user_id' => Auth::user()->id,
            'user_detail_id' => $user_detail->id,
            'is_activate' => Activation::Activate,
        ]);

        if($create){
            return response()->json(['message' => 'Question added successfully']);
        }else{
            return response()->json(['message' => 'Oops! Something went wrong']);
        }
    }
}
