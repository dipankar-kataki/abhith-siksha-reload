@extends('layout.admin.layout.admin')

@section('title', 'Add Multiple Choice')

@section('head')
    <style>
        .stretch-card>.card {
            overflow: auto;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h3 class="page-title" style="width: 50%">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-bulletin-board"></i>
            </span> Multiple Choice Questions For <br> <q class="ml-5"> - <cite> {{ $details[0]['set']['set_name'] }}
        </h3>

        <nav aria-label="breadcrumb" style="width: 50%">
            <ol class="breadcrumb d-flex justify-content-end">
                <li class="breadcrumb-item"><a href="{{ route('admin.course.management.subject.all') }}">Subject</a></li>
                <li class="breadcrumb-item" aria-current="page"><a
                        href="{{ route('admin.course.management.lesson.create', Crypt::encrypt($lesson->assignSubject->id)) }}">{{ $lesson->assignSubject->subject_name }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ $lesson->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">




        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="lessonTable">
                    <thead>
                        <tr>
                            <th>#No</th>
                            <th> Question </th>
                            <th> Option 1 </th>
                            <th> Option 2 </th>
                            <th> Option 3 </th>
                            <th> Option 4</th>
                            <th> Answer </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach ($details as $key => $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->question }}</td>
                                <td>{{ $item->option_1 }}</td>
                                <td>{{ $item->option_2 }}</td>
                                <td>{{ $item->option_3 }}</td>
                                <td>{{ $item->option_4 }}</td>
                                <td>{{ $item->correct_answer }}</td>
                                <td>
                                    @if ($item->is_activate == 1)
                                        <a href="{{ route('admin.mcq.status', Crypt::encrypt($item->id)) }}"
                                            class="badge badge-success">Active</a>
                                    @else
                                        <a href="{{ route('admin.mcq.status', Crypt::encrypt($item->id)) }}"
                                            class="badge badge-danger">InActive</a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>




            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('#addMoreMultipleChoice').click(function(e) {
                e.preventDefault();
                let html = $(".copy").html();
                $(".after-add-more").last().append(html);
            });

            $("body").on("click", "#removeMultipleChoice", function() {
                $(this).parents(".control-group").remove();
            });
        });
    </script>

    @if (session('success'))
        <script>
            toastr.success("{!! session('success') !!}");
        </script>
    @endif

@endsection
