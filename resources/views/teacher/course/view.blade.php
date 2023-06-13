@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects-create')
@section('head')
<style>
    <style>.panel-default>.panel-heading {
        color: #333;
        background-color: #fff;
        border-color: #e4e5e7;
        padding: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .panel-default>.panel-heading a {
        display: block;
        padding: 10px 15px;
    }

    .panel-default>.panel-heading a:after {
        content: "";
        position: relative;
        top: 1px;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        float: right;
        transition: transform .25s linear;
        -webkit-transition: -webkit-transform .25s linear;
    }

    .panel-default>.panel-heading a[aria-expanded="true"] {
        background-color: #eee;
    }

    .panel-default>.panel-heading a[aria-expanded="true"]:after {
        content: "\2212";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-default>.panel-heading a[aria-expanded="false"]:after {
        content: "\002b";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .panel-default>.topic-panel-heading {
        position: relative;
        color: #333;
        background-color: #fff;
        border-color: #e4e5e7;
        left: 40px;
        width: 95%;
        padding: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .panel-default>.topic-panel-heading a {
        display: block;
        padding: 10px 15px;
    }

    .panel-default>.topic-panel-heading a:after {
        content: "";
        position: relative;
        top: 1px;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        float: right;
        transition: transform .25s linear;
        -webkit-transition: -webkit-transform .25s linear;
    }



    .panel-default>.topic-panel-heading a[aria-expanded="true"]:after {
        content: "\2212";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-default>.topic-panel-heading a[aria-expanded="false"]:after {
        content: "\002b";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    /* //subtopic */
    .panel-default>.subtopic-panel-heading {
        position: relative;
        color: #333;
        background-color: #fff;
        border-color: #e4e5e7;
        left: 55px;
        width: 93%;
        padding: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .panel-default>.subtopic-panel-heading a {
        display: block;
        padding: 10px 15px;
    }

    .panel-default>.subtopic-panel-heading a:after {
        content: "";
        position: relative;
        top: 1px;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        float: right;
        transition: transform .25s linear;
        -webkit-transition: -webkit-transform .25s linear;
    }



    .panel-default>.subtopic-panel-heading a[aria-expanded="true"]:after {
        content: "\2212";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-default>.subtopic-panel-heading a[aria-expanded="false"]:after {
        content: "\002b";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .subject-dropdown {
        position: absolute;
        top: -38px;
        right: 0px;
    }
    
</style>
@endsection
@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> All Subject/{{$subject->subject_name}}
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{route('teacher.course.details',Crypt::encrypt($subject->id))}}"
                    class="btn btn-gradient-primary btn-fw" data-backdrop="static" data-keyboard="false">
                    View Subject Details</a>
            </li>
        </ul>
    </nav>
</div>
<div class="card">
    <div class="card-body">
        <div class="row m-2">
            <div class="col-md-3 offset-md-3"></div>
            <div class="col-md-3 offset-md-3">
                <div class="dropdown subject-dropdown">
                    <button class="btn btn-gradient-primary btn-lg btn-icon-text dropdown-toggle" type="button"
                        id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{$subject->subject_name}}
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <button class="dropdown-item text-info" type="button">Total Content
                            {{subjectTotalResource($subject->id,"content")}}</button>
                        <button class="dropdown-item text-info" type="button">Total Images
                            {{subjectTotalResource($subject->id,"image")}}</button>
                        <button class="dropdown-item text-info" type="button">Total Videos
                            {{subjectTotalResource($subject->id,"video")}}</button>
                    </div>
                </div>
            </div>
        </div>
        <blockquote class="blockquote blockquote-primary m-2">
           @include('common.lesson.content')
        </blockquote>
    </div>

</div>

@endsection

@section('scripts')

@endsection