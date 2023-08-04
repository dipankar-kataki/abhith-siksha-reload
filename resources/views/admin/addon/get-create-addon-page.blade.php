@extends('layout.admin.layout.admin')

@section('title', 'Addon')

@section('head')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span>
            Addon
        </h3>
        {{-- <nav aria-label="breadcrumb p-2">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.testimonial.add') }}" class="btn btn-gradient-primary" id="addNewTestimonial">Add New</a>
                </li>
            </ul>
        </nav> --}}
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

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" id="createAddonForm">
                        <div class="mb-3">
                            <label>Enter Addon Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g roadmap" required>
                        </div>
                        <div class="mb-3">
                            <label>Select Addon Type</label>
                            <select name="type" id="addonType" class="form-control">
                                <option selected disabled>- Select -</option>
                                <option value="pdf">PDF</option>
                                <option value="mcq">Multiple Choice Question</option>
                                <option value="image">Image</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Upload File</label>
                            <input type="file" class="form-control" id="addonFile" name="addonFile">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-md btn-success" type="submit" id="createAddonBtn">Create</button>
                        </div>
                    </form>
                    
                    {{-- @if ($item->image == '')
                        <img src="{{ asset('default.png') }}" alt="" width="200" height="200"
                            style="object-fit: cover">
                    @else
                        <img src="{{ asset($item->image) }}" alt="" width="200" height="200"
                            style="object-fit: cover">
                    @endif --}}

                    {{-- <h5 class="mt-4">{{ $item->name }}</h5>
                    <p> - {{ $item->qualification }}</p>
                    <p>"{{ $item->message }}"
                    </p>

                    <button class="btn btn-danger deleteBtn" data-id="{{ Crypt::encrypt($item->id) }}">Delete</button> --}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection
