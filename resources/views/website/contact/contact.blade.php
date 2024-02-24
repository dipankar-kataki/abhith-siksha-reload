@extends('layout.website.website')

@section('title', 'Contact Us')

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
                        <p class="mb0">
                            <span style="font-weight:600;">Email</span>:-
                            <span style="font-weight: 800;">support@abhithsiksha.com</span>
                        </p>
                        <p class="mb0">
                            <span style="font-weight:600;">Contact Number</span>:-
                            <span style="font-weight: 800;">+91-96780-83808</span>
                        </p>
                        <p class="mb0">
                            <span style="font-weight:600;">Address</span>:-
                            <span style="font-weight: 800;">16/34 F.C. Road, Uzanbazar, Guwahati-1
                                Near Income Tax Office, Assam, India</span>
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d57282.59193546999!2d91.71378059279492!3d26.19140805334039!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e0!4m0!4m5!1s0x375a59f4d27ee14d%3A0xb6698e711a1058f9!2sLatasil%2C%20Uzan%20Bazar%2C%20Guwahati%2C%20Assam%20781001!3m2!1d26.1913369!2d91.7549804!5e0!3m2!1sen!2sin!4v1708787511218!5m2!1sen!2sin"
                            width="100%" height="400" style="border:0;border-radius:10px;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    {{-- <div>
                        <h4 data-brackets-id="12020" class="small-heading-black mb20">Our Numbers</h4>
                        <p>16/34 F.C. Road, Uzanbazar, <br />
                            Guwahati-1 Near Income Tax Office, Assam, India</p>
                    </div> --}}
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
    <script>
        var url = 'https://widget.bot.space/js/widget.js';
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = url;
        var options = {
            "enabled": true,
            "chatButtonSetting": {
                "backgroundColor": "#13C656",
                "ctaText": "",
                "borderRadius": "25",
                "marginLeft": "20",
                "marginBottom": "20",
                "marginRight": "20",
                "position": "right"
            },
            "brandSetting": {
                "brandName": "Abhith Siksha",
                "brandSubTitle": "Typically replies within a minute",
                "brandImg": "https://widget.bot.space/images/BotSpaceLogoLight.png",
                "welcomeText": "Hi there! \nHow can I help you?",
                "messageText": "Hello, I have a question.",
                "backgroundColor": "#085E54",
                "ctaText": "Start Chat",
                "borderRadius": "25",
                "autoShow": false,
                "phoneNumber": "919678083808"
            }
        };
        s.onload = function() {
            CreateWhatsappChatWidget(options);
        };
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    </script>
@endsection
