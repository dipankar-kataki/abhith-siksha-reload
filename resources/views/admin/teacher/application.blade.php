@extends('layout.admin.layout.admin')

@section('title', 'Applied Teacher')

@section('head')
    <link rel="stylesheet" href="{{ asset('asset_admin/toaster/toastr.min.css') }}">
    <style>
        /* @import url("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"); */

        .padding-1 {
            padding-right: 0px;
            padding-left: 0px;
        }
    </style>

@endsection

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span>Teacher Details
        </h3>
    </div>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <strong>Promo Video</strong>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div id="videoAttach" class="tabcontent">
                                <video id="teacher-promo-video" class="video-js vjs-big-play-centered w-100" controls
                                    preload="auto" width="900" height="400" data-setup="{}">
                                    <source src="{{ asset($user_details->teacherdemovideo_url) }}" type="video/mp4" />
                                </video>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <a href="{{ asset($user_details->resume_url) }}" class="btn btn-primary" target="_blank">View Resume</a>

                    @if ($user_details->status == 1 && auth()->user()->type_id == 1)
                        <button class="btn btn-outline-danger" id="rejectTeacherBtn"
                            data-id="{{ Crypt::encrypt($user_details->id) }}" style="float:right">Reject</button>

                        <a href="{{ route('approved.teacher', Crypt::encrypt($user_details->id)) }}"
                            class="btn btn-inverse-success btn-fw" style="float: right; margin-right: 10px"
                            onclick="return confirm('Are you sure you would like to approve this application for Teacher Role?');"><i
                                class="mdi mdi-check-all"></i>
                            Approve Teacher</a>
                    @endif
                    @if ($user_details->status == 2)
                        <div class="btn btn-gradient-success" style="float:right!important;">Referral Code :
                            {{ $user_details->referral_id }}</div>
                    @endif
                    @if ($user_details->status == 3)
                        <div class="btn btn-warning" style="float:right!important;">Rejected</div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 text-primary padding-1">

                            <span class="font-weight-bold">Name:</span> <span>{{ $user_details->name }} </span><br>

                            <span class="font-weight-bold"> E-mail :</span><span> {{ $user_details->email }}</span><br>

                            <span class="font-weight-bold"> Phone :</span><span>{{ $user_details->phone }} </span><br>

                            <span class="font-weight-bold"> Gender :</span><span> {{ $user_details->gender }}</span><br>

                            <span class="font-weight-bold"> DOB :</span><span> {{ dateFormat($user_details->dob) }} </span>


                        </div>
                        <div class="col-4 text-primary padding-1">


                            <span class="font-weight-bold"> Applied For : </span><span>
                                {{ $user_details->board->exam_board }}--Class{{ $user_details->assignClass->class }}--{{ $user_details->assignSubject->subject_name }}
                            </span><br>
                            <span class="font-weight-bold"> Highest Qualification:
                            </span><span>{{ $user_details->education }}</span> <br>
                            <span class="font-weight-bold"> Class 10th Percentage :
                            </span><span>{{ $user_details->hslc_percentage }}% </span> <br>
                            <span class="font-weight-bold"> Class 12th Percentage:
                            </span><span>{{ $user_details->hs_percentage }}% </span> <br>
                            <span class="font-weight-bold">Total Exprience:
                            </span><span>{{ $user_details->total_experience_year ?? 0 }}-Year
                                {{ $user_details->total_experience_month ?? 0 }}-Month</span> <br>


                        </div>
                        <div class="col-4 text-primary padding-1 ">
                            <span class="font-weight-bold"> Current Organization : </span><span>
                                {{ $user_details->current_organization ?? 'NA' }}
                            </span><br>
                            <span class="font-weight-bold"> Current Designation : </span><span>
                                {{ $user_details->current_designation ?? 'NA' }}
                            </span><br>
                            <span class="font-weight-bold"> Current CTC : </span><span>
                                @if($user_details->current_ctc!=null)
                                {{number_format((float)$user_details->current_ctc, 2, '.', '')}}
                                @else
                                NA
                                @endif

                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#all_applied_teacher').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

    <script>
        $('#rejectTeacherBtn').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            if (confirm("Are you sure you want to reject this teacher?") == true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('reject.teacher') }}",
                    type: "POST",
                    data: {
                        teacher_id: id
                    },

                    success: function(response) {
                        toastr.options.timeOut = 3000;
                        if (response.status == 1) {

                            toastr.success(response.message);
                            location.reload();
                        }

                        if (response.status == 0) {
                            toastr.error(response.message);
                        }

                    }
                });
            } else {
                return 0;
            }
        })
    </script>
@endsection
