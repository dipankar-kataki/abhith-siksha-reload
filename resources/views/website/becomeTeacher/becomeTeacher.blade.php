@extends('layout.website.website')
@section('head')
    <style>
        #header{
            display:none;
        }
        .top-header .become-a-teacher-tag{
            display: none;
        }

        .top-header .header-cart-btn{
            display: none;
        }
        .card {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .accordion>.card:not(:last-of-type){
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }
        
        .card-header {
            background-color: none;
            border-bottom: none;
            background: transparent;
            padding: 0px;
        }
        .card-header p{
            color: black;
            font-weight: 600;
        }

        .form-control:focus,
        .btn.focus,
        .btn:focus {
            border-color: transparent;
        }
        #accordion{
            display: none;
        }

        @media (max-width: 767px){
            #header{
                display: block;
                top: 70px;
            }
            .mobile-nav-toggle {
                top: 94px;
            }
            .support-team{
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .support-team-icon1 img{
                width: 350px;
                height: 375px;
                text-align: center;
            }
            .support-team-icon2{
                display: none;
            }
            .fade img{
                width: inherit;
            }
            .leftBlock img, .centerBlock img, .rightBlock img{
                width: 75px;
            }
            #how-to-begin{
                display: none;
            }
            #accordion{
                display: block;
            }
            #accordion img{
                width: inherit;
            }
        }
        @media (min-width:768px) and (max-width:991px){
            #header{
                display: block;
                /* background: none; */
            }
            .login-details {
                float: none;
            }
            .support-team-icon1 img, .support-team-icon2 img {
                width: 250px;
                height: 275px;
            }
            .fade img{
                width: 100%;
            }
        }
    </style>
    
@endsection
@section('content')

    <section class="become-a-teacher">

        <!-- Hero Image -->
        <div class="hero-image-for-teacher">
            <img src="{{asset('asset_website/img/becomeTeacher/main-banner.png')}}" alt="">
            <div class="hero-image-girl">
                <img src="{{asset('asset_website/img/becomeTeacher/girl.png')}}" alt="">
            </div>
            <div class="hero-header">
                <h1>Come teach with us</h1>
                <p>Become an instructor and change <br> lives  — including your own</p>
                <div class="hero-header-btn">
                    <a href="{{route('teacher.login')}}" class="btn knowledge-link-1">Get Started</a>
                </div>
            </div>
            {{-- <div class="hero-header1">
                <h1 class="">Come teach with us</h1>
                <p>Become an instructor and change lives — including your own</p>
                <button class="btn btn-primary" type="button">Get Started</button>
            </div> --}}
        </div>

        <!-- Reasons -->
        <div class="container" id="reasons">
            <h1 class="heading-black text-center">So many reasons to start</h1>
            <div class="row py-4">
                <div class="col-md-4 text-center mb-2 leftBlock">
                    <img src="{{asset('asset_website/img/becomeTeacher/Teach_your_way.png')}}" alt="">
                    <h5 class="mt-3"><b>Teach your way</b></h5>
                    <p>Publish the course you want, in the way you want, and always have of control your own content.</p>
                </div>
                <div class="col-md-4 text-center mb-2 centerBlock">
                    <img src="{{asset('asset_website/img/becomeTeacher/Inspire_Learners.png')}}" alt="">
                    <h5 class="mt-3"><b>Inspire learners</b></h5>
                    <p>Teach what you know and help learners explore their interests, gain new skills, and advance their careers.</p>
                </div>
                <div class="col-md-4 text-center mb-2 rightBlock">
                    <img src="{{asset('asset_website/img/becomeTeacher/Get_Rewarded.png')}}" alt="">
                    <h5 class="mt-3"><b>Get rewarded</b></h5>
                    <p>Expand your professional network, build your expertise, and earn money on each paid enrollment.</p>
                </div>
            </div>
        </div>
        <!-- End Reasons -->
        
        <!-- How to Begin -->
        <div class="container mt-5" id="how-to-begin">
            <h2 class="heading-black text-center">How to begin</h2>
            <nav class="mt-5">
                <div class="nav" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active mr-2" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                        <h4>Curriculum</h4>
                    </a>
                    <a class="nav-item nav-link mr-2" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                        <h4>Record Your Video</h4>
                    </a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">
                        <h4>Launch course</h4>
                    </a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                <p>You start with your passion and knowledge. Then choose a promising topic with the help of our Marketplace Insights tool.</p>
                                <p>The way that you teach — what you bring to it — is up to you.</p>
                                <h5><b>How we help you</b></h5>
                                <p>We offer plenty of resources on how to create your first course. And, our instructor dashboard and curriculum pages help keep you organized.</p>
                            </div>
                            <div class="col-md-6 order-1 order-lg-2 order-md-2 order-sm-2">
                                <img src="{{asset('asset_website/img/becomeTeacher/plan-your-curriculum-v3.jpg')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                <p>Use basic tools like a smartphone or a DSLR camera. Add a good microphone and you’re ready to start.</p>
                                <p>If you don’t like being on camera, just capture your screen. Either way, we recommend two hours or more of video for a paid course.</p>
                                <h5><b>How we help you</b></h5>
                                <p>Our support team is available to help you throughout the process and provide feedback on test videos.</p>
                            </div>
                            <div class="col-md-6 order-1 order-lg-2 order-md-2 order-sm-2">
                                <img src="{{asset('asset_website/img/becomeTeacher/record-your-video-v3.jpg')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                <p>Gather your first ratings and reviews by promoting your course through social media and your professional networks.</p>
                                <p>Your course will be discoverable in our marketplace where you earn revenue from each paid enrollment.</p>
                                <h5><b>How we help you</b></h5>
                                <p>Our custom coupon tool lets you offer enrollment incentives while our global promotions drive traffic to courses. There’s even more opportunity for courses chosen for Udemy Business.</p>
                            </div>
                            <div class="col-md-6 order-1 order-lg-2 order-md-2 order-sm-2">
                                <img src="{{asset('asset_website/img/becomeTeacher/launch-your-course-v3.jpg')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Responsive view -->
            <div class="container mt-5" id="accordion">
                <h2 class="heading-black text-center">How to begin</h2>
                <div class="accordion">
                    <div class="card">
                      <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none;">
                            <div class="d-flex justify-content-between">
                                <p>Plan your Curriculum</p>
                                <p><i class="fa fa-plus"></i></p>
                            </div>
                        </button>
                        </h5>
                      </div>
                  
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                    <p>You start with your passion and knowledge. Then choose a promising topic with the help of our Marketplace Insights tool.</p>
                                    <p>The way that you teach — what you bring to it — is up to you.</p>
                                    <h5><b>How we help you</b></h5>
                                    <p>We offer plenty of resources on how to create your first course. And, our instructor dashboard and curriculum pages help keep you organized.</p>
                                </div>
                                <div class="col-md-6 order-1 order-lg-2 order-md-2 order-sm-2">
                                    <img src="{{asset('asset_website/img/becomeTeacher/plan-your-curriculum-v3.jpg')}}" alt="">
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
                                    <p>Record Your Video</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                            </button>
                        </h5>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                    <p>Use basic tools like a smartphone or a DSLR camera. Add a good microphone and you’re ready to start.</p>
                                    <p>If you don’t like being on camera, just capture your screen. Either way, we recommend two hours or more of video for a paid course.</p>
                                    <h5><b>How we help you</b></h5>
                                    <p>Our support team is available to help you throughout the process and provide feedback on test videos.</p>
                                </div>
                                <div class="col-md-6 order-1 order-lg-2 order-md-2 order-sm-2">
                                    <img src="{{asset('asset_website/img/becomeTeacher/record-your-video-v3.jpg')}}" alt="">
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
                                    <p>Launch Course</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                            </button>
                        </h5>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 order-2 order-lg-1 order-md-1 order-sm-1 mt-3">
                                    <p>Gather your first ratings and reviews by promoting your course through social media and your professional networks.</p>
                                    <p>Your course will be discoverable in our marketplace where you earn revenue from each paid enrollment.</p>
                                    <h5><b>How we help you</b></h5>
                                    <p>Our custom coupon tool lets you offer enrollment incentives while our global promotions drive traffic to courses. There’s even more opportunity for courses chosen for Udemy Business.</p>
                                </div>
                                <div class="col-md-6 order-1 order-lg-2 order-md-2 order-sm-2">
                                    <img src="{{asset('asset_website/img/becomeTeacher/launch-your-course-v3.jpg')}}" alt="">
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <!-- End Responsive view -->

        <!-- End How to Begin -->

        <!-- Support Team -->
        <div class="container-fluid mt-5">
            <div class="d-flex support-team">
            <div class="support-team-icon1">
                <img src="{{asset('asset_website/img/becomeTeacher/support-1-2x-v3.jpg')}}" alt="">
            </div>
            <div class="col-md-5 support-team-text text-center pt-4">
                <h2 class="heading-black">You won’t have to do it alone</h2>
                <p>Our <b>Instructor Support Team</b> is here to answer your questions and review your test video, while our <b>Teaching Center</b> gives you plenty of resources to help you through the process. Plus, get the support of experienced instructors in our <b>online community</b>.</p>
            </div>
            <div class="support-team-icon2">
                <img class="w-100" src="{{asset('asset_website/img/becomeTeacher/support-2-v3.jpg')}}" alt="">
            </div>
        </div>
        <!-- ENd Support Team -->

        <!-- Instructor Today -->
        <div class="col-md-12 text-center mt-5" id="instructor">
            <div class="py-5">
                <h2>Become an instructor today</h2>
                <p>Join one of the world’s largest online learning marketplaces.</p>
                <div class="teacher-btn">
                    <a href="{{route('teacher.login')}}" class="btn knowledge-link">Get Started</a>
                </div>
            </div>
        </div>
        <!-- ENd Instructor Today -->

    </section>

@endsection