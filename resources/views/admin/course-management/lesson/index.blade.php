@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Lesson')
@section('content')
<style>
    .lesson-image {
        height: 200px;
        width: 150px;
    }

  div.scrollmenu {
        overflow: auto;
        white-space: nowrap;
    }

    div.scrollmenu a {
        display: inline-block;
        color: white;
        text-align: center;
        padding: 14px;
        text-decoration: none;
    }

    div.scrollmenu a:hover {
        background-color: #777;
    }
</style>
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> All Lesson
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a type="button" class="btn btn-primary" href="{{route('admin.course.management.lesson.create')}}">Add
                    Lesson</a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="scrollmenu">
                <table id="boardsTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <td>#</td>
                            <td>Board</td>
                            <td>Class</td>
                            <td>Subject</td>
                            <td>Lesson</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all_lessons as $key=>$all_lesson)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$all_lesson->board->exam_board}}</td>
                            <td>{{$all_lesson->assignClass->class}}</td>
                            <td>{{$all_lesson->assignSubject->subject_name}}</td>
                            <td>{{$all_lesson->name}}</td>
                            <td> <a href="{{route('admin.course.management.lesson.edit',$all_lesson->id)}}"
                                    class="btn btn-gradient-primary p-2" title="Edit Lesson"><i
                                        class="mdi mdi-pencil"></i></a> <a
                                    href="{{route('admin.course.management.lesson.topic.create',Crypt::encrypt($all_lesson->id))}}"
                                    class="btn btn-gradient-primary p-2" title="Add New Topic"><i
                                        class="mdi mdi-plus"></i></a> <a
                                    href="{{route('admin.course.management.lesson.view',Crypt::encrypt($all_lesson->id))}}"
                                    class="btn btn-gradient-primary p-2" title="View Lesson Details"><i
                                        class="mdi mdi-eye"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Large modal -->


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="assignLessonModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="padding:1.5rem;background-color:#fff;">
            <div class="modal-header">
                <h5 class="modal-title">Add Lesson</h5>
            </div>
            <div class="modal-body">
                <form id="assignLessonForm">
                    @csrf
                    @include('admin.course-management.lesson.form')
                    <div style="float: right;">
                        <button type="button" class="btn btn-md btn-default" id="assignLessonCancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-md btn-success" id="assignLessonSubmitBtn" name="type"
                            value="lesson-create">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- lesson content display --}}
<div class="modal fade" id="displayMore" tabindex="-1" role="dialog" aria-labelledby="displayMoreTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="displayMoreTitle"><span id="modal-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="form-group">
                        <textarea class="ckeditor form-control" name="content" id="Content"></textarea>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
{{-- update lesson image --}}
<div class="modal fade" id="displayImageModal" tabindex="-1" role="dialog" aria-labelledby="displayImageModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="displayImageModalTitle"><span id="document-modal-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-md-offset-2">
                            <span id="displayLessonImage"></span>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lessonImageUpdate">Change Lesson Picture</label>
                                <br>&nbsp;<input type="file" class="filepond p-4" name="lessonImageUpdate"
                                    id="lessonImageUpdate" data-max-file-size="1MB" data-max-files="1" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
{{-- update lesson video --}}
<div class="modal fade" id="displayVideoModal" tabindex="-1" role="dialog" aria-labelledby="displayImageModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="displayImageModalTitle"><span id="document-modal-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-md-offset-2">
                            <span id="displayLessonVideo"></span>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lessonVideoUpdate">Change Lesson Video</label>
                                <br>&nbsp;<input type="file" class="filepond p-4" name="lessonVideoUpdate"
                                    id="lessonVideoUpdate" data-max-file-size="1MB" data-max-files="1" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    // For datatable
        $(document).ready( function () {
            $('#boardsTable').DataTable({
                "processing": true,
                "searching" : true,
                "ordering" : true
            });
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

            // Select the file input and use create() to turn it into a pond
            

            pondImage = FilePond.create(

                document.getElementById('lessonImage'), {
                    allowMultiple: true,
                    maxFiles: 1,
                    imagePreviewHeight: 135,
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    labelFileTypeNotAllowed:'File of invalid type. Acepted types are png and jpeg/jpg.',
                    labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
                },
            );
             pondImageUpdate = FilePond.create(

                document.getElementById('lessonImageUpdate'), {
                    allowMultiple: true,
                    maxFiles: 1,
                    imagePreviewHeight: 135,
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    labelFileTypeNotAllowed:'File of invalid type. Acepted types are png and jpeg/jpg.',
                    labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
                },
            );
            pondVideo = FilePond.create(

                document.getElementById('lessonVideo'), {
                    allowMultiple: true,
                    maxFiles: 50,
                    imagePreviewHeight: 135,
                    acceptedFileTypes: ['video/mp4'],
                    labelFileTypeNotAllowed:'File of invalid type. Acepted types are mp4.',
                    labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of video is 1 :</p> </div>',
                },
       );
       pondVideoUpdate = FilePond.create(

                document.getElementById('lessonVideoUpdate'), {
                    allowMultiple: true,
                    maxFiles: 50,
                    imagePreviewHeight: 135,
                    acceptedFileTypes: ['video/mp4'],
                    labelFileTypeNotAllowed:'File of invalid type. Acepted types are mp4.',
                    labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of video is 1 :</p> </div>',
                },
            );
        //
        $("#addLesson").on('click',function(){
            $('#assignLessonModal').modal({backdrop: 'static', keyboard: false}) ;
        })
        //For hiding modal
        $('#assignLessonCancelBtn').on('click', function(){
            $('#assignLessonModal').modal('hide');
            $('#assignLessonForm')[0].reset();
            pondImage.removeFiles();
            pondVideo.removeFiles();

        });
        $('#assignLessonForm').on('submit', function(e){
            e.preventDefault();

            $('#assignLessonSubmitBtn').attr('disabled', true);
            $('#assignLessonSubmitBtn').text('Please wait...');
            $('#assignLessonCancelBtn').attr('disabled', true);


            let formData = new FormData(this);
            pondImageFiles = pondImage.getFiles();
            pondVideoFiles = pondVideo.getFiles();
            for (var i = 0; i < pondImageFiles.length; i++) {
                // append the blob file
                formData.append('image_url', pondImageFiles[i].file);
            }
            // for (var i = 0; i < pondVideoFiles.length; i++) {
            //     // append the blob file
            //     formData.append('video_url', pondVideoFiles[i].file);
            // }
            // var Content = CKEDITOR.instances['content'].getData();
            
            // formData.append('content', Content);
            
            // $.ajax({
            //     url:"{{route('admin.course.management.lesson.store')}}",
            //     type:"POST",
            //     processData:false,
            //     contentType:false,
            //     data:formData,
            //     success:function(data){
            //         console.log(data);
            //         if(data.error != null){
            //             $.each(data.error, function(key, val){
            //                 toastr.error(val[0]);
            //             });
            //             $('#assignLessonSubmitBtn').attr('disabled', false);
            //             $('#assignLessonSubmitBtn').text('Submit');
            //             $('#assignLessonCancelBtn').attr('disabled', false);
            //         }
            //         if(data.status == 1){
            //             console.log(data);
            //             toastr.success(data.message);
            //             location.reload(true);
            //         }else{
                        
            //             toastr.error(data.message);
            //             $('#assignLessonSubmitBtn').attr('disabled', false);
            //             $('#assignLessonSubmitBtn').text('Submit');
            //             $('#assignLessonCancelBtn').attr('disabled', false);
            //         }
            //     },
            //     error:function(xhr, status, error){
                  
            //         if(xhr.status == 500 || xhr.status == 422){
            //             toastr.error('Whoops! Something went wrong failed to assign lesson');
            //         }

            //         $('#assignSubjectSubmitBtn').attr('disabled', false);
            //         $('#assignSubjectSubmitBtn').text('Submit');
            //         $('#assignSubjectCancelBtn').attr('disabled', false);
            //     }
            // });
        });
</script>
<script>
    function changeBoard()
      {
        let board_id=$("#assignedBoard").val();
        console.log(board_id);
          $.ajax({
                url:"{{route('board.class')}}",
                type:"POST",
                data:{
                    '_token' : "{{csrf_token()}}",
                    'board_id' : board_id
                },
                success:function(data){
                   
                    $('#board-class-dd').html('<option value="">Select Class</option>');
                    data.forEach((boardClass) => {
                        $("#board-class-dd").append('<option value="' + boardClass
                                .id + '">'+'Class-' + boardClass.class + '</option>');
                    });
                    $('#board-subject-dd').html('<option value="">Select Subject</option>');


                },
                error:function(xhr, status, error){
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Whoops! Something went wrong. Failed to fetch course');
                    }
                }
            });
      }
      $('#board-class-dd').on('change', function () {
                var classId = this.value;
                var boardId=$("#assignedBoard").val();
                $("#board-subject-dd").html('');
                $.ajax({
                    url: "{{route('board.class.subject')}}",
                    type: "POST",
                    data: {
                         class_id: classId,
                         board_id:boardId,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#board-subject-dd').html('<option value="">Select Subject</option>');
                        data.forEach((subject) => {
                        $("#board-subject-dd").append('<option value="' + subject
                                .id + '">'+'Subject-' + subject.subject_name + '</option>');
                        });

                    }
                });
     });
    
</script>
<script>
    $("#displayMoreLesson").click(function(){
            let id = $(this).attr("data-id");
            let content = $(this).attr("data-value");
            $("#modal-name").html("Lesson Content");
            CKEDITOR.instances["Content"].setData(content);
          
        });
        $("#displayImage").click(function(){
            let id = $(this).attr("data-id");
            let image = $(this).attr("data-value");
            var displayImage=`<img src="{{asset('${image}')}}" class="lesson-image"></img>`
           
            $("#displayLessonImage").html(displayImage);
           
            $("#document-modal-name").html("Lesson Image");
            
          
        });
        $("#displayVideo").click(function(){
            let id = $(this).attr("data-id");
            let video = $(this).attr("data-value");
            var displayVideo=`<div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="{{asset('${video}')}}" allowfullscreen></iframe>
                              </div>`;
             
            $("#displayLessonVideo").html(displayVideo);
          
            $("#document-modal-name").html("Lesson Video");
            
          
        });
</script>
@endsection