@extends('layout.admin.layout.admin')

@section('title', 'Homepage Youtube Link')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span>Homepage Youtube Link
    </h3>
    {{-- <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.create.blog') }}" class="btn btn-gradient-primary btn-fw">Add Blog</a>

            </li>
        </ul>
    </nav> --}}
</div>

<div class="col-md-12">
    <div class="card mt-5">
        <div class="card-body">
            <p style="font-size:14px;font-weight:500;">
                {{$link != null ? "Active Link : ". $link : "No Active Link"}}
            </p>
            <form action="{{route('admin.change.homepage.media.link')}}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label for="" class="mb-2">Add Link</label>
                    <input type="text" class="form-control" name="media_link" placeholder="Add link https://...">
                </div>
                <div class="form-group mb-2">
                    <button type="submit" class="btn btn-md btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    
</div>

@endsection

{{-- scripts --}}
@section('scripts')
    @if (session('success'))
        <script>
            toastr.success('{!! session('success') !!}');
        </script>
    @endif
@endsection