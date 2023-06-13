@extends('layout.admin.layout.admin')

@section('title', 'Enrolled Students')

@section('head')
<style>
    @import url("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css");

    table {
        border: 1px solid #f3f3f3;
        border-radius: 10px;
        box-shadow: 0px 5px 5px #efecec;
    }

    th {
        border-top: 0px !important;
    }

    #enrolled_students_table_filter {
        margin-top: -30px;
    }
</style>

@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span>Registered Students
    </h3>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto;">
                <table id="enrolled_students_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th>Parent Name</th>
                            <th>Is above 18</th>
                            <th>Board</th>
                            <th>Class</th>
                            <th> Email </th>
                            <th> Phone number </th>
                            <th> Registration Date </th>
                        </tr>

                    </thead>
                    <tbody>
                        @forelse ($students as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->userDetail->name ?? 'NA' }}</td>
                            <td>{{ $user->userDetail->parent_name ?? 'NA' }}</td>
                            <td>@if($user->userDetail->is_above_eighteen==1)Yes @else No @endif</td>
                            <td>{{$user->userDetail->board->exam_board??'NA'}}</td>
                            <td>{{$user->userDetail->assignClass->class??'NA'}}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'NA' }}</td>
                            <td>{{ date('d-m-Y', strtotime($user->created_at)) }}</td>
                        </tr>
                        @empty
                        <div class="text-center">
                            <p>No Data Found</p>
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- <div style="float:right;margin-top:10px;">
                {{ $details->links() }}
            </div> --}}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
            $('#enrolled_students_table').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });
</script>
@endsection