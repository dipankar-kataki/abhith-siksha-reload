<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">

        <div class="card-header">
            All Lesson

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
                            <th> Total Recources</th>
                            <th> Total Videos </th>
                            <th> Total Documents </th>
                            <th> Total Articles </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subject->lesson as $key => $lesson)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $lesson->name }} </td>
                                <td><span class="badge rounded-pill bg-danger">
                                        @php $total_topic= $lesson->topics->count()+$lesson->Sets->count()@endphp
                                        {{ $total_topic }}
                                    </span><a
                                        href="{{ route('admin.course.management.lesson.topic.create', Crypt::encrypt($lesson->id)) }}">
                                        Add Recources
                                    </a>
                                </td>
                                <td style="text-align: center"> {{ $lesson->topics->where('type', 2)->count() }} </td>
                                <td style="text-align: center">{{ $lesson->topics->where('type', 1)->count() }} </td>
                                <td style="text-align: center">{{ $lesson->topics->where('type', 3)->count() }}</td>
                                <td><a class="openEditModal" title="Edit Lesson"
                                        data-id="{{ Crypt::encrypt($lesson->id) }}" data-lesson="{{ $lesson->name }}"><i
                                            class="mdi mdi-grease-pencil mr-2"></i></a>
                                    <a href="{{ route('admin.course.management.lesson.topic.display', Crypt::encrypt($lesson->id)) }}"
                                        title="View Details"><i class="mdi mdi-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editLessonModal" tabindex="-1" aria-labelledby="editLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLessonModalLabel">Edit Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateLessonForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="lessonId" name="lessonId" value="">
                    <div class="col-mb-3">
                        <label for="lessonName">Lesson</label>
                        <input type="text" id="lessonName" name="lessonName" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateLessonBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
