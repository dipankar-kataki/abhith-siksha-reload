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
        <h4 class="mb-3"><b>My Courses</b></h4>
        <div class="row">
            @foreach($subjects as $key=>$subject)
    
            <div class="col-md-4 mb-4">
                {{-- <div class="course-pic">
                    <img src="{{asset($subject->subject->image)}}" class="w100">
                </div>
                <div class="course-desc">
                    <h4 class="small-heading-black">{{$subject->subject->subject_name}}</h4>
                    Board:{{$order->board->exam_board}} Class:{{$order->assignClass->class}}<br>
                    <span>Created by : Demo Teacher</span><br>
                    <span></i>Total Lesson:
                        {{$subject->subject->lesson->count()??'NA'}}</span>
                    <a href="{{route('website.subject.detatils',Crypt::encrypt($subject->subject->id))}}" class="enroll">View Details</a>
                </div> --}}
                <div class="course-pic">
                    <img src="{{asset($subject->subject->image)}}" class="w100">
                </div>
                <div class="course-desc">
                    <h4 class="small-heading-black">{{$subject->subject->subject_name}}</h4>
                    <p>Board : {{$order->board->exam_board}} Class : {{$order->assignClass->class}}</p>
                    <p>Created by : Demo Teacher</p>
                    <p>Total Lesson : {{$subject->subject->lesson->count()??'NA'}}</p>
                    <a href="{{route('website.subject.detatils',Crypt::encrypt($subject->subject->id))}}" class="btn-sm enroll">View Details</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</section>

@endsection