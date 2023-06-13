<section class="knowledge-header-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5">
                <div class="knowledge-logo">
                    <a href="{{route('website.dashboard')}}">
                        <img src="{{asset('asset_website/img/home/logo_.png')}}" class="">
                    </a>
                </div>
            </div>
           
            <div class="col-lg-7">
                <ul class="list-inline knowledge-header-list" style="display: none;">
                    <li>
                        <a href="{{route('website.dashboard')}}">Home</a>
                    </li>
                    <li>
                        <input type="text" class="form-control" id="search" onkeyup="myFunction()" placeholder="Search Course">
                    </li>
                    {{-- <li>
                        <a data-toggle="modal" @guest data-target="#login-modal" @endguest  @auth data-target="#add-question-modal" @endauth class="add-post" style="cursor: pointer">Add Post</a>
                    </li> --}}
                </ul>
            </div>
          
        </div>
    </div>
</section>
