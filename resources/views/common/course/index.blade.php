<div class="card">
    <div class="card-body">
        @if($subjects->count()>0)
        @foreach ($subjects as $key => $item)

        <div class="blockquote blockquote-primary">
            <span>Publish</span>
            @if ($item->is_activate == 1)
            <label class="switch">
                <input type="checkbox" id="isPublish" data-id="{{ $item->id }}" checked>
                <span class="slider round"></span>
            </label>
        @else
            <label class="switch">
                <input type="checkbox" id="isPublish" data-id="{{ $item->id }}">
                <span class="slider round"></span>
            </label>
        @endif

            <span style="float: right">
                <a href="{{route('admin.course.management.subject.edit',Crypt::encrypt($item->id) )}}" class="btn btn-gradient-primary p-2" title="Edit Subject"><i class="mdi mdi-pencil"></i></a>
                <a href="{{route('teacher.course.view',Crypt::encrypt($item->id))}}"
                    class="btn btn-gradient-primary p-2" title="View Lesson Details"><i class="mdi mdi-eye"></i></a>
            </span>


            <div class="card-body">
                <div class="row">
                    <div class="col-8">

                        <h5>SUBJECT:{{$item->subject_name}} &nbsp;&nbsp; ASSIGN CLASS: Class -
                            {{$item->assignClass->class}} -- {{$item->boards->exam_board}} Board </h5>

                        <h4 class="card-title text-info">AMOUNT: {{number_format($item->subject_amount,2,'.','')}} </h4>
                    </div>
                    <div class="col-4">
                        <h4 class="card-title text-info">TOTAL LESSON:{{$item->lesson()->count()}}</h4>
                        <h4><a href="{{route('teacher.subject.student',Crypt::encrypt($item->id))}}"><mark
                                    class="bg-success text-white">
                                    TOTAL STUDENTS: {{$item->assignOrder->count()}}</mark></a></h4>
                    </div>
                </div>
            </div>

        </div>

        @endforeach
        @else
        <div class="alert alert-dark" role="alert">
           No data available for display
        </div>
        @endif
    </div>


</div>
<div class="card">
    <div style="text-align: center;">{{$subjects->links() }}</div>
</div>