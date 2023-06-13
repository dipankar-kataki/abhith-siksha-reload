@extends('layout.admin.layout.admin')

@section('title', 'Reported Post')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi  mdi-alert menu-icon"></i>
            </span> Reported Posts
        </h3>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {{-- <h4 class="card-title">Subjects List</h4> --}}
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Post Name </th>
                                <th>Number Of Reports</th>
                                <th>Report Reason</th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportedPosts as $key => $item)
                                <tr>
                                    <td> {{ $key + 1 }} </td>
                                    <td id="postId">
                                        <a href="{{route('website.knowledge.details.post',['id' => Crypt::encryptString($item->knowledge_forum_post_id)])}}" target="_blank">
                                            {!! Illuminate\Support\Str::limit(strip_tags($item->knowledgeForumPost->question), $limit = 50, $end = '...') !!}
                                        </a>
                                    </td>
                                    <td>{{ $item->report_count }}</td>
                                    <td>
                                        @php
                                            $all_reason = implode(', ', array_unique($item->report_reason));
                                            echo $all_reason;
                                        @endphp
                                    </td>
                                    <td>
                                        @if ($item->is_activate == 1)
                                            <label class="switch">
                                                <input type="checkbox" id="postStatusUpdate" data-id="{{ $item->knowledge_forum_post_id }}" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        @else
                                            <label class="switch">
                                                <input type="checkbox" id="postStatusUpdate" data-id="{{ $item->knowledge_forum_post_id }}">
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center">No Reported Post Found.</div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    <div style="float: right;margin-top:10px;">
                        {{$reportedPosts->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- scripts --}}
@section('scripts')
    <script>
        $(document.body).on('change', '#postStatusUpdate', function() {
            let status = $(this).prop('checked') == true ? 1 : 0;
            let post_id = $(this).data('id');
            let formData = {
                'post_id': post_id,
                'active': status
            }
            $.ajax({
                type: "post",

                url: "{{route('website.remove.reported.post') }}",
                data: formData,

                success: function(result) {
                    // console.log(result);
                    toastr.success(result.success)
                },
                error:function(xhr, status, error){
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Oops! Something went wrong while reporting.');
                    }
                }
            });
        });
    </script>
{{-- <script>
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
                console.log(data)
            }
        });
    });
</script> --}}
@endsection
