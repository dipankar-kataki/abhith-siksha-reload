@php
    use App\Models\Gallery;
    use App\Common\Activation;
    $gallery = Gallery::where('is_activate',Activation::Activate)->take(4)->get();
@endphp

<footer>
    <div class="container-fluid">
        <div class="row footer-top">
            <div class="col-lg-3 col-6 p0">
                <div class="footer-logo">
                    <a href="{{route('website.dashboard')}}"><img src="{{asset('asset_website/img/home/abhith-reloaded.png')}}" ></a>
                </div>
            </div>
            <div class="col-lg-9 col-6 p0">
                <ul class="list-inline social-media mb0">
                    <li><a href="#"><span class="icon-facebook-07"></span></a></li>
                    <li><a href="#"><span class="icon-linkdin-07"></span></a></li>
                    <li><a href="#"><span class="icon-twitter-07"></span></a></li>
                    <li><a href="#"><span class="icon-instagram-07"></span></a></li>
                </ul>
            </div>
        </div>
        <div class="row footer-mid">
            <div class="col-lg-4 footer-first p0">
                <h5 class="small-heading-black">Contact us</h5>
                <p class="mb0">Latasil, Uzan Bazar, Guwahati,<br />
                    Assam 781001</p>
                <p class="mb0">example@gmail.com</p>
            </div>
            <div class="col-lg-4 footer-second p0">
                <h5 class="small-heading-black">Quick Links</h5>
                <ul class="list-inline footer-list mb0">
                    <li>
                        <ul class="mb0">
                            <li><a href="{{route('website.dashboard')}}">Home</a></li>
                            <li><a href="{{route('website.about')}}">About us</a></li>
                            <li><a href="{{route('website.course')}}">Courses</a></li>
                        </ul>
                    </li>
                    <li>
                        <ul class="mb0">
                            <li><a href="{{route('website.blog')}}">Blogs</a></li>
                            <li><a href="{{route('website.knowledge.forum')}}">Knowledge Forum</a></li>
                            <li><a href="{{route('website.contact')}}">Contact Us</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4 footer-third p0">
                <ul class="list-inline footer-image-list  mb0">
                    @foreach ($gallery as $item)
                        <li><img src="{{asset($item->gallery)}}" class="w100"></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row footer-mid">
            <div class="col-lg-7 p0 footer-mid-left">
                <ul class="list-inline footer-menu-list mb0">
                    <li><a href="{{route('website.faq')}}">FAQ</a></li>
                    <li><a href="{{route('website.privacy')}}">Privacy Policy </a></li>
                    <li><a href="{{route('website.terms')}}">Terms and Conditions</a></li>
                    <li><a href="{{route('website.refund')}}">Refund & Cancellation Policy</a></li>
                </ul>
            </div>
            <div class="col-lg-5 p0 footer-mid-right">
                <p class="mb0">&copy; 2021 Abhith Sikhsha, All Rights Reserved</p>
            </div>
        </div>
    </div>
</footer>
