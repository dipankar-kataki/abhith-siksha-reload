@extends('layout.admin.layout.admin')

@section('title', 'Time Table')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-calendar-clock"></i>
            </span>Add Time Table
        </h3>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form id="addTimeTableForm">
                    @csrf
                    <div class="form-group">
                        <label for="exampleSelectGender">Boards</label>
                        <select class="form-control" name="board" id="selectBoard" required onchange="changeBoard()">
                            <option value="" disabled selected>-- Select Board --</option>
                            @foreach ($boards as $board)
                                <option value="{{ $board->id }}">{{ $board->exam_board }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger">
                            @error('board')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="exampleSelectGender">Class</label>
                        <select class="form-control" name="class" id="selectClass" required onchange="changeClass()">
                            <option value="" disabled selected>-- Select Class --</option>

                        </select>
                        <span class="text-danger">
                            @error('class')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="exampleSelectGender">Subject</label>
                        <select class="form-control" name="subject" id="selectSubject" required>
                            <option value="" disabled selected>-- Select Subject --</option>

                        </select>
                        <span class="text-danger">
                            @error('subject')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputCity1">Add Date</label>
                        <input type="text" class="form-control" name="add_date" id="add_date" autocomplete="off"
                            placeholder="Add Date" required>
                        <span class="text-danger">
                            @error('add_date')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Add Time</label>
                        <input type="text" class="form-control" name="add_time" id="add_time" autocomplete="off"
                            placeholder="Add Time" required>
                        <span class="text-danger">
                            @error('add_time')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Add Zoom Link</label>
                        <input type="url" class="form-control" name="zoom_link" id="add_zoom_link"
                            placeholder="e.g https://zoom.com/erjdknc22334455kdsl" required>
                        <span class="text-danger">
                            @error('zoom_link')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <button class="btn btn-primary" id="addTimeTable">Submit</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>

    <script>
        $(document).ready(function() {


            $('#addTimeTableForm').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#addTimeTable');
                btn.text('Please wait...');
                btn.attr('disabled', true);
                let formdata = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.save.time.table') }}",
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        btn.text('Submit');
                        btn.attr('disabled', false);
                        if (result.status == 1) {
                            toastr.success(result.message);
                            $('#addTimeTableForm')[0].reset();
                            location.reload(true);
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        btn.text('Submit');
                        btn.attr('disabled', false);
                        if (xhr.status == 500 || xhr.status == 422) {
                            toastr.error('Oops! Something went wrong');
                        }
                    }
                });
            });

        });

        function changeBoard() {
            let board_id = $("#selectBoard").val();
            $("#selectClass").html('');
            $.ajax({
                url: "{{ route('webboard.class') }}",
                type: "post",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'board_id': board_id
                },
                success: function(data) {

                    $("#selectBoard").prop("disabled", false);
                    $('#selectClass').html('<option value="">Select Class</option>');
                    data.forEach((boardClass) => {
                        $("#selectClass").append('<option value="' + boardClass
                            .id + '">' + 'Class-' + boardClass.class + '</option>');

                    });


                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Whoops! Something went wrong. Failed to fetch course');
                    }
                }
            });
        }

        function changeClass() {
            let board_id = $("#selectBoard").val();
            let class_id = $("#selectClass").val();
            $.ajax({
                url: "{{ route('find.subject') }}",
                type: "post",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'board_id': board_id,
                    'class_id': class_id
                },
                success: function(data) {

                    $("#selectBoard").prop("disabled", false);
                    $("#selectClass").prop("disabled", false);
                    $('#selectSubject').html('<option value="">Select Subject</option>');
                    if (data.code == "200") {
                        data.subjects.forEach((subject) => {
                            $("#selectSubject").append('<option value="' + subject
                                .id + '">' + subject.subject_name + '</option>');
                        });
                    } else {
                        $('#selectSubject').html('<option value="">Subject not available.</option>');
                    }




                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Whoops! Something went wrong. Failed to fetch course');
                    }
                }
            });
        }
        $("#add_date").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1990:+20',
            minDate: 0,
            // showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
        });

        $('#add_time').timepicker({});
    </script>


@endsection
