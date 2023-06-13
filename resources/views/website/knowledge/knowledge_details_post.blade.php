@extends('layout.website.website')

@section('title', 'Knowledge Details Post')

@section('head')
<style>
    #header{
        display: none;
    }

    .sidebar {
        position: sticky;
        top: 150px;
    }
</style>
@endsection

@section('content')
@include('layout.website.include.forum_header')
<section class="knowledge-forum">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="knowledge-forum-left-new">
                    <ul class="list-inline">
                        <li>
                            <div class="answer-profile">
                                <div class="answer-profile-pic">
                                    <img src="{{asset('/files/profile/'.$knowledge_post->userDetail->image)}}" onerror="this.onerror=null;this.src='{{asset('asset_website/img/noimage.png')}}';" height="45px" width="45px" class="rounded-circle">
                                </div>
                                <div class="answer-profile-desc">
                                    <h4 class="small-heading-black mb0">{{$knowledge_post->user->firstname}} {{$knowledge_post->user->lastname}}</h4>
                                    <p class="mb0">{{$knowledge_post->userDetail->education}}</p>
                                </div>
                                <span class="answer-span">posted : {{$knowledge_post->created_at->diffForHumans()}}&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <span class="dropdown" style="float:right;">
                                        <a type="button" class="btn btn-default"  data-toggle="dropdown" style="padding: 0px 10px;"><i class="fa fa-ellipsis-v" aria-hidden="true" style="font-size:22px;" ></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @auth
                                                <a href="javascript:void(0);" data-id="{{$knowledge_post->id}}" data-toggle="modal" data-target="#ReportPostModal" class="dropdown-item reportModalLink" style="float:right;font-size:12px;" ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                                  &nbsp;  Report</a>
                                            @endauth
                                            <div class="dropdown-item social-link-btn">
                                                <span style="font-size: 15px;"><i class="fa fa-share" aria-hidden="true" ></i> &nbsp; Share</span><br>
                                                <a href="#" id="facebookLink" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true" style="color:#1877f2;font-size: 20px;margin-top:10px;"></i></a>&nbsp;&nbsp;&nbsp;
                                                <a href="#" id="whatsappLink" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="color:#128c7e;font-size: 20px;margin-top:10px;"></i></a>&nbsp;&nbsp;&nbsp;
                                                <a href="#" id="mailLink" target="_blank"><i class="fa fa-envelope" aria-hidden="true" style="color:#dd4b39;font-size: 20px;margin-top:10px;"></i></a>&nbsp;&nbsp;&nbsp;
                                                <a href="#" id="redditLink" target="_blank"><i class="fa fa-reddit-square" aria-hidden="true" style="color:#ff4500;font-size: 20px;margin-top:10px;"></i></a>
                                                
                                            </div>
                                            {{-- <a href="javascript:void(0);" class="dropdown-item"  data-toggle="modal" data-target="#sharePostModal" style="font-size:12px;"><i class="fa fa-share" aria-hidden="true"></i> &nbsp; Share</a> --}}
                                        </div>
                                    </span> 
                                </span>
                                
                            </div>
                            <div class="answer-describtion">
                                <h4 class="small-heading-black">Q: {{$knowledge_post->question}}</h4>
                               
                                <p class="text-justify">{!! $knowledge_post->description !!}</p>
                                <a href="{{$knowledge_post->links}}" class="post-link">{{$knowledge_post->links}}</a>
                                <div class="answer-btn-box">
                                    <ul class="list-inline answer-btn-list">
                                        <span>Comment {{$knowledge_post->total_comments}}</span>&nbsp;
                                        <span>Views {{$total_knowledge_post_views}}</span>&nbsp;
                                        @guest
                                            <span><a data-toggle="modal" data-target="#login-modal" style="cursor: pointer;">Add Comment</a></span>
                                        @endguest
                                    </ul>
                                </div>
                                @auth
                                    <div class="mt20 mb-4">
                                        @if($user_details != null)
                                            <div class="answer-profile-pic"><img src="{{asset('/files/profile/'.$user_details->image)}}" onerror="this.onerror=null;this.src='{{asset('asset_website/img/noimage.png')}}';" height="30px" class="rounded-circle"></div>
                                        @else
                                            <div class="answer-profile-pic"><img src="{{asset('asset_website/img/knowladge-forum/image2.png')}}" class="w100"></div>
                                        @endif
                                        <h6 class="knowledge-text ">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</h6>
                                        <form action="{{route('website.knowledge.comment')}}" method="POST" >
                                            @csrf
                                            <input type="hidden" name="commented_by" value={{Auth::user()->id}}>
                                            <input type="hidden" name="post_id" value="{{$knowledge_post->id}}">
                                            <textarea name="comment" class="form-control" id="comment" placeholder="Add answer here" required></textarea>
                                            <button class="btn knowledge-link-post-btn mt-1" style="float:right">Post</button>
                                        </form>
                                    </div>
                                @endauth
                                <div>
                                    <h4 class="small-heading-black mt20 mb0">Answers</h4>
                                    @if(!$knowledge_comment->isEmpty())
                                    <ul class="list-inline comment-list">
                                        @foreach($knowledge_comment as $comment)
                                        <li>
                                            <div class="answer-profile1">
                                                <div class="answer-profile-pic1">
                                                    <img src="{{asset('/files/profile/'.$comment->userDetailComment->image)}}" onerror="this.onerror=null;this.src='{{asset('asset_website/img/noimage.png')}}';" height="30px" width="30px" class="rounded-circle">
                                                </div>
                                                <div class="answer-profile-desc1">
                                                    <h4 class="small-heading-black1 mb0">{{$comment->user->firstname}} {{$comment->user->lastname}}</h4>
                                                    <p class="small-comment">{{$comment->userDetailComment->education}}</p>
                                                    <p class="text-justify">{{$comment->comments}}</p>
                                                </div>
                                                <span class="answer-span1">{{$comment->created_at->diffForHumans()}}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    {{$knowledge_comment->links()}}
                                    @else 
                                        <div class="text-center">
                                            <p>No answers to show</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @auth
              <div class="col-lg-4">
                <div class="knowledge-forum-right1 sidebar">
                    <div class="knowledge-forum-profile-top"><img src="{{asset('asset_website/img/knowladge-forum/bg.png')}}" class="w100"></div>
                    <div class="knowledge-forum-profile-bottom">
                        @if(!empty($user_details))
                            <div class="knowledge-pic"><img src="{{asset('/files/profile/'.$user_details->image)}}" onerror="this.onerror=null;this.src='{{asset('asset_website/img/noimage.png')}}';" class="rounded-circle w100" style="border:4px solid white;"></div>
                        @else
                            <div class="knowledge-pic"><img src="{{asset('asset_website/img/knowladge-forum/image1.png')}}" class="w100"></div>
                        @endif
                        <div class="knowledge-desc">
                            <h4 class="small-heading-black text-center mt-2 mb0">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</h4>
                            @if(!empty($user_details))
                                <p class="text-center">{{$user_details->education}}</p>
                            @else
                                <p class="text-center">education</p>
                            @endif
                            <div class="question-box">
                                <ul class="list-inline question-point-list">
                                  <ul class="list-inline question-point-list">
                                    <li><a href="{{route('website.knowledge.tab')}}">Questions Asked<span class="question-no">{{$total_questions}}</span></a></li>
                                    <li><a href="{{route('website.knowledge.tab')}}">Answered<span class="question-no">{{$total_post_commented_by_one_user}}</span></a></li>
                                    {{-- <li><a href="{{route('website.knowledge.tab')}}">Post<span class="question-no">{{$total_knowledge_post}}</span></a></li> --}}
                                </ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div> 
            @endauth    
        </div>
    </div>
</section>

@include('layout.website.include.modals')
@endsection

@section('scripts')
    @include('layout.website.include.modal_scripts')

    <script>
        const facebookBtn = document.getElementById('facebookLink');
        const whatsappBtn = document.getElementById('whatsappLink');
        const mailBtn = document.getElementById('mailLink');
        const redditBtn = document.getElementById('redditLink');

        let postUrl = encodeURI(document.location.href);
        let postTitle = encodeURI('{{$knowledge_post->question}}');

        facebookBtn.setAttribute('href', `https://www.facebook.com/sharer.php?u=${postUrl}`);
        whatsappBtn.setAttribute('href', `https://api.whatsapp.com/send?text=Post-Title: ${postTitle} ---- Post-Link:- ${postUrl}`);
        mailBtn.setAttribute('href',`https://mail.google.com/mail/?view=cm&su=Post Title: ${postTitle}&body=${postUrl}`);
        redditBtn.setAttribute('href',`https://reddit.com/submit?url=${postUrl}&title=${postTitle}`);

    </script>

    @if (session('comment_posted'))
        <script>
            toastr.success("{!! session('comment_posted') !!}");
        </script>
    @endif
@endsection