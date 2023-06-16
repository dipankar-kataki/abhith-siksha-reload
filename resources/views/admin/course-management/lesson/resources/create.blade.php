@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Lesson')
@section('form-label','Topic')
@section('content')
<style>
    .dyn-height {
        width: 100px;
        max-height: 692px;
        overflow-y: auto;
    }
</style>
<link rel="stylesheet" href="{{ asset('asset_admin/css/lesson.css') }}">
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> Add Resources
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.course.management.subject.all')}}">Subject</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('admin.course.management.lesson.create',Crypt::encrypt($lesson->assignSubject->id))}}">{{$lesson->assignSubject->subject_name}}</a></li>
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
                            <input type="hidden" name="parent_id" value="{{$lesson->id}}">
                            <input type="hidden" name="type" value="create-topic">

                            @include('admin.course-management.lesson.common.form')

                            <p style="color: #fc0000;font-weight: 600;display:none;" id="file-upload-warning-text">
                                Note: Please donot refresh the page or click any link during file upload. 
                            </p>

                            <div class="progress upload-bar-display" style="height:30px;margin-bottom:20px;display:none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated upload-bar-percent" style="width:40%">
                                    <h5 style="padding:5px;" id="upload-bar-text">40 % Uploaded</h5>
                                </div>
                            </div>

                            <div style="float: right;">
                                <button type="button" class="btn btn-gradient-light btn-fw"
                                    id="assignTopicCancelBtn" onclick="window.history.go(-1);">Cancel</button>
                                <button type="submit" class="btn btn-md btn-success" id="assignTopicSubmitBtn"
                                    name="type" value="create-topic">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('admin.course-management.lesson.topic.all')

<!-- Large modal -->

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#lessonTable').DataTable({
            "processing": true,
            "searching": true,
            "ordering": true
        });
        $('#lessonTableVideo').DataTable({
            "processing": true,
            "searching": true,
            "ordering": true
        });
        $('#lessonTableArticle').DataTable({
            "processing": true,
            "searching": true,
            "ordering": true
        });
        $('#lessonTableMcq').DataTable({
            "processing": true,
            "searching": true,
            "ordering": true
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
    
    function showDiv(){
   var showDivId= document.getElementById("resource_type").value;
  
   if(showDivId==1){
          $('#fileattachment').show();
            $('#video').hide();
            $('#article').hide();
            $('#practice-test').hide();
   }else if(showDivId==2){
            $('#fileattachment').hide();
            $('#video').show();
            $('#article').hide();
            $('#practice-test').hide();
   }else if(showDivId==3){
            $('#fileattachment').hide();
            $('#video').hide();
            $('#article').show();
            $('#practice-test').hide();
   }else if(showDivId==4){
            $('#practice-test').show();
            $('#fileattachment').hide();
            $('#video').hide();
            $('#article').hide();
   }else{
     $('#fileattachment').hide();
            $('#video').hide();
            $('#article').hide();
   }
   
 }

    $('#assignTopicCancelBtn').on('click', function() {
            document.getElementById("assignTopicForm").reset();
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
                required: "Resource name can not be empty."
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
            $('#assignTopicSubmitBtn').html('Uploading Please Wait..');
            for ( instance in CKEDITOR.instances ){
                     CKEDITOR.instances[instance].updateElement();
                }
                var data = new FormData(document.getElementById("assignTopicForm"));
                
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
                    return xhr;
                },
                url: "{{route('admin.course.management.lesson.topic.store')}}" ,
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
                        // $.each(response.message,function(prefix,val){
                        //     toastr.error(val[0]);
                        // })
                        toastr.error(response.message);
                        $('#file-upload-warning-text').css('display', 'none');
                        $('.upload-bar-display').css('display', 'none');
                        $('#assignTopicSubmitBtn').text('Submit');
                    }
                    if(response.status==2){
                        toastr.error(response.message);
                       
                        $('#assignTopicSubmitBtn').text('Submit');
                    }
                    if(response.status==3){
                        toastr.error(response.message);
                       
                        $('#assignTopicSubmitBtn').text('Submit');
                    }
                           
                },
                error:function(xhr, status, error){
                    console.log('Ajax Error===>',xhr.responseText.message)
                    $('#file-upload-warning-text').css('display', 'none');
                    $('.upload-bar-display').css('display', 'none');
                    $('#assignTopicSubmitBtn').text('Submit');
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