@extends('layout.website.website')

@section('title','Courses')
@section('head')
<style>
    .course-price-custom {
        position: absolute;
        font-size: 16px;
        left: 449px;
        font-weight: 600;
    }

    .course-price2 {
        position: absolute;
        right: 56px;
        top: 15%;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<main>
    <section class="subheader1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p0">
                    <div class="subheader1-desc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="course.html">{{$board->exam_board}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Class-{{$class->name}}&nbsp; <i
                                        class="fa fa-level-down" aria-hidden="true"></i></li>
                            </ol>
                        </nav>
                        <h2 class="heading-white"><span style="font-size:12px;"></span></h2>
                        <p></p>
                    </div>
                </div>
                <div class="col-lg-6 p0">
                    <div class="subheader1-img"><img
                            src="https://abhith.dev-ekodus.com/files/course/08-12-2021-17-51-12_p185554_b_v10_az.jpg"
                            class="w100" style="opacity: 0.6">
                        <a href="https://abhith.dev-ekodus.com/files/course/courseVideo/08-12-2021-17-51-12_file_example_MP4_480_1_5MG.mp4"
                            data-fancybox="images" data-fancybox-group='image-gallery'>
                            <i class="fa fa-play-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="course-describtion">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 p0">
                    <ul class="list-inline course-desc-list">
                        <h4 data-brackets-id="12020" class="small-heading-black mb20">Description
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

    <section class="course-describtion">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 p0">
                    <ul class="list-inline course-desc-list">
                        <form action="{{route('website.add-to-cart')}}" method="post">
                            @csrf
                            <li>
                                <p>
                                <div class="course-desc-list1 p4">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input course_type" type="radio" name="course_type"
                                            id="full_course" value="1" onclick="changeCourse(this.value)" checked>
                                        <label class="form-check-label" for="full_course">Full Course</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input course_type" type="radio" name="course_type"
                                            id="custom_package" value="2" onclick="changeCourse(this.value)">
                                        <label class="form-check-label" for="custom_package">Custom Package</label>
                                    </div>
                                </div>
                                </p>
                            </li>
                            <li class="">
                                <span id="message-for-custom-package"></span>
                                <div class="course-desc-list1">
                                    <form action="{{route('website.add-to-cart')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="board_id" value="{{$board->id}}">
                                        <input type="hidden" name="class_id" value="{{$class->id}}">
                                        <label class="box1 ">Full Course</label>
                                        <hr>
                                        <ul class="list-inline centered">
                                            @foreach($subjects as $key=>$subject)
                                            <li>
                                                <input class="styled-checkbox item_price chapter_value"
                                                    id="styled-checkbox-full-course-{{$key}}" data-id="{{$subject->id}}"
                                                    data-name="{{$subject->subject_name}}"
                                                    data-price="{{number_format($subject->subject_amount,2,'.','')}}"
                                                    type="checkbox" value="{{$subject->id}}" name="subjects[]"
                                                    onclick="checkedSubject()">
                                                <label for="styled-checkbox-full-course-{{$key}}">
                                                   <a href="{{route('website.subject.detatils',Crypt::encrypt($subject->id))}}"> {{$subject->subject_name}}</a></label>
                                                <span class="course-price mr-2"><i class="fa fa-inr" aria-hidden="true"></i>  {{number_format($subject->subject_amount,2,'.','')}}</span>

                                            </li>
                                            @endforeach
                                        </ul>

                                        <div class="total">
                                            <p class=""><b>Total</b></p>
                                            <span class="course-price1 mr-2" id="total_price"><i class="fa fa-inr"
                                                    aria-hidden="true"></i> {{number_format($total_amount, 2, '.', '
                                                ')}}</span>
                                        </div>
                                        <span id="total-cart">
                                            
                                        </span>
                                    </form>
                                </div>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </section>






    @section('scripts')
    <script>
        $(document).ready(function() {
      var courseType= $(".course_type").val();
        changeCourse(courseType);
     });
   function changeCourse(value){
        if(value==1){
            $("#message-for-custom-package").html(``);
            $("#full_course").prop("checked", true);
            $(".chapter_value").each(function(index) {
                $(this).prop("checked", true);
                $(this).prop("disabled", true);
         
           });
           checkedSubject();
        }else{
            var messageForCustomPackage=` <button type="button" class="btn btn-secondary btn-lg btn-block">Please Selete Subjects for custom your package</button>`;
            $("#message-for-custom-package").html(messageForCustomPackage);
            $(".chapter_value").each(function(index) {
                $(this).prop("checked", false);
                $(this).prop("disabled", false);
         
           });
           
           checkedSubject();
        }
   }
   function checkedSubject(){
    var totalAmount=0.00;
   
      $(".chapter_value").each(function(index) {
          if(this.checked==true){         
            totalAmount= parseFloat(totalAmount) + parseFloat($(this).attr('data-price'));
          }
         
      });
      var amount=`<i class="fa fa-inr" aria-hidden="true"></i> &nbsp;`;
      if(totalAmount==0){
        $("#add_cart").prop("disabled",true);
        $("#total-cart").html(``);
       
      }else{
          var totalCart=`<ul class="list-inline total-car-list p-6"><button type="submit" class="add-cart form-control" id="add_cart">Add to Cart</button></ul>`
        
        $("#add_cart").prop("disabled",false);
        $("#total-cart").html(totalCart);
      }
      $("#total_price").html(amount+totalAmount.toFixed(2));
   }
  
    </script>



    @endsection