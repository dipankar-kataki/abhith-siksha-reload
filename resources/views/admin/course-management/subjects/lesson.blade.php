@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects')
@section('head')
<link rel="stylesheet" href="{{ asset('asset_admin/css/lesson.css') }}">
@endsection
@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> Subject Details
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{route('admin.course.management.subject.all')}}" class="btn btn-gradient-primary btn-fw"
                    data-backdrop="static" data-keyboard="false">All
                    Subject</a>
            </li>
        </ul>
    </nav>
</div>


@include('admin.course-management.lesson.topic.all')





@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#lessonTable').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        });
    });
</script>
@endsection