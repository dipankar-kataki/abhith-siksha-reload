@forelse ($order as $item)
    @if ($item->payment_status == 'paid')
        <li>
            <div class="cart-course-image1"><img src="{{asset($item->course->course_pic)}}" style="height:50px;width:70px;"></div>
            <div class="cart-course-desc">
                <h6 data-brackets-id="12020">Chapter: {{$item->chapter->name}}</h6>
                <p>course: {{$item->course->name}}</p>
                <span class="course-price2" id="itemPrice"><i class="fa fa-inr" aria-hidden="true"></i>{{$item->chapter->price}}</span>
            </div>
        </li>
    @endif
@empty
    <div style="position: absolute;left: 34%;"> <i class="fa fa-check-circle-o" aria-hidden="true" style="color:green;font-size:22px;"></i>&nbsp; No items found. </div>
@endforelse