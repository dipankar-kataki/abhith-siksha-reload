@extends('layout.website.website')

@section('title','Enroll Course')

@section('head')
    <link href="{{ asset('asset_website/css/jquery.fancybox.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .subheader1-img{
            position: relative;
        }

        .fa-play-circle{
            font-size: 5rem;
            color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            transition: 0.2s all;
        }

        .fa-play-circle:hover{
            color: #111;
        }
    </style>
@endsection
@section('content')
    <section class="subheader1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p0">
                    <div class="subheader1-desc">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="course.html">Course</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><strong>{{ $course->name }}</strong></li>
                                <li class="breadcrumb-item active" aria-current="page">Subject&nbsp; <i class="fa fa-level-down" aria-hidden="true"></i></li>
                            </ol>
                        </nav>
                        <h2 class="heading-white"><span style="font-size:12px;"></span>{{ $course->subject->name }}</h2>
                        <p></p>
                        <div class="text-box">
                            @auth
                                <a href="#" class="mcq-test" style="cursor: pointer">MCQ Test</a>
                            @endauth
                            @guest
                                <a data-toggle="modal" class="btn btn-default" data-target="#login-modal"
                                    style="cursor: pointer;border: 1px solid white;color: white !important;">MCQ Test</a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p0">
                    <div class="subheader1-img">
                        @if ($course->course_video == null)
                            <img src="{{ asset($course->course_pic) }}" class="w100">
                        @else
                            <img src="{{ asset($course->course_pic) }}" class="w100" style="opacity: 0.6">                            
                            <a href="{{asset($course->course_video)}}" data-fancybox="images"
                                data-fancybox-group='image-gallery'>
                                <i class="fa fa-play-circle"></i>
                            </a> 
                        @endif
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
                        <li>
                            <h4 data-brackets-id="12020" class="small-heading-black mb20">Description
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" data-toggle="modal"
                                    data-target="#sharePostModal" style="display:inline;font-size:12px;">
                                    <i class="fa fa-share" aria-hidden="true"></i> &nbsp; Share
                                </a>
                            </h4>

                            <p>{!! $course->description ?? 'Desciption not given' !!}</p>

                            <div class="form-div1 mt-5">
                                <h4 data-brackets-id="12020" class="small-heading-black mb20">Enquiry</h4>
                                <form class="row" action="">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Name" id="name1">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Phone Number"
                                                id="p_number1">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" placeholder="Email Id" id="email1">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 p-md-0">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" placeholder="Message"
                                                id="Message"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-block knowledge-link">Send</button>
                                    </div>


                                </form>
                            </div>
                        </li>
                        <li class="">

                            <div class=" course-desc-list1">
                                <label class="box1 ">Full Course
                                    <input type="checkbox" data-price="0.00" id="select-all" data-selected="fullCourse">
                                    <span class="checkmark"></span>
                                </label>
                                <h5 class="small-heading-black mt15 mb20">Select Lesson</h5>
                                <ul class="list-inline centered">
                                    @forelse ($chapters as $key => $item)
                                        <li>
                                            @auth 
                                                @if( ! $item->cart->isEmpty())
                                                
                                                        @if ($item->cart[0]['chapter_id'] == $item->id)
                                                            <label><i class="fa fa-check-circle-o" style="color:green"></i></label>&nbsp;
                                                            <label for="styled-checkbox-{{ $key + 1 }}">{{ $item->name }} <span style="font-size:12px;color:rgb(63, 63, 63);">( Item present inside cart )</span></label>
                                                            <span class="course-price mr-2"><i class="fa fa-inr"  aria-hidden="true"></i>{{ $item->price }}</span>
                                                        @else
                                                            <input class="styled-checkbox item_price chapter-value"  id="styled-checkbox-{{ $key + 1 }}" data-id="{{ $item->id }}"  data-name="{{ $item->name }}" data-price="{{ $item->price }}"  type="checkbox" value="{{ $item->id }}">
                                                            <label for="styled-checkbox-{{ $key + 1 }}">{{ $item->name }}</label>
                                                            <span class="course-price mr-2"><i class="fa fa-inr"  aria-hidden="true"></i>{{ $item->price }}</span>
                                                        @endif
                                                    
                                                @else
                                                    <input class="styled-checkbox item_price chapter-value"  id="styled-checkbox-{{ $key + 1 }}" data-id="{{ $item->id }}"  data-name="{{ $item->name }}" data-price="{{ $item->price }}"  type="checkbox" value="{{ $item->id }}">
                                                    <label for="styled-checkbox-{{ $key + 1 }}">{{ $item->name }}</label>
                                                    <span class="course-price mr-2"><i class="fa fa-inr"  aria-hidden="true"></i>{{ $item->price }}</span>
                                                @endif
                                            @endauth
                                            @guest
                                                <input class="styled-checkbox item_price chapter-value"  id="styled-checkbox-{{ $key + 1 }}" data-id="{{ $item->id }}"  data-name="{{ $item->name }}" data-price="{{ $item->price }}"  type="checkbox" value="{{ $item->id }}">
                                                <label for="styled-checkbox-{{ $key + 1 }}">{{ $item->name }}</label>
                                                <span class="course-price mr-2"><i class="fa fa-inr"  aria-hidden="true"></i>{{ $item->price }}</span>
                                            @endguest
                                            {{-- <input class="styled-checkbox item_price chapter-value"  id="styled-checkbox-{{ $key + 1 }}" data-id="{{ $item->id }}"  data-name="{{ $item->name }}" data-price="{{ $item->price }}"  type="checkbox" value="{{ $item->id }}">
                                            <label for="styled-checkbox-{{ $key + 1 }}">{{ $item->name }}</label>
                                            <span class="course-price mr-2"><i class="fa fa-inr"  aria-hidden="true"></i>{{ $item->price }}</span>  --}}
                                        </li>
                                    @empty
                                        <li>
                                            <div class="text-center">
                                                <p>No Lessons Found!</p>
                                            </div>
                                        </li>
                                    @endforelse
                                </ul>

                                <div class="total">
                                    <p class=""><b>Total</b></p>
                                    <span class=" course-price1 mr-2" id="total_price"><i class="fa fa-inr"
                                            aria-hidden="true"></i>0.00</span>
                                </div>
                                <div class="total-cart">
                                    <ul class="list-inline total-car-list">
                                        <li class="mr-md-3"><button class="add-cart form-control" id="add_cart">Add
                                                to Cart</button></li>
                                        {{-- <li><a href="#" class="text-center enquiry form-control">Buy Now</a></li> --}}
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <!-- The Modal -->
    <div class="modal" id="add-test-modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-heading">
                    <h5>{{ $course->subject->name }} Test</h5>
                    {{-- <p class="modal-sub-head-left">Question <span id="mcqLeft">1</span> /{{ $countMultiChoice }}</p> --}}
                    <p class="modal-sub-head-left">Time Left : <b><span id="timer">15:00</span></b></p>
                    {{-- <button type="button" class="close" data-dismiss="modal"><span class="icon-cancel-20"></span></button> --}}
                </div>


                <!-- Modal body -->
                <div class="modal-body" id="multipleChoiceModel">
                    @include('website.multiple-choice.mcq')
                    <div class="end-message"></div>
                </div>
            </div>
        </div>
    </div>

    


    <div class="modal" id="startMcqModel">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-heading">
                    <h4>{{ $course->subject->name }} Test</h4>
                    {{-- <p class="modal-sub-head-left">Total Questions {{ $countMultiChoice }}</p> --}}
                    <p class="modal-sub-head-right mcqTimer">Time : <b>15:00 Minutes</b></p>
                    <button type="button" class="close" data-dismiss="modal"><span
                            class="icon-cancel-20"></span></button>
                </div>


                <!-- Modal body -->
                <div class="modal-body">
                    <div class="text-center">
                        <button class="knowledge-link startTest">Start Test</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal" id="reviewMcqResultModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-heading">
                    <h4>Mcq Result</h4>
                    <button type="button" class="close" data-dismiss="modal" id="closeReviewMcqResultModal"><span class="icon-cancel-20"></span></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" style="height:300px;overflow-y:scroll;scroll-behaviour:smooth; padding-right:50px">
                    <div id="reviewMcqResultQuestions"></div>
                </div>
            </div>
        </div>
    </div>
    @include('layout.website.include.modals')

@endsection

@section('scripts')
    @include('layout.website.include.modal_scripts')

    {{-- Close review modal --}}
    <script>
        $('#closeReviewMcqResultModal').on('click', function(){
            location.reload(true);
        });
    </script>

    <script type="text/javascript">
        $('#add_cart').prop('disabled', true);
        $("#add_cart").css("background-color", "grey");
        let selectAllItems = "#select-all";
        let checkboxItem = '.item_price';
        let chapterId = [];
        let allPrice = 0.00;
        let is_full_course_selected = '';


        $(selectAllItems).click(function() {
            allPrice = 0.00
            if (this.checked) {
                is_full_course_selected =  $(this).data('selected');
                $(checkboxItem).each(function() {
                    this.checked = true;
                    allPrice = parseFloat(allPrice) + parseFloat($(this).attr("data-price"));
                    chapterId.push($(this).data('id'));
                    $('#total_price').html('<i class="fa fa-inr" aria-hidden="true"></i>' + allPrice)
                    $('#add_cart').prop('disabled', false)
                    $("#add_cart").css("background-color", "#3ac162");
                });
            } else {
                $(checkboxItem).each(function() {
                    this.checked = false;
                    is_full_course_selected = '';
                    allPrice = 0.00
                    chapterId = [];
                    $('#total_price').html('<i class="fa fa-inr" aria-hidden="true"></i>' + allPrice)
                    $('#add_cart').prop('disabled', true)
                    $("#add_cart").css("background-color", "grey");

                });
            }

        });

        $(checkboxItem).change(function() {
            if (this.checked) {
                is_full_course_selected = '';
                allPrice = (parseFloat(allPrice) + parseFloat($(this).attr("data-price"))).toFixed(2);
                chapterId.push($(this).data('id'));
                $('#total_price').html('<i class="fa fa-inr" aria-hidden="true"></i>' + allPrice)
                $('#add_cart').prop('disabled', false)
                $("#add_cart").css("background-color", "#3ac162");
                console.log(allPrice);
            } else {
                is_full_course_selected = '';
                allPrice = (parseFloat(allPrice) - parseFloat($(this).attr("data-price"))).toFixed(2);
                let itemName = $(this).attr('data-name');
                let indexOf = chapterId.indexOf($(this).data('id'));
                if (indexOf > -1) {
                    chapterId.splice(indexOf, 1);
                }
                $('#total_price').html('<i class="fa fa-inr" aria-hidden="true"></i>' + allPrice)
                if (allPrice == 0) {
                    $('#add_cart').prop('disabled', true)
                    $("#add_cart").css("background-color", "grey");
                } else {
                    $('#add_cart').prop('disabled', false)
                    $("#add_cart").css("background-color", "#3ac162");
                }
            }
        });

        /********************************************* Adding Item To Cart ************************************************/
        $('#add_cart').on('click', function(e) {
            e.preventDefault();
            if ("{{ Auth::check() }}" == false) {
                $('#add_cart').on('click', function(e) {
                    e.preventDefault();
                    $('#login-modal').modal('show');
                });
            } else {
                $.ajax({
                    url: "{{ route('website.add-to-cart') }}",
                    type: "POST",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'course_id': "{{ $course->id }}",
                        'chapter_id': chapterId,
                        'is_full_course_selected' : is_full_course_selected,
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.status == 1){
                            toastr.success(result.message);
                        }else{
                            toastr.error(result.message);
                        }
                        $(checkboxItem).each(function() {
                            this.checked = false;
                            allPrice = 0.00
                            $('#total_price').html(
                                '<i class="fa fa-inr" aria-hidden="true"></i>' + allPrice)
                            $('#add_cart').prop('disabled', true)
                            $("#add_cart").css("background-color", "grey");
                            // window.location.href = "{{route('website.cart')}}";
                        });
                        location.reload(true);
                        chapterId = [];
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 500 || xhr.status == 422) {
                            toastr.error('Oops! Something went wrong. Not able to add to cart.');
                        }
                        chapterId = [];
                    }
                });
            }

        });


        /********************************************* For  MCQ's ************************************************/
        let interval = '';
        $('.mcq-test').on('click', function() {
            $('#startMcqModel').modal('show');
            if ({{ $countMultiChoice }} == 0) {
                $('.mcqTimer').css('display','none');
                $('#startMcqModel').find('.modal-body').addClass('text-center');
                $('#startMcqModel').find('.modal-body').html('<strong style="color:red;">Oops! No Questions Found</strong>');
            } else {
                $('.startTest').on('click', function(e) {
                    e.preventDefault();
                    $('.mcqTimer').css('display','block');
                    $('#add-test-modal').modal('show');
                    $('#startMcqModel').modal('hide');
                    interval = setInterval(updateTimer, 1000);
                })
            }

        });


        function loadMcq(page) {
            let html =
                '<div class="text-center"> <i class="fa fa-check-circle-o" aria-hidden="true" style="color:green;font-size:22px;"></i>&nbsp;Test Done</div>';
            $.ajax({
                    url: '?page=' + page,
                    type: 'get',
                })
                .done(function(data) {
                    if (data.mcq == '') {
                        $('.end-message').html(html);
                        return;
                    } else {
                        $('.end-message').hide();
                        $('#multipleChoiceModel').html(data.mcq);
                    }

                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    toastr.error('Oops!, Something went wrong');
                })
        }

        let page = 1;
        $(document).on('click', '.mcq-page-link #saveOptions', function(e) {
            e.preventDefault();
            let options = document.getElementsByName('mcq-group');
            if (!$("input[name='mcq-group']:checked").val()) {
                toastr.error('Nothing is selected!');
                return false;
            } else {
                page++;
                loadMcq(page);
                if (page == "{{ $countMultiChoice }}") {
                    $('#mcqLeft').text(page);
                }
            }
        });


        /******************************* For Time Left Countdown Timer *********************************/

        const startingMinutes = 15;
        let time = startingMinutes * 60;
        const timer = document.getElementById('timer');

        function updateTimer() {
            const minutes = Math.floor(time / 60);
            let seconds = time % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timer.innerHTML = `${minutes}:${seconds}`;
            if (time == 0) {
                time = 0;
                alert('Oops!, Times Up');
                clearInterval(interval);
                $('.mcq-page-link').hide();
                $('.check-result').show();
            } else {
                time--;
            }
        }

        /******************************* Check MCQ's If it is correct *********************************/
        let mcqArray = [];
        let setId ;
        $(document).on('click', '.mcq-page-link #saveOptions', function() {
            let options = document.getElementsByName('mcq-group');
            setId = document.getElementById('setId').value;
            for (let i = 0; i < options.length; i++) {
                if (options[i].checked) {
                    mcqArray.push(options[i].value);
                }
            }
        });

        $('.check-result ').on('click',function(e){
            $.ajax({
                url: "{{ route('website.check.is.correct-mcq') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'setId' : setId,
                    'mcArray': mcqArray,
                    'subject_id': "{{ $course->subject->id }}"
                },
                success: function(result) {

                    $('#mcqSubmitBtn').hide();
                    $('#add-test-modal').modal('hide');
                    $('#reviewMcqResultModal').modal('show');
                    let mcqAnswers = '';
                    let userAnswer = '';
                    let finalMcqArray = [];
                    if(result.selectedAnswer == null){
                        document.getElementById('reviewMcqResultQuestions').innerHTML = 'No option Selected. Test evaluation incomplete.';
                    }else{
                        for(let j = 0; j < result.selectedAnswer.length ; j++){
                        userAnswer +=  '<p style="margin-left:20px;margin-bottom:5px;">Your Answer : '+ result.selectedAnswer[j] +' </p>'
                        }
                        for(let i= 0; i <result.checkMcq.length;i++){
                            mcqAnswers +=   '<ul style="list-style-type: none;">'+
                                '<li>' +
                                '<span style="font-weight:bold;font-size:15px;"></span> <span style="font-size:.9375rem;">'+ (i+1) +' ) Question:</span>'+
                                    '<p style="margin-left:10px;margin-bottom:5px;font-weight:bold;">'+ result.checkMcq[i]["question"]  +'</p>';

                                        mcqAnswers += '<p style="background-color:green;color:white;width:100%;padding-left:20px;">'+  '<span> <span style="font-size:12px;">Correct Answer</span> -> '+ result.checkMcq[i]["correct_answer"] +'</span> </p>';                                        
                                        mcqAnswers += '<p style="background-color:grey;color:white;width:100%;padding-left:20px;">'+  '<span> <span style="font-size:12px;">Your Answer</span> -> '+ result.selectedAnswer[i] +'</span> </p>';                                        
                                    mcqAnswers += '</li>'+
                            '</ul>'
                        }

                        document.getElementById('reviewMcqResultQuestions').innerHTML = mcqAnswers;
                    }
                    
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error("Oops! Something went wrong");
                    }
                }
            });
        })








        $(document).on('submit', '#mcqForm', function(e) {
            e.preventDefault();
            timer.innerHTML = '0:00';
            clearInterval(interval);

            $.ajax({
                url: "{{ route('website.check.is.correct-mcq') }}",
                type: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'setId' : setId,
                    'mcArray': mcqArray,
                    'subject_id': "{{ $course->subject->id }}"
                },
                success: function(result) {

                    $('#mcqSubmitBtn').hide();
                    $('#add-test-modal').modal('hide');
                    $('#reviewMcqResultModal').modal('show');
                    let mcqAnswers = '';
                    let userAnswer = '';
                    let finalMcqArray = [];
                    
                    if(result.selectedAnswer == null){
                        document.getElementById('reviewMcqResultQuestions').innerHTML = 'No option Selected. Test evaluation incomplete.';
                    }else{
                        for(let j = 0; j < result.selectedAnswer.length ; j++){
                        userAnswer +=  '<p style="margin-left:20px;margin-bottom:5px;">Your Answer : '+ result.selectedAnswer[j] +' </p>'
                        }
                        for(let i= 0; i <result.checkMcq.length;i++){
                            mcqAnswers +=   '<ul style="list-style-type: none;">'+
                                '<li>' +
                                '<span style="font-weight:bold;font-size:15px;"></span> <span style="font-size:.9375rem;">'+ (i+1) +' ) Question:</span>'+
                                    '<p style="margin-left:10px;margin-bottom:5px;font-weight:bold;">'+ result.checkMcq[i]["question"]  +'</p>';

                                        mcqAnswers += '<p style="background-color:green;color:white;width:100%;padding-left:20px;">'+  '<span> <span style="font-size:12px;">Correct Answer</span> -> '+ result.checkMcq[i]["correct_answer"] +'</span> </p>';                                        
                                        mcqAnswers += '<p style="background-color:grey;color:white;width:100%;padding-left:20px;">'+  '<span> <span style="font-size:12px;">Your Answer</span> -> '+ result.selectedAnswer[i] +'</span> </p>';                                        
                                    mcqAnswers += '</li>'+
                            '</ul>'
                        }

                        document.getElementById('reviewMcqResultQuestions').innerHTML = mcqAnswers;
                    }




                    // $('#mcqResult').html('<div class="text-center text-success">' +
                    //     '<span style="font-size:20px;">Total Corrects: ' + result.Total_corrects +
                    //     ' out of ' + "{{ $countMultiChoice }}" + '</div>');
                    // setTimeout(() => {
                    //     location.reload(true);
                    // }, 2000);
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error("Oops! Something went wrong");
                    }
                }
            });
        });
    </script>


    <script src="{{ asset('asset_website/js/jquery.fancybox.js') }}"></script>
    <script type="text/javascript">
        $('[data-fancybox="images"]').fancybox({
            thumbs: {
                autoStart: true
            }
        });
        $('[data-fancybox="images1"]').fancybox({
            thumbs: {
                autoStart: true
            }
        });
    </script>
@endsection
