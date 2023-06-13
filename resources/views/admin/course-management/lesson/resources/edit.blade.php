@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Lesson')
@section('form-label','Topic')
@section('content')
<link rel="stylesheet" href="{{ asset('asset_admin/css/lesson.css') }}">
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> Edit Resources
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.course.management.subject.all')}}">Subject</a></li>
            <li class="breadcrumb-item" aria-current="page"><a
                href="{{ route('admin.course.management.lesson.topic.create', ['lesson_id' => Crypt::encrypt($lesson->assignSubject->id)]) }}">{{$lesson->assignSubject->subject_name}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{$lesson->name}}</li>
        </ol>
    </nav>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form id="assignTopicForm" enctype="multipart/form-data" method="post">
                            @csrf
                            <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Lesson Name<span class="text-danger">*</span></label>
                                    <input type="text" name="lesson_name" id="lesson_name" class="form-control" value="{{$lesson->parentLesson->name??''}}" disabled>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">@yield('lesson-type')Resource Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g Perimeter and Area" value="{{$lesson->name??''}}"disabled>
                                    <span class="text-danger">{{ $errors->first('board_id') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">@yield('lesson-type') Resource Type(Pdf/video/article)<span
                                            class="text-danger">*</span></label>
                        
                                    <select name="resource_type" id="resource_type" class="form-control" onchange="showDiv()" disabled>
                                        <option value="">-- Select -- </option>
                                        <option value="1">File Attachement(pdf)</option>
                                        <option value="2">Video</option>
                                        <option value="3" selected>Article</option>
                                        <option value="4">Practice Test</option>
                                    </select>
                        
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">@yield('lesson-type') Assign Teacher</label>
                        
                                    <select  id="teacher_id" class="form-control" name="teacher_id">
                                        <option value="">-- Select -- </option>
                                        @foreach($teachers as $key=>$teacher)
                                        <option value="{{$teacher->user_id}}">{{$teacher->name}}</option>
                                        @endforeach
                                    </select>
                        
                                </div>
                            </div>
                        </div>
                                    
                        <div class="row" id="article">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Write Article<span class="text-danger">*</span></label>
                                    <textarea class="ckeditor form-control" name="content" id="content">
                                        {{$lesson->content??''}}
                                    </textarea>
                                </div>
                        
                            </div>
                        </div>
                        <div style="float: right;">
                            <button type="button" class="btn btn-gradient-light btn-fw"
                                id="assignTopicCancelBtn">Cancel</button>
                            <button type="submit" class="btn btn-md btn-success" id="assignTopicSubmitBtn"
                                name="type" value="create-topic">Update</button>
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Large modal -->

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#lessonTable').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        });
        $('#lessonTableVideo').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        });
        $('#lessonTableArticle').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        });
        $('#lessonTableMcq').DataTable({
            "processing": true,
            "searching": true,
            "ordering": false
        })
    });



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

    imageUpload.onchange = evt => {
        const [file] = imageUpload.files
        if (file) {
            var input=evt.srcElement;
            $("#noCoverImage").html(input.files[0].name);

        }
    }
    videoThumbnailImageUpload.onchange = evt => {
        const [file] = videoThumbnailImageUpload.files
        if (file) {
            videothumbnailimagepreview.style.display = "block";
            videothumbnailimagepreview.src = URL.createObjectURL(file)
            var input=evt.srcElement;
            $("#noImageFilePromoVideo").html(input.files[0].name);
            
        }
    }
    
    // videoUpload.onchange = function (event) { 
    //     videoPriview.style.display = "block";
    //     let file = event.target.files[0];
    //     let blobURL = URL.createObjectURL(file);
    //     document.querySelector("video").src = blobURL;
    //     var input=event.srcElement;
    //     $("#noFileVideo").html(input.files[0].name);
       
    // }
    
    
    //For hiding modal
    $('#assignTopicCancelBtn').on('click', function () {
        $('#assignLessonModal').modal('hide');
        $('#assignTopicForm')[0].reset();


    });
    //count video duration
    var myVideos = [];

window.URL = window.URL || window.webkitURL;

document.getElementById('videoUpload').onchange = setFileInfo;

function setFileInfo() {
  var files = this.files;
  videoPriview.style.display = "block";
  let file = files[0];
  let blobURL = URL.createObjectURL(file);
  document.querySelector("video").src = blobURL;
  $("#noFileVideo").html(file.name);
  myVideos.push(files[0]);
  var video = document.createElement('video');
  video.preload = 'metadata';

  video.onloadedmetadata = function() {
    window.URL.revokeObjectURL(video.src);
    var duration = video.duration;
    myVideos[myVideos.length - 1].duration = duration;
    document.getElementById("duration").value=duration;
    
  }

  video.src = URL.createObjectURL(files[0]);
}




</script>
<script>
    $(document).ready(function () {

    $('#assignTopicForm').validate({
        rules: {
            name: {
                required: true
            },
            resource_type:{
                required:true
            }
          
        },
        messages: {
            name: {
                required: "Resource name may not be empty ."
            },    
            resource_type:{
                required:"Please Select Resource Type."
            },
           
        },
        submitHandler: function() {
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#assignTopicSubmitBtn').html('Sending..');
            for ( instance in CKEDITOR.instances ){
                     CKEDITOR.instances[instance].updateElement();
                }
                var data = new FormData(document.getElementById("assignTopicForm"));
                
            $.ajax({
                url: "{{route('admin.course.management.lesson.update')}}" ,
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function( response ) {
                    console.log(response);
                    toastr.options.timeOut = 3000;
                    if(response.status==1){
                        
                        toastr.success(response.message);
                        $('#assignTopicSubmitBtn').html('Submit');
                        
                        location.reload();
                    }
                    if(response.status==0){
                        $.each(response.message,function(prefix,val){
                            toastr.error(val[0]);
                        })
                       
                        $('#assignTopicSubmitBtn').html('Submit');
                    }
                           
                }
            });
        }
      });
});
</script>
<script>
    $(document).ready(function () {
    $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')
    });
</script>
@endsection