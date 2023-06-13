@extends('layout.admin.layout.admin')



@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Create Subject</h4>
        <form class="forms-sample" action="{{route('admin.creating.subject')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="exampleInputName1">Name</label>
            <input type="text" class="form-control" id="subject_name" name="name" placeholder="Enter Subject Name" value="{{old('name')}}">
            <span class="text-danger" id="name_error">{{ $errors->first('name') }}</span>
        </div>
          <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @if(session('subject_created'))
    <script>
        toastr.success("{!! session('subject_created') !!}");
    </script>
  @endif

@endsection