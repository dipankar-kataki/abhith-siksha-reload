<script>
    CKEDITOR.replace( 'editorQuestion');


    $('#signupForm').on('submit',function(e){
        e.preventDefault();

        $('#signupBtn').text('Please wait...');

        let pass = $('#pwd').val();
        let confirm_pass = $('#confPwd').val();

        if(pass != confirm_pass){
            toastr.error('Oops! password not matched');
            $('#signupBtn').text('Sign up');
        }else if(pass.length < 5 ){
            toastr.error('Oops! password must be 5 characters long');
            $('#signupBtn').text('Sign up');
        }else{
            $.ajax({
                url:"{{route('website.auth.signup')}}",
                type:"POST",
                data:$('#signupForm').serialize(),
                success:function(data){
                    
                    if(data.status == '201'){
                        toastr.success(data.message);
                    }else if(data.status == '403'){
                        toastr.error(data.message);
                    }else{
                        toastr.error('Oops! Something went wrong');
                    }
                    $('#signupForm')['0'].reset();
                    $('#signupBtn').text('Sign up');

                },
                error: function(xhr, status, error) {
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Oops! something went wrong');
                    }
                    $('#signupForm')['0'].reset();
                    $('#signupBtn').text('Sign up');
                }

            });
        }

    })

    /********************** Knowledge Form Submit ************************/

    $('#knowledgeQuestionForm').on('submit',function(e){
        e.preventDefault();

        let question = $('#questionAsk').val();
        let editorQuestion = CKEDITOR.instances.editorQuestion.getData();
        let questionLink = $('#questionLink').val();

        $('#addQuestionBtn').text('Please wait...');

        if(editorQuestion.length <= 1){
            toastr.error('Description is required');
            $('#addQuestionBtn').text('Add Question');
        }else{
            $.ajax({
                url:"{{route('website.add.knowledge.question')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    'question' : question,
                    'description' : editorQuestion,
                    'link' : questionLink,
                },
                success:function(data){
                    toastr.success(data.message);
                    $('#knowledgeQuestionForm')[0].reset();
                    CKEDITOR.instances.editorQuestion.setData('');
                    $('#addQuestionBtn').text('Add Question');
                    $('#add-question-modal').modal('hide');
                    location.reload(true);

                },
                error:function(xhr, status, error){
                    if(xhr.status == 500 || xhr.status == 422){
                        toastr.error('Oops! something went wrong');
                    }
                    $('#addQuestionBtn').text('Add Question');
                }
            });
        }

    });

    $('#cancelAddQuestionBtn').on('click',function(){
        $('#knowledgeQuestionForm')[0].reset();
        CKEDITOR.instances.editorQuestion.setData('');
    });


    /********************* For Website Add BLog **********************/
    CKEDITOR.replace('websiteAddBlogEditor');

        FilePond.registerPlugin(

            FilePondPluginFileEncode,

            FilePondPluginFileValidateSize,

            FilePondPluginImageExifOrientation,

            FilePondPluginImagePreview,

            FilePondPluginFileValidateType
        );

        // Select the file input and use create() to turn it into a pond
        let pond = FilePond.create(
            document.getElementById('banner_pic'), {
                allowMultiple: true,
                maxFiles: 5,
                instantUpload: false,
                imagePreviewHeight: 135,
                acceptedFileTypes: ['image/*'],
                labelFileTypeNotAllowed:'File of invalid type. Acepted types are png and jpeg/jpg.',
                labelIdle: '<div style="width:100%;height:100%;margin-top:10px;"><p> Drag &amp; Drop your files or <span class="filepond--label-action" tabindex="0">Browse</span><br> Maximum number of image is 1 :</p> </div>',
            }
        );

        $('#websiteBlogForm').on('submit',function(e){
            e.preventDefault();

            $('.websiteAddBlogBtn').text('Please wait...');
            let formdata = new FormData(this);

            let websiteAddBlogEditor = CKEDITOR.instances.websiteAddBlogEditor.getData();

            pondFiles = pond.getFiles();
            for (var i = 0; i < pondFiles.length; i++) {
                // append the blob file
                formdata.append('pic', pondFiles[i].file);
            }

            formdata.append('description', websiteAddBlogEditor);

            if(websiteAddBlogEditor.length <= 1){
                toastr.error('Blog Description is required');
                $('.websiteAddBlogBtn').text('Create');
            }else if(pondFiles[0].status != 2){
                toastr.error('Not a valid image.');
            }else{
                $.ajax({
                    url:"{{route('website.blog.create')}}",
                    type:'POST',
                    processData: false,
                    contentType: false,
                    data:formdata,
                    success:function(result){
                        console.log(result)
                        toastr.success(result.blogMessage)
                        $('#websiteBlogForm')[0].reset();
                        CKEDITOR.instances.websiteAddBlogEditor.setData('');
                        $('.websiteAddBlogBtn').text('Create');
                        $('#websiteAddBlogModal').modal('hide');
                        $('#websiteAddBlogConfirmationModal').modal('show');
                        pond.removeFile();
                    },
                    error:function(xhr, status, error){
                        if(xhr.status == 500 || xhr.status == 422){
                            toastr.error('Oops! Something went wrong while creating blog.');
                            $('.websiteAddBlogBtn').text('Create');
                        }
                    }
                });
            }
        });

        $('.websiteCancelBlogBtn').on('click',function(){
            $('#websiteBlogForm')[0].reset();
            CKEDITOR.instances.websiteAddBlogEditor.setData('');
            $('#websiteAddBlogModal').modal('hide');
            pond.removeFile();
        });



        /***************************** Report Post Modal **************************/
        $('.reportModalLink').click(function(){
            let postId = $(this).data('id');
            
            $('.close-post-modal').on('click',function(){
                $('#reason_of_report').prop('selectedIndex',0);
            })

            
            $('.reportPostButton').on('click',function(e){
                e.preventDefault();
                let reason_of_report = $('#reason_of_report_post').val();
                if(reason_of_report == null || reason_of_report.length == 0){
                    toastr.error('Reason of reporting is required. Please select one from the dropdown');
                }else{
                    $.ajax({
                        url:"{{route('website.report.knowledge.post')}}",
                        type:'POST',
                        data:{ '_token': '{{ csrf_token() }}','postId' : postId, 'reason_of_report' : reason_of_report},
                        success:function(result){
                            toastr.success(result.success);
                            $('#ReportPostModal').modal('hide');
                            $('#reason_of_report').prop('selectedIndex',0);
                            location.reload(true);
                        },
                        error:function(xhr, status, error){
                            if(xhr.status == 500 || xhr.status == 422){
                                toastr.error('Oops! Something went wrong while reporting.');
                            }
                        }
                    });
                }
            });
        });


        /***************************** Report Blog Modal **************************/
        $('.reportBlogModal').click(function(){
            let blogId = $(this).data('id');

            $('.close-blog-modal').on('click',function(){
                $('#reason_of_report').prop('selectedIndex',0);
            })

            $('.reportBlogButton').on('click',function(e){
                e.preventDefault();

                let reason_of_report = $('#reason_of_report').val();
                if(reason_of_report == null || reason_of_report.length == 0){
                    toastr.error('Reason of reporting is required. Please select one from the dropdown');
                }else{
                    $.ajax({
                        url:"{{route('website.blog.report')}}",
                        type:'POST',
                        data:{ '_token': '{{ csrf_token() }}','blogId' : blogId, 'reason_of_report' : reason_of_report},
                        success:function(result){
                            // console.log(result);
                            toastr.success(result.success);
                            $('#ReportBlogModal').modal('hide');
                            $('#reason_of_report').prop('selectedIndex',0);
                            location.reload(true);
                        },
                        error:function(xhr, status, error){
                            if(xhr.status == 500 || xhr.status == 422){
                                toastr.error('Oops! Something went wrong while reporting.');
                            }
                        }
                    });
                }
            });
        });


        /*********************** Share Post *********************/
        $('.copy-link-share-btn').on('click',function(e){
            e.preventDefault();
            navigator.clipboard.writeText(window.location.href);
            $('#linkCopiedConfirmation').text('Copied');
            setTimeout(function(){
                $('#sharePostModal').modal('hide')
            },2000);
        });
        
</script>