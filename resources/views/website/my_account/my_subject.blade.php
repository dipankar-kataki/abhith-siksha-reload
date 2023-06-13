@extends('layout.website.website')

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">
<style>
.subjectimg{
    height: 150px;
    width: 100%;
}
</style>

@endsection

@section('content')
@include('layout.website.include.forum_header')
<section class="account-section">
    <div class="container-fluid mt-2">
        <div class="row">
            @foreach($subjects as $key=>$subject)
            <div class="col-md-3 col-sm-6 item">
                <div class="card item-card-box card-block">
                    <h6 class="card-title text-right see-more">See More</h6>
                    <img src="{{asset($subject->image)}}" alt="Photo of subject" class="subjectimg">
                    <h4 class="item-card-title mt-3 mb-3 see-more">
                        <a href="{{route('website.user.lesson',[$order->id,$subject->id])}}">
                            {{$subject->subject_name}}</a><br>
                            Board:{{$order->board->exam_board}} Class:{{$order->assignClass->class}}
                    </h4>
                    <p class="card-text">
                        <span class="badge badge-primary my-badges" style="float:left;">Total Lesson:
                            {{$subject->lesson->count()}}</span>
                        <span class="badge badge-primary my-badges" style="float:right;">Total
                            Topic:{{$subject->lesson->count()}}</span>
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</section>

@endsection