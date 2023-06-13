@extends('layout.website.website')

@section('title','Blogs')

@section('head')
<style>
    .add-blog-floating{
        position: fixed;
        bottom: 60px;
        right: 20px;
        z-index: 1000;
        height: 70px;
        width: 70px;
        border-radius: 50%;
        background-image: linear-gradient(to left, #076fef, #01b9f1);
        font-size: 14px;
        font-weight: 700;
        color: #fff !important;
        filter: drop-shadow(3px 5px 2px rgb(0 0 0 / 0.4));
        transition: 0.2s ease-in-out;
    }

    .add-blog-floating:hover{
        transform: translateY(2px);
        color: #fff;
    }
</style>
@endsection

@section('content')
{{-- Floating button --}}
@auth
    <a href="javascript:void(0)" class="btn add-blog-floating" data-toggle="modal" data-target="#websiteAddBlogModal"><p class="mt-2">Add Blog</p></a>    
@endauth
@guest
    <a href="javascript:void(0)" class="btn add-blog-floating" data-toggle="modal" data-target="#login-modal"><p class="mt-2">Add Blog</p></a>    
@endguest

    <section class="subheader">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 p0">
                    <div class="subheader-image"><img src="{{ asset('asset_website/img/blog/banner.png') }}"
                            class="w100"></div>
                    <div class="subheader-image-desc">
                        <h2 class="heading-black">Blog</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 p0">
                    <ul class="list-inline blog-list-div">
                        @foreach ($blogs as $item)
                            <li>
                                
                                <a href="{{route('website.blog.details',['id'=>\Crypt::encrypt($item->id)])}}">
                                   
                                    <div class="blog-box">
                                        <div class="blog-image"><img src="{{asset($item->blog_image)}}"
                                                class="w100"></div>
                                        <div class="blog-desc">
                                            <span class="icon-Calender-09 calendar-icon"></span><span>{{\Carbon\Carbon::parse($item->created_at)->format('F d, Y')}}</span>
                                            <div class="block-ellipsis1">
                                                <h4 class="small-heading-black">
                                                    {!! Illuminate\Support\Str::limit(strip_tags($item->name), $limit = 100, $end = '...') !!}</h4>
                                                    <span class="badge badge-success mb-3">{{ $item->category }}</span>
                                            </div>
                                            <div class="block-ellipsis2 ">
                                                {!! Illuminate\Support\Str::limit(strip_tags($item->blog), $limit = 100, $end = '...') !!}
                                                {{-- {!!$item->blog!!} --}}
                                                {{-- {{ \Illuminate\Support\Str::limit(strip_tags($item->blog), 100) }}
                                                {!! strlen(strip_tags($item->blog)) > 100 ? "<p class='mt-3'><b>ReadMore...</b></p>" : "" !!}  --}}
                                            </div>
                                            <div>
                                                <span class="mb0">- Anonymous</span>
                                                <h6 class="mt-2 ml-2">Read More</h6>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                
                            </li>
                        @endforeach
                    </ul>
                    <div style="float:right;margin-right:20px;">
                        {{$blogs->links()}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layout.website.include.modals')
@endsection

@section('scripts')
    @include('layout.website.include.modal_scripts')
@endsection
