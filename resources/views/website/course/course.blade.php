@extends('layout.website.website')
@section('head')
    <style>
        .course-breadcrumbs {
            width: 100%;
        }

        .subject-heading-black {
            font-size: 15px;
            font-weight: 700;
            color: #000;
        }

        .search-form-wrapper {
            position: relative;
            border: 1px solid #eee;
            padding: 20px 10px;
            box-shadow: 1px 3px 5px #dad7d7;
        }

        .search-form-wrapper label {
            font-weight: bold;
        }

        .reset-form-btn {
            /* padding: 0px 3px !important;
            width: 55px !important;
            position: absolute;
            top: 0;
            right: 0;
            border-radius: 0; */
            background-image: linear-gradient(to right, #e9e5e5, #e1dedf, #d9d8d9, #d2d2d2, #cbcbcb);
            color: black;
            font-weight: 500;
            border: 1px solid #f3f0f0;
        }

        #submitWebsiteFilterCourseForm{
            font-weight: 600;
        }
        
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.css" rel="stylesheet" />
@endsection
@section('title', 'Courses')

@section('content')

    <section class="subheader">
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col-lg-12">
                    <ul class="list-inline cart-course-list1">
                        @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
    @endif
                        @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
    @endif
                    </ul>
                </div> -->
            </div>
            <div class="row">
                <div class="col-lg-12 p0">
                    <div class="subheader-image"><img src="{{ asset('asset_website/img/course/banner.png') }}"
                            class="course-breadcrumbs">
                    </div>
                    <div class="subheader-image-desc">
                        <h2 class="heading-black">Our Courses<br>
                            <span class="heading-blue">Study Beyond The Classroom</span>
                        </h2>

                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- class="row mt-2 justify-content-center" --}}
    <section class="home-courses">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="heading-black">All Courses</h5>
                </div>
                <div class="col-lg-12">
                    <form method="get">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <label>Select Board</label>
                                <select name="assignedBoard" id="assignedBoard" class="form-control"
                                    onchange="changeBoard()" required>
                                    <option value="">Select Board </option>
                                    @forelse ($boards as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->exam_board }}</option>
                                    @empty
                                        <option value="">No boards to show</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label class="selectClass">Select Class</label>
                                <select id="board-class-dd" class="form-control" name="class_id" required>
                                       
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-12 py-4 submitBtn">
                                <button type="submit" id="submitWebsiteFilterCourseForm"
                                    class="btn knowledge-link enquiry-form-btn">Submit</button>
                                <a href="{{ request()->url() }}" class="btn btn-default reset-form-btn"><i
                                        class="fa fa-refresh" aria-hidden="true"></i> &nbsp; Reset Filter</a>
                            </div>
                        </div>

                        {{-- <a href="{{request()->url()}}" class="btn btn-block  reset-form-btn">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                    </a> --}}
                    </form>
                </div>
            </div>
        </div>

        @if ($subjects->count() != 0 && Request::get('assignedBoard') && Request::get('class_id'))
        <div class="container-fluid">
            <h6 class="mb-4">Search result for "{{$subjects[0]->boards->exam_board}} Class {{$subjects[0]->assignClass->class}}"</h6>
        </div>
            
        @endif

        <div class="container-fluid">
            @if ($subjects->count() > 0)
                <div class="row">

                    @foreach ($subjects as $key => $subject)
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3 hover-effect-custom">
                            <div class="course-pic">
                                <img src="{{ asset($subject->image) }}" class="w100">
                                <div class="course-image-overlay">
                                    <a href="{{ route('website.subject.detatils', Crypt::encrypt($subject->id)) }}"
                                        class="btn btn-default course-image-overlay-eye-icon text-white">View</a>
                                    <!-- <i class="fa fa-eye course-image-overlay-eye-icon text-white"  aria-hidden="true"></i> -->
                                </div>
                            </div>
                            <div class="course-desc">
                                {{-- <span class="icon-clock-09 clock-icon"></span><span>{{ $item['duration'] }}</span> --}}
                                <h4 class="subject-heading-black mb-3">
                                    {{ $subject->subject_name }}
                                    (Class-{{ $subject->assignClass->class }}, {{ $subject->boards->exam_board }})
                                </h4>
                                <span>
                                    <h6><i class="fa fa-inr" aria-hidden="true"></i>
                                        {{ number_format($subject->subject_amount, 2, '.', '') }}
                                    </h6>
                                </span>
                                @if(auth()->check() && subjectAlreadyPurchase($subject->id)==1)
                                <a href="{{route('website.subject.detatils',Crypt::encrypt($subject->id))}}"
                                    class="enroll mb-2">Start Learning</a>
                               
                                @else
                                <a href="{{ route('website.course.package.enroll.all', Crypt::encrypt($subject->id)) }}"
                                    class="enroll mb-2">Enroll Now</a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <p class="knowledge-para">No record found!
                            </p>

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    <script>
        $(document).ready(function() {
            //  var requestData=@json(request()->all());
            //  var classId=requestData['class_id'];
            //     if(classId !=="undefined"){
            //         changeBoard(classId);
            //     }
            $("#board-class-dd").prop("disabled", true);
            $("#owl-demo").owlCarousel({

                autoPlay: 8000, //Set AutoPlay to 3 seconds

                items: 4,
                itemsDesktop: [1199, 3],
                itemsDesktopSmall: [979, 3]

            });

        });

        function changeBoard() {
            $("#board-class-dd").html('');
            let board_id = $("#assignedBoard").val();
            $.ajax({
                url: "{{ route('webboard.class') }}",
                type: "post",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'board_id': board_id
                },
                success: function(data) {
                    if (data.length > 0) {
                        $("#board-class-dd").prop("disabled", false);
                        $('#board-class-dd').html('<option value="">Select Class</option>');
                        data.forEach((boardClass) => {
                            $("#board-class-dd").append('<option value="' + boardClass
                                .id + '">' + 'Class-' + boardClass.class + '</option>');

                        });
                    } else {
                        $("#board-class-dd").prop("disabled", true);

                    }



                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Whoops! Something went wrong. Failed to fetch course');
                    }
                }
            });
        }
    </script>


@endsection
