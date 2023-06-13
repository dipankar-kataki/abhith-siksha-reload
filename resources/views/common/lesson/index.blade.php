<div class="card-body">
    
 
    <blockquote class="blockquote blockquote-primary">
        <p>
        <div class="row">
            <div class="col-6">
                <h4 class="card-title text-info">SUBJECT: {{$lesson->assignSubject->subject_name}}
                </h4>
                <h4 class="card-title text-success">LESSON NAME:{{$lesson->name}} </h4>
                <h4 class="card-title text-info"> ASSIGN CLASS:
                    Class -
                    {{$lesson->assignClass->class}} -- {{$lesson->board->exam_board}} Board
                </h4>
            </div>
            <div class="col-6">
                <span style="float: right">
                    <td> <a href="" class="btn btn-gradient-primary p-2" title="Edit Subject"><i
                                class="mdi mdi-pencil"></i></a> <a
                            href="{{route('teacher.lesson.view',Crypt::encrypt($lesson->id))}}"
                            class="btn btn-gradient-primary p-2" title="View Lesson Details"><i
                                class="mdi mdi-eye"></i></a>
                </span>

            </div>
        </div>


        </p>



    </blockquote>
</div>