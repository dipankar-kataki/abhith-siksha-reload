<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('asset_website/img/home/favicon.png')}}" rel="icon">
    <link href="{{asset('asset_website/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('asset_website/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset_website/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset_website/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset_website/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('asset_website/css/responsive.css')}}" rel="stylesheet">

    {{-- ============================ Added by Alok ============================ --}}
    <link href="{{asset('asset_website/css/style_new.css')}}" rel="stylesheet">
    <link href="{{asset('asset_website/css/responsive_new.css')}}" rel="stylesheet">
    {{-- ===================================================================== --}}

    <link href="{{asset('asset_website/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset_website/svg/icomoon/style.css')}}" rel="stylesheet">
    <link src="{{asset('asset_website/css/videojs.watermark.css')}}" rel="stylesheet">
    
    <link rel="stylesheet"
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">

    {{--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
    <link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" />
    <link href="https://unpkg.com/video.js@7/dist/video-js.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('head')

</head>
