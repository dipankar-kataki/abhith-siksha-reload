@if(subjectStatus($subject->subject->id)==3)
<div class="cart-course-description d-flex justify-content-between">
    <div class="subject-div d-flex">
        <div class="subject-img mr-3">
            <img src="{{asset($subject->subject->image)}}" alt="" width="60" height="50">
        </div>
        <div class="subjectName">
            <h5>{{$subject->subject->subject_name}}</h5>
            <p> {{$subject->subject->lesson->count()}} Lessons</p>
            <a href="{{route('website.subject.detatils',Crypt::encrypt($subject->subject->id))}}">View</a>
        </div>
    </div>
    <div class="price-div">
        <p><i class="fa fa-inr mr-2" aria-hidden="true"></i>{{$subject->subject->subject_amount}}</p>
    </div>
</div>
@endif
{{-- @foreach ($subject->assignSubject as $item)
    @if(subjectStatus($item->subject->id)==3)
    <div class="cart-course-description d-flex justify-content-between">
        <div class="subject-div d-flex">
            <div class="subject-img mr-3">
                <img src="{{asset($item->subject->image)}}" alt="" width="60" height="50">
            </div>
            <div class="subjectName">
                <h4>{{$item->subject->subject_name}}</h4>
                <p> {{$item->subject->lesson->count()}} Lessons</p>
                <a href="{{route('website.subject.detatils',Crypt::encrypt($item->subject->id))}}">View</a>
            </div>
        </div>
        <div class="price-div">
            <p><i class="fa fa-inr mr-2" aria-hidden="true"></i>{{$item->subject->subject_amount}}</p>
        </div>
    </div>
    @endif
@endforeach --}}

{{-- <div class="col-lg-4">
    <h6> Course Type:
        @if($item->is_full_course_selected==1)Full Course
        @else Custom Package
        @endif
        <span data-toggle="tooltip" data-html="true" title="<ol>
                 @foreach($item->assignSubject as $key=>$subject)
                    <li>{{$subject->subject->subject_name??''}} (RS:  {{number_format($subject->amount,2,'.','')}} )</li>
                         @endforeach </ol>">
            <i class="fa fa-info-circle" aria-hidden="true" style="color:#076fef"></i>
        </span>
    </h6>
    <h6>Board: {{$item->board->exam_board??''}}Class: {{$item->assignClass->class??''}}</h6>
</div>
<div class="col-lg-3 course-price2" id="itemPrice"><i class="fa fa-inr" aria-hidden="true"></i>
    {{number_format($item->assignSubject->sum('amount')??'00',2,'.','')}}
</div>
<div class="col-lg-3">
    <a href="#" class="remove removeCartItem" data-id="{{$item->id??''}}">Remove</a>
</div>
{{-- <div class="col-lg-2 col-md-2">
</div> --}}
<div class="col-lg-4 col-md-4 rightBlock">

</div> 