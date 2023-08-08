@extends('layout.admin.layout.admin')

@section('title', 'Addons List')

@section('head')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            </span>
            Addons List
        </h3>
        {{-- <nav aria-label="breadcrumb p-2">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.testimonial.add') }}" class="btn btn-gradient-primary" id="addNewTestimonial">Add New</a>
                </li>
            </ul>
        </nav> --}}
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="addon_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th> Sl. No.</th>
                                <th> Name </th>
                                <th> Addon Type </th>
                                <th>Price</th>
                                <th>File</th>
                                <th>Related To Subject</th>
                                <th>Subject Name</th>
                                <th>For Class</th>
                                <th>Exam Board</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd('Addons List ===>', $addonList) --}}
                            @foreach ($addonList as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><span style="text-transform:capitalize;">{{$item->type}}</span></td>
                                    <td> <span class="mdi mdi-currency-inr text-success"></span>{{ $item->price}}</td>
                                    <td>
                                        @if ($item->type == 'pdf')
                                            <a href="{{asset($item->file_path)}}" target="_blank" style="text-decoration: none;">View PDF</a>
                                        @else
                                            <a href="{{asset($item->file_path)}}" target="_blank" style="text-decoration: none;">View Image</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->subject_id == null)
                                            <span class="text-danger">NO</span>
                                        @else
                                            <span class="text-success">YES</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->assignSubject != null)
                                            <span>{{$item->assignSubject->subject_name}}</span>
                                        @else
                                            <span class="text-muted">Not Provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->assignClass != null)
                                            <span>{{$item->assignClass->class}}</span>
                                        @else
                                            <span class="text-muted">Not Provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->boards != null)
                                            <span>{{$item->boards->exam_board}}</span>
                                        @else
                                            <span class="text-muted">Not Provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 1)
                                            <a href="javascript:void(0);" class="text-success" style="text-decoration: none">Active</a>
                                        @else
                                            <a href="javascript:void(0);" class="text-danger" style="text-decoration: none">Inactive</a>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->status == 1)
                                            <a href="#" class="btn btn-xs btn-danger change-status-btn" style="text-decoration: none" data-id="{{$item->id}}" data-status=0>Click To Deactive</a> 
                                        @else
                                            <a href="#" class="btn btn-xs btn-primary change-status-btn" style="text-decoration: none"  data-id="{{$item->id}}" data-status=1>Click To Active</a>
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

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#addon_table').DataTable({
                "processing": true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });

        $('.change-status-btn').on('click', function() {
            let status = $(this).data('status');
            let id = $(this).data('id');

            $.ajax({
                url:"{{route('admin.change.addon.status')}}",
                type:"POST",
                data:{
                    status : status,
                    id : id
                },
                success:function(response){

                    if(response.status == 1){
                        Swal.fire({
                            icon: 'success',
                            text: response.message
                        })

                        window.location.reload(true)
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: response.message
                        })
                    }
                     
                },error:function(xhr, status, error){
                    console.log(error)
                }
            })
        });
    </script>
@endsection
