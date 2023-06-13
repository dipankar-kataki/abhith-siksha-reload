@extends('layout.admin.layout.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    label.error {
        color: #dc3545;
        font-size: 14px;
    }
</style>
@if(auth()->user()->hasRole('Teacher') && !isTeacherApply())
@include('admin.teacher.applicationform')
@else
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
    </h3>
    {{-- <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav> --}}
</div>
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('asset_admin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                    alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Registered Student <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{$total_student}}</h2>
            </div>
        </div>
    </div>
    @if(!auth()->user()->hasRole('Teacher'))
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('asset_admin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                    alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Registered Teacher <i
                        class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{$total_teacher}}</h2>
            </div>
        </div>
    </div>
    @endif
    {{-- <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('asset_admin/images/dashboard/circle.svg') }}" class="card-img-absolute"
                    alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Visitors Online <i class="mdi mdi-diamond mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">95,5741</h2>
                <h6 class="card-text">Increased by 5%</h6>
            </div>
        </div>
    </div> --}}
</div>
@endif
@endsection
@section('scripts')
<script>
    var myVideos = [];

    window.URL = window.URL || window.webkitURL;
    document.getElementById('fileUp').onchange = setFileInfo;

        function setFileInfo() {
        var files = this.files;
        myVideos.push(files[0]);
        var video = document.createElement('video');
        video.preload = 'metadata';

        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            var duration = video.duration;
            myVideos[myVideos.length - 1].duration = duration;
            updateInfos();
        }

        video.src = URL.createObjectURL(files[0]);;
        }
        function updateInfos() {


        for (var i = 0; i < myVideos.length; i++) {
           if(myVideos[i].duration/60>=5){
            toastr.error('Video Dureation should be less then 5 minutes');
            $('#applicationSubmit').attr('disabled',true);
           }else{
            $('#applicationSubmit').attr('disabled',false);
           }
        }
      }
</script>
<script>
    function changeBoard()
      {
        let board_id=$("#assignedBoard").val();
        console.log(board_id);
          $.ajax({
                url:"{{route('webboard.class')}}",
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
    $(document).ready(function () {
        $("#applyForm").validate({
            rules: {
                   name: {
                        required: true,

                    },
                    email: {
                        required: true,
                        email: true,

                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                        number: true
                    },

                    gender: {
                        required: true,
                    },
                    dob: {
                        required: true,
                        date: true
                    },
                    total_experience_year: {
                        required: true,
                    },
                    education: {
                        required: true,
                        maxlength: 40,
                    },
                    board: {
                        required: true,
                    },
                    class: {
                        required: true,
                    },
                    subject: {
                        required: true,
                    },
                    hslc_percentage: {
                        required: true,
                    },
                    hs_percentage:{
                        required:true,
                    },
                    resume:{
                        required:true,
                    },
                    teacherdemovideo:{
                        required:true,
                    }
                },
                messages: {
                    name: {
                        required: "First name is required",

                    },
                    email: {
                        required: "Email is required",
                        email: "Email must be a valid email address",

                    },
                    phone: {
                        required: "Phone number is required",
                        minlength: "Phone number must be of 10 digits",
                    },
                    gender: {
                        required:  "Please select the gender",
                    },
                    dateOfBirth: {
                        required: "Date of birth is required",
                        date: "Date of birth should be a valid date",
                    },
                    total_experience_year: {
                        required: "Total Experience is required",
                    },
                    education: {
                        required: "Maximum qualification is required",
                    },
                    board: {
                        required: "Board is required",
                    },
                    class: {
                        required: "Class is required",
                    },
                    subject: {
                        required: "Subject is required",
                    },
                    hslc_percentage: {
                        required: "Percentage is required",
                    },
                    hs_percentage: {
                        required: "Percentage is required",
                    },
                    resume:{
                        required:"Please upload your resume",
                    },
                    teacherdemovideo:{
                        required:"Please upload demo video",
                    }
                },
                submitHandler: function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
             var data = new FormData(document.getElementById("applyForm"));

            $.ajax({
                url: "{{route('teacher.store')}}" ,
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                success: function( response ) {
                    console.log(response);
                    toastr.options.timeOut = 3000;
                    if(response.status==1){

                        toastr.success(response.message);
                        $('#applicationSubmit').html('Submit');

                        location.reload();
                    }
                    if(response.status==0){
                        $.each(response.message,function(prefix,val){
                            toastr.error(val[0]);
                        })

                        $('#applicationSubmit').html('Submit');
                    }

                }
            });
        }
            //     submitHandler: function(form) {
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     $.ajax({
            //         url: '{{route('teacher.store')}}' ,
            //         type: "POST",
            //         data: $('#applyForm').serialize(),
            //         success: function( response ) {
            //            console.log(response);
            //         }
            //     });
            //  });
    });
});
</script>
@endsection
