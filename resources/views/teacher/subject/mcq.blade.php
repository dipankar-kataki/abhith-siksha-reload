@extends('layout.admin.layout.admin')

@section('title', 'MCQ All attempt')

@section('head')


@endsection

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">MCQ All attempt</h4>

            @include('common.lesson.mcqattempt')
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#lessonTableMcq').DataTable({
            "processing": true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection
