@extends('layout.website.auth')

@section('title', 'New Password')

@section('main')

    <section class="login-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="newpass-div">
                        <div class="login-logo"><img src="{{ asset('asset_website/img/home/abhith-new-logo-reloaded.png') }}" style="height:80px;"></div>
                        <a onclick="goBack()" class="page-close"><span class="icon-cancel-30"></span></a>
                        <div class="forget-cover">
                            <form id="resetPasswordForm" class="row" action="">
                                @csrf
                                <div class="col-lg-12">
                                    <h4 class="small-heading-grey">New Password</h4>
                                </div>
                                <input type="hidden" class="form-control" value="{{ Request::route('user_id') }}"
                                    placeholder="User ID" id="user_id" name="user_id">

                                <div class="form-group col-lg-12">
                                    <input type="text" class="form-control" placeholder="6 digit OTP" id="otp"
                                        name="otp">
                                </div>
                                <div class="form-group col-lg-12">
                                    <input type="password" class="form-control" placeholder="New Password" id="password"
                                        name="password">
                                </div>
                                <div class="form-group col-lg-12">
                                    <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_password"
                                        name="confirm_password">
                                </div>

                                <div class="form-group mb0 col-lg-12">
                                    <button type="submit" class="btn btn-block login-btn" id="submitBtn">Change</button>
                                    <p>Please Creat a new Password that you
                                        don't use on any other site</p>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $("#otp").inputFilter(function(value) {
            return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 999999);
        });
    </script>

    <script>
        $('#resetPasswordForm').on('submit', function(e) {
            e.preventDefault();
            $('#submitBtn').text('Please wait...');
            $('#submitBtn').attr('disabled', true);

            let formData = new FormData(this);
            let url = "{{ route('useer.reset.password') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#submitBtn').text('Change');
                    $('#submitBtn').attr('disabled', false);

                    if (data.status == 1) {
                        $('#resetPasswordForm')[0].reset();
                        toastr.success('Password changed successfully');
                        
                        location.href = "{{ route('website.login') }}";
                    } else {
                        console.log(data);
                        toastr.error(data.message);
                    }
                }
            });
        })
    </script>
@endsection

</body>

</html>
