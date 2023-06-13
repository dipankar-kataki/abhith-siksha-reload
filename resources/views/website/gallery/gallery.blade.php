@extends('layout.website.website')

@section('title', 'Gallery')

@section('head')
<link href="{{ asset('asset_website/css/jquery.fancybox.css') }}" rel="stylesheet">
@endsection

@section('content')
<section class="cart">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="heading-black mb0">Gallery</h2>
            </div>
        </div>
    </div>
</section>


<section class="gallery-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p0">
                <div class="galleryWrap">
                    <ul class="list-inline gallery-list" id="portfoliolist">
                        @forelse ($gallery as $item)
                        <li class="portfolio">
                            <a href="{{asset($item->gallery) }}" class='portfolio- cwrapper fancybox imgContainer'
                                data-fancybox="images" data-caption="{{$item->name}}"
                                data-fancybox-group='image-gallery'>
                                <img src="{{ asset($item->gallery) }}" />
                            </a>
                        </li>
                        @empty
                      
                            <div class="text-center">
                                <p>Oops! No pictures found.</p>

                            </div>

                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
        <div class="pagination-gallery">
            {{ $gallery->links() }}
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('asset_website/js/jquery.fancybox.js') }}"></script>
<script type="text/javascript">
    $('[data-fancybox="images"]').fancybox({
            beforeShow : function(){
            this.title =  $(this.element).data("caption");
            },
            thumbs: {
                autoStart: true
            },
           
        });
        // $('[data-fancybox="images1"]').fancybox({
        //     beforeShow : function(){
        //         this.title =  $(this.element).data("caption");
        //     },
        //     thumbs: {
        //         autoStart: true
        //     }
        // });
</script>
@endsection