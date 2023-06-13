@extends('layout.admin.layout.admin')

@section('title', 'Enquiry')

@section('head')
    <style>
        @import url("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css");

        table {
            border: 1px solid #f3f3f3;
            border-radius: 10px;
            /* box-shadow: 0px 5px 5px #efecec; */
        }

        th {
            border-top: 0px !important;
        }

        #enrolled_students_table_filter {
            margin-top: -30px;
        }
    </style>

@endsection

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span>Enquiry Details
        </h3>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="enquiry_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Name </th>
                                <th> Phone </th>
                                <th> Email </th>
                                <th> Message </th>
                                <th> Date of Enquiry </th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ Str::limit($item->message, 20) }}</td>
                                    <td>{{ dateFormat($item->date_of_enquiry, 'd-m-Y') }}</td>
                                    <td>

                                        @if ($item->marked_as_contacted == 0)
                                            <button class="btn btn-sm btn-warning" id="markContact"
                                                data-id="{{ $item->id }}" data-status="1">Mark as Reached</button>
                                        @else
                                            <h6 class="text-success">Reached</h6>
                                        @endif
                                    </td>
                                    <td> <button class="btn btn-primary btn-sm openModalBtn" data-name="{{ $item->name }}"
                                            data-phone="{{ $item->phone }}" data-email="{{ $item->email }}"
                                            data-message="{{ $item->message }}"
                                            data-doe="{{ dateFormat($item->date_of_enquiry, 'd-m-Y') }}">View</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div style="float:right;margin-top:10px;">
                {{ $details->links() }}
            </div> --}}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewFullDetailsModal" tabindex="-1" aria-labelledby="viewFullDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFullDetailsModalLabel">Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table style="border: 0">
                        <tr>
                            <th class="p-2">Name</th>
                            <td class="p-2"><span id="enquiryName"></span></td>
                        </tr>

                        <tr>
                            <th class="p-2">Phone</th>
                            <td class="p-2"><span id="enquiryPhone"></span></td>
                        </tr>

                        <tr>
                            <th class="p-2">Email</th>
                            <td class="p-2"><span id="enquiryEmail"></span></td>
                        </tr>

                        <tr>
                            <th class="p-2">Message</th>
                            <td class="p-2"><span id="enquiryMessage"></span></td>
                        </tr>

                        <tr>
                            <th class="p-2">Date of enquiry</th>
                            <td class="p-2"><span id="enquiryDate"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#enquiry_table').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });


        $('#markContact').on('click', function(e) {
            e.preventDefault();
            let enquiry_id = $('#markContact').data('id');
            let enquiry_status = $('#markContact').data('status');

            $('#markContact').text('Please wait...');
            $('#markContact').attr('disabled', true);
            $.ajax({
                url: "{{ route('admin.mark.enquiry') }}",
                type: 'POST',
                data: {
                    'enquiry_id': enquiry_id,
                    'enquiry_status': enquiry_status
                },
                success: function(data) {
                    location.reload(true);
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 500 || xhr.status == 422) {
                        toastr.error('Oops! Something went wrong.');
                        $('#markContact').text('Mark as Reached');
                        $('#markContact').attr('disabled', false);
                    }
                }
            });
        });
    </script>

    <script>
        $('.openModalBtn').on('click', function() {
            $('#enquiryName').text($(this).data('name'));
            $('#enquiryPhone').text($(this).data('phone'));
            $('#enquiryEmail').text($(this).data('email'));
            $('#enquiryMessage').text($(this).data('message'));
            $('#enquiryDate').text($(this).data('doe'));
            $('#viewFullDetailsModal').modal('show');
        })
    </script>

@endsection
