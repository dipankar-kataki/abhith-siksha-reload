<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title" style="text-transform: unset">Become a Teacher</h4>
            <form class="forms-sample" id="applyForm" enctype="multipart/form-data">
                @csrf
                <p class="card-description"> Personal Details </p>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Name" name="name"
                            value="{{auth()->user()->name}}" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email"
                            value="{{auth()->user()->email}}" readonly>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" placeholder="Contact number" name="phone"
                            value="{{auth()->user()->phone}}" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="gender">Gender <span class="text-danger">*</span></label>
                        <select class="form-control" id="gender" name="gender">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="dob"> Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="dob" id="dob" class="form-control" placeholder="Date of Birth"
                            name="dob" pattern="^(0[1-9]|1[012])[-/.](0[1-9]|[12][0-9]|3[01])[-/.](19|20)\\d\\d$">
                    </div>
                </div>

                <h4 class="card-title">Professional Details</h4>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputCity">Total Experience in Year <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="total_experience_year" name="total_experience_year" min="1" max="100">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputCity">Total Experience in Month</label>
                        <input type="text" class="form-control" id="total_experience_month"
                            name="total_experience_month" min="0" max="11">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="exampleTextarea1">Highest Qualification<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="education" placeholder="Highest Qualification"
                            name="education">
                    </div>
                </div>



                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputCity">Course applying for Board <span class="text-danger">*</span></label>
                        <select name="board_id" id="assignedBoard" class="form-control" onchange="changeBoard()">
                            <option value="">-- Select -- </option>
                            @forelse ($boards as $item)
                            <option value="{{$item->id}}" @isset($lesson)@if($lesson->board_id==$item->id) selected
                                @endif
                                @endisset>{{$item->exam_board}}</option>
                            @empty
                            <option>No boards to show</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputState">Class <span class="text-danger">*</span></label>
                        <select name="assign_class_id" id="board-class-dd" class="form-control">
                            <option value="">-- Select -- </option>

                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputZip">Subject <span class="text-danger">*</span></label>
                        <select name="assign_subject_id" id="board-subject-dd" class="form-control">
                            <option value="">-- Select -- </option>

                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-6">
                        <label for="exampleTextarea1">10th Percentage <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hslc_percentage" placeholder="10th percentage"
                            name="hslc_percentage" min="10" max="100">
                    </div>
                    <div class="form-group col-6">
                        <label for="exampleTextarea1">12th Percentage <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hs_percentage" placeholder="12th percentage"
                            name="hs_percentage" min="10" max="100">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="currentOrganization">Current organization </label>
                        <input type="text" class="form-control" id="currentOrganization"
                            placeholder="Current Organinzation" name="current_organization">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ciurrentDesignation">Current Designation </label>
                        <input type="text" class="form-control" id="currentDesignation"
                            placeholder="Current Designation" name="current_designation">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="currentCTC">Current CTC </label>
                        <input type="text" class="form-control" id="currentCTC" placeholder="Current CTC"
                            name="current_ctc">
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group">
                            <label>Upload resume (Document should be in .pdf format)<span class="text-danger">*</span></label>
                            <input type="file" name="resume" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled
                                    placeholder="Upload Image" name="resume" accept=".doc,.docx,.pdf">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-gradient-primary"
                                        type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleTextarea1">Upload any Demo video (Maximum Duration 5 minutes)<span class="text-danger">*</span></label>
                    <input type="file" name="teacherdemovideo" class="file-upload-default" id="fileUp">
                    <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled
                            placeholder="Upload Demo Video" name="teacherdemovideo" accept=".mp4,.webm,">
                        <span class="input-group-append">
                            <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary me-2" id="applicationSubmit">Submit</button>
                <button class="btn btn-secondary btn-fw" id="applicationCancel" type="reset" value="Reset">Reset</button>
            </form>
        </div>
    </div>
</div>
