@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects')
@section('subjectdetails')
<li class="nav-item">
    <a class="nav-link collapsed" data-toggle="collapse" href="#course-management" aria-expanded="false">
        <span class="menu-title">{{$subject->name}}</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-book menu-icon"></i>
    </a>
    <div class="collapse" id="course-management">
        <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{route('teacher.course')}}">Subjects</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{route('teacher.lesson')}}">Lesson</a></li>
        </ul>
    </div>
</li>
@endsection
@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> All Lesson
    </h3>
</div>
<div class="card">

    @foreach($all_lessons as $key=>$lesson)

    @include('common.lesson.index')
    @endforeach

</div>

@endsection

@section('scripts')

@endsection