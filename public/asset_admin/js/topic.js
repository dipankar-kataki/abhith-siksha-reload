
// For datatable
$(document).ready(function () {
    $('#boardsTable').DataTable({
        "processing": true,
        "searching": true,
        "ordering": false
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
    labelFileTypeNotAllowed: 'File of invalid type. Acepted types are png and jpeg/jpg.',
    labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
},
);

pondVideo = FilePond.create(

    document.getElementById('lessonVideo'), {
    allowMultiple: true,
    maxFiles: 50,
    imagePreviewHeight: 135,
    acceptedFileTypes: ['video/mp4'],
    labelFileTypeNotAllowed: 'File of invalid type. Acepted types are mp4.',
    labelIdle: '<div style="width:100%;height:100%;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of video is 1 :</p> </div>',
},
);

//
$("#addLesson").on('click', function () {
    $('#assignLessonModal').modal({ backdrop: 'static', keyboard: false });
})
//For hiding modal
$('#assignLessonCancelBtn').on('click', function () {
    $('#assignLessonModal').modal('hide');
    $('#assignLessonForm')[0].reset();
    pondImage.removeFiles();
    pondVideo.removeFiles();

});
$('#assignLessonForm').on('submit', function (e) {
    e.preventDefault();

    $('#assignLessonSubmitBtn').attr('disabled', true);
    $('#assignLessonSubmitBtn').text('Please wait...');
    $('#assignLessonCancelBtn').attr('disabled', true);


    let formData = new FormData(this);
    pondImageFiles = pondImage.getFiles();
    pondVideoFiles = pondVideo.getFiles();
    alert(pondImageFiles);
    for (var i = 0; i < pondImageFiles.length; i++) {
        // append the blob file
        formData.append('image_url', pondImageFiles[i].file);
    }
    for (var i = 0; i < pondVideoFiles.length; i++) {
        // append the blob file
        formData.append('video_url', pondVideoFiles[i].file);
    }
    var Content = CKEDITOR.instances['content'].getData();

    formData.append('content', Content);

    $.ajax({
        url: "{{route('admin.course.management.lesson.store')}}",
        type: "POST",
        processData: false,
        contentType: false,
        data: formData,
        success: function (data) {
            console.log(data);
            if (data.error != null) {
                $.each(data.error, function (key, val) {
                    toastr.error(val[0]);
                });
                $('#assignLessonSubmitBtn').attr('disabled', false);
                $('#assignLessonSubmitBtn').text('Submit');
                $('#assignLessonCancelBtn').attr('disabled', false);
            }
            if (data.status == 1) {
                toastr.success(data.message);
                location.reload(true);
            } else {
                toastr.error(data.message);
                $('#assignLessonSubmitBtn').attr('disabled', false);
                $('#assignLessonSubmitBtn').text('Submit');
                $('#assignLessonCancelBtn').attr('disabled', false);
            }
        },
        error: function (xhr, status, error) {
            if (xhr.status == 500 || xhr.status == 422) {
                toastr.error('Whoops! Something went wrong failed to assign lesson');
            }

            $('#assignSubjectSubmitBtn').attr('disabled', false);
            $('#assignSubjectSubmitBtn').text('Submit');
            $('#assignSubjectCancelBtn').attr('disabled', false);
        }
    });
});


function changeBoard() {
    let board_id = $("#assignedBoard").val();
    $.ajax({
        url: "{{route('board.class')}}",
        type: "get",
        data: {
            '_token': "{{csrf_token()}}",
            'board_id': board_id
        },
        success: function (data) {
            $('#board-class-dd').html('<option value="">Select Class</option>');
            data.forEach((boardClass) => {
                $("#board-class-dd").append('<option value="' + boardClass
                    .id + '">' + 'Class-' + boardClass.class + '</option>');
            });
            $('#board-subject-dd').html('<option value="">Select Subject</option>');


        },
        error: function (xhr, status, error) {
            if (xhr.status == 500 || xhr.status == 422) {
                toastr.error('Whoops! Something went wrong. Failed to fetch course');
            }
        }
    });
}
$('#board-class-dd').on('change', function () {
    var classId = this.value;
    var boardId = $("#assignedBoard").val();
    $("#board-subject-dd").html('');
    $.ajax({
        url: "{{route('board.class.subject')}}",
        type: "POST",
        data: {
            class_id: classId,
            board_id: boardId,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (data) {
            $('#board-subject-dd').html('<option value="">Select Subject</option>');
            data.forEach((subject) => {
                $("#board-subject-dd").append('<option value="' + subject
                    .id + '">' + 'Subject-' + subject.subject_name + '</option>');
            });

        }
    });
});

$('.ckeditor').ckeditor();


//update lesson content
$("#displayMoreLesson").click(function () {
    $('#displayMore').modal({ backdrop: 'static', keyboard: false });
    let id = $(this).attr("data-id");
    let content = $(this).attr("data-value");
    $("#modal-name").html("Lesson Content");
    $("#lesson-id").value = id;
    CKEDITOR.instances["Content"].setData(content);

});
$('#updateLessonContent').on('submit', function (e) {
    e.preventDefault();

    $('#updateLessonContentSubmitBtn').attr('disabled', true);
    $('#updateLessonContentSubmitBtn').text('Please wait...');
    $('#updateLessonContentCancelBtn').attr('disabled', true);


    let formData = new FormData(this);

    var Content = CKEDITOR.instances['content'].getData();

    formData.append('content', Content);

    $.ajax({
        url: "{{route('admin.course.management.lesson.store')}}",
        type: "POST",
        processData: false,
        contentType: false,
        data: formData,
        success: function (data) {
            console.log(data);
            if (data.error != null) {
                $.each(data.error, function (key, val) {
                    toastr.error(val[0]);
                });
                $('#assignLessonSubmitBtn').attr('disabled', false);
                $('#assignLessonSubmitBtn').text('Submit');
                $('#assignLessonCancelBtn').attr('disabled', false);
            }
            if (data.status == 1) {
                toastr.success(data.message);
                location.reload(true);
            } else {
                toastr.error(data.message);
                $('#assignLessonSubmitBtn').attr('disabled', false);
                $('#assignLessonSubmitBtn').text('Submit');
                $('#assignLessonCancelBtn').attr('disabled', false);
            }
        },
        error: function (xhr, status, error) {
            if (xhr.status == 500 || xhr.status == 422) {
                toastr.error('Whoops! Something went wrong failed to assign lesson');
            }

            $('#assignSubjectSubmitBtn').attr('disabled', false);
            $('#assignSubjectSubmitBtn').text('Submit');
            $('#assignSubjectCancelBtn').attr('disabled', false);
        }
    });
});







$("#displayImage").click(function () {
    let id = $(this).attr("data-id");
    let image = $(this).attr("data-value");
    var displayImage = `<img src="{{asset('${image}')}}" class="lesson-image"></img>`

    $("#displayLessonImage").html(displayImage);

    $("#document-modal-name").html("Lesson Image");


});
$("#displayVideo").click(function () {
    let id = $(this).attr("data-id");
    let video = $(this).attr("data-value");
    var displayVideo = `<div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="{{asset('${video}')}}" allowfullscreen></iframe>
                              </div>`;

    $("#displayLessonVideo").html(displayVideo);

    $("#document-modal-name").html("Lesson Video");


});
