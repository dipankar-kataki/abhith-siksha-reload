@extends('layout.website.website')

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">
<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
        width: 100%;
        padding-left: 0.5rem;
        border-left: 0.5rem solid #076fef;
    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        padding-left: 0.5rem;
        border-left: 0.5rem solid #076fef;
    }

    .container {
        padding: 2px 16px;
    }
</style>
@endsection

@section('content')
@include('layout.website.include.forum_header')
<section class="account-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h5>Multiple Choice Questions For Class {{$subject->assignClass->class}} {{$subject->subject_name}}
                    {{$subject->boards->exam_board}} Board
                </h5>
                @foreach($subject->sets as $key=>$set)
                <div class="card">
                    <div class="card-body">

                        <b>MCQ Set Name: {{$set->set_name}}</b><br>

                        <b>Total Question : {{$set->question->count()}}</b>
                        <span style="float:right"><a href="{{route('website.subject.mcqstart',Crypt::encrypt($set->id))}}" class="add-post"> Start Now</a></span>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection