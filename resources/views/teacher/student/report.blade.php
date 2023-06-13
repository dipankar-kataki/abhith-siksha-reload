@extends('layout.admin.layout.admin')
@section('title', 'Student Management')
@section('head')
@endsection
@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> Student Report
    </h3>

</div>

@include('common.lesson.all')


@endsection

<script>
    $(document).ready(function() {
        $('#lessonTable').DataTable({
            "processing": true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
