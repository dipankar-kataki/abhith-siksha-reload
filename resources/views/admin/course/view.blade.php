@extends('layout.admin.layout.admin')

@section('title', 'Course')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-book"></i>
            </span> Course
        </h3>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h3 class="mb-4 ml-3">Course Name: {{$Total_price->name}}</h3>

                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            @if ($Total_price->course_pic != null && $Total_price->course_video != null)
                                <iframe width="100%" class="mt-3" src="{{ asset($Total_price->course_video) }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>                                
                            @else
                                <img class="rounded mt-2" src="{{ asset($Total_price->course_pic) }}" width="100%" alt="">                                
                            @endif

                            {{-- If course with video --}}
                        </div>
                        <div class="col-md-8">
                            <ul class="list-inline centered view-chapters">
                                <li class="d-flex justify-content-between ">
                                    <label for="styled-checkbox-1"><b>Chapter</b></label>
                                    <span class="course-price"><b>Price</b></span>
                                </li>
                                <hr class="mt-0 mb-0">
                                @foreach ($Total_price->priceList as $item)
                                <li class="d-flex justify-content-between ">
                                    <label for="styled-checkbox-1">{{$item->name}}</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>{{$item->price}}</span>
                                </li>
                                @endforeach
                                <hr>
                                <span style="font-size:20px;font-weight:bold;">Total Amount</span>
                                <h3 style="float:right;"> <span style="color:green;">&#8377;</span> &nbsp;{{$final_price}}/- </h3>

                                {{-- <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-1">Aldehydes, Ketones and Carboxylic Acids</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-2">Alcohols, Phenols and Ethers</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-3">Haloalkanes and Haloarenes</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-4">Biomolecules</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-5">Polymers</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-6">Chemistry in Everyday Life</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-7">d and f- Block Elements</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-8">Electrochemistry</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-9">Chemical Kinetics</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-10">Surface Chemistry</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <label for="styled-checkbox-11">Solutions</label>
                                    <span class="course-price"><i class="fa fa-inr" aria-hidden="true"></i>500</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>


@endsection


{{-- scripts --}}
@section('scripts')
@endsection
