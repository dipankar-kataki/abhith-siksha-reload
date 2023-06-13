@php
use App\Models\Course;
use App\Common\Activation;

$course = Course::where('is_activate', Activation::Activate)->get();
@endphp

@extends('layout.admin.layout.admin')

@section('title','Banner')

@section('head')
<script src="{{ asset('asset_admin/ckeditor/ckeditor.js') }}"></script>

<link rel="stylesheet"
    href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
<link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span> Create Banner
    </h3>
    <nav aria-label="breadcrumb p-2">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.get.banner') }}" class="btn btn-gradient-primary btn-fw">All Banners</a>
                {{-- <span></span>Overview <i
                    class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> --}}
            </li>
        </ul>
    </nav>
</div>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form class="forms-sample" id="bannerForm" action="{{ route('admin.creating.subject') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="exampleInputName1">Name</label>
                    <input type="text" class="form-control" id="banner_name" name="name" maxlength="20"
                        placeholder="Enter Banner Name">
                    <span class="text-muted" style="font-size:12px;">Maximum allowed characters 20.</span>
                    <span class="text-danger" id="name_error"></span>
                </div>

                <div class="form-group">
                    <label>File upload<span class="text-danger">*</span></label>
                    <input type="file" class="filepond" name="pic" id="banner_pic" data-max-file-size="1MB"
                        data-max-files="1" required />
                    <span class="text-danger" id="pic_error"></span>
                </div>

                <div class="form-group">
                    <label for="exampleTextarea1">Description</label>
                    <textarea class="form-control" id="" name="description" maxlength="80" rows="4"
                        placeholder="Describe banner here."></textarea>
                    <span class="text-muted" style="font-size:12px;">Maximum allowed characters 80.</span>
                    <span class="text-danger" id="description_error"></span>
                </div>

                {{-- <div class="form-group">
                    <label for="exampleSelectGender">Related to Course</label>
                    <select class="form-control" id="related_course" required>
                        <option value="" disabled selected>-- Select --</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="form-group" id="course_id">
                    <label for="exampleSelectGender">Course</label>
                    <select class="form-control" id="course_list" name="course_list">
                        <option value="" disabled selected> -- Select Course --</option>
                        @foreach ($course as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js">
</script>
<script
    src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js">
</script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>


<script>
    //  window.onload = function() {
        //     CKEDITOR.replace('editor', {
        //         height: 200,
        //         filebrowserUploadMethod: 'form',
        //         filebrowserUploadUrl: '{{ route('admin.course.upload', ['_token' => csrf_token()]) }}'
        //     });
        // };
        $('#course_id').hide();
        $("#course_list").prop('required',false);
        FilePond.registerPlugin(

            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates the size of the file
            FilePondPluginFileValidateSize,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            // previews dropped images
            FilePondPluginImagePreview,

            FilePondPluginFileValidateType
        );

        // Select the file input and use create() to turn it into a pond
        pond = FilePond.create(
            document.getElementById('banner_pic'), {
                allowMultiple: true,
                maxFiles: 5,
                instantUpload: false,
                imagePreviewHeight: 135,
                acceptedFileTypes: ['image/png', 'image/jpeg'],
                labelFileTypeNotAllowed:'File of invalid type. Acepted types are png and jpeg/jpg.',
                labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
                // files: [{
                //     source: "{{ asset('site/img/icons/check.png') }}",
                // }]
            }
        );

        $("#bannerForm").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            $("#name_error").empty();
            $("#description_error").empty();
            $("#pic_error").empty();

            var formdata = new FormData(this);
            // var data = CKEDITOR.instances.editor.getData();
            // formdata.append('description', data);

            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }


            $.ajax({

                type: "POST",
                url: "{{ route('admin.creating.banner') }}",
                // data: form.serialize(), // serializes the form's elements.
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
                        // console.log(data);
                        toastr.success(data.message);
                        toastr.options.timeOut = 3000;
                        location.reload();
                    },
                    500: function() {
                        alert('500 someting went wrong');
                    }
                }
            });


        })
        // $('.input-images').imageUploader();

        $("#related_course").change(function() {
            var value = this.value;
            if( this.value == 'yes'){
                $('#course_id').show();
                $("#course_list").prop('required',true);

            } else {
                $('#course_id').hide();
                $("#course_list").prop('required',false);
            }
            // var firstDropVal = $('#pick').val();
        });
</script>

@endsection