<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">

        <div class="card-header">
            Board:<b>{{$subject->boards->exam_board}}</b> / Class:<b>{{$subject->assignClass->class}}</b> / Subject:<b>{{$subject->subject_name}}</b>
            <span style="float: right"> Student Name: <b>{{$user->name}}</b></span>
        </div>
        {{-- <a href="" style="float:right" class="btn btn-gradient-primary btn-fw">All
            Subject</a> --}}
        <div class="card-body">
            <div style="overflow-x:auto;">
                <table class="table table-striped" id="lessonTable">
                    <thead>
                        <tr>
                            <th>#No</th>
                            <th> Lesson Name </th>
                            <th> Total Videos </th>
                            <th> Total Documents </th>
                            <th> Total Articles </th>
                            <th>Total MCQ test(s)</th>
                            <th> Recource wise Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subject->lesson as $key => $lesson)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $lesson->name }} </td>
                            <td style="text-align: center"> {{ $lesson->topics->where('type', 2)->count() }} </td>
                            <td style="text-align: center">{{ $lesson->topics->where('type', 1)->count() }} </td>
                            <td style="text-align: center">{{ $lesson->topics->where('type', 3)->count() }}</td>
                            <td style="text-align: center">{{$lesson->Sets->count()}}</td>
                            <td>
                                @if(auth()->user()->hasRole('Admin'))
                                <a href="{{ route('admin.course.management.lesson.topic.report',
                                [Crypt::encrypt($lesson->id),Crypt::encrypt($user->id)]) }}">
                                    Reports
                                </a>
                                @endif
                                @if(auth()->user()->hasRole('Teacher'))
                                <a href="{{ route('teacher.course.management.lesson.topic.report',
                                [Crypt::encrypt($lesson->id),Crypt::encrypt($user->id)]) }}">
                                    Reports
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
