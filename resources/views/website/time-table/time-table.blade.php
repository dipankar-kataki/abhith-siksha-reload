@extends('layout.website.website')

@section('title', 'Time Table')

@section('head')
<style>
    /* @import url("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"); */
    table {
        border: 1px solid #f3f3f3;
        border-radius: 10px;
        box-shadow: 0px 5px 5px #efecec;
    }

    th {
        border-top: 0px !important;
    }
</style>

@endsection

@section('content')

<section class="time-table">
    <div class="container-fluid">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="time_table_website" class="table table-bordered">
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

                                @forelse ($time_tables as $key => $item)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$item->board->exam_board}}</td>
                                    <td>{{$item->assignClass->class}}</td>
                                    <td>{{$item->assignSubject->subject_name}}</td>
                                    <td>
                                        <a href="{{$item->zoom_link}}" target="_blank">{!! Illuminate\Support\Str::limit(strip_tags($item->zoom_link), $limit = 50,
                                        $end =
                                        '...') !!}
                                        </a>
                                    </td>
                                    <td>{{$item->date}} &nbsp;at&nbsp; {{$item->time}}</td>
                                    <td>
                                        @if ($item->is_activate == 1)
                                        Active
                                        @else
                                        Inactive
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="6">
                                        @auth
                                        <strong>Oops! No Time-Table Found.</strong>
                                        @endauth
                                        @guest
                                        <strong>Login to check time-table.</strong>
                                        @endguest
                                    </td>
                                </tr>

                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('layout.website.include.modals')

@endsection

@section('scripts')
@include('layout.website.include.modal_scripts')
<script>
    $(document).ready( function () {
            $('#time_table_website').DataTable({
                "processing": true,
                "searching":true,
            });
        });
</script>
@endsection