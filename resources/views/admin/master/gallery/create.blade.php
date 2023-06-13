@php
use App\Models\Course;
use App\Common\Activation;

$course = Course::where('is_activate', Activation::Activate)->get();
@endphp

@extends('layout.admin.layout.admin')

@section('title','Gallery')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span> Create Gallery
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
                <form class="forms-sample" id="bannerForm" action="{{ route('admin.creating.subject') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="banner_name" name="name"
                            placeholder="Enter Gallery Name">
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
            $("#pic_error").empty();

            var formdata = new FormData(this);

            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }


            $.ajax({

                type: "POST",
                url: "{{ route('admin.creating.gallery') }}",
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
