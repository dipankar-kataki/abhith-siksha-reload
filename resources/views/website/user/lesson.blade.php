@extends('layout.website.website')
@php
$prefix = Request::route()->getPrefix();
@endphp

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/css/star-rating.min.css" media="all"
  rel="stylesheet" type="text/css" />

<style>
  /* video js css */
  .video-js {
    height: 280px;
    /* width: 365px; */
    width: 100%;
    top: 0%;
    /* border-style: double;
    border-width: thick; */
    /* border-color: black; */
  }

  .video-js .vjs-play-progress {
    background-color: #007bff;
  }

  .lesson-video-content {
    padding: 6px 20px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
    border-style: solid;
    align-items: center;
    justify-content: space-between;
  }
</style>


@endsection

@section('content')
@if($prefix=='/account')
@include('layout.website.include.forum_header')
@endif
<section class="account-section">

  @include('common.subject.details')


</section>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/js/star-rating.min.js"
  type="text/javascript"></script>
<script src="{{asset('asset_website/js/videojs.watermark.js')}}"></script>
<script src="{{asset('asset_website/js/videojs-resolution-switcher.js')}}"></script>
<script>
  $(document).ready(function() {
    var myPlayer = videojs('player');
   });
   
  
</script>
<script>
  $('#previewVideo').click(function(){
    var lesson_id=$(this).attr('data-lesson');
    let url = "{{ route('admin.subject.promovideo', ':id') }}";
    var displayLessons=``;
    var demoVideoPlayer=``;
    url = url.replace(':id', lesson_id);
    // http://206.189.132.212/abhith-siksha/public
    let base_url= "http://localhost/abhith-new/public";
    $.ajax({
				url: url,
				method: 'get',
				success: function(result) {
					if(result.status==1){
           var topic=result.result.lesson;
            var lessons=result.result.all_lessons;
            demoVideoPlayer=`
            <video id="player" class="video-js vjs-big-play-centered" controls preload="auto"
                    poster="http://206.189.132.212/abhith-siksha/public/${topic.lesson_attachment.video_thumbnail_image}" data-setup="{}">
                    <source src="http://206.189.132.212/abhith-siksha/public/${topic.lesson_attachment.attachment_origin_url}" type="video/mp4"
                        class="w100" />
                </video>`;
             
            lessons.forEach(function(lesson) {
              displayLessons += `
                                <div class="lesson-video-content d-flex mx-1">
                                  <p><div><img src="http://206.189.132.212/abhith-siksha/public/${lesson.lesson_attachment.video_thumbnail_image}" style="height:50px;width:70px;"></div><i class="fa fa-play-circle"></i> ${lesson.name} &nbsp;
                                    
                                      ${lesson.lesson_attachment.video_duration} mins
                                       </p>       
                                </div>`;
							});
              $('.demoVideo').html(displayLessons);
              $('.demoVideoPlayer').html(demoVideoPlayer);
              
          }
					
				}
			});
  });
</script>

@endsection