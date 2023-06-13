<div class="container-fluid" id="detailsPage">
    <p class="cross-line">
    <h2 class="heading-black mx-2">{{$subject->subject_name}}</h2>
    </p>
    <div class="row">
        <div class="col-lg-8 col-md-12 courseLeftBox order-2 order-lg-1 order-md-2 order-sm-1">

            {{-- <p class="cross-line">
                <span>Description</span>
            </p>
            <p class="cross-line">
                {!!$subject->description!!}
            </p>
            <p class="cross-line">
                <span>{{$subject->subject_name}} All Content</span>
            </p>
            @include('common.lesson.content') --}}

            <div class="col-lg-12 col-md-8 mt-3">
                <div class="board-class-div d-flex">
                    <div class="mr-5">
                        <p>Board</p>
                        <p>Class</p>
                    </div>
                    <div class="mr-5">
                        <h5 class="mb-3">{{$subject->boards->exam_board??'NA'}}</h5>
                        <h5>&nbsp;{{$subject->assignClass->class??'NA'}}</h5>
                    </div>
                    @if($total_review!=0)
                    @php $rating= round($rating_average); @endphp
                    <div>
                        <h5>Rating({{$rating}})</h5>
                        <p>
                            @for ($i = 0; $i < $rating; $i++) <i class="fa fa-star"></i> @endfor
                                &nbsp;
                                <span> reviews</span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="description">
                <nav class="mt-4">
                    <div class="nav" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active mr-4" id="nav-home-tab" data-toggle="tab" href="#nav-home"
                            role="tab" aria-controls="nav-home" aria-selected="true">
                            <h4>Overview</h4>
                        </a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact"
                            role="tab" aria-controls="nav-contact" aria-selected="false">
                            <h4>Reviews</h4>
                        </a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="container">
                            <div class="row">
                                <div class="mt-5">
                                    <h4>Subject Description</h4>
                                    {!!$subject->description!!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        @if(auth()->check() && (subjectAlreadyPurchase($subject->id)==1))
                        <div class="container p-2">

                            <div class="form-group">
                                <textarea name="review" placeholder="write your review here" class="form-control"
                                    id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                            <button class="btn btn-success btn-lg btn-block">Post Review</button>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            <!-- Responsive view -->
            <div class="container mt-5" id="description-accordion">
                <div class="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"
                                    style="text-decoration: none;">
                                    <div class="d-flex justify-content-between">
                                        <p>Overview</p>
                                        <p><i class="fa fa-plus"></i></p>
                                    </div>
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#description-accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                        <h4>Subject Description</h4>
                                        <p>{{!!$subject->description!!}}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo" style="text-decoration: none;">
                                    <div class="d-flex justify-content-between">
                                        <p>Curriculum</p>
                                        <p><i class="fa fa-plus"></i></p>
                                    </div>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                            data-parent="#description-accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                        <h4>Course Description</h4>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat architecto
                                            expedita ratione itaque vero reiciendis odit perspiciatis possimus beatae?
                                            Consectetur cupiditate nesciunt nulla quod vero dolorem explicabo, eos
                                            sapiente quibusdam.</p>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius sapiente
                                            voluptas perferendis nemo repellat necessitatibus id, eum, in explicabo ipsa
                                            velit. Ratione, quos! Veniam cumque perspiciatis harum placeat, nemo ab.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree" style="text-decoration: none;">
                                    <div class="d-flex justify-content-between">
                                        <p>Reviews</p>
                                        <p><i class="fa fa-plus"></i></p>
                                    </div>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#description-accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                        <h4>Subject Description</h4>
                                        <p>{{$subject->description}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Responsive view -->

            <!-- What u'll learn -->
            <div class="mt-5" id="learning">
                <h4>What you'll learn</h4>
                <div class="d-flex mt-4">
                    <div>
                        {!!$subject->why_learn!!}
                    </div>


                </div>
            </div>
            <!-- End What u'll learn -->

            <!-- Requirements -->
            <div class="mt-5" id="requirements">
                <h4>Requirements</h4>
                <div class="mt-3">

                    {!!$subject->requirements!!}


                </div>
            </div>
            <!-- End Requirements -->
        </div>

        <div class="col-lg-4 col-md-12 courseRightBlock order-1 order-lg-2 order-md-1 order-sm-2">
            <div style="box-shadow: 0px 6px 10px #d1d1d1;">
                @if($subject->subjectAttachment->attachment_origin_url!=null)
                <video id="player" class="video-js vjs-big-play-centered" controls preload="auto"
                    poster="{{asset($subject->subjectAttachment->video_thumbnail_image)}}" data-setup="{}">
                    <source src="{{asset($subject->subjectAttachment->attachment_origin_url)}}" type="video/mp4"
                        class="w100" />
                </video>
                @else
                <img src="{{asset($subject->image)}}" class="w100">
                @endif
                <div class="course-desc1">
                    <h4 class="small-heading-black">
                        <span class="d-flex  course-header-and-back-to-pckg-btn">
                            {{$subject->subject_name}}
                            @guest
                            <a href="#">
                                <i class="fa fa-reply"></i> &nbsp;Package
                            </a>
                            @endguest
                        </span>
                    </h4>
                    {{-- <span>Created by : Demo Teacher</span><br> --}}
                    {{-- <span></i>Total Lesson: {{$subject->lesson->count()}}</span> --}}

                    {{-- <a
                        href="{{route('website.user.lesson',[Crypt::encrypt($order->id),Crypt::encrypt($subject->id)])}}"
                        class="enroll">View Details</a> --}}

                    @if(auth()->check() && (subjectAlreadyPurchase($subject->id)==1))
                    <a href="{{route('website.course.package.subject.detatils',Crypt::encrypt($subject->id))}}"
                        target="_blank"
                        class="btn btn-primary btn-lg btn-block mt-2 course-details-start-course-btn">Start Your
                        Course</a>
                    @else
                    <div class="d-flex justify-content-between align-items-center mx-4"
                        style="margin-bottom: -15px; margin-top:15px">
                        <p>
                            <span style="font-weight:700; font-size: 18px"><i
                                    class="fa fa-inr mr-1"></i>{{$subject->subject_amount}}</span> &nbsp;
                            @php $original_amount=$subject->subject_amount+($subject->subject_amount*40/100) @endphp
                            <s style="color: grey"><i class="fa fa-inr mr-1"
                                    aria-hidden="true"></i>{{$original_amount}}</s>
                        </p>
                        <p class="discount-percentage">40% Off</p>
                    </div>
                    <span style="font-size: 16px; color: red; padding-bottom:10px"><i class="fa fa-clock-o mr-1"></i> 2
                        days left at this price!</span>
                    <div class="d-flex card-button mb-2 mx-4">
                        <a href="{{route('website.course.package.enroll.all',Crypt::encrypt($subject->id))}}"
                            class="btn btn-success btn-lg btn-block mt-2 course-details-add-to-cart-btn">
                            <i class="fa fa-shopping-cart"></i> &nbsp; Add to cart</a>
                        <a href="{{route('website.course.package.enroll.all',Crypt::encrypt($subject->id))}}"
                            class="btn btn-primary btn-lg btn-block mt-3 mb-3">Buy it Now</a>
                    </div>
                    <div class="details-bottom d-flex justify-content-between mx-4">
                        <p class="details-bottom-text">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp; Duration
                        </p>
                        <p>60 Minutes</p>
                    </div>
                    <div class="details-bottom d-flex justify-content-between mx-4">
                        <p class="details-bottom-text">
                            <i class="fa fa-book" aria-hidden="true"></i> &nbsp; Lesson
                        </p>
                        <p>{{$subject->lesson->count()}}</p>
                    </div>
                    <div class="details-bottom d-flex justify-content-between mx-4">
                        <p class="details-bottom-text">
                            <i class="fa fa-user" aria-hidden="true"></i> &nbsp; Enrolled by
                        </p>
                        <p>@if($subject->assignOrder->count()==0) Not Yet Enrolled
                            @else
                            {{$subject->assignOrder->count()}} students </a>
                            @endif
                        </p>
                    </div>
                    <div class="details-bottom d-flex justify-content-between mx-4">
                        <p class="details-bottom-text">
                            <i class="fa fa-language" aria-hidden="true"></i> &nbsp; Language
                        </p>
                        <p>English</p>
                    </div>
                    <div class="details-bottom d-flex justify-content-between mx-4">
                        <p class="details-bottom-text">
                            <i class="fa fa-certificate" aria-hidden="true"></i> &nbsp; Certificate
                        </p>
                        <p>Yes</p>
                    </div>
                    <div class="text-center pb-3">
                        <a href="#" target="_blank">
                            <i class="fa fa-share-alt" aria-hidden="true"></i> &nbsp; Share this Course</a>
                    </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Lessons -->
<div class="container-fluid mt-4" id="lesson">
    <div class="row">
        <div class="col-lg-8 col-md-12">

            {{-- <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h5 class="panel-title">
                            <a href="{{route('website.subject.mcq',Crypt::encrypt($subject->id))}}" role="button"
                                data-toggle="collapse" data-parent="#accordion" href="#collapseLesson"
                                aria-expanded="true" aria-controls="collapseLesson">
                                <i class="more-less glyphicon glyphicon-plus"></i>
                                ALL MCQ'S QUESTION SET
                            </a>
                        </h5>
                    </div>
                    <div id="collapseLesson" class="panel-collapse collapse show" role="tabpanel"
                        aria-labelledby="headingOne">
                        <div class="panel-body" style="position:relative; left:40px;">
                            @foreach($subject->sets as $key=>$set)
                            <i class="fa fa-file" aria-hidden="true"></i> &nbsp; {{$set->set_name}} <span
                                class="badge badge-info">Total Questions:
                                {{$set->question->where('is_activate',1)->count()}}</span>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div><!-- panel-group --> --}}
            <p>Subject content</p>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    @foreach($subject->lesson as $key=>$lesson)
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseOne{{$key}}" aria-expanded="true" aria-controls="collapseOne"
                                style="text-decoration: none">
                                <div class="ml-3 pt-3">
                                    <p><i class="fa fa-plus"></i> &nbsp;{{$lesson->name}}</p>
                                </div>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne{{$key}}" @if($key==0) class="collapse show" @else class="collapse" @endif
                        aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            @foreach($lesson->topics as $topic)
                            <div class="lesson-content d-flex mx-3">
                                <p> @if($topic->type==1) <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    @elseif($topic->type==2)<i class="fa fa-play-circle"></i>@else<i
                                        class="fa fa-book"></i>@endif &nbsp; {{$topic->name}}</p>
                                <div class="d-flex course-duration-div">

                                    @if($topic->type==2 && $topic->preview==1 ) <p data-toggle="modal"
                                        data-target="#exampleModalLong" data-lesson="{{$topic->id}}" id="previewVideo">
                                        preview
                                    </p>
                                    <p> {{gmdate("H:i:s", $topic->lessonAttachment->video_duration)}}
                                    </p> @endif
                                    @if($topic->type==2) @if($topic->preview==1)<i class="fa fa-play mt-2"></i>@endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Lessons -->

{{-- <div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-7">
            <ul class="nav nav-tabs">
                <!-- <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#teacher">
                        <h4 class="small-heading-black">Teacher</h4>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#whylearn">
                        <h4 class="small-heading-black">What you'll learn</h4>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content mt-3">
                <div class="tab-pane container fade show active" id="whylearn">
                    <h6> {!!$subject->why_learn!!} </h6>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Student Feedback -->
@if($reviews)
<div class="container-fluid mt-5" id="student-feedback">
    <div class="row">
        <div class="col-lg-8 col-md-12">

            <h4>Students Reviews</h4>

            @foreach($reviews as $key=>$review)
            <div class="d-md-flex mt-4">
                <div class="studentImageBox mr-4">
                    <img src="{{asset($review->user->userDetail->image)}}" alt="" style="height: 50px;width:50px;" />
                </div>
                <div class="studentReviewBox">
                    <div class="d-flex justify-content-between mt-2">
                        <div class="studentName">
                            <h5>{{$review->user->userDetail->name}}</h5>
                        </div>
                        <div class="studentrating">
                            @for($i=0;$i<$review->rating;$i++)
                                <i class="fa fa-star"></i>
                                @endfor
                        </div>
                    </div>
                    <div class="studentReview">
                        <p>
                            {{$review->review??'No review found'}}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@else
<div class="container-fluid mt-5" id="student-feedback">
    <div class="row">
        <div class="col-lg-8 col-md-12">

            <h4>Students Reviews</h4>

            <div class="d-md-flex mt-4">
                <p>No review found</p>
            </div>

        </div>
    </div>
</div>
@endif
<!-- End Student Feedback -->
{{-- demo vidio display subject --}}
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="box-shadow: 0px 6px 10px #d1d1d1;">

                <span class="demoVideoPlayer"></span>


                <div class="course-desc1">
                    <h4 class="small-heading-black">
                        <span class="d-flex  course-header-and-back-to-pckg-btn">
                            {{$subject->subject_name}}
                        </span>
                    </h4>
                </div>

                <span class="demoVideo"></span>
                {{-- <div class="details-bottom d-flex justify-content-between mx-4">
                    <p class="details-bottom-text">
                        <i class="fa fa-book" aria-hidden="true"></i> &nbsp; Lesson
                    </p>
                    <p>{{$subject->lesson->count()}}</p>
                </div> --}}
            </div>


        </div>

    </div>
</div>
</div>