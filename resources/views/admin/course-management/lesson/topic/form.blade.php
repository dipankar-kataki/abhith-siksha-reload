<div class="card-body">
   
    <form id="assignLessonForm" enctype="multipart/form-data" method="post">
        @csrf
        <input type="hidden" name="parent_id" value="{{$lesson->id}}">
        <input type="hidden" name="parent_lesson_id" value="@isset($topic){{$topic->id}}@endisset">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="">@yield('form-label') Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g Perimeter and Area"
                        required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="">Upload @yield('form-label') Picture</label>
                    <input type="file" class="filepond" name="image_url" id="lessonImage"
                        data-max-file-size="1MB" data-max-files="1" />
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="">Upload @yield('form-label') Video</label>
                    <input type="file" class="filepond" name="video_url" id="lessonVideo"
                        data-max-file-size="50MB" data-max-files="50" />
                </div>
            </div>
            <div class="col-12">
                <label for="">@yield('form-label') content</label>
                <div class="form-group">
                    <textarea class="ckeditor form-control" name="content" id="content"></textarea>
                </div>

            </div>
        </div>
        <div style="float: right;">
            <button type="button" class="btn btn-md btn-default" id="assignLessonCancelBtn">Cancel</button>
            <button type="submit" class="btn btn-md btn-success" id="assignLessonSubmitBtn">Submit</button>
        </div>
    </form>
</div>