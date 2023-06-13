@extends('layout.website.website')

@section('title', 'Knowledge tab')

@section('head')
    <style>
        #header {
            display: none;
        }

    </style>
@endsection

@section('content')
    @include('layout.website.include.forum_header')

    <section class="knowledge-forum">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="knowledge-forum-right1">
                        <div class="knowledge-forum-profile-top"><img
                                src="{{ asset('asset_website/img/knowladge-forum/bg.png') }}" class="w100"></div>
                        <div class="knowledge-forum-profile-bottom">
                            @if (!empty($user_details))
                                <div class="knowledge-pic"><img src="{{ asset('/files/profile/' . $user_details->image) }}"
                                        onerror="this.onerror=null;this.src='{{ asset('asset_website/img/noimage.png') }}';"
                                        class="rounded-circle w100" style="border:4px solid white;"></div>
                            @else
                                <div class="knowledge-pic"><img
                                        src="{{ asset('asset_website/img/knowladge-forum/image1.png') }}"
                                        class="w100"></div>
                            @endif
                            <div class="knowledge-desc">
                                <h4 class="small-heading-black text-center mt-3 mb0">{{ Auth::user()->firstname }}
                                    {{ Auth::user()->lastname }}</h4>
                                @if (!empty($user_details))
                                    <p class="text-center">{{ $user_details->education }}</p>
                                @else
                                    <p class="text-center">education</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="knowledge-forum-right2 mt-2">
                        <a data-toggle="modal" data-target="#add-question-modal" class="small-heading-grey">What is your
                            question?</a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <ul class="nav nav-tabs knowledge-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#question" role="tab"
                                aria-controls="question">Asked By You</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#answer" role="tab"
                                aria-controls="answer">Answered By You</a>
                        </li>
                        {{-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#post" role="tab" aria-controls="post">Post 15</a>
                    </li> --}}
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="question" role="tabpanel">
                            <div class="knowledge-forum-left-new">
                                <ul class="list-inline answer-list2 ">
                                    <span id="postAskedByYou">
                                        @include('website.knowledge.post_asked_by_you')
                                    </span>
                                </ul>
                            </div>
                            <div class="mt-2">
                                <div class="ajax-loading" style="display:none;">
                                    <p>
                                        <img src="{{asset('asset_website/img/ajax-loader.gif')}}" alt="loading-gif">
                                        Fetching Posts
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="answer" role="tabpanel">
                            <div class="knowledge-forum-left-new">
                                <ul class="list-inline answer-list2">
                                    <span id="postAnsweredByYou">
                                        @include('website.knowledge.post_answer_by_you')
                                    </span>
                                </ul>
                            </div>
                            <div class="mt-2">
                                <div class="ajax-loading" style="display:none;">
                                    <p>
                                        <img src="{{asset('asset_website/img/ajax-loader.gif')}}" alt="loading-gif">
                                        Fetching Posts
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="post" role="tabpanel">
                            <div class="knowledge-forum-left-new">
                                <ul class="list-inline answer-list2">
                                    <li>
                                        <div class="answer-describtion">
                                            <p class="small-text-heading">26 min ago,</p>
                                            <h4 class="small-heading-black">What are the weirdest examples of natural
                                                selection in nature?</h4>
                                            <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipiscing
                                                elit, sed do eiusmod tempor incididunt ut labore
                                                et dolore magna aliqua...</p>
                                            <div class="answer-btn-box">
                                                <ul class="list-inline answer-btn-list">
                                                    <li><a href="knowledge-post-details.html">14 Comment</a></li>
                                                    <li><a href="knowledge-post-details.html">14 Views</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layout.website.include.modals')

@endsection

@section('scripts')
    @include('layout.website.include.modal_scripts')
    <script>
        function loadAskedByYou(page){
            $.ajax({
                url:'?page=' + page,
                type:'get',
                beforeSend: function(){
                    $('.ajax-loading').show();
                }
            })
            .done(function(data){
                if(data.posts== ''){
                    $('.ajax-loading').text('No post to show');
                    return;
                }else{
                    $('.ajax-loading').hide();
                    $('#postAskedByYou').append(data.posts);
                }
               
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                toastr.error('Oops!, Something went wrong');
            })
        }

        let page = 1;
        $(window).scroll(function(){
            if($(window).scrollTop() + $(window).height() >= $(document).height()){
                page ++;
                loadAskedByYou(page);
            }
        });


        function loadAnsweredByYou(answerPage){
            $.ajax({
                url:'?page=' + answerPage,
                type:'get',
                beforeSend: function(){
                    $('.ajax-loading').show();
                }
            })
            .done(function(data){
                if(data.answerView== ''){
                    $('.ajax-loading').text('No post to show');
                    return;
                }else{
                    $('.ajax-loading').hide();
                    $('#postAnsweredByYou').append(data.answerView);
                }
               
            })
            .fail(function(jqXHR,ajaxOptions,thrownError){
                toastr.error('Oops!, Something went wrong');
            })
        }

        let answerPage = 1;
        $(window).scroll(function(){
            if($(window).scrollTop() + $(window).height() >= $(document).height()){
                answerPage ++;
                loadAnsweredByYou(answerPage);
            }
        });
    </script>
@endsection
