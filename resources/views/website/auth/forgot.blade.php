@extends('layout.website.auth')

@section('title', 'Forgot Password')

@section('main')

    <section class="login-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="forget-div">
                        <div class="login-logo"><img src="{{ asset('asset_website/img/home/logo.png') }}" class="w100"></div>
                        <a onclick="goBack()" class="page-close"><span class="icon-cancel-30"></span></a>
                        <div class="forget-cover">
                            <form id="sendOtpForm" class="row" action="#">
                                @csrf
                                <div class="col-lg-12">
                                    <h4 class="small-heading-grey">Forgot Password</h4>
                                </div>

                                {{-- Dropdown --}}
                                <div class="form-group col-lg-12">
                                    <select class="form-control" id="selectType" required>
                                        <option value="" selected disabled>Select type</option>
                                        <option value="1">Phone number</option>
                                        <option value="2">Email</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-12 d-none" id="phoneDiv">
                                    <input type="text" class="form-control" placeholder="10 digit phone number"
                                        id="phone" name="phone">
                                    <div id="validationFeedbackPhone" class="invalid-feedback">
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 d-none" id="emailDiv">
                                    <input type="text" class="form-control" placeholder="Email address" id="email"
                                        name="email">
                                    <div id="validationFeedbackEmail" class="invalid-feedback">
                                    </div>
                                </div>

                                <div class="form-group mb0 col-lg-12">
                                    <button type="submit" class="btn btn-block login-btn" id="submitBtn">Continue</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@section('script')
    <script>
        $("#phone").inputFilter(function(value) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 9999999999);
        });
    </script>
    <script>
        $('#selectType').on('change', function() {
            if ($(this).val() == '1') {
                $('#emailDiv').addClass('d-none');
                $('#phoneDiv').removeClass('d-none');
            } else if ($(this).val() == '2') {
                $('#phoneDiv').addClass('d-none');
                $('#emailDiv').removeClass('d-none');
            }
        })
    </script>

    <script>
        $('#sendOtpForm').on('submit', function(e) {
            e.preventDefault();

            $('#submitBtn').text('Please wait...');
            $('#submitBtn').attr('disabled', true);

            // If selected phone
            if ($('#selectType').val() == '1') {
                if ($("#phone").val().length == '') {
                    $("#phone").addClass('is-invalid');
                    $('#validationFeedbackPhone').html('Enter phone number')
                    return 0;
                } else {
                    $("#phone").removeClass('is-invalid');
                    let phone = $("#phone").val();
                    let url = "{{ url('api/user/sendotp') }}";
                    sendOtpFunction(url, '1', phone);
                }

                // Send message API
            }
            // If selected email
            else {
                if ($("#email").val().length == '') {
                    $("#email").addClass('is-invalid');
                    $('#validationFeedbackEmail').html('Enter email address')
                    return 0;
                } else {
                    $("#email").removeClass('is-invalid');
                    let email = $("#email").val();
                    let url = "{{ url('api/user/sendotp') }}";
                    sendOtpFunction(url, '2', email);
                }
            }
        });

        function sendOtpFunction(url, inputType, inputData) {
            if (inputType == '1') {
                var data = {
                    type: inputType,
                    phone: inputData,
                };
            } else {
                var data = {
                    type: inputType,
                    email: inputData,
                };
            }

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(data) {
                    $('#submitBtn').text('Continue');
                    $('#submitBtn').attr('disabled', false);
                    $('#sendOtpForm')[0].reset();

                    if (data.status == 1) {
                        let user_id = data.result['user_id'];
                        toastr.success(data.result.message);
                        location.href = "{{ url('website/new-password') }}" + "/" + user_id;
                    } else {
                        toastr.error(data.result.message);
                    }
                }
            });
        }
    </script>
@endsection

</body>

</html>
