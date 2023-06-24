<header id="header" class="fixed-top">
    <div class="container-fluid d-flex align-items-center p0">
        <h1 class="logo mr-auto">
            <a href="{{route('website.dashboard')}}"><img src="{{asset('asset_website/img/home/abhith-logo-reloaded-new.png')}}"></a>
        </h1>
        <nav class="nav-menu d-none d-lg-block">
            <ul>
                <li class="{{ Route::is('website.dashboard') ? 'active' : '' }}"><a href="{{route('website.dashboard')}}">Home</a></li>
                <li class="{{ Route::is('website.about') ? 'active' : '' }}"><a href="{{route('website.about')}}">About Us</a></li>
                <li class="{{ Route::is('website.course') || Route::is('website.course.details') ? 'active' : '' }}"><a href="{{route('website.course')}}">Courses</a></li>
                <li class="{{ Route::is('website.gallery') ? 'active' : '' }}"><a href="{{route('website.gallery')}}">Gallery</a></li>
                <li class="{{ Route::is('website.blog') || Route::is('website.blog.details') ? 'active' : '' }}"><a href="{{route('website.blog')}}">Blogs</a></li>
                <li class="{{ Route::is('website.knowledge.forum') ? 'active' : '' }}"><a href="{{route('website.knowledge.forum')}}">Knowledge Forum</a></li>
                @if(auth()->check())<li class="{{ Route::is('website.get.time.table') ? 'active' : '' }}"><a href="{{route('website.get.time.table')}}">Time Table</a></li>@endif
                <li class="{{ Route::is('website.contact') ? 'active' : '' }}"><a href="{{route('website.contact')}}" class="btn btn-block knowledge-link enquiry-form-btn about-view">Contact Us</a></li>

            </ul>

        </nav>
        <!-- .nav-menu -->


    </div>
</header>
