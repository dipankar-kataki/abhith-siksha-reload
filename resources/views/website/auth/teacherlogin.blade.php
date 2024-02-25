@extends('layout.website.auth')
@if ($prefix == 'teacher')
@section('title', 'Teacher | Login')
@else
@section('title', 'User | Login')
@endif

@section('header')
@endsection
@section('main')

<section class="login-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="login-div">
                    <div class="login-logo"><img src="{{ asset('asset_website/img/home/abhith-new-logo-reloaded.png') }}" style="height:80px;">
                    </div>

                    <a onclick="goBack()" class="page-close"><span class="icon-cancel-30"></span></a>
                    <div class="login-cover">
                        <ul class="nav nav-tabs login-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Log In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false">Sign Up</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            {{-- Login --}}
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form class="row" action="{{ route('website.auth.login') }}" method="POST"
                                    id="loginForm">
                                    @csrf
                                    <input type="hidden" name="type" value="3">
                                    <div class="form-group col-lg-12">
                                        <input type="email" name="email" class="form-control" placeholder="Email"
                                            id="email" required>
                                        <span class="text-danger">
                                            @error('email')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="password" id="password" required>
                                        <span class="text-danger">
                                            @error('password')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <span class="text-danger ml-2">
                                        @if ($errors->any())
                                        {{ $errors->first() }}
                                        @endif
                                    </span>
                                    <div class="form-group mb0 col-lg-12">
                                        <button type="submit" class="btn btn-block login-btn"
                                            id="loginBtn">Login</button>
                                    </div>
                                    <div class="col-lg-12 forgot-div"><a href="{{ route('website.forgot.password') }}"
                                            class="text-center">Forgot Password</a></div>
                                </form>
                            </div>

                            {{-- Register --}}
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form class="row" id="signupForm">
                                    @csrf


                                    <div class="form-group col-lg-12">
                                        <input type="text" class="form-control" name="name" placeholder="Name" id="name"
                                            maxlength="50" value="{{ old('name') }}">
                                        <span class="text-danger">
                                            @error('name')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                    <div class="form-group col-lg-12">
                                        <div class="input-group">
                                            <input type="email" name="signupEmail" class="form-control"
                                                placeholder="e.g. abc@gmail.com" id="signupEmail"
                                                pattern="(0|91)?[6-9][0-9]{9}" title="Please enter valid email ID"
                                                required>
                                            <div class="input-group-append">
                                                <button class="input-group-text" id="sendEmailOtpBtn"
                                                    style="cursor: pointer;font-size:13px;color:white;background-image: linear-gradient(to left, #076fef, #01b9f1);">Send
                                                    OTP</button>
                                            </div>
                                        </div>
                                        <span class="text-danger">
                                            @error('email')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="form-group  verify-email-otp-div col-lg-12" style="display: none;">
                                        <div class="input-group ">
                                            <input type="text" name="emailotp" class="form-control"
                                                placeholder="Enter OTP e.g. 123456" id="enterEmailOtp" pattern="[0-9]+"
                                                title="Enter numbers only." required>
                                            <div class="input-group-append">
                                                <button class="btn input-group-text" id="verifyEmailOtpBtn"
                                                    style="cursor: pointer;font-size:13px;color:white;background-image: linear-gradient(to left, #7d9fc9, #79adbd);">Verify
                                                    OTP</button>
                                            </div>
                                        </div>
                                        <span class="text-danger">
                                            @error('otp')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>



                                    <div class="form-group col-lg-12">
                                        <div class="input-group">
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="e.g. 7895123572" id="phone" pattern="(0|91)?[6-9][0-9]{9}"
                                                maxlength="10"
                                                title="Phone number should start with 6 or 7 or 8 or 9 and 10 chars long. ( e.g 7896845214)"
                                                required>
                                            <div class="input-group-append">
                                                <button class="input-group-text" id="sendOtpBtn"
                                                    style="cursor: pointer;font-size:13px;color:white;background-image: linear-gradient(to left, #076fef, #01b9f1);">Send
                                                    OTP</button>
                                            </div>
                                        </div>
                                        <span class="text-danger">
                                            @error('phone')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="form-group  verify-otp-div col-lg-12" style="display: none;">
                                        <div class="input-group ">
                                            <input type="text" name="otp" class="form-control"
                                                placeholder="Enter OTP e.g. 123456" id="enterOtp" pattern="[0-9]+"
                                                title="Enter numbers only." required>
                                            <div class="input-group-append">
                                                <button class="btn input-group-text" id="verifyOtpBtn"
                                                    style="cursor: pointer;font-size:13px;color:white;background-image: linear-gradient(to left, #7d9fc9, #79adbd);">Verify
                                                    OTP</button>
                                            </div>
                                        </div>
                                        <span class="text-danger">
                                            @error('otp')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Password" id="pwd" required>
                                        <span class="text-danger">
                                            @error('password')
                                            {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Confirm Password" id="confPwd" required>
                                    </div>
                                    <div class="form-group mb0 col-lg-12">
                                        <button type="submit" class="btn btn-block sign-btn" id="signupBtn">Sign
                                            up</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')


<script>
    $('#verifyOtpBtn').attr('disabled', true);

        let interval = '';
        let no_of_otp_sent = 0;
        let no_of_phone_otp_sent = 0;
        let nameRegex = /^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/;
        let emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        let phoneRegex = /(0|91)?[6-9][0-9]{9}/;
        $("#sendEmailOtpBtn").on('click', function() {
            if ($('#signupEmail').val().length == 0) {
                toastr.error('Email is required');
            } else {
                if (no_of_otp_sent < 2) {
                    no_of_otp_sent += 1;
                    var prefix = @json($prefix);
                    if (prefix == "teacher") {
                        var url = "{{ route('apiemailotpsend') }}";
                    } else {
                        var url = "{{ route('apiemailotpsend') }}";
                    }
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'email': $('#signupEmail').val(),
                        },
                        success: function(data) {

                            if (data.result.code == 200) {
                                $('#sendEmailOtpBtn').attr('disabled', true);
                                $('#sendEmailOtpBtn').css('background-image',
                                    'linear-gradient(to left, #7d9fc9, #79adbd)');
                                $('#sendEmailOtpBtn').text('OTP Sent');
                                $('.verify-email-otp-div').css('display', 'block');

                                interval = setInterval(updateTimerPhone, 2000);
                                toastr.success(data.result.message);
                            } else {
                                toastr.error(data.result.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status == 500 || xhr.status == 422) {
                                toastr.error('Whoops! Something went wrong while sending OTP');
                            }
                        }
                    });
                } else {
                    $('#sendEmailOtpBtn').attr('disabled', true);
                    toastr.info(
                        'You have reached maximum attempts for sending otp. Please wait for 1 hour to resume the service. '
                    );
                }
            }
        });
        $('#sendOtpBtn').on('click', function(e) {

            e.preventDefault();

            if ($('#phone').val().length < 10) {
                toastr.error('Phone number is required. Enter valid phone number');
            } else {
                let no_of_phone_otp_sent = 0;


                if (no_of_phone_otp_sent < 2) {
                    no_of_otp_sent += 1;
                    var prefix = @json($prefix);
                    var url = "{{ route('apimobileotpsend') }}";
                    //  if(prefix=="teacher"){
                    //     var url="{{ route('teacher.signup') }}";
                    //  }else{
                    //     var url="{{ route('website.auth.signup') }}";
                    //  }
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'phone': $('#phone').val()
                        },
                        success: function(data) {

                            if (data.status == 1) {
                                if (data.result.code == 200) {
                                    // $('#sendOtpBtn').attr('disabled',true);
                                    $('#sendOtpBtn').css('background-image',
                                        'linear-gradient(to left, #7d9fc9, #79adbd)');
                                    $('#sendOtpBtn').text('OTP Sent');
                                    $('.verify-otp-div').css('display', 'block');

                                    interval = setInterval(updateTimer, 2000);
                                    toastr.success(data.result.message);
                                } else {
                                    toastr.error(data.result.message);
                                }

                            } else {
                                toastr.error(data.result.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status == 500 || xhr.status == 422) {
                                toastr.error('Whoops! Something went wrong while sending OTP');
                            }
                        }
                    });
                } else {
                    $('#sendOtpBtn').attr('disabled', true);
                    toastr.info(
                        'You have reached maximum attempts for sending otp. Please wait for 1 hour to resume the service. '
                    );
                }
            }


        });


        $('#enterOtp').on('keyup', function() {
            if ($('#enterOtp').val().length == 0) {
                $('#verifyOtpBtn').attr('disabled', true);
                $('#verifyOtpBtn').css('background-image', 'linear-gradient(to left, #7d9fc9, #79adbd)');
            } else {
                $('#verifyOtpBtn').css('background-image',
                    'linear-gradient(to right top, #63e0b9, #50cd9f, #3eba86, #2ca76c, #199453)');
                $('#verifyOtpBtn').attr('disabled', false);
            }

        });

        $('#enterEmailOtp').on('keyup', function() {
            if ($('#enterEmailOtp').val().length == 0) {
                $('#verifyEmailOtpBtn').attr('disabled', true);
                $('#verifyEmailOtpBtn').css('background-image', 'linear-gradient(to left, #7d9fc9, #79adbd)');
            } else {
                $('#verifyEmailOtpBtn').css('background-image',
                    'linear-gradient(to right top, #63e0b9, #50cd9f, #3eba86, #2ca76c, #199453)');
                $('#verifyEmailOtpBtn').attr('disabled', false);
            }

        });

        $('#verifyOtpBtn').on('click', function(e) {

            e.preventDefault();
            let otpRegex = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;

            if ($('#enterOtp').val().length > 6) {
                toastr.error('Not a valid OTP');
            } else if (!otpRegex.test($('#enterOtp').val())) {
                toastr.error('Enter numbers only');
            } else {
                var prefix = @json($prefix);

                var url = "{{ route('verifymobileotp') }}";
                //  if(prefix=="teacher"){
                //     var url="{{ route('teacher.verifyOtp') }}";
                //  }else{
                //     var url="{{ route('website.auth.verify.otp') }}";
                //  }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'email': $('#signupEmail').val(),
                        'phone': $('#phone').val(),
                        'otp': $('#enterOtp').val(),
                    },
                    success: function(data) {

                        if (data.status == 1) {
                            if (data.result.code == 200) {
                                toastr.success(data.result.message);
                                $('#phone').prop('readonly', true);
                                $('#enterOtp').prop('readonly', true);
                                $('#sendOtpBtn').attr('disabled', true);
                                $('#verifyOtpBtn').attr('disabled', true);
                                $('#verifyOtpBtn').css('background-image',
                                    'linear-gradient(to left, #7d9fc9, #79adbd)');
                                $('#pwd').css('display', 'block');
                                $('#confPwd').css('display', 'block');
                            } else {
                                toastr.error(data.result.message);
                            }


                        } else {
                            toastr.error(data.result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 500 || xhr.status == 422) {
                            toastr.error('Whoops! Something went wrong while sending OTP');
                        }
                    }
                });
            }

        });
        $('#verifyEmailOtpBtn').on('click', function(e) {

            e.preventDefault();
            let otpRegex = /^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/;

            if ($('#enterEmailOtp').val().length > 6) {
                toastr.error('Not a valid OTP');
            } else if (!otpRegex.test($('#enterEmailOtp').val())) {
                toastr.error('Enter numbers only');
            } else {
                var prefix = @json($prefix);

                var url = "{{ route('verifyemailotp') }}";
                //  if(prefix=="teacher"){
                //     var url="{{ route('teacher.verifyOtp') }}";
                //  }else{
                //     var url="{{ route('website.auth.verify.otp') }}";
                //  }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'email': $('#signupEmail').val(),
                        'otp': $('#enterEmailOtp').val(),
                    },
                    success: function(data) {

                        if (data.status == 1) {
                            if (data.result.code == 200) {
                                toastr.success(data.result.message);
                                // $('#phone').prop('readonly',true);
                                $('#enterEmailOtp').prop('readonly', true);
                                $('#sendEmailOtpBtn').attr('disabled', true);
                                $('#verifyEmailOtpBtn').attr('disabled', true);
                                $('#verifyEmailOtpBtn').css('background-image',
                                    'linear-gradient(to left, #7d9fc9, #79adbd)');
                                $('#pwd').css('display', 'block');
                                $('#confPwd').css('display', 'block');
                            } else {
                                toastr.error(data.result.message);
                            }


                        } else {
                            toastr.error(data.result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 500 || xhr.status == 422) {
                            toastr.error('Whoops! Something went wrong while sending OTP');
                        }
                    }
                });
            }

        });


        const startingMinutes = 0.05;
        let time = startingMinutes * 60;

        function updateTimer() {
            const minutes = Math.floor(time / 60);
            let seconds = time % 60;
            // seconds = seconds < 10 ? '0' + seconds : seconds;

            $('#sendEmailOtpBtn').text(` Resend in ${seconds} s`);
            if (time == 0) {

                $('#sendEmailOtpBtn').attr('disabled', false);
                $('#sendEmailOtpBtn').text('Send OTP');
                $('#sendEmailOtpBtn').css('background-image', 'linear-gradient(to left, #076fef, #01b9f1)');
                clearInterval(interval);
            } else {
                time--;
            }
        }

        function updateTimerPhone() {
            const minutes = Math.floor(time / 60);
            let seconds = time % 60;
            // seconds = seconds < 10 ? '0' + seconds : seconds;

            $('#sendEmailOtpBtn').text(` Resend in ${seconds} s`);
            if (time == 0) {

                $('#sendEmailOtpBtn').attr('disabled', false);
                $('#sendEmailOtpBtn').text('Send OTP');
                $('#sendEmailOtpBtn').css('background-image', 'linear-gradient(to left, #076fef, #01b9f1)');
                clearInterval(interval);
            } else {
                time--;
            }
        }
        $('#confPwd').on('keyup', function() {
            if ($('#confPwd').val().length == 0) {
                $('#signupBtn').attr('disabled', true);
            } else {
                $('#signupBtn').attr('disabled', false);
            }
        });

        $('#signupBtn').on('click', function(e) {

            e.preventDefault();

            if ($('#pwd').val().length < 5) {
                toastr.error('Password must be 5 characters long');
            } else if ($('#pwd').val() != $('#confPwd').val()) {
                toastr.error('Whoops! Password not matched');
            } else {
                var prefix = @json($prefix);
                if (prefix == "teacher") {
                    var url = "{{ route('teacher.completeSignup') }}";
                } else {
                    var url = "{{ route('website.auth.complete.signup') }}";
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'email': $('#signupEmail').val(),
                        'phone': $('#phone').val(),
                        'password': $('#pwd').val(),
                        'name': $('#name').val(),

                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 1) {
                            toastr.success(data.message);
                            location.reload(true);
                        } else {
                            toastr.error(data.message);
                        }
                        // location.reload(true);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 500 || xhr.status == 422) {
                            toastr.error('Whoops! Something went wrong while sending OTP');
                        }
                    }
                });
            }
        });
</script>
@endsection



</body>

</html>
