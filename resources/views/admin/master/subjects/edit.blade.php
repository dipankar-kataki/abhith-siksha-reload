@extends('layout.admin.layout.admin')



@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Subject</h4>
        <form class="forms-sample" action="{{route('admin.editing.subject')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{\Crypt::encrypt($subject->id)}}">
            <div class="form-group">
            <label for="exampleInputName1">Name</label>
            <input type="text" class="form-control" id="subject_name" name="name" placeholder="Enter Subject Name" value="{{$subject->name}}" required>
            @error('name')
              <span style="color:red;">{{$message}}</span>
            @enderror
          </div>
          <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        </form>
      </div>
    </div>
  </div>
  
@endsection
@section('scripts')
    @if (session('subject_update_message'))
        <script>
            toastr.success('{!! session('subject_update_message') !!}');
        </script>
    @endif
@endsection
