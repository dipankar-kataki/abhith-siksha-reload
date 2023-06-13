@extends('layout.admin.layout.admin')


@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-book"></i>
            </span> Subjects
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.create.subject') }}" class="btn btn-gradient-primary btn-fw">Add Subjects</a>
                    {{-- <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> --}}
                </li>
            </ul>
        </nav>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Subjects List</h4>
                </p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Subject name </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $key => $item)
                            <tr>
                                <td> {{ $subjects->firstItem() + $key}} </td>
                                <td> {{ $item->name }} </td>
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
                                    <a href="{{route('admin.edit.subject',['id'=>\Crypt::encrypt($item->id)])}}" data-toggle="tooltip" data-placement="top" title="Edit"  class="btn btn-gradient-primary btn-rounded btn-icon anchor_rounded">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div style="float:right;margin-top:10px;">
                    {{$subjects->links()}}
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

                url: "{{ route('admin.active.subject') }}",
                data: formDat,

                success: function(data) {
                    console.log(data)
                }
            });
        });
    </script>


@endsection
