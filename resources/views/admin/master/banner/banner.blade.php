@extends('layout.admin.layout.admin')

@section('title', 'Banner')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span> Banner
    </h3>
    <nav aria-label="breadcrumb p-2">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('admin.create.banner') }}" class="btn btn-gradient-primary btn-fw">Add Banner</a>
                {{-- <span></span>Overview <i
                    class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> --}}
            </li>
        </ul>
    </nav>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Banner List</h4>
            </p>
            <div class="table-responsive">
                <table class="table table-bordered" id="banner-table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Banner name </th>
                            <th> Banner </th>
                            <th> Status </th>
                            <th> Description </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $key => $item)
                        <tr>
                            <td> {{ $key + 1 }} </td>
                            <td> @if($item->name){!! Illuminate\Support\Str::limit(strip_tags($item->name), $limit = 50,
                                $end = '...')
                                !!}@else NA @endif </td>
                            <td>
                                <img src="{{ asset($item->banner_image) }}" alt="" srcset="">
                            </td>
                            <td>
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
                            </td>
                            <td>
                                {{-- {!! $item->description !!} --}}
                                {{-- {!! Illuminate\Support\Str::limit($item->description, 100, ' ...')!!} --}}
                                @if($item->description) {!!
                                Illuminate\Support\Str::limit(strip_tags($item->description), $limit = 50, $end =
                                '...') !!}@else NA @endif
                            </td>
                            <td>
                                <a href="{{route('admin.edit.banner',Crypt::encrypt($item->id))}}"
                                    title="View Details"><i class="mdi mdi-grease-pencil"></i></a>
                                <a href="{{route('admin.delete.banner',Crypt::encrypt($item->id))}}"
                                    title="Delete Banner" onclick="return confirm('Are you sure,you want to delete this banner?')"><i class="mdi mdi-delete"></i></a>

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
    $(document).ready( function () {
            $('#banner-table').DataTable({
                "processing": true,
                "searching" : false,
                "ordering" : false
            });
        });
    $(document.body).on('change', '#testingUpdate', function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var cart_id = $(this).data('id');
            // console.log(status);
            var formDat = {
                catId: cart_id,
                active: status
            }
            // console.log(formDat);
            $.ajax({
                type: "post",

                url: "{{ route('admin.active.banner') }}",
                data: formDat,

                success: function(data) {
                toastr.success(data.message);
            }
            });
        });
</script>


@endsection