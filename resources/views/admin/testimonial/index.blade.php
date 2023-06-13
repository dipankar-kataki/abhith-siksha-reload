@extends('layout.admin.layout.admin')

@section('title', 'Testimonial')

@section('head')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span>
            Testimonial
        </h3>
        <nav aria-label="breadcrumb p-2">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.testimonial.add') }}" class="btn btn-gradient-primary" id="addNewTestimonial">Add New</a>
                </li>
            </ul>
        </nav>
    </div>

    @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('alert-type') }} alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('message') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">

        @forelse ($data as $item)
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if ($item->image == '')
                            <img src="{{ asset('default.png') }}" alt="" width="200" height="200"
                                style="object-fit: cover">
                        @else
                            <img src="{{ asset($item->image) }}" alt="" width="200" height="200"
                                style="object-fit: cover">
                        @endif

                        <h5 class="mt-4">{{ $item->name }}</h5>
                        <p> - {{ $item->qualification }}</p>
                        <p>"{{ $item->message }}"
                        </p>

                        <button class="btn btn-danger deleteBtn" data-id="{{ Crypt::encrypt($item->id) }}">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-6 grid-margin stretch-card">
                <p>*No data found</p>
            </div>
        @endforelse
    </div>

@endsection

@section('scripts')
    <script>
        $('.deleteBtn').on('click', function() {
            let btn = $(this);
            let id = $(this).data('id');
            let url = "{{ route('admin.testimonial.delete') }}";

            btn.text('Please wait...');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            id: id
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    btn.text('Delete');
                                    location.reload();
                                })
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message,
                                    'error'
                                )
                            }
                        },

                        error: function(data) {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            )
                        }
                    });
                } else {
                    btn.text('Delete');
                }
            })
        })
    </script>
@endsection
