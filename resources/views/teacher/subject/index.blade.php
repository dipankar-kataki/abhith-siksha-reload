@extends('layout.admin.layout.admin')

@section('title', 'My Subjects')

@section('head')


@endsection

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">My Subjects</h4>

            <table class="table table-bordered" id="all-assign-subject">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Subject Name </th>
                        <th> Board </th>
                        <th> Class </th>
                        <th>Assign Date</th>
                        <th> Total Enroll Student(s) </th>
                        <th>Subject Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($all_subjects as $key=>$all_subject)
                    <tr>
                        <td> {{++$key}} </td>
                        <td> {{$all_subject->subject->subject_name}} </td>
                        <td>{{$all_subject->subject->boards->exam_board}}</td>
                        <td>Class - {{$all_subject->subject->assignClass->class}}</td>
                        <td>
                            {{dateFormat($all_subject->created_at,"F j, Y, g:i a")}}
                        </td>
                        <td>@if($all_subject->subject->assignOrder->count()==0)Not yet enrolled
                            @else
                            <a href="{{ route('teacher.subject.student', Crypt::encrypt($all_subject->subject->id)) }}">
                                {{ $all_subject->subject->assignOrder->count() }} student(s) Enrolled </a>

                            @endif
                        </td>
                        <td> @if($all_subject->subject->is_activate==1)Active @else InActive @endif </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#all-assign-subject').DataTable({
            "processing": true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection