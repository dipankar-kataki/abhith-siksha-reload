@extends('layout.website.website')

@section('title','About')

@section('head')
@endsection

@section('content')
<section class="subheader">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p0">
                <div class="subheader-image"><img src="{{asset('asset_website/img/about/banner.png')}}" class="w100">
                </div>
                <div class="subheader-image-desc">
                    <h2 class="heading-black">Transform your life through<br>
                        <span class="heading-blue">Abhith Siksha</span>
                    </h2>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-us1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7 about-left1">
                <p class="cross-line">
                    <span>ABOUT US</span>
                </p>
                <h2 class="heading-black">Learning Beyond Limits</h2>
                <p class="aboutLeft-text">With a rapid increase in technology and anything being available at just a click away, people are
                    becoming more inclined towards modern technological gadgets. The digital urgency has proved to be a
                    boon for society as a whole.</p>
                <p class="aboutLeft-text">Learning has invariably expanded its horizons and has carved a niche for itself in the larger
                    scenario. The advent of a more learner-centric, skill-based approach has eventually led to the rise
                    of a platform for learners online. </p>
                <!-- <ul class="list-inline about-list">
                    <li>
                        <span class="icon-best-learning--09"></span>
                        <h3 class="small-heading-black">Best Learning Communities</h3>
                        <p>Abhith Siksha is an interactive learning platform created to improve student engagement and
                            teacher efficiency. Join millions of learners from around the world, find the right
                            instructor for you, choose from many topics, skill levels, and languages.</p>
                    </li>
                    <li>
                        <span class="icon-learn-online-09"></span>
                        <h3 class="small-heading-black">Learn Courses Online</h3>
                        <p>Unlimited access to a wide range of courses, specializations, and certifications. Earn
                            recognition for every learning program that you complete. </p> -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 pl-0">
                            <span class="icon-best-learning--09" style="font-size: 35px"></span>
                            <h3 class="about-small-heading-black">Best Learning Communities</h3>
                            <p class="aboutLeft-text">Abith Siksha is an interactive learning platform created to improve student engagement
                                and teacher efficiency. Join millions of learners from around the world, find the right
                                instructor for you, choose from many topics, skill levels, and languages.</p>
                        </div>

                        <div class="col-md-6 pl-0">
                            <span class="icon-learn-online-09" style="font-size: 35px"></span>
                            <h3 class="about-small-heading-black">Learn Courses Online</h3>
                            <p class="aboutLeft-text">Unlimited access to a wide range of courses, specializations, and certifications. Earn
                                recognition for every learning program that you complete. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 about-right1">
                <div class="about-us-img">
                    <img src="{{asset('asset_website/img/about/image.png')}}" class="w100">
                </div>
            </div>
        </div>
    </div>


</section>


<section class="counter">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p0">
                <ul class="list-inline counter-list">
                    <li>
                        <img src="{{asset('asset_website/img/about/image1.jpg')}}" class="w100" alt="">
                    </li>
                    <li>
                        <img src="{{asset('asset_website/img/about/image2.jpg')}}" class="w100" alt="">
                    </li>
                    <li>
                        <p class="cross-line3">
                            <span>Who We Are</span>
                        </p>
                        <h2 class="heading-white">Who We Are</h2>
                        <p class="about-content">Abhith Siksha is an incredibly personalized online learning platform for aspirants wanting to
                            take the next step towards enhancing personal and professional goals. </p>
                        <p class="about-content">We believe in breaking the barriers of traditional learning experience to a more interactive,
                            user-friendly approach thus redefining one's learning behavior for the learners to grow.
                        </p>
                        <p class="about-content">We partner with the best institutions to bring the best learning to every corner of the world
                            so that anyone anywhere can easily access the perks of an effective e-learning platform.
                        </p>
                        <p class="about-content">Abhith Siksha is an amalgamation of immensely talented teachers and aspiring learners under
                            one roof, thus providing an environment for an individual to grow leaps and bounds. </p>
                        <div id="projectFacts">
                            <div class="">
                                <div class="projectFactsWrap ">
                                    <div class="item" data-number="12">
                                        <span class="number-icon icon1"><i class="fa fa-plus"
                                                aria-hidden="true"></i></span>
                                        <p id="number1" class="number">3045</p>
                                        <p class="item-text">Student enroll</p>
                                    </div>
                                    <div class="item" data-number="55">
                                        <span class="number-icon icon2"><i class="fa fa-plus"
                                                aria-hidden="true"></i></span>
                                        <p id="number2" class="number">10690</p>
                                        <p class="item-text">Available Courses</p>
                                    </div>
                                    <div class="item" data-number="359">
                                        <span class="number-icon icon3"><i class="fa fa-plus"
                                                aria-hidden="true"></i></span>
                                        <p id="number3" class="number">8963</p>
                                        <p class="item-text">Available Courses</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>


<section class="moto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p0">
                <ul class="list-inline moto-list">
                    <li>
                        <div class="moto-image"><img src="{{asset('asset_website/img/about/mission.png')}}" class="w100"
                                alt=""></div>
                        <h2 class="heading-black">Mission</h2>
                        <p>We believe in expanding access to world-class learning for anyone, anywhere, with leading
                            universities to bring flexible, affordable online learning to individuals globally. </p>
                        <p>Learn and avail high-quality curriculum at affordable pricing and flexible scheduling. The
                            content would be streamed live. If you happen to miss out on some of the sessions online,
                            you can always go back and have a look at the recorded content available at any convenient
                            hour. </p>
                        <p>Master the art of productivity with comprehensive skills data, expand professional skills
                            with applied learning. </p>
                    </li>
                    <li>
                        <div class="moto-image"><img src="{{asset('asset_website/img/about/vision.png')}}" class="w100"
                                alt=""></div>
                        <h2 class="heading-black">Vision</h2>
                        <p>Technology integrates well-crafted content offering students the best learning experience.
                            The holistic learning approach has merged technology with the best practices like the use of
                            videos and engaging content, with the best teachers who provide assessments along with
                            recommendations, personalized to each student's learning style. </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $.fn.jQuerySimpleCounter = function(options) {
        var settings = $.extend({
            start: 0,
            end: 100,
            easing: 'swing',
            duration: 400,
            complete: ''
        }, options);

        var thisElement = $(this);

        $({
            count: settings.start
        }).animate({
            count: settings.end
        }, {
            duration: settings.duration,
            easing: settings.easing,
            step: function() {
                var mathCount = Math.ceil(this.count);
                thisElement.text(mathCount);
            },
            complete: settings.complete
        });
    };


    $('#number1').jQuerySimpleCounter({
        end: 3045,
        duration: 2000
    });
    $('#number2').jQuerySimpleCounter({
        end: 10690,
        duration: 2000
    });
    $('#number3').jQuerySimpleCounter({
        end: 8963,
        duration: 2000
    });
</script>
@endsection