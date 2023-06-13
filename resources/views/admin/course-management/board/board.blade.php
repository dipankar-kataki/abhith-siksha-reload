@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Board')

@section('head')
    <style>
        .actionBtn {
            width: 40px;
            aspect-ratio: 1/1;
            border-radius: 50%;
            background-color: #007BFF;
        }

        .actionBtn i {
            color: #fff;
        }
    </style>
@endsection

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-bulletin-board"></i>
            </span> All Exam Boards
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#" class="btn btn-gradient-primary btn-fw" data-toggle="modal"
                        data-target="#addExamBoardModal" data-backdrop="static" data-keyboard="false">Add Exam Board</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="exam_board_table" class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th> # </th>
                                <th> Exam Board </th>
                                <th> Logo </th>
                                <th> Created At </th>
                                <th> Status </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($board as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->exam_board }}</td>
                                    <td>
                                        @if ($item->logo != null)
                                            {{-- Logo --}}
                                            <img src="{{ asset($item->logo) }}" alt="">
                                        @else
                                            {{-- Default logo --}}
                                            <img src="{{ asset('asset_website/img/noimage.png') }}" alt="">
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if ($item->is_activate == 1)
                                            <label class="switch">
                                                <input type="checkbox" id="boardStatus" data-id="{{ $item->id }}"
                                                    checked>
                                                <span class="slider round"></span>
                                            </label>
                                        @else
                                            <label class="switch">
                                                <input type="checkbox" id="boardStatus" data-id="{{ $item->id }}">
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td><button class="btn btn-sm openEditModal actionBtn" data-board="{{ $item->exam_board }}"
                                            data-id="{{ Crypt::encrypt($item->id) }}"><i
                                                class="mdi mdi-grease-pencil"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="addExamBoardModal">
        <div class="modal-dialog">
            <div class="modal-content" style="padding:1.5rem;background-color:#fff;">
                <div class="modal-body">
                    <form id="addBoardForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">Add Exam Board</label>
                            <input type="text" name="examBoard" class="form-control" placeholder="e.g SEBA, ICSC, CBSE"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" name="examLogo" class="form-control" accept="image/png, image/jpeg" required>
                        </div>
                        <div style="float: right;">
                            <button type="button" class="btn btn-md btn-default" id="addBoardCancelBtn">Cancel</button>
                            <button type="submit" class="btn btn-md btn-success" id="addBoardSubmitBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Board</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="updateBoardForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="boardId" name="boardId" value="">
                        <div class="mb-3">
                            <label for="boardName">Board</label>
                            <input type="text" id="boardName" name="boardName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" name="examLogo" class="form-control" accept="image/png, image/jpeg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="updateBoardBtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // For datatable
        $(document).ready(function() {
            $('#exam_board_table').DataTable({
                "processing": true,
                "searching": false,
                "ordering": true
            });
        });

        //For modal cancel button
        $('#addBoardCancelBtn').on('click', function() {
            $('#addExamBoardModal').modal('hide');
            $('#addBoardForm')[0].reset();
        });

        //For adding a board
        $('#addBoardForm').on('submit', function(e) {
            e.preventDefault();

            let fileInputElement = $('#examLogo');

            $('#addBoardSubmitBtn').attr('disabled', true);
            $('#addBoardSubmitBtn').text('Please wait...');
            $('#addBoardCancelBtn').attr('disabled', true);

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.course.management.board.add') }}",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {

                    // if (data.error != null) {
                    //     $.each(data.error, function(key, val) {
                    //         toastr.error(val[0]);
                    //     });
                    //     $('#addBoardSubmitBtn').attr('disabled', false);
                    //     $('#addBoardSubmitBtn').text('Submit');
                    //     $('#addBoardCancelBtn').attr('disabled', false);
                    // }

                    if (data.status == 1) {
                        toastr.success(data.message);
                        $('#addExamBoardModal').modal('hide');
                        location.reload(true);
                    } else {
                        toastr.error(data.message);
                        $('#addBoardSubmitBtn').attr('disabled', false);
                        $('#addBoardSubmitBtn').text('Submit');
                        $('#addBoardCancelBtn').attr('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Whoops! Something went wrong.');
                    }
                    $('#addBoardSubmitBtn').attr('disabled', false);
                    $('#addBoardSubmitBtn').text('Submit');
                    $('#addBoardCancelBtn').attr('disabled', false);
                }

            });
        })

        //For active deactive board

        $(document.body).on('change', '#boardStatus', function() {
            let status = $(this).prop('checked') == true ? 1 : 0;
            let board_id = $(this).data('id');
            let formData = {
                "board_id": board_id,
                "active": status,
                "_token": "{{ csrf_token() }}"
            }
            $.ajax({
                url: "{{ route('admin.course.management.board.update.status') }}",
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

    <script>
        // Open edit modal
        $('.openEditModal').on('click', function() {
            $('#editModal').modal('show');
            $('#boardName').val($(this).data('board'));
            $('#boardId').val($(this).data('id'));
            // console.log($(this).data('board'));
        })
    </script>

    <script>
        // Submit update board
        $('#updateBoardForm').on('submit', function(e) {
            e.preventDefault();

            $('#updateBoardBtn').attr('disabled', true);
            $('#updateBoardBtn').text('Please wait...');

            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.course.management.board.update') }}",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    if (data.error != null) {
                        $.each(data.error, function(key, val) {
                            toastr.error(val[0]);
                        });
                        $('#addBoardSubmitBtn').attr('disabled', false);
                        $('#addBoardSubmitBtn').text('Submit');
                        $('#addBoardCancelBtn').attr('disabled', false);
                    }

                    if (data.status == 1) {
                        toastr.success(data.message);
                        $('#editModal').modal('hide');
                        location.reload(true);
                    } else {
                        toastr.error(data.message);
                        $('#updateBoardBtn').attr('disabled', false);
                        $('#updateBoardBtn').text('Submit');
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Whoops! Something went wrong.');
                    }
                    $('#updateBoardBtn').attr('disabled', false);
                    $('#updateBoardBtn').text('Submit');
                }

            });
        })
    </script>
@endsection
