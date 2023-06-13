@extends('layout.admin.layout.admin')

@section('title', 'Applied Teacher')

@section('head')
@endsection

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-format-list-bulleted"></i>
            </span>
            Testimonial
        </h3>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.testimonial.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Name<sup>*</sup></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="qualification">Qualification<sup>*</sup></label>
                        <input type="text" name="qualification" id="qualification"
                            class="form-control @error('qualification') is-invalid @enderror"
                            value="{{ old('qualification') }}">
                        @error('qualification')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image"
                            class="form-control @error('image') is-invalid @enderror" accept=".jpg, .jpeg, .png">
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="message">Message<sup>*</sup></label>
                        <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="4">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection
