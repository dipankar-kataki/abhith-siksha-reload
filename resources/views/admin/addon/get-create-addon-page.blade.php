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
                            <select name="type" id="addonType" class="form-control" required>
                                <option value=''>- Select -</option>
                                <option value="pdf">PDF</option>
                                {{-- <option value="mcq">Multiple Choice Question</option> --}}
                                <option value="image">Image</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Enter Price</label>
                            <input type="number" name="price" class="form-control" placeholder='e.g 1000'  min="1" max="99999" maxlength="5" required>
                        </div>
                        <div class="mb-3">
                            <label>Upload File</label>
                            <input type="file" class="form-control" id="addonFile" name="addonFile" accept=".png, .jpg, .jpeg, .pdf" required>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-md btn-success" type="submit" id="createAddonBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('#createAddonForm').on('submit', function(e){
            e.preventDefault();

            $('#createAddonBtn').attr('disabled', true);
            $('#createAddonBtn').text('Please Wait...');

            let addonType = $('#addonType').val();
            let addonFile = $('#addonFile').val();
            let addonFileExtension = addonFile.split('.')[1];

            // console.log('addonType ===>', addonType);
            // console.log('addonFileExtension ===>', addonFileExtension);

            if(addonType == 'pdf' && (addonFileExtension != 'pdf') ){
                Swal.fire({
                    icon:'error',
                    text:'Oops! Addon type and selected addon file must be of same extension.'
                });

                $('#createAddonBtn').attr('disabled', false);
                $('#createAddonBtn').text('Submit');
            }else if( (addonType == 'image') && ( !(addonFileExtension == 'jpg' || addonFileExtension == 'jpeg' || addonFileExtension == 'png') ) ){
                Swal.fire({
                    icon:'error',
                    text:'Oops! Addon type and selected addon file must be of same type.'
                });

                $('#createAddonBtn').attr('disabled', false);
                $('#createAddonBtn').text('Submit');
            }else{
                let formData = new FormData(this);
           
                formData.append('addonFile', addonFile );

                $.ajax({
                    url:"{{route('admin.create.addon')}}",
                    type:"POST",
                    contentType:false,
                    processData:false,
                    data:formData,
                    success:function(response){
                        if(response.status === 1){

                            Swal.fire({
                                icon:'success',
                                text:response.message
                            });

                            console.log(response.data)

                            $('#createAddonForm')[0].reset();
                            $('#createAddonBtn').attr('disabled', false);
                            $('#createAddonBtn').text('Submit');
                        }else{
                            $('#createAddonBtn').attr('disabled', false);
                            $('#createAddonBtn').text('Submit');
                        }
                    },
                    error:function(xhr, status, error){
                        $('#createAddonBtn').attr('disabled', false);
                        $('#createAddonBtn').text('Submit');
                    }
                });
            }
        });
    </script>
@endsection
