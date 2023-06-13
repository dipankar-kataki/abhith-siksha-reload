<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeForumComment;
use App\Models\KnowledgeForumPost;
use App\Models\User;
use App\Models\UserDetails;
use App\Common\BadWords;
use Illuminate\Support\Facades\Auth;

class KnowledgeForumCommentsController extends Controller
{
    public function knowledgeComment(Request $request){

        $comment = $request->comment;

        $request->validate([
            'comment' => 'required'
        ]);
        $is_activate = User::where('id',$request->commented_by)->first();

        $userDetails = UserDetails::where('user_id', Auth::user()->id)->first();

        $create = KnowledgeForumComment::create([
            'knowledge_forum_post_id' => $request->post_id,
            'comments' =>  \ConsoleTVs\Profanity\Builder::blocker($comment, BadWords::badWordsReplace)->strictClean(false)->filter(),
            'user_id' => $request->commented_by,
            'user_detail_id' => $userDetails->id,
            'is_activate' => $is_activate->is_activate,
        ]);

        $total_comments = KnowledgeForumComment::where('knowledge_forum_post_id', $request->post_id)->count();
        KnowledgeForumPost::where('id', $request->post_id,)->update(['total_comments' => $total_comments]);

        if($create){
            $request->session()->flash('comment_posted','Comment added successfully');
            return back()->with(['comment_posted' => 'Comment added successfully']);
        }else{
            return back()->with(['comment_error' => 'Oops! something went wrong. Not able to Comment']);
        }
    }
}
