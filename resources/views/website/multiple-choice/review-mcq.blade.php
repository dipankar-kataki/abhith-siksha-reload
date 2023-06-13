@extends('layout.website.website')
@section('title','Review Multiple Choice Questions')
@section('head')
    <link href="{{ asset('asset_website/css/jquery.fancybox.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .subheader1-img{
            position: relative;
        }

        .fa-play-circle{
            font-size: 5rem;
            color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            transition: 0.2s all;
        }

        .fa-play-circle:hover{
            color: #111;
        }
    </style>
@endsection
@section('content')

    <h3>MCQ Result</h3>

@endsection