@extends('layout.website.website')

@section('title', 'My Course')

@section('head')
@endsection

@section('content')

@include('layout.website.include.forum_header')
@section('content')
<div class="container-fluid">

    <h3 class="text-center">{{$data->lesson->name}}</h3>


    {{-- <video poster="{{ asset($video->video_thumbnail_image) }}" controls style="width: 100%;">
        <source src="{{ asset($video->video_origin_url) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video> --}}
    <video id="player" class="video-js" controls preload="auto" autoplay muted
        poster="{{ asset($data->video_thumbnail_image) }}" loading="lazy">
    </video>
</div>
@endsection
@section('scripts')
{{-- <script src="{{asset('asset_website/js/videojs.watermark.js')}}"></script> --}}
<script src="{{asset('asset_website/js/videojs-resolution-switcher.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script>
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        var lesson_attachment=@json($data);
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
        // http://localhost/abhith-new/public/; 
        player.updateSrc([
        {
            src: 'https://abhithsiksha.com'+SD,
            type: 'video/mp4',
            res: 480,
            label: '480px'
        },
        {
            src: 'https://abhithsiksha.com'+HD,
            type: 'video/mp4',
            res: 720,
            label: '720px'
        },
            {
            src: 'https://abhithsiksha.com'+FULLHD,
            type: 'video/mp4',
            res: 1080,
            label: '1080px'
        },
        
        ]);
        // player.watermark({
        //     file: 'http://localhost/abhith-new/public/asset_website/img/home/logo_.png',
           
        // });
        if (player.readyState() < 1) {
        // wait for loadedmetdata event
        player.one("loadedmetadata", onLoadedMetadata);
       }
        else {
            // metadata already loaded
            onLoadedMetadata();
        }
    
    function onLoadedMetadata() {
        var lesson_id=@json($lesson_id);
        var subject_id=@json($subject_id);
        var user_id=@json($user_id);
        $.ajax({
            type:'POST',
            url:"{{route('website.course.package.subject.video.duration')}}",
            data:{lesson_id:lesson_id,subject_id:subject_id,user_id:user_id},
            success:function(Responsedata){
                localStorage['subject_lesson_visitor_id']=Responsedata.result.data.id;
               
            }
        });
        
        
    }
    
    player.on("play", function () {
        localStorage['play_time']=new Date();
       
     });
    player.on("pause", function () {
        
        var playTime=moment(localStorage['play_time']).format('YYYY-MM-DD HH:mm:ss');
        var pauseTime=moment(new Date()).format('YYYY-MM-DD HH:mm:ss');
        var lessonVisitorId=localStorage['subject_lesson_visitor_id'];
        $.ajax({
            type:'POST',
            url:"{{route('website.course.package.subject.video.duration.update')}}",
            data:{subject_lesson_visitor_id:lessonVisitorId,play_time:playTime,pause_time:pauseTime},
            success:function(Responsedata){
              console.log(Responsedata);
               
            }
        });
    
     });
   });
   
 
</script>

@endsection