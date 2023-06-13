@extends('layout.website.website') @section('title', 'My Account')

@section('head')
    <link href="{{ asset('asset_website/css/my_account.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        main {
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <div class="mcq-head d-flex">
        <div class="mcq-head-text d-flex">
            <div class="mcq-head-icon mr-3">
                <img src="{{ asset('asset_website/img/mcq.png') }}" alt="" />
            </div>
            <div class="mcq-header-text">
                <h3>
                    Multiple Choice Questions For Class
                    {{ $set->assignClass->class }} {{ $set->subject_name }}
                    <span>{{ $set->board->exam_board }} Board</span>
                </h3>
                <p><span class="mcq_question_number"></span></p>
            </div>
        </div>
        <div class="mcq-cross-icon">
            <button onclick="mcqFinalSubmit()" type="button" class="btn" id="submit_test">Submit Test</button>
        </div>
    </div>
    <div class="container-fluid" id="mcq-question">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="mcq-question"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection @section('scripts')

    <script>
        $(document).ready(function() {

            $('#submit_test').prop('disabled', true);
            var page = 1;
            var set_id = @json($set['id']);
            var last = @json($total_question);
            var type = "start";
            var question_answer = null;
            var user_practice_test_store_id = null;
            var question_id = null;
            getQuestion(page, set_id, last, type, question_answer, user_practice_test_store_id, question_id);
        });

        function nextQuestion(current_page) {
            var current_page = current_page;
            $('#submit_test').prop('disabled', false);
            var question_answer = $("input[name='question_option']:checked").val();

            if (question_answer == undefined) {
                toastr.error('Please selete an answer first');
            } else {
                toastr.success('Answer submited successfully');
            }
            var question_id = document.getElementById('question_id').value;
            var user_practice_test_store_id = $("#user_practice_test_store_id").val();
            var page = current_page + 1;
            var set_id = @json($set['id']);
            var last = @json($total_question);
            var type = "next";

            getQuestion(page, set_id, last, type, question_answer, user_practice_test_store_id, question_id);
        }

        function skipQuestion(current_page) {

            $('#submit_test').prop('disabled', false);
            var current_page = current_page;
            var page = current_page + 1;
            var set_id = @json($set['id']);
            var last = @json($total_question);
            var type = "skip";
            var user_practice_test_store_id = $("#user_practice_test_store_id").val();
            if (current_page == last) {
                toastr.error('Please submit your answer by clicking the submit button.');
                // var question_answer = $("input[name='question_option']:checked").val();
                // mcqSubmit();

            } else {
                var question_answer = null;
            }
            var question_id = document.getElementById('question_id').value;
            getQuestion(page, set_id, last, type, question_answer, user_practice_test_store_id, question_id);
        }

        function getQuestion(page, set_id, last, type, question_answer, user_practice_test_store_id, question_id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                url: "{{ route('website.subject.mcqgetquestion') }}",
                type: "POST",
                data: {
                    "page": page,
                    "set_id": set_id,
                    "last": last,
                    "type": type,
                    "question_answer": question_answer,
                    "user_practice_test_store_id": user_practice_test_store_id,
                    "question_id": question_id

                },
                success: function(response) {

                    var code = response.result.code;
                    var result = response.result.result;


                    if (code == 200) {
                        var question_option = result.mcq_question.options;
                        var mcq_question = ``;
                        var mcq_button = ``;

                        mcq_question = `<h4>Question ${result.page}: ${result.mcq_question.question}</h4>
                                <form action="" class="mcq-options d-flex">
                                    <div class="mcq-option-text">
                                        <h5>Option:</h5>
                                    </div>
                                    <div class="mcq-option-div">
                                        <div class="options">
                                            <input type="radio" id="question_option1" name="question_option" value="${question_option[0]}" required="required">
                                        <label for="question_option1">${question_option[0]}</label>
                                        </div>
                                        <div class="options">
                                            <input type="radio" id="question_option2" name="question_option" value="${question_option[1]}" required="required">
                                            <label for="question_option2">${question_option[1]}</label>
                                        </div>
                                        <div class="options">
                                            <input type="radio" id="question_option3" name="question_option" value="${question_option[2]}" required="required">
                                            <label for="question_option3">${question_option[2]}</label>
                                        </div>
                                        <div class="options">
                                            <input type="radio" id="question_option4" name="question_option" value="${question_option[3]}" required="required">
                                            <label for="question_option4">${question_option[3]}</label>
                                        </div>
                                        <input type="hidden" name="user_practice_test_store_id" id="user_practice_test_store_id" value="${response.result.result.user_practice_test_store}">
                                        <input type="hidden" name="question_id" id="question_id" value="${result.mcq_question.id}">
                                    </div>
                                </form>
                                <div class="mcq-button"></div>
                               `;


                        $('.mcq-question').html(mcq_question);
                        if (page == last) {
                            mcq_button = ` <div class="mcq-submit-btn d-flex">
                                    <div class="mcq-submit">
                                        <button type="button" class="btn btn-outline-success mcq-btn-width mr-2" onclick="skipQuestion(${result.page})">Skip</button>
                                    </div>
                                </div>`;
                            $('.mcq-button').html(mcq_button);
                        } else {
                            mcq_button = ` <div class="mcq-submit-btn d-flex">
                                    <div class="mcq-submit">
                                        <button type="button" class="btn btn-outline-success mcq-btn-width mr-2" onclick="skipQuestion(${result.page})">Skip</button>
                                    </div>
                                    <div class="mcq-next">
                                        <button type="button" class="btn btn-primary mcq-btn-width" onclick="nextQuestion(${result.page})">Next</button>
                                    </div>
                                </div>`;
                            $('.mcq-button').html(mcq_button);
                        }

                    }

                }
            });

        }

        function mcqSubmit() {

            var user_practice_test_store_id = $("#user_practice_test_store_id").val();
            const redirectURL = "{{ route('website.subject.mcqresult') }}" + "?id=" + user_practice_test_store_id;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                url: "{{ route('website.subject.totalAttempt') }}",
                type: "POST",
                data: {
                    "user_practice_test": user_practice_test_store_id,

                },
                success: function(response) {

                    if (response.code == 401) {
                        toastr.error('You have to submit atleast one answer.');
                    } else {
                        window.location.href = redirectURL
                    }
                }
            });

            // return 0;

        }

        function mcqFinalSubmit() {
            var question_id = document.getElementById('question_id').value;
            var question_answer = $("input[name='question_option']:checked").val();
            var user_practice_test_store_id = $("#user_practice_test_store_id").val();
            var set_id = @json($set['id']);
            if (question_answer == undefined) {
                question_answer == null;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                url: "{{ route('website.subject.mcqSubmit') }}",
                type: "POST",
                data: {
                    "set_id": set_id,
                    "question_answer": question_answer,
                    "user_practice_test_store_id": user_practice_test_store_id,
                    "question_id": question_id

                },
                success: function(response) {
                    if (response.code == 200) {
                        const redirectURL = "{{ route('website.subject.mcqresult') }}" + "?id=" +
                            user_practice_test_store_id;

                        window.location.href = redirectURL
                    } else {
                        toastr.error('You have to submit atleast one answer.');
                    }
                }
            });
        }
    </script>
@endsection
