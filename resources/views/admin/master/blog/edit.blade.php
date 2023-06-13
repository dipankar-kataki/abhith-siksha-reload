@extends('layout.admin.layout.admin')

@section('title', 'Blog')

@section('head')

<script src="{{asset('asset_admin/ckeditor/ckeditor.js')}}"></script>

    <link rel="stylesheet"
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span> Edit Blog
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.get.blog.by.id') }}" class="btn btn-gradient-primary btn-fw">All Blogs</a>

            </li>
        </ul>
    </nav>
</div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" id="bannerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{\Crypt::encrypt($blog->id)}}">
                    <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="100" value="{{$blog->name}}" placeholder="Enter Blog Name" required>
                        <span class="text-muted" style="font-size:12px;margin-top:5px;">Allowed characters 100.</span>

                        <span class="text-danger" id="name_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Select Category</label>
                        <select name="blog_category" id="blog_category" class="form-control" required>
                            <option value="{{$blog->category}}" selected> {{$blog->category}} </option>
                            <option value="Fashion">Fashion</option>
                            <option value="Food ">Food </option>
                            <option value="Travel">Travel</option>
                            <option value="Music">Music</option>
                            <option value="Lifestyle">Lifestyle</option>
                            <option value="Fitness">Fitness</option>
                            <option value="DIY">DIY</option>
                            <option value="Sports">Sports</option>
                            <option value="Movie">Movie</option>
                            <option value="Education">Education</option>
                            <option value="Technology">Technology</option>
                        </select>
                        <span class="text-danger" id="blog_category_error"></span>
                    </div>



                    <div class="form-group">
                        <label>Blog image upload</label>
                        <input type="file" class="filepond" name="pic" id="banner_pic" data-max-file-size="1MB"
                            data-max-files="1" required/>
                            <span class="text-danger" id="pic_error"></span>

                    </div>

                    <div class="form-group">
                        <label for="exampleTextarea1">Description</label>
                        <textarea class="form-control" id="editor" name="description" required>{{$blog->blog}}</textarea>
                        <span class="text-danger" id="data_error"></span>
                    </div>

                    <button type="submit" class="btn btn-gradient-primary mr-2  blog-form-submit">Submit</button>
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

    window.onload = function() {
            CKEDITOR.replace( 'editor', {
                height: 200,
                filebrowserUploadMethod: 'form',
                filebrowserUploadUrl: '{{ route('upload',['_token' => csrf_token() ]) }}',
                toolbarGroups: [
                    { name: 'links' },
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                 
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] },
	            ]
            });
	};

        FilePond.registerPlugin(

            FilePondPluginFileEncode,

            FilePondPluginFileValidateSize,

            FilePondPluginImageExifOrientation,

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
                acceptedFileTypes: ['image/*'],
                labelFileTypeNotAllowed:'Not a valid image.',
                labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
                files: [{
                    source: "{{ asset($blog->blog_image) }}",
                }]
            }
        );

        $("#bannerForm").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            $("#name_error").empty();
            $("#subject_id_error").empty();
            $("#pic_error").empty();



            var formdata = new FormData(this);

            var data = CKEDITOR.instances.editor.getData();

            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }

            formdata.append('data', data);

            if(pondFiles.length == 0){
                toastr.error('Blog image is required.');
            }else if(data.length == 0){
                toastr.error('Description is required.');
            }else if(pondFiles[0].status != 2){
                toastr.error('Not a valid image.');
            }else{

                $('.blog-form-submit').text('Please wait...');
                $('.blog-form-submit').attr('disabled',true);
                $.ajax({

                    type: "POST",
                    url: "{{ route('admin.editing.blog') }}",
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
                            $('.blog-form-submit').text('Submit');
                            $('.blog-form-submit').attr('disabled',false);
                            location.reload();

                            // alert('200 status code! success');
                        },
                        500: function() {
                            toastr.error('Oops! Something went wrong');
                            $('.blog-form-submit').text('Submit');
                            $('.blog-form-submit').attr('disabled',false);
                        }
                    }
                });
            }
        })
    </script>
@endsection
