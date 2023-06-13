@php
use App\Models\Subject;
use App\Common\Activation;

$subjects = Subject::where('is_activate', Activation::Activate)
    ->orderBy('id', 'DESC')
    ->get();

@endphp

@extends('layout.admin.layout.admin')

@section('title', 'Course')

@section('head')

    <script src="{{ asset('asset_admin/ckeditor/ckeditor.js') }}"></script>

    <link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
    <link rel="stylesheet"
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">

    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' rel='stylesheet'>


@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Course</h4>
                <form id="createCourse" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Name" required>
                        <span class="text-danger" id="name_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleSelectGender">Subjects</label>
                        <select class="form-control" name="subject_id" required>
                            <option value="" disabled selected>-- Select Subject --</option>
                            @foreach ($subjects as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="subject_id_error"></span>
                    </div>

                    <div class="form-group">
                        {{-- <label>File Upload</label> --}}
                        {{-- <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select File
                            <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="btn ml-3" id="uploadVideo">Video</a></li>
                                <li><a href="#" class="btn ml-3" id="uploadImage">Image</a></li>
                            </ul>
                        </div>
                        <div class="upload-image-div" style="margin-top:15px;display: none;">
                            <input type="file" class="filepond" name="pic" id="course_pic" data-max-file-size="1MB" data-max-files="1">
                        </div> --}}
                        {{-- <div class="video-upload-div" style="margin-top:15px;margin-bottom:15px;display: none;"> --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Upload Course Thumbnail <sup class="text-danger">*</sup></label>
                                    <input type="file" class="filepond" name="pic" id="course_pic" data-max-file-size="1MB" data-max-files="1">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Video File ( optional )</label>
                                    <div class="video-section">
                                        <input type="file" name="video" id="course_video" accept="video/mp4,video/x-m4v,video/*">
                                    </div>
                                </div>
                            </div>
                        {{-- </div> --}}
                        <span class="text-danger" id="pic_error"></span>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="exampleInputName1">Duration </label>
                                <input type="text" class="form-control" name="duration" placeholder="Enter Duration" pattern="^\d+$" title="Enter numbers only" required>
                                <span class="text-danger" id="duration_error"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputName1">Duration Type </label>
                                <select name="duration_type" id="duration_type" class="form-control" required> 
                                    <option  value = "" disabled selected>-- Select --</option>
                                    <option value="Days">Days</option>
                                    <option value="Hours">Hours</option>
                                    <option value="Weeks">Weeks</option>
                                    <option value="Months">Months</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Publish Date</label>
                        <input type="text" class="form-control" name="publish_date" id="publish_date" autocomplete="off"
                            placeholder="Publish Date">
                        <span class="text-danger" id="publish_date_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputCity1">Publish Time</label>
                        <input type="text" class="form-control" name="publish_time" id="publish_time" autocomplete="off"
                            placeholder="Publish Time">
                        <span class="text-danger" id="publish_time_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea1">Description</label>
                        <textarea class="form-control" id="editor" name="description" rows="4"></textarea>
                        <span class="text-danger" id="data_error"></span>

                    </div>


                    <button class="btn btn-primary" id="addCourseSubmitButton">Submit</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')


   
    


    <script>
        window.onload = function() {
            CKEDITOR.replace('editor', {
                height: 200,
                filebrowserUploadMethod: 'form',
                filebrowserUploadUrl: '{{ route('admin.course.upload', ['_token' => csrf_token()]) }}'
            });
        };
        FilePond.registerPlugin(

            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates the size of the file
            FilePondPluginFileValidateSize,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            // previews dropped images
            FilePondPluginImagePreview
        );

        // Select the file input and use create() to turn it into a pond
        pond = FilePond.create(
            document.getElementById('course_pic'), {
                allowMultiple: true,
                maxFiles: 5,
                instantUpload: false,
                imagePreviewHeight: 135,
                labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
                // files: [{
                //     source: "{{ asset('site/img/icons/check.png') }}",
                // }]
            }
        );



        $('#uploadVideo').on('click',function(e){

           $('.video-upload-div').css('display', 'block');
           $('.upload-image-div').css('display', 'none');
           pond.removeFile();

        });

        $('#uploadImage').on('click',function(){
            $('.upload-image-div').css('display', 'block');
            $('.video-upload-div').css('display', 'none');
            document.getElementById('course_video').value = '';
        });

        $("#createCourse").submit(function(e) {

            e.preventDefault(); 

            $("#name_error").empty();
            $("#subject_id_error").empty();
            $("#pic_error").empty();
            $("#publish_date_error").empty();
            $("#publish_time_error").empty();
            $("#data_error").empty();

            var formdata = new FormData(this);
            var data = CKEDITOR.instances.editor.document.getBody().getText();

            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }


            formdata.append('data', data);
            
            formdata.append('video',document.getElementById('course_video').files[0]);
            
       

            if( pondFiles.length == 0){
                toastr.error('Course thumbnail must be selected');
            }else if($('#duration_type').val() == null){
                toastr.error('Duration type is required');
            }else{
                
                $('#addCourseSubmitButton').text('Please wait');
                $('#addCourseSubmitButton').attr('disabled', true);

                let video = document.getElementById('course_video').value;
                let videoExtension = video.split('.').pop();
                if(videoExtension == 'jpg' || videoExtension == 'jpeg' || videoExtension == 'png'){
                    toastr.error('Please add video file only.');
                    $('#addCourseSubmitButton').text('Submit');
                    $('#addCourseSubmitButton').attr('disabled', false);
                }else{
                    $.ajax({

                        type: "POST",
                        url: "{{ route('admin.creating.course') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        // data: form.serialize(), // serializes the form's elements.
                        data: formdata,
                        processData: false,
                        contentType: false,
                        statusCode: {
                            422: function(data) {
                                var errors = $.parseJSON(data.responseText);
                                $.each(errors.errors, function(key, val) {
                                    $("#" + key + "_error").text(val[0]);
                                });
                                $('#addCourseSubmitButton').text('Submit');
                                $('#addCourseSubmitButton').attr('disabled', false);

                            },
                            200: function(data) {

                                console.log(data)
                                if (data.status == 2) {
                                    toastr["error"](data.error);
                                    $('#addCourseSubmitButton').text('Submit');
                                    $('#addCourseSubmitButton').attr('disabled', false);
                                    document.getElementById('course_video').value = '';
                                    pond.removeFile();

                                } else {
                                    toastr.success('Publish Successfull');
                                    $('#addCourseSubmitButton').text('Submit');
                                    $('#addCourseSubmitButton').attr('disabled', false);
                                    location.reload();

                                }
                            },
                            500: function() {
                                alert('500 someting went wrong');
                                $('#addCourseSubmitButton').text('Submit');
                                $('#addCourseSubmitButton').attr('disabled', false);
                                document.getElementById('course_video').value = '';
                                pond.removeFile();
                            }
                        }
                    });
                }
                
            }
            


        })

        $('#publish_time').clockpicker({
            autoclose: true,
            twelvehour: true,
        });
        $("#publish_date").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1990:+20',
            minDate: 0,
            // showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
        });


        /**************************** close-upload-image-modal **********************************/

        $('.close-upload-image-modal').on('click', function(e){
            e.preventDefault();
            pond.removeFile();
            $('#courseImageUploadModal').modal('hide');
        })







    </script>


@endsection
