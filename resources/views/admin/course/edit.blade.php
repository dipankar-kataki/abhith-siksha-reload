@php
use App\Models\Subject;
use App\Common\Activation;

$subjects = Subject::where('is_activate', Activation::Activate)
    ->orderBy('id', 'DESC')
    ->get();

@endphp

@extends('layout.admin.layout.admin')

@section('title','Course')

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
                <h4 class="card-title">Edit Course</h4>
                <form class="forms-sample" id="bannerForm" action="{{ route('admin.editing.course') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{\Crypt::encrypt($course->id)}}">
                    <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="banner_name" value="{{ $course->name}}" name="name"
                            placeholder="Enter Banner Name">
                    </div>

                    <div class="form-group">
                        <label for="exampleSelectGender">Subjects</label>
                        <select class="form-control" name="subject_id">
                            <option value="" disabled selected>-- Select Subject --</option>
                            @foreach ($subjects as $item)
                                <option value="{{ $item->id }}" @if ($course->subject_id == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>File upload</label>
                        <input type="file" class="filepond" name="pic" id="banner_pic" data-max-file-size="1MB"
                            data-max-files="1" />
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName1">Duration</label>
                        <input type="text" class="form-control" name="duration" placeholder="Enter Duration" value="{{$course->durations}}">
                        <span class="text-danger" id="duration_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputCity1">Publish Date</label>
                        <input type="text" class="form-control" name="publish_date" id="publish_date" autocomplete="off" value="{{\Carbon\Carbon::parse($course->publish_date)->format('Y-m-d')}}"
                            placeholder="Publish Date">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputCity1">Publish Time</label>
                        <input type="text" class="form-control" name="publish_time" id="publish_time" autocomplete="off" value="{{\Carbon\Carbon::parse($course->publish_date)->format('h:i A')}}"
                            placeholder="Publish Time">
                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea1">Description</label>
                        <textarea class="form-control" id="editor" name="description" rows="4">{{ $course->description}}</textarea>
                    </div>


                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">
    </script>
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>

    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js">
    </script>
    <script
        src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>


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
            document.getElementById('banner_pic'), {
                allowMultiple: true,
                maxFiles: 5,
                instantUpload: false,
                imagePreviewHeight: 135,
                labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
                files: [{
                    source: "{{ asset( $course->course_pic) }}",
                }]
            }
        );

        $("#bannerForm").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var formdata = new FormData(this);
            var data = CKEDITOR.instances.editor.getData();
            formdata.append('data', data);
            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }


            $.ajax({

                type: "POST",
                url: "{{route('admin.editing.course')}}",
                data: formdata,
                processData: false,
                contentType: false,
                statusCode: {
                    422: function(data) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "_error").empty();
                        });
                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "_error").text(val[0]);
                        });

                    },
                    200: function(data) {
                        toastr.success(data.message);
                        location.reload();

                    },
                    500: function() {
                        alert('500 someting went wrong');
                    }
                }
            });


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
    </script>

@endsection
