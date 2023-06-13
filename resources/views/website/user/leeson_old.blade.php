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
        <nav class="col-sm-4">
          <ul class="nav nav-pills flex-column">
            @foreach($all_lessons as $key=>$lesson)
            <li class="nav-item p-1">
              <a class="nav-link active-tab-button" onclick="lessonDetails({{$lesson->id}})">{{$lesson->name}}</a>
            </li>
            @endforeach
          </ul>
        </nav>
        <div class="col-sm-8 col-8 lesson-card" id="myScrollspy">
          
          <div class="row display_topic p-2">
          
          </div>
          <hr>
         
        </div>
      </div>
    </div>
  </div>

  </div>
</section>
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
  var allLesson=@json($all_lessons);
  var id=allLesson[0].id;
   initMenu();
   lessonDetails(id);
});
function lessonDetails(id){
   var LessonId=id;
   var filter_data = ``;
   var url = '{{ route("website.user.lessonbyid", ":id") }}';
   url = url.replace(':id', LessonId);
   $.ajax({
   
				url: url,
				method: 'get',
				success: function(result) {
          
          var topics=result.filter_data.topics;
          topics.forEach(function(data,index) {
							var index_value = index + 1;
              var id=data.id;
            
							filter_data +=`
              <a class="col-6 p-2" href="{{route('website.user.lesson.details',Crypt::encrypt(1))}}">
              <div class="card topic-cart">
                
                <div class="card-body topic-cart-body">
                  <h6 class="card-title text-right see-more">See More</h6>
                  <h6 class="chip">${data.name}</h6>
                </div>
                <div class="card-footer text-muted topic-cart-footer">
                  <h6 style="font-size:12px!important"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i> Videos <i class="fa fa-folder" aria-hidden="true"></i> Lessons</h6>
                </div>
              </div>
            </a>`;
						});
           
            $('.display_topic').html(filter_data);
				}
			});

}
</script>
@endsection