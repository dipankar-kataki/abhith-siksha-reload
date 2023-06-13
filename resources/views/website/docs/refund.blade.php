@extends('layout.website.website')

@section('title', 'Refund & Cancellation Policy')

@section('head')
<style>
        .text{
            font-size: 15px;
            font-weight: 500;
            margin-top: 20px;
        }
        .text li{
            padding-bottom: 15px ;
        }
</style>
@endsection

@section('content')
<main>
    <section class="terms">        
        <div class="container my-5">
            <h1 class="heading-black pt-md-5">Refund & Cancellation Policy</h1>
            <div class="container">
                <ul class="text">
                    <li>Thank You for buying a course with us. We make sure that our learners invest in an exceptional learning experience.</li>
                    
                    <li>If you choose to cancel the course booking within 14 calendar days of receiving your order confirmation and before the start of the course without giving any reason, you would be entitled to a full refund of the price paid.</li>

                    <li>Refund of the duplicate payment made by the customer will be processed via the same source (original method of payment) within 7 to 21 working days after intimation by the customer.</li>
                </ul>
            </div>            
        </div>
    </section>
</main>

@endsection

@section('scripts')
@endsection
