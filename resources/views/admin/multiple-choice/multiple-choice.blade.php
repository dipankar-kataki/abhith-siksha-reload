@extends('layout.admin.layout.admin')

@section('title','Multiple Choice Question')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span> Multiple Choice Question
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.add.multiple.choice') }}" class="btn btn-gradient-primary btn-fw">Add MCQ's</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Of Subjects Having MCQ's</h4>
                </p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th>Set Name</th>
                            <th>Board</th>
                            <th>Class</th>
                            <th> Subject </th>
                            <th> Active Status </th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($getMultipleChoice as $key => $mcq)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$mcq->set_name}}</td>
                                <td>{{$mcq->board->exam_board}}</td>
                                <td>{{$mcq->assignClass->class}}</td>
                                <td>{{$mcq->assignSubject->subject_name}}</td>
                                <td>
                                    @if ($mcq->is_activate == 1)
                                        <label class="switch">
                                            <input type="checkbox" id="mcqStatusUpdate" data-id="{{ $mcq->id }}" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    @else
                                        <label class="switch">
                                            <input type="checkbox" id="mcqStatusUpdate" data-id="{{ $mcq->id}}">
                                            <span class="slider round"></span>
                                        </label>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin.view.mcq.question',['id' => \Crypt::encrypt($mcq->id)])}}" data-toggle="tooltip" data-placement="top" title="View Mcq" class="btn mr-2 btn-gradient-primary btn-rounded btn-icon anchor_rounded"><i class="mdi mdi-eye-outline"></i></a>
                                </td>
                            </tr>
                       @empty
                           
                       @endforelse
                    </tbody>
                </table>
                <div style="float:right;margin-top:10px;">
                    {{$getMultipleChoice->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document.body).on('change', '#mcqStatusUpdate', function() {
            let status = $(this).prop('checked') == true ? 1 : 0;
            let mcq_id = $(this).data('id');
            let formData = {
                'mcq_id': mcq_id,
                'active': status
            }
            $.ajax({
                type: "post",

                url: "{{ route('admin.is.activate.multiple.choice') }}",
                data: formData,

                success: function(result) {
                    toastr.success(result.message);
                },
                error:function(xhr, status, error){
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Oops! Something went wrong while reporting.');
                    }
                }
            });
        });
    </script>


@endsection
