@php
use App\Models\Course;
use App\Common\Activation;

$course = Course::where('is_activate', Activation::Activate)->get();
@endphp

@extends('layout.admin.layout.admin')

@section('title','Gallery')

@section('head')

    <link rel="stylesheet"
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span> Edit Gallery
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.get.gallery') }}" class="btn btn-gradient-primary btn-fw">All Gallery</a>

            </li>
        </ul>
    </nav>
</div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Gallery</h4>
                <form class="forms-sample" id="bannerForm" action="{{ route('admin.creating.subject') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{\Crypt::encrypt($gallery->id)}}">
                    <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="banner_name" name="name" value="{{$gallery->name}}"
                            placeholder="Enter Gallery Name" required>
                            <span class="text-danger" id="name_error"></span>
                        </div>

                    <div class="form-group">
                        <label>File upload</label>
                        <input type="file" class="filepond" name="pic" id="banner_pic" data-max-file-size="1MB"
                            data-max-files="1" />
                            <span class="text-danger" id="pic_error"></span>
                    </div>

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
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>


    <script>
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
                    source: "{{ asset($gallery->gallery) }}",
                }]
            }
        );

        $("#bannerForm").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            $("#name_error").empty();
            $("#pic_error").empty();

            var formdata = new FormData(this);

            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }

            if(pondFiles.length == 0){
                toastr.error('Please add an image');
            }else{
                $.ajax({

                type: "POST",
                url: "{{ route('admin.editing.gallery') }}",
                data: formdata,
                processData: false,
                contentType: false,
                statusCode: {
                    422: function(data) {
                        var errors = $.parseJSON(data.responseText);

                        $.each(errors.errors, function(key, val) {
                            $("#" + key + "_error").text(val[0]);
                        });

                    },
                    200: function(data) {
                        // $('#bannerForm').trigger("reset");
                        toastr.success(data.message);
                        location.reload();

                        // alert('200 status code! success');
                    },
                    500: function() {
                        alert('500 someting went wrong');
                    }
                }
                });
            }
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
