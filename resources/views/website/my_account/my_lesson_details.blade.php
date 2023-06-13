@extends('layout.website.website')

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">
<style>
    <style>* {
        box-sizing: border-box
    }

    body {
        font-family: "Lato", sans-serif;
    }

    /* Style the tab */
    .tab {
        float: left;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        width: 25%;
        height: 440px;
    }

    /* Style the buttons inside the tab */
    .tab button {
        display: block;
        background-color: inherit;
        color: black;
        padding: 22px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #076fef;
    }

    /* Create an active/current "tab button" class */
    .tab button.active {
        background-color: #0770EF;
    }

    /* Style the tab content */
    .tabcontent {
        float: left;
        border: 1px solid #0770EF;
        width: 75%;
        border-left: none;
        height: 440px;
    }

    .subheader1-img img {

        width: 100%;
        object-fit: cover;
    }

    .topic-details-section {
        margin: 0px 67px;
        /* padding: 20px 0px; */
    }

    .tab button {
        display: block;
        background-color: inherit;
        color: black;
        padding: 9px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.3s;
        font-size: 14px;
    }
    .vjs-watermark{
        position: absolute !important; 
        top: 9px !important; 
        left: 0px !important;
    }
</style>

@endsection

@section('content')
@include('layout.website.include.forum_header')
<section class="subheader1">

    <div class="row">
        <div class="col-lg-6">
            <div class="subheader1-desc">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">{{$lesson->board->exam_board}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Class{{$lesson->assignClass->class}}-&nbsp; <i class="fa fa-level-down"
                                aria-hidden="true"></i></li>
                    </ol>
                </nav>
                <h2 class="heading-white"><span style="font-size:12px;">
                        {{$lesson->assignSubject->subject_name}}: {{$lesson->name}}</span></h2>
                <p></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="subheader1-img"><img src="{{asset($lesson->lessonAttachment->img_url)}}" class="w100"
                    style="opacity: 0.6">
                <a href="{{asset('/storage/'.$lesson->lessonAttachment->origin_video_url)}}" data-fancybox="images"
                    data-fancybox-group="image-gallery">
                    <i class="fa fa-play-circle"></i>
                </a>
            </div>
        </div>
    </div>


</section>
<section class="course-describtion">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-inline course-desc-list">
                    <h4 data-brackets-id="12020" class="small-heading-black mb20">
                        <h5>{!!$lesson->content!!}</h5>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" data-toggle="modal"
                            data-target="#sharePostModal" style="display:inline;font-size:12px;">
                            <i class="fa fa-share" aria-hidden="true"></i> &nbsp; Share
                        </a>
                    </h4>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="topic-details-section">
    <div class="container-fluid">
        <div class="tab">
            <div class="accordion" id="accordionExample">
                @foreach($lesson->topics as $key=>$topic)
                <div class="card">
                    <div class="card-header p-1" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-block text-left lesson-topic" type="button" data-toggle="collapse"
                                data-target="#collapseOne{{$key}}" aria-expanded="false" aria-controls="collapseOne">
                                {{$key+1}}.{{$topic->name}}
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne{{$key}}" class="collapse" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body p-1">
                            <button class="btn btn-primary" onclick="displayAttachment('content')"
                                value="{{$topic->id}}">{{$topic->name}}</button>

                            @if($topic->lessonAttachment!=null)
                            @if($topic->lessonAttachment->img_url!=null)
                            <button class="btn btn-primary" id="displayAttachment"
                                onclick="displayAttachment('imageAttach')" value="{{$topic->id}}"><i
                                    class="fa fa-picture-o" style="font-size:18px;color:#0770EF"></i>
                                {{$topic->name}}</button>
                            @endif
                            @if($topic->lessonAttachment->origin_video_url!=null)
                            <button class="btn btn-primary" onclick="displayAttachment('videoAttach')"
                                value="{{$topic->id}}"><i class="fa fa-play-circle"
                                    style="font-size:20px;color:#0770EF"></i> {{$topic->name}}</button>
                            @endif
                            @endif

                            @foreach($topic->subTopics as $key=>$sub_topic)
                            <button class="btn btn-primary" onclick="displayAttachment('Content')"
                                value="{{$sub_topic->id}}">{{$key+1}}. {{$sub_topic->name}}</button>

                            @if($sub_topic->lessonAttachment!=null)
                            @if($sub_topic->lessonAttachment->img_url!=null)
                            <button class="btn btn-primary" onclick="displayAttachment('imageAttach')"
                                value="{{$sub_topic->id}}"><i class="fa fa-picture-o"
                                    style="font-size:18px;color:#0770EF"></i>
                                {{$sub_topic->name}}</button>
                            @endif
                            @if($sub_topic->lessonAttachment->origin_video_url!=null)
                            <button class="btn btn-primary" onclick="displayAttachment('videoAttach')"
                                value="{{$sub_topic->id}}"><i class="fa fa-play-circle"
                                    style="font-size:20px;color:#0770EF"></i>
                                {{$sub_topic->name}}</button>
                            @endif
                            @endif
                            @endforeach


                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="Content" class="tabcontent">
            <span id="displayContent"></span>

        </div>

        <div id="imageAttach" class="tabcontent">
            <span id="displayImage"></span>
        </div>

        <div id="videoAttach" class="tabcontent">
            <video id="player" class="video-js" controls preload="auto" autoplay loop muted
                poster="{{asset($lesson->lessonAttachment->video_thumbnail_image)}}" loading="lazy">
            </video>
        </div>

    </div>
</section>


@endsection
@section('scripts')
<script>
    $(document).ready(function(){
            var i, tabcontent, tablinks;
           tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
        });
    function displayAttachment(type){
      
      if(type=='content'){
        document.getElementById("Content").style.display = "block";
        document.getElementById("imageAttach").style.display = "none";
        document.getElementById("videoAttach").style.display = "none";
      }else if(type=='imageAttach'){
        document.getElementById("imageAttach").style.display = "block"; 
        document.getElementById("Content").style.display = "none";
        document.getElementById("videoAttach").style.display = "none";
      }else if(type=='videoAttach'){
        document.getElementById("videoAttach").style.display = "block"; 
        document.getElementById("Content").style.display = "none";
        document.getElementById("imageAttach").style.display = "none";
      }
        var lesson_id=  $("#displayAttachment").attr('value');
    

        $.ajax({
            type:'POST',
            url:"{{ route('website.user.lesson.attachment') }}",
            data:{lesson_id:lesson_id,_token: '{{csrf_token()}}'},
            success:function(data){
               var content=`<div class="card"><div class="card-body">
                ${data.content}
                </div>

                </div>`;
              $("#displayContent").html(content,type);

              var Image=`<img src="{{ URL::asset('${data.lesson_attachment.img_url}') }}" class="img-fluid" alt="Responsive image"
                style="height:438px;weight:73%!important;" loading="lazy">`;
                $("#displayImage").html(Image);
                 if(type=="videoAttach"){
                    videoRationWiseDisplay(data);
                 }
               
            
            
            }
        });
    }
</script>
<script src="{{asset('asset_website/js/videojs.watermark.js')}}"></script>
<script src="{{asset('asset_website/js/videojs-resolution-switcher.js')}}"></script>
<script>
    function videoRationWiseDisplay(data){
       
        var lesson=data;
        var lesson_attachment=data.lesson_attachment;
        var storagePath = "{!! storage_path() !!}";
        var FULLHD= lesson_attachment['origin_video_url'] ;
        var SD= lesson_attachment['video_resize_480'] ;
        var HD= lesson_attachment['video_resize_720'] ;
        var player = videojs('player', {
        fluid: true,
        plugins: {
            videoJsResolutionSwitcher: {
            default: '480px',
            dynamicLabel: true
            }
        }
        });
        player.updateSrc([
        {
            src: 'http://localhost/abhith-new/public/storage/'+SD,
            type: 'video/mp4',
            res: 480,
            label: '480px'
        },
        {
            src: 'http://localhost/abhith-new/public/storage/'+HD,
            type: 'video/mp4',
            res: 720,
            label: '720px'
        },
            {
            src: 'http://localhost/abhith-new/public/storage/'+FULLHD,
            type: 'video/mp4',
            res: 1080,
            label: '1080px'
        },
        
        ]);
        player.watermark({
            file: 'watermarks.png',
           
        });
    }
  
</script>
@endsection