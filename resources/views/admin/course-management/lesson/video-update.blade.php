<div class="modal fade" id="displayVideoModal" tabindex="-1" role="dialog" aria-labelledby="displayImageModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="displayImageModalTitle"><span id="document-modal-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-md-offset-2">
                            <span id="displayLessonVideo"></span>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lessonVideoUpdate">Change Lesson Video</label>
                                <br>&nbsp;<input type="file" class="filepond p-4" name="lessonVideoUpdate"
                                    id="lessonVideoUpdate" data-max-file-size="1MB" data-max-files="1" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>