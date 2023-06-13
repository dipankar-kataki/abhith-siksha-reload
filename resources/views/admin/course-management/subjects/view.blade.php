@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects')
@section('head')
<link rel="stylesheet" href="{{ asset('asset_admin/css/lesson.css') }}">

<style>
    p span.heading {
        display: inline-block;
        width: 150px !important;
        font-style: italic;
        font-weight: 600
    }
</style>
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


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <p>
                <span class="heading">Subject Name:</span>
                <span class="text">{{$subject->subject_name}}</span>
            </p>

            <p>
                <span class="heading">Board:</span>
                <span class="text">{{$subject->boards->exam_board}}</span>
            </p>

            <p>
                <span class="heading">Class:</span>
                <span class="text">{{$subject->assignClass->class}}</span>
            </p>

            <p>
                <span class="heading">Image:</span>
                <span class="text"><a target="_blank" href="{{asset($subject->image)}}">Click to view</a></span>
            </p>

            <p>
                <span class="heading">Thumbnail Image:</span>
                <span class="text">@if($subject->subjectAttachment)<a target="_blank"
                        href="{{asset($subject->subjectAttachment->video_thumbnail_image)}}">Click to view</a>
                    @else NA @endif</span>
            </p>

            <p>
                <span class="heading">Promo Video:</span>
                <span class="text">
                    @if($subject->subjectAttachment)<a target="_blank"
                        href="{{asset($subject->subjectAttachment->attachment_origin_url)}}">Click to view</a>
                    @else NA @endif</span>
            </p>

            <p>
                <span class="heading">Description:</span>
                <span class="text">{!!$subject->description!!}</span>
            </p>

            <p>
                <span class="heading">Why Learn:</span>
                <span class="text">{!!$subject->why_learn!!}</span>
            </p>

            <p>
                <span class="heading">Requirements:</span>
                <span class="text">{!!$subject->requirements??'NA'!!}</span>
            </p>
        </div>
    </div>
</div>



@include('admin.course-management.lesson.all')

@if($assignTeachers)
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">All Assigned Teacher</h4>
            <table class="table table-striped" id="teacherTable">
                <thead>
                    <tr>
                        <th>#No</th>
                        <th> Name </th>
                        <th> Assign Date </th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($assignTeachers as $key=>$assignTeacher)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$assignTeacher->user->userDetail->name}}</td>
                        <td>{{dateFormat($assignTeacher->created_at,"F j, Y, g:i a")}}</td>
                        <td>
                            @if ($assignTeacher->status == 1)
                            <label class="switch">
                                <input type="checkbox" id="teacherStatus" data-id="{{ $assignTeacher->id }}" checked>
                                <span class="slider round"></span>
                            </label>
                            @else
                            <label class="switch">
                                <input type="checkbox" id="teacherStatus" data-id="{{ $assignTeacher->id }}">
                                <span class="slider round"></span>
                            </label>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif




@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#lessonTable').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        });
        $('#teacherTable').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        });
        
    });
</script>
<script>
     $(document.body).on('change', '#teacherStatus', function() {
            let status = $(this).prop('checked') == true ? 1 : 0;
            let assign_id = $(this).data('id');
            let formData = {
                "assign_id": assign_id,
                "active": status,
                "_token": "{{ csrf_token() }}"
            }
            $.ajax({
                url: "{{ route('admin.course.management.assignteacher.update.status') }}",
                type: "POST",
                data: formData,
                success: function(data) {
                    if (data.status == 1) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Whoops! Something went wrong. Failed to update status.');
                    }
                }

            });

        });
</script>
@endsection