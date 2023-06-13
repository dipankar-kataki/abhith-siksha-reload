<table class="table table-striped" id="lessonTableMcq">
    <thead>
        <tr>
            <th> #No </th>
            <th> User Name </th>
            <th> Set Name </th>
            <th> Total Question </th>
            <th>Attempt Question</th>
            <th>InCorrect Question</th>
            <th>Correct Question</th>
            <th>Total Duration</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mcq_attempts as $key=>$mcq_attempt)
        <tr>
            <td>{{++$key}}</td>
            <td> {{$mcq_attempt->user->name}}</td>
            <td> {{$mcq_attempt->set->set_name}}</td>
            <td> {{$mcq_attempt->set->question->count()}}</td>
            <td> {{$mcq_attempt->total_attempts}}</td>
            <td>{{$mcq_attempt->total_correct_count}}</td>
            <td>
                {{$mcq_attempt->total_attempts-$mcq_attempt->total_correct_count}}
            </td>
            <td>{{$mcq_attempt->total_duration}}</td>
        </tr>
        @endforeach
    </tbody>
</table>