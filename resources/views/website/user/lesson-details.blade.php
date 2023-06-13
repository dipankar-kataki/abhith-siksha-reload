@extends('layout.website.website')
@php
$prefix = Request::route()->getPrefix();
@endphp

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">

<link href="{{ asset('asset_website/css/jquery.fancybox.css') }}" rel="stylesheet">
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

</style>
@endsection

@section('content')
@if($prefix=='/account')
@include('layout.website.include.forum_header')
@endif
<section class="account-section">

  @include('common.subject.start')


</section>
@endsection
@section('scripts')
<script src="{{asset('asset_website/js/videojs.watermark.js')}}"></script>
<script src="{{asset('asset_website/js/videojs-resolution-switcher.js')}}"></script>
<script src="{{ asset('asset_website/js/jquery.fancybox.js') }}"></script>
<script type="text/javascript">
    $('[data-fancybox="pdf"]').fancybox({
        protect    : true,
        beforeShow : function(){
        this.title =  $(this.element).data("caption");
        },
        thumbs: {
            autoStart: true
        },
        type   :'iframe',
       
    });
    // $('[data-fancybox="images1"]').fancybox({
    //     beforeShow : function(){
    //         this.title =  $(this.element).data("caption");
    //     },
    //     thumbs: {
    //         autoStart: true
    //     }
    // });
</script>
<script>
  $(document).ready(function() {
    var myPlayer = videojs('player');
   });
   
  
</script>

@endsection