@extends('layout.website.website')

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">



@endsection

@section('content')
{{-- @include('layout.website.include.forum_header') --}}
<br>
<section class="account-section">
  <div class="container-fluid">
    <div data-spy="scroll" data-target="#myScrollspy" data-offset="1">
      <label>
        <h6>Lesson</h6>
      </label>
      <div class="row">
        <nav class="col-sm-3 col-4">
          <ul class="nav nav-pills flex-column">
            @foreach($all_lessons as $key=>$lesson)
            <li class="nav-item p-1">
              <a class="nav-link active-tab-button" href="#section{{++$key}}">{{$lesson->name}}</a>
            </li>
            @endforeach
          </ul>
        </nav>
        <div class="col-sm-9 col-8 lesson-card" id="myScrollspy">
          @foreach($all_lessons as $key=>$lesson)
          <div class="row">
            @foreach($lesson->topics as $key=>$topic)

            <a class="col-4" href="{{route('website.user.lesson.details',Crypt::encrypt($topic->parent_id))}}">
              <div class="card topic-cart">
                <div class="card-body topic-cart-body">
                  <h6 class="card-title text-right see-more">See More</h6>
                  <h6 class="chip">{{$topic->name??''}}</h6>
                </div>
                <div class="card-footer text-muted topic-cart-footer">
                  <h6 style="font-size:12px!important"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i> Videos <i class="fa fa-folder" aria-hidden="true"></i> {{$topic->subTopics->count()}} Lessons</h6>
                </div>
              </div>
            </a>

            @endforeach
          </div>
          <hr>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  </div>
</section>

{{-- <div class="container p-5">
  <div class="row">
    <div class="col-8">
      <b>All Lessons</b>
      <div class="accordion p-2" id="accordionExample">
        @foreach($all_lessons as $key=>$lesson)
        <div class="card">
          <div class="card-header" id="headingOne">
            <h2 class="mb-0">
              <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                data-target="#collapse{{$lesson->id}}" aria-expanded="true" aria-controls="collapseOne">
                {{$lesson->name}}
              </button>
            </h2>
          </div>

          <div id="collapse{{$lesson->id}}" class="collapse @if($key==0) show @endif" aria-labelledby="headingOne"
            data-parent="#accordionExample">
            <div class="card-body">
              <ol>
                @foreach($lesson->topics as $key=>$topic)
                <li>{{substr(strip_tags($lesson->content), 0, 100)}}...</li>
                @endforeach

              </ol>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-3">

      <div class="course-pic"><img src="{{asset($subject->image)}}" class="w100"></div>
      <div class="course-desc"></span>
        <div class="block-ellipsis5">
          <h4 class="small-heading-black">{{$subject->subject_name}}</h4>
        </div>
        <span></span>
      </div>

    </div>
  </div>
</div> --}}

@endsection
@section('scripts')
<script>
  $("#menu-toggle").click(function(e) {
   e.preventDefault();
   $("#wrapper").toggleClass("toggled");
});
$("#menu-toggle-2").click(function(e) {
   e.preventDefault();
   $("#wrapper").toggleClass("toggled-2");
   $('#menu ul').hide();
});

function initMenu() {
   $('#menu ul').hide();
   $('#menu ul').children('.current').parent().show();
   //$('#menu ul:first').show();
   $('#menu li a').click(
      function() {
         var checkElement = $(this).next();
         if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            return false;
         }
         if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            $('#menu ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
            return false;
         }
      }
   );
}
$(document).ready(function() {
   initMenu();
});
</script>
@endsection