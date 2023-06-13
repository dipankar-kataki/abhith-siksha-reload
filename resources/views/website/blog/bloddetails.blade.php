@extends('layout.website.website')

@section('title', 'Blog')

@section('head')
<style>
    #blogDetailsDiv a{
        color: #1877f2;
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
                                <li class="breadcrumb-item"><a href="course.html">Blog</a></li>
                                {{-- <li class="breadcrumb-item active" aria-current="page">{{$blog->name}}</li> --}}
                            </ol>
                        </nav>
                        <div class="block-ellipsis3">
                            <h2 class="heading-white">{{$blog->name}}</h2>
                        </div>

                        {{-- <div>
                            <h6 class="mb0">Ramjan Ali Anik</h6>
                            <p class="mb0">Math Teacher</p>
                        </div> --}}

                    </div>
                </div>
                <div class="col-lg-6 p0">
                    <div class="subheader1-img">
                        <img src="{{asset($blog->blog_image)}}" class="w100">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="blog-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <span class="icon-Calender-09 calendar-icon"></span><span>{{\Carbon\Carbon::parse($blog->created_at)->format('F d, Y')}}</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        @auth
                        <a href="javascript:void(0);" data-id="{{$blog->id}}" data-toggle="modal" data-target="#ReportBlogModal" class="reportBlogModal" style="display:inline;font-size:12px;" >
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;  Report
                        </a>
                        @endauth
                        &nbsp;&nbsp;&nbsp;&nbsp;<a type="button" class="btn btn-default"  data-toggle="dropdown"> <span style="font-size: 15px;"><i class="fa fa-share" aria-hidden="true" ></i> &nbsp; Share</span></a>

                        <span class="badge badge-success mb-3">{{ $blog->category }}</span>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-item social-link-btn">
                                <a href="#" id="facebookLink" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true" style="color:#1877f2;font-size: 20px;margin-top:10px;"></i></a>&nbsp;&nbsp;&nbsp;
                                <a href="#" id="whatsappLink" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="color:#128c7e;font-size: 20px;margin-top:10px;"></i></a>&nbsp;&nbsp;&nbsp;
                                <a href="#" id="mailLink" target="_blank"><i class="fa fa-envelope" aria-hidden="true" style="color:#dd4b39;font-size: 20px;margin-top:10px;"></i></a>&nbsp;&nbsp;&nbsp;
                                <a href="#" id="redditLink" target="_blank"><i class="fa fa-reddit-square" aria-hidden="true" style="color:#ff4500;font-size: 20px;margin-top:10px;"></i></a>

                            </div>
                            {{-- <a href="javascript:void(0);" class="dropdown-item"  data-toggle="modal" data-target="#sharePostModal" style="font-size:12px;"><i class="fa fa-share" aria-hidden="true"></i> &nbsp; Share</a> --}}
                        </div>
                        </a>
                    <div id="blogDetailsDiv" class="mt-5">
                        {!!$blog->blog!!}
                    </div>
                </div>
            </div>

        </div>
    </section>
    @include('layout.website.include.modals')
@endsection

@section('scripts')
    @include('layout.website.include.modal_scripts')
    <script>
        const facebookBtn = document.getElementById('facebookLink');
        const whatsappBtn = document.getElementById('whatsappLink');
        const mailBtn = document.getElementById('mailLink');
        const redditBtn = document.getElementById('redditLink');

        let postUrl = encodeURI(document.location.href);
        let postTitle = encodeURI('{{$blog->name}}');

        facebookBtn.setAttribute('href', `https://www.facebook.com/sharer.php?u=${postUrl}`);
        whatsappBtn.setAttribute('href', `https://api.whatsapp.com/send?text=Post-Title: ${postTitle} ---- Post-Link:- ${postUrl}`);
        mailBtn.setAttribute('href',`https://mail.google.com/mail/?view=cm&su=Post Title: ${postTitle}&body=${postUrl}`);
        redditBtn.setAttribute('href',`https://reddit.com/submit?url=${postUrl}&title=${postTitle}`);

    </script>
@endsection
