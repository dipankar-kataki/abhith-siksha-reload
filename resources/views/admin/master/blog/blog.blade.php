@extends('layout.admin.layout.admin')

@section('title', 'Blog')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span> Blogs
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.create.blog') }}" class="btn btn-gradient-primary btn-fw">Add Blog</a>

            </li>
        </ul>
    </nav>
</div>

<div class="col-md-12">
    @if($blogs->count()>0)
    <div class="row">
        {{-- Loop starts --}}

        @foreach ($blogs as $item)

        <div class="col-md-4 mb-5">
            @if ($blog_id != null)
            @if ($blog_id == $item->id)
            <div class="card" style="box-shadow: 0px 10px 10px #979595; border: 1px solid #7ea5df;">
                <div class="card-body">
                    <div class="action-buttons">
                        @if ($item->is_activate == 1)
                        <label class="switch">
                            <input type="checkbox" id="testingUpdate" data-id="{{ $item->id }}" checked>
                            <span class="slider round"></span>
                        </label>
                        @else
                        <label class="switch">
                            <input type="checkbox" id="testingUpdate" data-id="{{ $item->id }}">
                            <span class="slider round"></span>
                        </label>
                        @endif

                        <a href="{{ route('admin.edit.blog',['id'=>\Crypt::encrypt($item->id)]) }}"
                            class="btn btn-gradient-primary btn-rounded btn-icon anchor_rounded float-right mb-3"
                            data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="mdi mdi-pencil-outline"></i>
                        </a>
                    </div>

                    <img src="{{ asset($item->blog_image) }}" width="100%" height="200" alt="" style="object-fit:cover">

                    <h4 class="mt-3">{{ Str::of($item->name)->limit(30) }}
                    </h4>
                    <p class="mt-2">
                        <span><a href="{{ route('admin.read.blog',['id'=>\Crypt::encrypt($item->id)]) }}">Read
                                more...</a></span>
                    </p>
                </div>
            </div>
            @endif
            @else
            <div class="card">
                <div class="card-body">
                    <div class="action-buttons">
                        @if ($item->is_activate == 1)
                        <label class="switch">
                            <input type="checkbox" id="testingUpdate" data-id="{{ $item->id }}" checked>
                            <span class="slider round"></span>
                        </label>
                        @else
                        <label class="switch">
                            <input type="checkbox" id="testingUpdate" data-id="{{ $item->id }}">
                            <span class="slider round"></span>
                        </label>
                        @endif

                        <a href="{{ route('admin.edit.blog',['id'=>\Crypt::encrypt($item->id)]) }}"
                            class="btn btn-gradient-primary btn-rounded btn-icon anchor_rounded float-right mb-3">
                            <i class="mdi mdi-pencil-outline"></i>
                        </a>
                    </div>

                    <img src="{{ asset($item->blog_image) }}" width="100%" height="200" alt="" style="object-fit:cover">

                    <h4 class="mt-3">{{ Str::of($item->name)->limit(30) }}
                    </h4>
                    <p class="mt-2">
                        <span><a href="{{ route('admin.read.blog',['id'=>\Crypt::encrypt($item->id)]) }}">Read
                                more...</a></span>
                    </p>
                </div>
            </div>
            @endif

        </div>
        {{-- Loop ends --}}
        @endforeach

    </div>
    @else
    <div class="card">
        <div class="card-body">

            <p class="card-description" style="text-align: center">No Record found.
            </p>

        </div>
    </div>

    @endif
</div>

@endsection

{{-- scripts --}}
@section('scripts')
<script>
    $(document.body).on('change', '#testingUpdate', function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var user_id = $(this).data('id');
        // console.log(status);
        var formDat = {
            catId: user_id,
            active: status
        }
        // console.log(formDat);
        $.ajax({
            type: "post",

            url: "{{ route('admin.active.blog') }}",
            data: formDat,

            success: function(data) {
                toastr.success(data.message);
            }
        });
    });
</script>
@endsection