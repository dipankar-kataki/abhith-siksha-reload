@extends('layout.admin.layout.admin')
@section('title', 'Student Management')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-bulletin-board"></i>
            </span> All Students
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <button onclick="window.history.back();" class="btn btn-gradient-primary btn-fw" data-toggle="modal"
                        data-target="#addExamBoardModal" data-backdrop="static" data-keyboard="false">Back</button>
                </li>
            </ul>
        </nav>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title"> SUBJECT : {{ $assign_orders[0]->subject->subject_name }}</h4>
                            <div class="table-responsive">
                                <table class="table" id="enroll-student">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Name </th>
                                            <th> Package Type</th>
                                            <th> Payment Date </th>
                                            <th> Price </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assign_orders as $key => $assign_order)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <b
                                                        style="text-transform: capitalize">{{ $assign_order->order->user->name }}</b>
                                                </td>
                                                <td>
                                                    @if ($assign_order->order->is_full_course_selected == 1)
                                                        <label class="badge badge-success">Full Package</label>
                                                    @else
                                                        <label class="badge badge-warning"> Customized Package </label>
                                                    @endif
                                                </td>

                                                <td>{{ dateFormat($assign_order->order->created_at, 'F j, Y, g:i a') }}
                                                </td>
                                                <td> Rs. {{ number_format((float) $assign_order->amount, 2, '.', '') }}
                                                </td>
                                                <td>
                                                    @if (auth()->user()->hasRole('Teacher'))
                                                        <a
                                                            href="{{ route('teacher.subject.student.report', [Crypt::encrypt($assign_orders[0]->subject->id), Crypt::encrypt($assign_order->order->user->id)]) }}"><label
                                                                class="badge badge-info"> PROGRESS REPORT <i
                                                                    class="mdi mdi-eye"></i> </label></a>
                                                    @endif
                                                    @if (auth()->user()->hasRole('Admin'))
                                                        <a
                                                            href="{{ route('admin.subject.student.report', [Crypt::encrypt($assign_orders[0]->subject->id), Crypt::encrypt($assign_order->order->user->id)]) }}"><label
                                                                class="badge badge-info"> PROGRESS REPORT <i
                                                                    class="mdi mdi-eye"></i> </label></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#enroll-student').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endsection
