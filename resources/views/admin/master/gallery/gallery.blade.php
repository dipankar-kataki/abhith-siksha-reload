@extends('layout.admin.layout.admin')

@section('title', 'Gallery')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-book"></i>
            </span> Gallery
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('admin.create.gallery') }}" class="btn btn-gradient-primary btn-fw">Add Gallery</a>
                    {{-- <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i> --}}
                </li>
            </ul>
        </nav>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Gallery List</h4>
                </p>
                <table class="table table-bordered" id="gallery-table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th> Image </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gallerries as $key => $item)
                        <tr>
                            <td>{{ $gallerries->firstItem() + $key }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <img src="{{ asset($item->gallery) }}" alt="" srcset="">
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
                                    <a href="{{route('admin.edit.gallery',Crypt::encrypt($item->id))}}"
                                        title="Edit Gallery"><i class="mdi mdi-grease-pencil"></i></a>
                                    <a href="{{route('admin.delete.gallery',Crypt::encrypt($item->id))}}"
                                        title="Delete Gallery" onclick="return confirm('Are you sure,you want to delete this gallery image?')"><i class="mdi mdi-delete"></i></a>
    
                                </td>
                            

                            
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
     $(document).ready( function () {
            $('#gallery-table').DataTable({
                "processing": true,
                "searching" : false,
                "ordering" : false
            });
        });
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

            url: "{{ route('admin.active.gallery') }}",
            data: formDat,

            success: function(data) {
                console.log(data)
            }
        });
    });
</script>

@endsection
