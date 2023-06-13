@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-bulletin-board"></i>
            </span> All Subject
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('teacher.course.create')}}" class="btn btn-gradient-primary btn-fw"  data-backdrop="static" data-keyboard="false">New Course</a>
                </li>
            </ul>
        </nav>
    </div>
    @include('common.course.index')
    
@endsection

@section('scripts')
   
@endsection