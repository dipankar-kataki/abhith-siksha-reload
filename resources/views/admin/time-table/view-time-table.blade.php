@extends('layout.admin.layout.admin')

@section('title','Time Table')

@section('head')
<style>
    /* @import url("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css");

    table {
        border: 1px solid #f3f3f3;
        border-radius: 10px;
        box-shadow: 0px 5px 5px #efecec;
    }

    th {
        border-top: 0px !important;
    }

    #time_table_filter {
        margin-top: -30px;
    } */
</style>

@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-calendar-clock"></i>
        </span> Time Table
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.create.time.table') }}" class="btn btn-gradient-primary btn-fw">Create
                    Time-Table</a>
                {{-- <span></span>Overview <i
                    class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> --}}
            </li>
        </ul>
    </nav>
</div>

<div class="col-lg-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="time_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Board </th>
                            <th> Class </th>
                            <th> Subject </th>
                            <th> Link</th>
                            <th>Class Date & Time</th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($getTimeTables as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->board->exam_board}}</td>
                            <td>{{$item->assignClass->class}}</td>
                            <td>{{$item->assignSubject->subject_name}}</td>
                            <td><a href="{{$item->zoom_link}}">{!! Illuminate\Support\Str::limit(strip_tags($item->zoom_link), $limit = 50, $end =
                                '...') !!}</a></td>
                            <td> {{dateFormat($item->date, $format = "d-m-Y")}} &nbsp;at&nbsp; {{$item->time}}</td>
                            <td>
                                @if ($item->is_activate == 1)
                                <label class="switch">
                                    <input type="checkbox" id="timeTableUpdate" data-id="{{ $item->id }}" checked>
                                    <span class="slider round"></span>
                                </label>
                                @else
                                <label class="switch">
                                    <input type="checkbox" id="timeTableUpdate" data-id="{{ $item->id }}">
                                    <span class="slider round"></span>
                                </label>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="text-center">
                            <td colspan="6"><strong>Oops! No time table found.</strong></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document.body).on('change', '#timeTableUpdate', function() {
            let status = $(this).prop('checked') == true ? 1 : 0;
            let timeTable = $(this).data('id');
            let formData = {
                'timeTable': timeTable,
                'active': status
            }
            $.ajax({
                type: "post",

                url: "{{route('admin.change.visibility.time.table') }}",
                data: formData,

                success: function(result) {
                    toastr.success(result.message);

                },
                error:function(xhr, status, error){
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Oops! Something went wrong while reporting.');
                    }
                }
            });
        });

        $(document).ready( function () {
            $('#time_table').DataTable({
                "processing": true,
                'searching':false
            });
        });

</script>
@endsection
