@extends('layout.website.website')

@section('title', 'Gallery')

@section('head')
    <link href="{{ asset('asset_website/css/jquery.fancybox.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="cart">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="heading-black mb0">Contact us</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <h4 data-brackets-id="12020" class="small-heading-black mb20">Leave us a Message</h4>
                    <p>Whether you’re looking for answers, would like to solve a problem, or just
                        want to let us know how we did, you’ll find many ways to contact us right
                        here. We’ll help you resolve your issues quickly and easily, getting you back
                        to more important things</p>
                    <div class="contact-form">
                        <form class="row" id="contactForm">
                            @csrf
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Name" id="name"
                                        name="name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Phone Number" id="phone"
                                        name="phone">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email Id" id="email"
                                        name="email">
                                </div>
                            </div>
                            <div class="col-lg-6 p-md-0">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" placeholder="Message" id="message" name="message"></textarea>
                                </div>
                                <button type="submit" class="btn btn-block knowledge-link"
                                    id="sendContactForm">Send</button>
                            </div>
                        </form>
                    </div>
                    <div>
                        <h4 data-brackets-id="12020" class="small-heading-black mb20">Our Numbers</h4>
                        <p class="mb0">0123456789</p>
                        <p>0123456789</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3581.7460996361547!2d91.76351221502873!3d26.139820283466502!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375a5945023af7f3%3A0xb6b5476554bde53f!2sPiya+Exotica!5e0!3m2!1sen!2sin!4v1526636600223"
                            width="100%" height="290" frameborder="0" style="border:0;border-radius: 8px;"
                            allowfullscreen></iframe>
                    </div>
                    <div>
                        <h4 data-brackets-id="12020" class="small-heading-black mb20">Our Numbers</h4>
                        <p>Latasil, Uzan Bazar, <br />
                            Guwahati, Assam 781001</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $('#sendContactForm');

            if ($('#name').val().length == 0) {
                toastr.error('Name is required');
            } else if ($('#phone').val().length == 0) {
                toastr.error('Phone number is required');
            } else if ($('#email').val().length == 0) {
                toastr.error('Email is required');
            } else if ($('#message').val().length == 0) {
                toastr.error('Message is required');
            } else {

                btn.text('submiting....');
                btn.attr('disabled', true);

                $.ajax({
                    url: "{{ route('website.save.contact.details') }}",
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    success: function(data) {
                        if (data.status == 1) {
                            toastr.success(data.message);
                            btn.text('Send');
                            btn.attr('disabled', false);
                            $('#contactForm')[0].reset();
                        } else {
                            toastr.error(data.message);
                            btn.text('Send');
                            btn.attr('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 500 || xhr.status == 422) {
                            toastr.error('Oops! Something went wrong while saving.');
                        }
                    }
                });
            }
        });
    </script>
@endsection
