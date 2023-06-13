@php
use App\Models\Subject;
use App\Common\Activation;

$subjects = Subject::where('is_activate', Activation::Activate)
->orderBy('id', 'DESC')
->get();

@endphp

@extends('layout.admin.layout.admin')

@section('title', 'Add Multiple Choice')

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add Multiple Choice Questions</h4>
            <form action="{{route('admin.insert.mcq.question')}}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(session('status'))
                <div class="alert alert-success">
                    {{session('status')}}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    {{session('error')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Select Set Name</label>
                            <select name="setName" id="setName" class="form-control" required>
                                <option value="" disabled selected>-- select --</option>
                                <option value="Set A">Set A</option>
                                <option value="Set B">Set B</option>
                                <option value="Set C">Set C</option>
                                <option value="Set D">Set D</option>
                                <option value="Set E">Set E</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Select Board<span class="text-danger">*</span></label>
                            <select name="board_id" id="assignedBoard" class="form-control" onchange="changeBoard()">
                                <option value="">-- Select -- </option>
                                @forelse ($boards as $item)
                                <option value="{{$item->id}}" @isset($lesson)@if($lesson->board_id==$item->id) selected
                                    @endif
                                    @endisset>{{$item->exam_board}}</option>
                                @empty
                                <option>No boards to show</option>
                                @endforelse
                            </select>
                            <span></span>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Select Class<span class="text-danger">*</span></label>
                            <select name="assign_class_id" id="board-class-dd" class="form-control">
                                <option value="">-- Select -- </option>

                            </select>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Select Subject<span class="text-danger">*</span></label>
                            <select name="assign_subject_id" id="board-subject-dd" class="form-control">
                                <option value="">-- Select -- </option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <p>
                        <span style="color:red;">Note <sup>*</sup></span> To upload questions, proper excel
                        format is required to avoid errors. Download the format by <a
                            href="{{asset('/files/mcq_format/Mcq_Upload_Format.xlsx')}}" download>Clicking Here
                            <i class="mdi mdi-cloud-download menu-icon"></i></a> &nbsp;and fillup the excel
                        sheet without removing the headers.
                    </p>
                </div>

                <div class="form-group">
                    <label for="">Upload questions in excel format</label>
                    <input type="file" name="questionExcel" class="form-control" required>
                </div>
                <button class="btn btn-primary">Submit</button>
            </form>

            @if (count($errors) > 0)
            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        @foreach($errors->all() as $error)
                        {{ $error }} <br>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    setTimeout(function () {
            $('.alert').slideUp();
        }, 3000);
        $('#assignedBoard').on('change', function () {
        let board_id = $("#assignedBoard").val();
        const url="{{route('board.class')}}";
        $.ajax({
            url: url,
            type: "POST",
            data: {
                '_token': "{{csrf_token()}}",
                'board_id': board_id
            },
            success: function (data) {

                $('#board-class-dd').html('<option value="">Select Class</option>');
                data.forEach((boardClass) => {
                    $("#board-class-dd").append('<option value="' + boardClass
                        .id + '">' + 'Class-' + boardClass.class + '</option>');
                });
                $('#board-subject-dd').html('<option value="">Select Subject</option>');


            },
            error: function (xhr, status, error) {
                if (xhr.status == 500 || xhr.status == 422) {
                    toastr.error('Whoops! Something went wrong. Failed to fetch course');
                }
            }
        });
    });
    $('#board-class-dd').on('change', function () {
        var classId = this.value;
        var boardId = $("#assignedBoard").val();
        const url="{{route('board.class.subject')}}";
        $("#board-subject-dd").html('');
        $.ajax({
            url: url,
            type: "POST",
            data: {
                class_id: classId,
                board_id: boardId,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function (data) {
                $('#board-subject-dd').html('<option value="">Select Subject</option>');
                data.forEach((subject) => {
                    $("#board-subject-dd").append('<option value="' + subject
                        .id + '">' + 'Subject-' + subject.subject_name + '</option>');
                });

            }
        });
    });

        // $(document).ready(function(){
        //     $('#addMoreMultipleChoice').click(function(e){
        //         e.preventDefault();
        //         let html = $(".copy").html();
        //         $(".after-add-more").append(html);
        //     });

        //     $("body").on("click","#removeMultipleChoice",function(){ 
        //         $(this).parents(".control-group").remove();
        //     });
        // });
        
</script>

@endsection