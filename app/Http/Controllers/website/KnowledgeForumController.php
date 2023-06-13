<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KnowledgeForumPost;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\KnowledgeForumComment;

class KnowledgeForumController extends Controller
{
    public function index(Request $request){
        $top_knowledge_post =''; $total_post = ''; $total_post_commented_by_one_user = ''; $user_details = '';
        $knowledge_post = KnowledgeForumPost::with('user')->where('is_activate', 1)->orderBy('created_at', 'desc')->paginate(3);

        if($request->ajax()){
            $post = view('website.knowledge.knowledge_post', compact('knowledge_post'))->render();
            return response()->json(['knowledge_forum_post' =>  $post]);
        }    
        $top_knowledge_post = KnowledgeForumPost::with('user')->where('is_activate', 1)->orderBy('created_at', 'desc')->limit(3)->get();
        if(Auth::check()){
            $total_post = KnowledgeForumPost::where('user_id',Auth::user()->id)->count();
            $total_post_commented_by_one_user = KnowledgeForumComment::where('user_id' , Auth::user()->id)->count();
            $user_details = UserDetails::where('email', Auth::user()->email)->first();
        }
        
        
        return view('website.knowledge.knowledge_forum')->with(['knowledge_post' => $knowledge_post, 'top_knowledge_post' =>  $top_knowledge_post, 'total_questions' => $total_post, 'total_knowledge_post_commented_by_one_user' => $total_post_commented_by_one_user, 'user_details' => $user_details]);
    }

    public function knowledgeDetailPost(Request $request, $id){
        $post_id =  \Crypt::decryptString($id);

        $knowledge_post = KnowledgeForumPost::where('id',$post_id)->with('user','userDetail')->first();
        $increment_views = ($knowledge_post->total_views + 1);
        KnowledgeForumPost::where('id',$post_id)->update(['total_views' => $increment_views ]);
        $total_knowledge_post_views =  KnowledgeForumPost::where('id',$post_id)->first();


        $knowledge_comment = KnowledgeForumComment::with('user','userDetailComment')->where('knowledge_forum_post_id',$post_id)->orderBy('created_at', 'desc')->simplePaginate(3);
        $total_post_commented_by_one_user = '';
        $total_knowledge_post = '';
        $total_questions = '';
        $user_details = '';

        if(Auth::check()){
            $total_questions = KnowledgeForumPost::where('user_id',Auth::user()->id)->count();
            $total_post_commented_by_one_user = KnowledgeForumComment::where('user_id' , Auth::user()->id)->count();
            $total_knowledge_post = KnowledgeForumPost::where('user_id' , Auth::user()->id)->sum('user_id');
            $user_details = UserDetails::with('user')->where('email', Auth::user()->email)->first();
        }
        return view('website.knowledge.knowledge_details_post')->with(['knowledge_post' => $knowledge_post, 'total_questions' => $total_questions, 'knowledge_comment' => $knowledge_comment, 'total_knowledge_post_views' => $total_knowledge_post_views->total_views, 'total_post_commented_by_one_user' => $total_post_commented_by_one_user, 'total_knowledge_post' => $total_knowledge_post, 'user_details' => $user_details]);
    }


    public function knowledgeTab(Request $request){
        $questions_asked_by_user = KnowledgeForumPost::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->simplePaginate(3);
        $answered_by_user = KnowledgeForumComment::with('knowledgeForumPost')->where('user_id' , Auth::user()->id)->orderBy('created_at', 'desc')->simplePaginate(3);

        if($request->ajax()){
            $view = view('website.knowledge.post_asked_by_you',compact('questions_asked_by_user'))->render();
            $answerView = view('website.knowledge.post_answer_by_you',compact('answered_by_user'))->render();
            return response()->json(['posts' => $view, 'answerView' => $answerView]);
        }
        $user_details = UserDetails::where('email', Auth::user()->email)->first();
        return view('website.knowledge.knowledge_tab')->with(['questions_asked_by_user' => $questions_asked_by_user, 'user_details' => $user_details, 'answered_by_user' => $answered_by_user]);
    }
}
