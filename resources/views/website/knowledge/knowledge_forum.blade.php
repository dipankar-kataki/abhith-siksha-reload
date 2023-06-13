@extends('layout.website.website')

@section('title', 'Knowledge Forum')

@section('head')
    <style>
        #header {
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
            <div class="text-center">
                <h2 class="heading-black mb-4">Knowledge Forum</h2>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="knowledge-forum-left">
                        <ul class="list-inline answer-list">
                            @auth
                                <li>
                                    <a data-toggle="modal" data-target="#add-question-modal">
                                        <span class="icon-user-08 admin-icon"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></span>
                                        <p class="small-text-heading mb0">{{ Auth::user()->firstname }}
                                            {{ Auth::user()->lastname }}</p>
                                        <h4 class="small-heading-grey mb0">What is your question?</h4>
                                    </a>
                                </li>
                            @endauth
                            @guest
                                <li>
                                    <a data-toggle="modal" data-target="#login-modal">
                                        <span class="icon-user-08 admin-icon"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span></span>
                                        {{-- <p class="small-text-heading mb0">Himadri Shekhar Das</p> --}}
                                        <h4 class="small-heading-grey mb0">What is your question?</h4>
                                    </a>
                                </li>
                            @endguest
                            <span id="allKnowledgePost">
                                @include('website.knowledge.knowledge_post')
                            </span>
                        </ul>
                        {{-- {{ $knowledge_post->links() }} --}}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">


                        @auth
                            <div class="knowledge-forum-right1 mb-2">
                                <div class="knowledge-forum-profile-top"><img
                                        src="{{ asset('asset_website/img/knowladge-forum/bg.png') }}" class="w100">
                                </div>
                                <div class="knowledge-forum-profile-bottom">
                                    @if (!empty($user_details))
                                        <div class="knowledge-pic"><img
                                                src="{{ asset('/files/profile/' . $user_details->image) }}"
                                                onerror="this.onerror=null;this.src='{{ asset('asset_website/img/noimage.png') }}';"
                                                class="rounded-circle w100" style="border:4px solid white;"></div>
                                    @else
                                        <div class="knowledge-pic"><img
                                                src="{{ asset('asset_website/img/knowladge-forum/image1.png') }}"
                                                class="w100"></div>
                                    @endif
                                    <div class="knowledge-desc">
                                        <h4 class="small-heading-black text-center mt-2 mb0">{{ Auth::user()->firstname }}
                                            {{ Auth::user()->lastname }}</h4>
                                        @if (!empty($user_details))
                                            <p class="text-center">{{ $user_details->education }}</p>
                                        @else
                                            <p class="text-center">education</p>
                                        @endif
                                        <div class="question-box">
                                            <ul class="list-inline question-point-list">
                                                <li><a href="{{ route('website.knowledge.tab') }}">Questions Asked<span
                                                            class="question-no">{{ $total_questions }}</span></a></li>
                                                <li><a href="{{ route('website.knowledge.tab') }}">Answered<span
                                                            class="question-no">{{ $total_knowledge_post_commented_by_one_user }}</span></a>
                                                </li>
                                                {{-- <li><a href="knowledge-tab.html#post">Post<span class="question-no">10</span></a></li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endauth
                        <div class="knowledge-forum-right2">
                            <h4 class="small-heading-grey">Today’s top Topics</h4>
                            <ul class="list-inline todays-topic-list">
                                @foreach ($top_knowledge_post as $top_post)
                                    @php $encrypted_id = Crypt::encryptString($top_post->id)@endphp
                                    <li>
                                        <p class="small-text-heading">{{ $top_post->created_at->diffForHumans() }},
                                            &nbsp;Posted by: {{ $top_post->user->firstname }}
                                            {{ $top_post->user->lastname }}</p>
                                        <a href="{{ route('website.knowledge.details.post', ['id' => $encrypted_id]) }}"
                                            target="_blank" class="small-heading-black">{{ $top_post->question }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="caughtUP d-lg-none"> 
                <i class="fa fa-check-circle-o" aria-hidden="true" style="color:green;font-size:22px;"></i>
                &nbsp; You are all caught up. 
            </div> --}}

            <div class="ajax-loading" style="display:none;">
                <p>
                    <img src="{{ asset('asset_website/img/ajax-loader.gif') }}" alt="loading-gif">
                    Fetching Posts
                </p>
            </div>
        </div>
        
    </section>



    @include('layout.website.include.modals')

@endsection

@section('scripts')
    @include('layout.website.include.modal_scripts')
    <script>
        function loadMorePost(page) {
            let html = '<div style="position: absolute;left: 27%; bottom: 20%"> <i class="fa fa-check-circle-o" aria-hidden="true" style="color:green;font-size:22px;"></i>&nbsp; You are all caught up. </div>';
            $.ajax({
                    url: '?page=' + page,
                    type: 'get',
                    beforeSend: function() {
                        $('.ajax-loading').show();
                    }
                })
                .done(function(data) {
                    if (data.knowledge_forum_post == '') {
                        $('.ajax-loading').html(html);
                        return;
                    } else {
                        $('.ajax-loading').hide();
                        $('#allKnowledgePost').append(data.knowledge_forum_post);
                    }

                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    toastr.error('Oops!, Something went wrong');
                })
        }

        let page = 1;
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                loadMorePost(page);
            }
        });
    </script>
@endsection
