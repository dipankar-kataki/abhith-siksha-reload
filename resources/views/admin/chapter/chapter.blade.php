@extends('layout.admin.layout.admin')

@section('title','Chapter')

@section('head')
<style>
    .width-100{
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Create Chapter </h4>
            <form id="createCourse" action="{{route('admin.creating.chapter')}}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{\Crypt::encrypt($course_id)}}">
                <table class="table table-bordered footable footable-1 footable-paging footable-paging-center breakpoint-lg" id="dynamic_field" style="">
                    <tbody>
                        <tr>
                            <td class="footable-first-visible" style="width: 40%; display: table-cell;"><input type="text" name="name[]" placeholder="Enter Chapter" class="form-control name_list" required></td>
                            <td class="footable-first-visible" style="width: 40%; display: table-cell;"><input type="text" name="price[]"  placeholder="Enter Price" class="form-control name_list" pattern="\d+(\.)?(\d+)?" maxlength="4" title="Value should be a format of price. E.g 22 or 22.9" required></td>
                            <td class="footable-last-visible" style="display: table-cell;"><button type="button" name="add" id="adding" class="btn btn-success" style="width: 100%;">Add More</button></td>
                        </tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>

                <button class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
</div>

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modify Chapter <span style="float:right;margin-right:10px;"> {{$chapters->links()}}</span></h4>
            <form id="updateChapter">
                @csrf
                <input type="hidden" name="course_id"  id="course_id" value="{{\Crypt::encrypt($course_id)}}">
                <table class="table table-bordered footable footable-1 footable-paging footable-paging-center breakpoint-lg" id="dynamic_field" style="">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th class="ml-5">Chapter Name</th>
                            <th>Price</th>
                            <th>Status</th>
                            @if (!$chapters->isEmpty())
                                <th>
                                    <button type="button" class="btn btn-success editChapterBtn width-100">Edit</button>
                                    <button type="button" class="btn btn-warning cancelEditChapterBtn" style="display:none;color:black;">Cancel Edit</button>
                                </th>
                            @endif
                        </tr>
                        @forelse ($chapters as $key => $item)
                            <tr>
                                <td>{{$chapters->firstItem() + $key}}</td>
                                <td class="footable-first-visible" style="width: 40%; display: table-cell;">
                                    <input type="text" name="name"  placeholder="Enter Chapter" class="form-control name_list chapterName" id="cname_{{$item->id}}" value="{{$item->name}}"  required>
                                </td>
                                <td class="footable-first-visible" style="width: 40%; display: table-cell;">
                                    <input type="text" name="price"  placeholder="Enter Price" class="form-control name_list chapterPrice" id="cprice_{{$item->id}}" pattern="\d+(\.)?(\d)?" title="Value should be a format of price. E.g 22 or 22.9" value="{{$item->price}}" required>
                                </td>
                                <td>
                                    @if ($item->is_activate == 1)
                                        <label class="switch">
                                            <input type="checkbox" id="chapterVisibilityUpdate" data-id="{{$item->id}}" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    @else
                                        <label class="switch">
                                            <input type="checkbox" id="chapterVisibilityUpdate" data-id="{{$item->id}}">
                                            <span class="slider round"></span>
                                        </label>
                                    @endif
                                </td>
                                <td class="footable-last-visible" style="display: table-cell;">
                                    <button type="button" name="add"  class="btn btn-success updateChapterBtn width-100" data-id="{{$item->id}}" data-name="" data-price="{{$item->price}}" style="display:none;">Update</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="text-center">
                                        <h3>No Chapter's Found</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                    <tfoot>
                        
                    </tfoot>
                </table>
                

                {{-- <button class="btn btn-primary mt-3">Submit</button> --}}
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
      var i = 1;
    $(document).on('click', '#adding', function() {
        i++;
        $('#dynamic_field').append('<tr id="row' + i + '" class="dynamic-added"><td><input type="text" name="name[]" placeholder="Enter Chapter" class="form-control name_list" required/></td><td><input type="text" name="price[]" placeholder="Enter Price" class="form-control name_list" required/></td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove" style="width: 100%;">Remove</button></td></tr>');
    });
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });


    /**********************************  Update Chapter Details *********************************/

    $('.chapterName').attr('disabled',true);
    $('.chapterPrice').attr('disabled',true);

    $('.editChapterBtn').on('click',function(e){
        e.preventDefault();
        $('.editChapterBtn').css('display','none');
        $('.cancelEditChapterBtn').css('display','block');
        $('.updateChapterBtn').css('display','block');
        $('.chapterName').attr('disabled',false);
        $('.chapterPrice').attr('disabled',false);

    });

    $('.cancelEditChapterBtn').on('click',function(e){
        e.preventDefault();
        $('.cancelEditChapterBtn').css('display','none');
        $('.editChapterBtn').css('display','block');
        $('.updateChapterBtn').css('display','none');
        $('.chapterName').attr('disabled',true);
        $('.chapterPrice').attr('disabled',true);
        location.reload(true);
    });

   

    $('.updateChapterBtn').on('click', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let cName = $('#cname_'+id).val();
        let cPrice = $('#cprice_'+id).val();
        let regex = new RegExp(" \d+(\.)?(\d)?");
        if(cName.length == 0){
            toastr.error('Chapter name is required');
        }else if(cPrice.length == 0){
            toastr.error('Chapter price is required');
        }else if(cPrice.length > 4){
            toastr.error('Chapter price should not be greater than 4 digits');
        }else if(/^(\d+)(\.)?(\d+)?$/.test(cPrice) == false){
            toastr.error('Chapter price should be natural number or a decimal number');
        }else{
            $.ajax({
                url:"{{route('admin.edit.chapter')}}",
                type:'POST',
                data:{
                    'course_id' : $('#course_id').val(),
                    'itemId' : id,
                    'chapterName' : cName,
                    'chapterPrice' : cPrice,
                    "_token": "{{ csrf_token() }}",
                },
                success:function(data){
                    console.log(data.message)
                    toastr.success(data.message);
                    location.reload(true);
                },
                error:function(xhr,status,error){
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Oops! Something went wrong while updating.');
                    }
                }
            });
        }
       
        // alert($(this).data('id'));
    })


    $(document.body).on('change', '#chapterVisibilityUpdate', function() {
        let status = $(this).prop('checked') == true ? 1 : 0;
        let chapter = $(this).data('id');
        let formData = {
            'chapter': chapter,
            'active': status
        }
        $.ajax({
            type: "post",

            url: "{{route('admin.change.visibility.chapter') }}",
            data: formData,

            success: function(result) {
                toastr.success(result.message);
            },
            error:function(xhr, status, error){
                if(xhr.status == 500 || xhr.status == 422){
                    toastr.error('Oops! Something went wrong while changing status.');
                }
            }
        });
    });
      
   





</script>


@if (session('success'))
    <script>
        toastr.success('{!! session('success') !!}');
    </script>
@endif

@if (session('error'))
    <script>
        toastr.error('{!! session('error') !!}');
    </script>
@endif

@endsection
