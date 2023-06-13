@extends('layout.admin.layout.admin')

@section('title', 'Blog')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-book"></i>
            </span> Read Blog
        </h3>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4 text-center">{{$blog->name}}</h3>
                {{-- Blog image --}}
                <div class="text-center">
                    <img class="rounded w-100" src="{{ asset($blog->blog_image) }}" height="500" alt="">
                </div>

                <p class="mt-4">
                    {!!$blog->blog!!}
                </p>
            </div>
            </div>
    </div>


@endsection


{{-- scripts --}}
@section('scripts')
@endsection
