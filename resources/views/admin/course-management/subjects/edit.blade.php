@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects Edit')
@section('head')
    <link rel="stylesheet" href="{{ asset('asset_admin/css/lesson.css') }}">


    <style>
        .showImageDiv .showUploadedImage {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .showImageDiv {
            position: relative;
        }

        .clearImageBtn,
        .clearVideoThumbnailBtn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            background-color: #fff;
            border-radius: 50%;
        }

        .clearImageBtn:hover,
        .clearVideoThumbnailBtn:hover {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-bulletin-board"></i>
            </span> Edit Subject
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.course.management.subject.all') }}" class="btn btn-gradient-primary btn-fw"
                        data-backdrop="static" data-keyboard="false">All
                        Subject</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form enctype="multipart/form-data" id="addSubject" method="POST" action="javascript:void(0)">
                @csrf
                @include('admin.course-management.subjects.form')
                <p style="color: #fc0000;font-weight: 600;display:none;" id="file-upload-warning-text">Note: Please donot refresh the page or click any link during file upload. </p>
                <div class="progress upload-bar-display" style="height:30px;margin-bottom:20px;display:none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated upload-bar-percent" style="width:40%">
                        <h5 style="padding:5px;" id="upload-bar-text">40 % Uploaded</h5>
                    </div>
                </div>
                <div class="progress complete-upload-bar-display" style="height:30px;margin-bottom:20px;display:none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%; background-color: #33b20c;">
                        <h5 style="padding:5px;" id="complete-upload-bar-text">Upload Complete</h5>
                    </div>
                </div>
                <div style="float: right;">
                    <button type="reset" class="btn btn-gradient-light btn-fw" id="assignSubjectCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-md btn-success" id="assignSubjectSubmitBtn">Update</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.clearImageBtn').on('click', function(e) {
            e.preventDefault();
            $('#imageUpload').val('');
            $('#noCoverImage').text('No file chosen...');
            $(this).prev().attr('src', "{{ asset('files/subject/placeholder.jpg') }}");
        })

        $('.clearVideoThumbnailBtn').on('click', function(e) {
            e.preventDefault();
            $('#videoThumbnailImageUpload').val('');
            $('#noImageFilePromoVideo').text('No file chosen...');
            $(this).prev().attr('src', "{{ asset('files/subject/placeholder.jpg') }}");
        })
    </script>

    <script>
        CKEDITOR.replace('description', {
            toolbar: [{
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup'],
                    items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
                },
            ]
        });
    </script>
    <script>
        CKEDITOR.replace('why_learn', {
            toolbar: [{
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup'],
                    items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
                },
            ]
        });
    </script>
    <script>
        CKEDITOR.replace('requirements', {
            toolbar: [{
                    name: 'basicstyles',
                    groups: ['basicstyles', 'cleanup'],
                    items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
                },
            ]
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#addSubject').validate({
                rules: {
                    subjectName: {
                        required: true
                    },
                    assignedClass: {
                        required: true
                    },
                    subject_amount: {
                        required: true
                    }
                },
                messages: {
                    subjectName: {
                        required: "Please Enter Subjet Name ."
                    },
                    assignedClass: {
                        required: "Please Select Class."
                    },
                    subject_amount: {
                        required: "Subject Amount is required."
                    }
                },
                submitHandler: function() {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#assignSubjectSubmitBtn').html('Sending..');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    var data = new FormData(document.getElementById("addSubject"));

                    $.ajax({
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = Math.floor ( (evt.loaded / evt.total) * 100 );
                                    $('#file-upload-warning-text').css('display', 'block');
                                    $('.upload-bar-display').css('display', 'block');
                                    $('.upload-bar-percent').width(percentComplete + '%');
                                    $('#upload-bar-text').text(percentComplete + ' %' + ' Completed');
                                }
                            }, false);

                            xhr.addEventListener("loadend", function (evt) {
                                $('#file-upload-warning-text').css('display', 'none');
                                $('.upload-bar-display').css('display', 'none');
                                $('.complete-upload-bar-display').css('display', 'block');
                            }, false);
                            return xhr;
                        },
                        url: "{{ route('admin.course.management.subject.store') }}",
                        type: "POST",
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            toastr.options.timeOut = 3000;
                            if (response.status == 1) {

                                toastr.success(response.message);
                                $('#assignSubjectSubmitBtn').html('Submit');

                                location.reload();
                            }
                            if (response.status == 0) {
                                // $.each(response.message, function(prefix, val) {
                                //     toastr.error(val[0]);
                                // })
                                toastr.error(response.message);
                                $('#file-upload-warning-text').css('display', 'none');
                                $('.upload-bar-display').css('display', 'none');
                                $('.complete-upload-bar-display').css('display', 'none');
                                $('#assignSubjectSubmitBtn').html('Submit');
                            }

                        }
                    });
                }
            });
        });
        $('#assignSubjectCancelBtn').on('click', function() {
            window.location.reload();
            document.getElementById("addSubject").reset();
        });

        imageUpload.onchange = evt => {
            const [file] = imageUpload.files
            if (file) {
                blah.style.display = "block";
                blah.src = URL.createObjectURL(file);
                var input = evt.srcElement;
                $("#noCoverImage").html(input.files[0].name);
            }
        }
        videoThumbnailImageUpload.onchange = evt => {
            const [file] = videoThumbnailImageUpload.files
            if (file) {
                videothumbnailimagepreview.style.display = "block";
                videothumbnailimagepreview.src = URL.createObjectURL(file)
                var input = evt.srcElement;
                $("#noImageFilePromoVideo").html(input.files[0].name);
            }
        }
        videoUpload.onchange = evt => {
            videoPriview.style.display = "block";
            let file = evt.target.files[0];
            let blobURL = URL.createObjectURL(file);
            document.querySelector("video").src = blobURL;
            var input = evt.srcElement;
            $("#noFileVideo").html(input.files[0].name);
        }

        function changeBoard() {
            let board_id = $("#assignedBoard").val();
            $("#assignedClass").html('');
            $.ajax({
                url: "{{ route('webboard.class') }}",
                type: "post",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'board_id': board_id
                },
                success: function(data) {

                    $("#assignedBoard").prop("disabled", false);
                    $('#assignedClass').html('<option value="" selected disabled>Select Class</option>');
                    data.forEach((boardClass) => {
                        $("#assignedClass").append('<option value="' + boardClass
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
    </script>
@endsection
