<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="">Lesson Name<span class="text-danger">*</span></label>
            <input type="text" name="lesson_name" id="lesson_name" class="form-control" value="{{$lesson->name??''}}" disabled>
            <span class="text-danger"></span>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="">@yield('lesson-type')Resource Name<span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" placeholder="e.g Perimeter and Area" value="">
            <span class="text-danger">{{ $errors->first('board_id') }}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="">@yield('lesson-type') Resource Type(Pdf/video/article)<span
                    class="text-danger">*</span></label>

            <select name="resource_type" id="resource_type" class="form-control" onchange="showDiv()">
                <option value="">-- Select -- </option>
                <option value="1">File Attachement(pdf)</option>
                <option value="2">Video</option>
                <option value="3">Article</option>
                <option value="4">Practice Test</option>
            </select>

        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="">@yield('lesson-type') Assign Teacher</label>

            <select  id="teacher_id" class="form-control" name="teacher_id">
                <option value="">-- Select -- </option>
                @foreach($teachers as $key=>$teacher)
                <option value="{{$teacher->user_id}}">{{$teacher->name}}</option>
                @endforeach
            </select>

        </div>
    </div>
</div>

<div class="blockquote blockquote-primary fileattachment" id="fileattachment" style="display:none;">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="">Upload File Attachement(pdf)</label>
                <div class="file-upload">
                    <div class="file-select">
                        <div class="file-select-button" id="fileName">Choose File</div>
                        <div class="file-select-name" id="noCoverImage">No file chosen...</div>
                        <input type="file" id='imageUpload' name="image_url" accept=".jpg,.jpeg,.png,.pdf"
                            value="{{asset('files/subject/placeholder.jpg')}}">
                    </div>
                </div>
                <span id="imageUrlError"></span>
            </div>
        </div>
    </div>
</div>
<div class="video" id="video" style="display:none;">
    <input type="hidden" name="duration" id="duration">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="">Upload Video Thumbnail Image</label>
                <div class="file-upload">
                    <div class="file-select">
                        <div class="file-select-button" id="fileName">Choose File</div>
                        <div class="file-select-name" id="noImageFilePromoVideo">No file chosen...</div>
                        <input type="file" id='videoThumbnailImageUpload' onchange="changeVideoImage(this);"
                            name="video_thumbnail_image_url" value="{{asset('files/subject/placeholder.jpg')}}"
                            accept=".jpg,.jpeg,.png">
                    </div>
                </div>
                <span id="imageUrlError"></span>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <img id="videothumbnailimagepreview" src="{{asset('files/subject/placeholder.jpg')}}" alt="your image"
                    height="200" width="350" controls style="" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="">Upload Video</label>
                <div class="file-upload">
                    <div class="file-select">
                        <div class="file-select-button" id="fileName">Choose File</div>
                        <div class="file-select-name" id="noFileVideo">No file chosen...</div>
                        <input type="file" id='videoUpload' name="video_url" accept="video/mp4,video/x-m4v,video/*">
                    </div>
                </div>
                <span id="videoUrlError"></span>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <video width="600" height="250" id='videoPriview' controls style="display: none;">

                </video>
            </div>
        </div>
    </div>
</div>


<div class="row" id="article" style="display:none;">
    <div class="col-12">
        <div class="form-group">
            <label for="">Write Article<span class="text-danger">*</span></label>
            <textarea class="ckeditor form-control" name="content" id="content">

            </textarea>
        </div>

    </div>
</div>
<div class="row" id="practice-test" style="display:none;">
    <div class="col-12">
        <div class="form-group">
            <p>
                <span style="color:red;">Note <sup>*</sup></span> To upload questions, proper excel
                format is required to avoid errors. Download the format by <a
                    href="{{asset('/files/mcq_format/Mcq_Upload_Format.xlsx')}}" download>Clicking Here
                    <i class="mdi mdi-cloud-download menu-icon"></i></a> &nbsp;and fillup the excel
                sheet without removing the headers.
            </p>
        </div>

        <div class="form-group">
            <label for="">Upload questions in excel format</label>
            <input type="file" name="questionExcel" class="form-control">
        </div>
    </div>
</div>