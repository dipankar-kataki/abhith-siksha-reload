@extends('layout.admin.layout.admin')
@section('title', 'Course Management - Subjects-create')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-bulletin-board"></i>
        </span> Create Course
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{route('teacher.course.create')}}" class="btn btn-gradient-primary btn-fw"
                    data-backdrop="static" data-keyboard="false">New Course</a>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Select Board<span class="text-danger">*</span></label>
                            <select name="board_id" id="assignedBoard" class="form-control" onchange="changeBoard()">
                                <option value="">-- Select -- </option>
                                @forelse ($boards as $item)
                                <option value="{{$item->id}}" @isset($lesson)@if($lesson->board_id==$item->id) selected
                                    @endif
                                    @endisset>{{$item->exam_board}}</option>
                                @empty
                                <option>No boards to show</option>
                                @endforelse
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Select Class<span class="text-danger">*</span></label>
                            <select name="assign_class_id" id="board-class-dd" class="form-control">
                                <option value="">-- Select -- </option>

                            </select>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Select Subject<span class="text-danger">*</span></label>
                            <select name="assign_subject_id" id="board-subject-dd" class="form-control">
                                <option value="">-- Select -- </option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection