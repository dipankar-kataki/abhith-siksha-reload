@extends('layout.admin.layout.admin')

@section('title','Course')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-book"></i>
            </span> Course
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.create.course') }}" class="btn btn-gradient-primary btn-fw">Add Course</a>
                    {{-- <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> --}}
                </li>
            </ul>
        </nav>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Course List</h4>
                </p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th> Image </th>
                            <th> Status </th>
                            <th>Publish Date & Time</th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($course as $key => $item)
                            <tr>
                                <td>{{ $course->firstItem() + $key }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <img src="{{ asset($item->course_pic) }}" alt="" srcset="">
                                </td>
                                <td>
                                    @if ($item->is_activate == 1)
                                        <label class="switch">
                                            <input type="checkbox" id="testingUpdate" data-id="{{ $item->id }}" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    @else
                                        <label class="switch">
                                            <input type="checkbox" id="testingUpdate" data-id="{{ $item->id }}">
                                            <span class="slider round"></span>
                                        </label>
                                    @endif
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($item->publish_date)->format('Y-m-d H:i:s')}}
                                </td>
                                <td class="d-flex">

                                    <a href="{{route('admin.edit.course',['id'=>\Crypt::encrypt($item->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit Course" class="btn mr-2 btn-gradient-primary btn-rounded btn-icon anchor_rounded">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>

                                    <a href="{{route('admin.get.chapter',['id'=>\Crypt::encrypt($item->id)])}}" data-toggle="tooltip" data-placement="top" title="Add or Edit Chapter" class="btn mr-2 btn-gradient-primary btn-rounded btn-icon anchor_rounded">
                                        <i class="mdi mdi-plus-outline"></i>
                                    </a>
                                    <a href="{{route('admin.price.course',['id'=>\Crypt::encrypt($item->id)])}}" data-toggle="tooltip" data-placement="top" title="View Details of Course" class="btn btn-gradient-primary btn-rounded btn-icon anchor_rounded">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="float:right;margin-top:10px;">
                    {{$course->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document.body).on('change', '#testingUpdate', function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');
            // console.log(status);
            var formDat = {
                catId: user_id,
                active: status
            }
            // console.log(formDat);
            $.ajax({
                type: "post",

                url: "{{ route('admin.active.course') }}",
                data: formDat,

                success: function(data) {
                    console.log(data)
                }
            });
        });
    </script>


@endsection
