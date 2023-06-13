@extends('layout.website.website')

@section('title', 'Cart')

@section('head')
    <style>
        .bold-600 {
            font-weight: 600;
        }

        .btn-bg-main {
            background-image: linear-gradient(to left, #076fef, #01b9f1);
            border: none;
            color: #fff
        }

        .shipping-btn:hover {
            background: #111;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <section class="cart">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="heading-black mb0">Checkout</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="cart-describtion">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8" id="leftBlock">
                    <div class="cart-course d-flex justify-content-between">
                        <p class="courseName">{{ $cart->board->exam_board }} - Class {{ $cart->assignClass->class }}</p>
                        <p>
                            @if ($cart->is_full_course_selected == 1)
                                Full Package
                            @else
                                Custom Package
                            @endif
                        </p>
                    </div>
                    @forelse ($cart->assignSubject as $subject)
                        @include('website.cart.cart-items')
                    @empty
                        <h4 class="text-center">Cart empty !</h4>
                    @endforelse

                </div>
                <div class="col-lg-4">
                    <div class="checkout-details d-flex justify-content-between">
                        <p class="checkout-details-text">Name</p>
                        <p><b>{{ auth()->user()->name }}</b></p>
                    </div>
                    <div class="checkout-details d-flex justify-content-between">
                        <p class="checkout-details-text">Email</p>
                        <p><b>{{ auth()->user()->email }}</b></p>
                    </div>
                    <div class="checkout-details d-flex justify-content-between">
                        <p class="checkout-details-text">Phone</p>
                        <p><b>{{ auth()->user()->phone }}</b></p>
                    </div>
                    <div class="checkout-details d-flex justify-content-between">
                        <p class="checkout-details-text">Order Id</p>
                        <p><b>#{{$order->order_no}}</b></p>
                    </div>

                    @auth
                        <div class="cart-checkout p-0" style="background: unset;">
                            <div class="" style="border-left:2px solid #ddd;">
                                <button id="rzp-button" class="btn btn-lg btn-success bold-600">Pay &nbsp; <i class="fa fa-inr"
                                        aria-hidden="true"></i> {{ $countPrice }}</span></button>
                                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                <form name='razorpayform' action="{{ route('payment.verify') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_id" value={{ $cart->id }}>
                                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        // const select = document.querySelectorAll('.selectBtn');
        // const option = document.querySelectorAll('.option');
        // let index = 1;
        // select.forEach(a => {
        //     a.addEventListener('click', b => {
        //         const next = b.target.nextElementSibling;
        //         next.classList.toggle('toggle');
        //         next.style.zIndex = index++;
        //     })
        // })
        // option.forEach(a => {
        //     a.addEventListener('click', b => {
        //         b.target.parentElement.classList.remove('toggle');
        //         const parent = b.target.closest('.select').children[0];
        //         parent.setAttribute('data-type', b.target.getAttribute('data-type'));
        //         parent.innerText = b.target.innerText;
        //     })
        // })
        // Checkout details as a json
        var options = @json($checkoutParam);
        /**
         * The entire list of checkout fields is available at
         * https://docs.razorpay.com/docs/checkout-form#checkout-fields
         */
        options.handler = function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.razorpayform.submit();
            if (response.flag == 201) {
                toastr.success(response.message);
            } else if (response.flag == 400) {
                toastr.error(response.message);
            }
        };
        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = false;
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button').onclick = function(e) {
            rzp.open();
            e.preventDefault();
        }
    </script>
@endsection
