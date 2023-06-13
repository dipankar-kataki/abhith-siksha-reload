@extends('layout.website.website')

@section('title', 'My Account')

@section('head')
<style>
    * {
        scrollbar-width: thin;
        scrollbar-color: rgb(190, 190, 190) rgb(238, 237, 236);
    }

    /* Works on Chrome, Edge, and Safari */
    *::-webkit-scrollbar {
        width: 10px;
    }

    *::-webkit-scrollbar-track {
        background: rgb(238, 237, 236);
    }

    *::-webkit-scrollbar-thumb {
        background-color: rgb(190, 190, 190);
        border-radius: 20px;
        border: 3px solid rgb(231, 231, 230);
    }

    .modal {
        padding: 0 !important; // override inline padding-right added from js
    }

    .modal .modal-dialog {
        width: 100%;
        max-width: none;
        height: 100%;
        margin: 0;
    }

    .modal .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }

    .modal .modal-body {
        overflow-y: auto;
    }
</style>
@endsection

@section('content')

@include('layout.website.include.forum_header')

<div class="lesson-details-main-div">
    <div class="lesson-details-sidebar">
        <div class="lesson-sidebar-btn">
            <h4 class="text-center"><b>{{$lesson->assignSubject->subject_name}}</b></h4>
            @if($topicVideos->count()>0)
            <button class="lessonLinks" onclick="openFile(event, 'videos')" id="defaultOpen">{{ $topicVideos->count() }}
                Video(s)</button>
            @endif
            @if($topicArticles->count()>0)
            <button class="lessonLinks" onclick="openFile(event, 'articles')"> {{ $topicArticles->count() }}
                Article(s)</button>
            @endif
            @if( $topicDocuments->count())
            <button class="lessonLinks" onclick="openFile(event, 'documents')">{{ $topicDocuments->count() }}
                Document(s)</button>
            @endif
            @if($mcq_questions->activeSets()->count())
            <button class="lessonLinks" onclick="openFile(event, 'mcq_test')">{{ $mcq_questions->activeSets()->count() }}
                MCQ Test(s)</button>
            @endif
        </div>
    </div>
    <div class="topic-content mb-5">
        <div class="topic-content-heading d-flex">
            <div class="topic-name">
                <h2>Lesson: {{ $lesson->name }}</h2>
            </div>

            <div class="topic-next-btn d-flex">
                @if ($previous_lesson_id)
                <div class="topic-previous">
                    <a href="{{ route('getLessonDetails', [Crypt::encrypt($lesson->id), 1]) }}"
                        class="btn btn-outline-success text-white mr-2">Previous Lesson</a>
                </div>
                @endif
                @if ($next_lesson_id)
                <div class="topic-next">
                    <a href="{{ route('getLessonDetails', [Crypt::encrypt($lesson->id), 2]) }}"
                        class="btn btn-outline-danger text-white">Next Lesson</a>
                </div>
                @endif
            </div>
        </div>
        <div class="topic-content-body">
            @if($topicVideos->count()>0)
            <div class="container lessonContent" id="videos">
                <div class="topic-content-sub-heading mt-4">
                    <h3>Video(s)</h3>
                </div>
                <div class="row">
                    @if ($topicVideos->count() > 0)
                    @foreach ($topicVideos as $key => $video)
                    <div class="col-lg-4 col-md-6">
                        <div class="card video-lesson-pic">
                            <img src="{{ asset($video->lessonAttachment->video_thumbnail_image) }}" alt="">
                            <div class="video-lesson-overlay">
                                <a href="{{route('website.course.package.subject.video', ['id' => Crypt::encrypt($video->lessonAttachment->id)])}}"
                                    target="_blank" class="btn btn-default video-lesson-overlay-eye-icon"><i
                                        class="fa fa-play-circle-o" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="video-lesson-text">
                            <p>{{ $video->name }}</p>
                        </div>
                    </div>
                    @endforeach
                    @else
                    @endif

                </div>
            </div>
            @endif
            @if($topicArticles->count()>0)
            <div class="container lessonContent" id="articles">
                <div class="topic-content-sub-heading mt-4">
                    <h3>Article(s)</h3>
                </div>
                <div class="row">
                    @if ($topicArticles->count() > 0)
                    @foreach ($topicArticles as $key => $article)
                    <div class="col-lg-6 col-md-6">
                        <div class="article-div d-flex">
                            <div class="article-icon">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                            </div>
                            <div class="article-content">
                                <h5>{{ $article->name }}</h5>
                                <p>{{ dateFormat($article->created_at, 'D,F j, Y') }}</p>
                            </div>
                            <a href="#" class="btn btn-sm ml-auto openContentModal" data-title="Article"
                                data-content="{{ $article->content }}" data-name={{ $article->name }}
                                style="background-color: #f2f2f2; font-size: 20px; width: 40px; aspect-ratio: 1/1;
                                border-radius: 50%"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    @endforeach
                    @else
                    @endif
                </div>
            </div>
            @endif
            @if( $topicDocuments->count())
            <div class="container lessonContent" id="documents">
                <div class="topic-content-sub-heading mt-4">
                    <h3>Document(s)</h3>
                </div>
                <div class="row">
                    @if ($topicDocuments->count() > 0)
                    @foreach ($topicDocuments as $key => $document)
                    <div class="col-lg-6 col-md-6">
                        <div class="doc-div">
                            <div class="doc-icon">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </div>
                            <div class="doc-content">
                                <h5>{{ $document->name }}</h5>
                                <p>{{ dateFormat($document->created_at, 'D ,F j, Y') }}</p>
                            </div>
                            <button class="btn btn-sm ml-auto openContentModal" data-title="Document"
                                data-title="Document" data-url="{{ asset($document->lessonAttachment->img_url) }}"
                                data-name={{ $document->name }}
                                style="background-color: #f2f2f2; font-size: 20px; width: 40px; aspect-ratio: 1/1;
                                border-radius: 50%"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>
                    @endforeach
                    @else
                    @endif
                </div>
            </div>
            @endif
            @if($mcq_questions->Sets()->count())
            <div class="container lessonContent" id="mcq_test">
                <div class="topic-content-sub-heading mt-4">
                    <h3>MCQ Test(s)</h3>
                </div>
                <div class="row">
                    @if ($mcq_questions->Sets()->count() > 0)
                    @foreach ($mcq_questions->Sets as $key => $set)
                    <div class="col-lg-6 col-md-6">
                        <div class="mcq-div">
                            <div class="mcq-icon">
                                <img src="{{ asset('asset_website/img/mcq.png') }}" alt="">
                            </div>
                            <div class="mcq-content">
                                <h5><a href="{{ route('website.subject.mcqstart', Crypt::encrypt($set->id)) }}"
                                        target="_blank">{{ $set->set_name }} </a></h5>
                                <h6>{{ $set->question->where('is_activate',1)->count() }} Questions</h6>
                                <p>{{ dateFormat($set->created_at, 'D ,F j, Y') }}</p>
                            </div>
                            @if(isPracticeTestPlayed($set->id)==1)
                            @php $get_practice_test_id=getPracticeTestId($set->id) @endphp
                            <a href="{{route('website.subject.analysis',crypt::encrypt($get_practice_test_id))}}"
                                class="btn btn-sm ml-auto" style="background-color: #f2f2f2"><i class="fa fa-line-chart"
                                    aria-hidden="true"></i> analysis</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @else
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
{{-- content modal --}}
<div class="modal fade" id="content" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header shadow-sm border-0">
                <h3 id="contentTitle"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="articleContent" class="d-none"></p>
                <iframe class="d-none" src="" id="documentIframe" frameborder="0"
                    style="width: 100%; height: 100%"></iframe>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    function openFile(evt, fileName) {
            let i, lessonContent, lessonLinks;
            lessonContent = document.getElementsByClassName("lessonContent");
            for (i = 0; i < lessonContent.length; i++) {

                lessonContent[i].style.display = "none";
            }
            lessonLinks = document.getElementsByClassName("lessonLinks");
            for (i = 0; i < lessonLinks.length; i++) {
                lessonLinks[i].className = lessonLinks[i].className.replace(" active", "");
            }
            document.getElementById(fileName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
</script>

<script>
    $('.openContentModal').on('click', function() {

            if ($(this).data('title') == "Article") {
                $('#contentTitle').html($(this).data('name'));
                $('#documentIframe').addClass('d-none');
                $('#articleContent').removeClass('d-none');
                $('#articleContent').html($(this).data('content'));
            } else {
                $('#contentTitle').html($(this).data('name'));
                $('#articleContent').addClass('d-none');
                $('#documentIframe').removeClass('d-none');
                $('#articleContent').html("");

                $('#documentIframe').attr('src', $(this).data('url') + '#toolbar=0');
            }
            $('#content').modal('show');
        })
</script>
@endsection
