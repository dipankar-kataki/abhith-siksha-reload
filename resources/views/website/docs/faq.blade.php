@extends('layout.website.website')

@section('title', 'FAQ')

@section('head')
<style>
    .card {
        border: none;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .accordion>.card:not(:last-of-type){
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }
    
    .card-header {
        background-color: none;
        border-bottom: none;
        background: transparent;
    }

    .form-control:focus,
    .btn.focus,
    .btn:focus {
        border-color: transparent;
    }

</style>
@endsection

@section('content')
<main>
    <section class="faqs">
        <h1 class="heading-black text-center">Frequently Asked Questions (FAQs)</h1>
        <div class="container mt-3">
            <h3 class="heading-black mt-5">Getting Started :</h3>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none;">
                                <div class="d-flex justify-content-between">
                                    <p>1. How do I get started?</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p class="ml-3">Download the app or connect with our team @........ for a free demo
                                session</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo" style="text-decoration: none;">
                                <div class="d-flex justify-content-between">
                                    <p>2. Do all programs have live-doubt solving?</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p class="ml-3">Yes, a live doubt-solving feature is available. </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                aria-controls="collapseThree" style="text-decoration: none;">    
                                <div class="d-flex justify-content-between">
                                    <p>3. How do I register for a demo class?</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p class="ml-3">You can attend a free demo class by our top teachers to experience our
                                programs first-hand. Our counselor helps you book a demo class for you at your
                                convenience.</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                                aria-controls="collapseFour" style="text-decoration: none;">
                                <div class="d-flex justify-content-between">
                                    <p>4. How can I track progress?</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <p class="ml-3">A progress report is available that can be accessed by switching
                                profiles.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <h3 class="heading-black">SUBSCRIPTION AND BILLING :</h3>

            <div class="accordion" id="accordionExample2">
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="text-decoration: none;">
                                <div class="d-flex justify-content-between">
                                    <p>1.   How do I get charged?</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                                
                            </button>
                        </h2>
                    </div>

                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                        data-parent="#accordionExample2">
                        <div class="card-body">
                            <p class="ml-3">You will be charged based on your plan at the end of your 15-day trial.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                data-toggle="collapse" data-target="#collapseSix" aria-expanded="false"
                                aria-controls="collapseSix" style="text-decoration: none;">
                                <div class="d-flex justify-content-between">
                                    <p>2.   Can the subscription be cancelled?</p>
                                    <p><i class="fa fa-plus"></i></p>
                                </div>
                                
                            </button>
                        </h2>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                        data-parent="#accordionExample2">
                        <div class="card-body">
                            <p class="ml-3">Yes, the subscription can be cancelled anytime from your account</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>
@endsection

@section('scripts')
@endsection
