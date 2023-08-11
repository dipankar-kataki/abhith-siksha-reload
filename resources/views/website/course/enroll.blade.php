@extends('layout.website.website')

@section('title','Courses')
@section('head')
<style>
    .home-courses {
        margin-top: 150px;
    }

    /* .home-courses{
        padding-top: 75px;
    } */

    .inputGroup {
        background-color: #ffffff;
        border: 1px solid #cccccc;
        /* background-color: #e9e6e6; */
        display: block;
        margin: 10px 0;
        position: relative;
        height: 65px;
        border-radius: 5px;
    }

    .inputGroup label {
        padding: 24px 30px;
        width: 100%;
        display: block;
        text-align: left;
        color: #e3e3e3;
        cursor: pointer;
        position: relative;
        z-index: 2;
        transition: color 200ms ease-in;
        overflow: hidden;
        font-weight: 600;
        font-size: 16px;
    }

    /* .inputGroup label a{
        color:white;
    } */

    .inputGroup label:before {
        width: 10px;
        height: 10px;
        border-radius: 0;
        content: '';
        /* background-color: #5562eb; */
        background-image: linear-gradient(to right, #93bcf8, #8bb5f5, #83adf1, #7ba6ee, #749eea, #6d9be9, #6598e7, #5d95e6, #5196e6, #4496e6, #3497e6, #1c97e5);
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%) scale3d(1, 1, 1);
        transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        z-index: -1;
    }

    .inputGroup label:after {
        width: 32px;
        height: 32px;
        content: '';
        border: 2px solid #d1d7dc;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23fff' fill-rule='nonzero'/%3E%3C/svg%3E ");
        background-repeat: no-repeat;
        background-position: 2px 3px;
        border-radius: 50%;
        z-index: 2;
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        transition: all 200ms ease-in;
    }

    .inputGroup input:checked~label {
        color: #fff;
    }

    .inputGroup input:checked~label:before {
        transform: translate(-50%, -50%) scale3d(70, 70, 1);
        opacity: 1;
    }

    .inputGroup input:checked~label:after {
        background-color: #03bd9c;
        border-color: #01a386;
    }

    .inputGroup input {
        width: 32px;
        height: 32px;
        order: 1;
        z-index: 2;
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        visibility: hidden;
    }

    .form {
        padding: 0 16px;
        max-width: 550px;
        margin: 50px auto;
        font-size: 18px;
        font-weight: 600;
        line-height: 36px;
    }

    a:hover {
        color: blue;
    }
    .addons-div{
        height:300px;
        overflow: auto;
        scroll-behavior: smooth;
    }
    .addon-details{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding-left: 10px;
        padding-top: 7px;
        padding-bottom: 7px;
        border: 1px solid #d0cfcf;
        border-radius: 5px;
        margin-bottom: 15px;

    }

    .addon-details .addon-image{
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .addon-details .addon-image img{
        height: 60px;
        width: 60px;
        border-radius: 50%;
        border: 1px solid #e8e8e8;
        padding: 5px;
        object-fit: contain;

    }
    .addon-details .addon-image .addon-name p{
        margin-left: 10px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom:2px;
    }

    .addon-details .addon-select{
        margin-right: 30px;
    }
    
    /* ===== Scrollbar CSS ===== */

    /* Firefox */
    * {
        scrollbar-width: auto;
        scrollbar-color: #d4d9ec #ffffff;
    }

    /* Chrome, Edge, and Safari */
    *::-webkit-scrollbar {
        width: 11px;
    }

    *::-webkit-scrollbar-track {
        background: #ffffff;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #d4d9ec;
        border-radius: 10px;
        border: 3px solid #ffffff;
    }

    .round {
  position: relative;
}

.round label {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 50%;
  cursor: pointer;
  height: 28px;
  left: 0;
  position: absolute;
  top: 0;
  width: 28px;
}

.round label:after {
  border: 2px solid #fff;
  border-top: none;
  border-right: none;
  content: "";
  height: 6px;
  left: 7px;
  opacity: 0;
  position: absolute;
  top: 8px;
  transform: rotate(-45deg);
  width: 12px;
}

.round input[type="checkbox"] {
  visibility: hidden;
}

.round input[type="checkbox"]:checked + label {
  background-color: #66bb6a;
  border-color: #66bb6a;
}

.round input[type="checkbox"]:checked + label:after {
  opacity: 1;
}

input.largerCheckbox {
    width: 20px;
    height: 20px;
}

</style>
<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap');



    .course-type {
        max-width: 100%;
        vertical-align: middle;
    }

    .container {
        max-width: 99vw;
        margin: 15px auto;
        padding: 0 15px;
    }

    .top-text-wrapper {
        margin: 20px 0 30px 0;
    }

    .top-text-wrapper h4 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .top-text-wrapper code {
        font-size: 0.85em;
        background: linear-gradient(90deg, #fce3ec, #ffe8cc);
        color: #f20;
        padding: 0.1rem 0.3rem 0.2rem;
        border-radius: 0.2rem;
    }

    .tab-section-wrapper {
        padding: 30px 0;
    }

    .grid-wrapper {
        display: grid;
        grid-gap: 30px;
        place-items: center;
    }

    .grid-col-auto {
        grid-auto-flow: column;
        grid-template-rows: auto;
    }

    /* ******************* Main Styeles : Radio Card */
    label.radio-card {
        cursor: pointer;
    }

    label.radio-card .card-content-wrapper {
        background: #fff;
        border-radius: 5px;
        max-width: 280px;
        min-height: 200px;
        min-width: 200px;
        padding: 15px;
        display: grid;
        box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.04);
        transition: 200ms linear;
    }

    label.radio-card .check-icon {
        width: 20px;
        height: 20px;
        display: inline-block;
        border: solid 2px #e3e3e3;
        border-radius: 50%;
        transition: 200ms linear;
        position: relative;
    }

    label.radio-card .check-icon:before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='12' height='9' viewBox='0 0 12 9' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0.93552 4.58423C0.890286 4.53718 0.854262 4.48209 0.829309 4.42179C0.779553 4.28741 0.779553 4.13965 0.829309 4.00527C0.853759 3.94471 0.889842 3.88952 0.93552 3.84283L1.68941 3.12018C1.73378 3.06821 1.7893 3.02692 1.85185 2.99939C1.91206 2.97215 1.97736 2.95796 2.04345 2.95774C2.11507 2.95635 2.18613 2.97056 2.2517 2.99939C2.31652 3.02822 2.3752 3.06922 2.42456 3.12018L4.69872 5.39851L9.58026 0.516971C9.62828 0.466328 9.68554 0.42533 9.74895 0.396182C9.81468 0.367844 9.88563 0.353653 9.95721 0.354531C10.0244 0.354903 10.0907 0.369582 10.1517 0.397592C10.2128 0.425602 10.2672 0.466298 10.3112 0.516971L11.0651 1.25003C11.1108 1.29672 11.1469 1.35191 11.1713 1.41247C11.2211 1.54686 11.2211 1.69461 11.1713 1.82899C11.1464 1.88929 11.1104 1.94439 11.0651 1.99143L5.06525 7.96007C5.02054 8.0122 4.96514 8.0541 4.90281 8.08294C4.76944 8.13802 4.61967 8.13802 4.4863 8.08294C4.42397 8.0541 4.36857 8.0122 4.32386 7.96007L0.93552 4.58423Z' fill='white'/%3E%3C/svg%3E%0A");
        background-repeat: no-repeat;
        background-size: 12px;
        background-position: center center;
        transform: scale(1.6);
        transition: 200ms linear;
        opacity: 0;
    }

    label.radio-card input[type='radio'] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    label.radio-card input[type='radio']:checked+.card-content-wrapper {
        /* box-shadow: 0 2px 4px 0 rgba(219, 215, 215, 0.5), 0 0 0 2px #3057d5; */
        box-shadow: 0 2px 10px 0 rgb(173 173 173 / 50%), 0 0 0 2px #f4f0f0;
    }

    label.radio-card input[type='radio']:checked+.card-content-wrapper .check-icon {
        background: #3057d5;
        border-color: #3057d5;
        transform: scale(1.2);
    }

    label.radio-card input[type='radio']:checked+.card-content-wrapper .check-icon:before {
        transform: scale(1);
        opacity: 1;
    }

    label.radio-card input[type='radio']:focus+.card-content-wrapper .check-icon {
        box-shadow: 0 0 0 4px rgba(48, 86, 213, 0.2);
        border-color: #3056d5;
    }

    label.radio-card .card-content img {
        margin-bottom: 10px;
    }

    label.radio-card .card-content h4 {
        font-size: 16px;
        letter-spacing: -0.24px;
        text-align: center;
        color: #1f2949;
        margin-bottom: 10px;
    }

    label.radio-card .card-content h5 {
        font-size: 14px;
        line-height: 1.4;
        text-align: center;
        color: #686d73;
    }
</style>
@endsection

@section('content')

<section class="subheader">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-inline cart-course-list1">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</section>



<section class="home-courses">
    <h5 class="mb-4">Board : <b> {{$data['board']->exam_board}}</b> Class : <b>{{$data['assignclass']->class}} </b></h5>
    <form action="{{route('website.add-to-cart')}}" method="post">
        <input type="hidden" name="is_buy" value="0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="leftBlock d-flex justify-content-around">
                        <div class="mb-4">
                            <label for="radio-card-1" class="radio-card">
                                <input class="course_type" type="radio" name="course_type" id="radio-card-1" value="1"
                                    onclick="changeCourse(this.value)" />
                                <div class="card-content-wrapper">
                                    <span class="check-icon"></span>
                                    <div class="card-content" style="width:100%; text-align:center">
                                        <img src="{{asset('asset_website/img/fullcourse.png')}}" alt=""
                                            class="course-type" />
                                        <h4>FULL COURSE</h4>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div>
                            <label for="radio-card-2" class="radio-card">
                                <input type="radio" class="course_type" name="course_type" id="radio-card-2" value="2"
                                    onclick="changeCourse(this.value)" checked />
                                <div class="card-content-wrapper">
                                    <span class="check-icon"></span>
                                    <div class="card-content" style="width:100%; text-align:center">
                                        <img src="{{asset('asset_website/img/custompackage.png')}}" alt=""
                                            class="course-type" />
                                        <h4>CUSTOM COURSE</h4>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <!-- /.radio-card -->

                    <!-- <label for="radio-card-2" class="radio-card">
                            <input type="radio" class="course_type" name="course_type" id="radio-card-2" value="2"
                                onclick="changeCourse(this.value)" />
                            <div class="card-content-wrapper">
                                <span class="check-icon"></span>
                                <div class="card-content" style="width:100%; text-align:center">
                                    <img src="{{asset('asset_website/img/custompackage.png')}}" alt=""
                                        class="course-type" />
                                    <h4>CUSTOM YOUR COURSE</h4>
                                </div>
                            </div>
                        </label> -->
                    <!-- /.radio-card -->
                </div>
                <div class="rightBlock col-lg-6 col-md-12">
                    @csrf
                    <input type="hidden" name="board_id" value="{{$data['board']->id}}">
                    <input type="hidden" name="class_id" value="{{$data['assignclass']->id}}">
                    @foreach($data['subjects'] as $key=>$subject)
                        @if($data['subjects'][$key]['already_purchase']==0)
                            <div class="inputGroup">
                                <input class="chapter_value" id="option{{$key}}" type="checkbox"
                                    value="{{$data['subjects'][$key]['id']}}" name="subjects[]"
                                    data-price="{{number_format($data['subjects'][$key]['subject_amount'],2,'.','')}}"
                                    onclick="checkedSubject()" />
                                <label for="option{{$key}}">
                                    <a
                                        href="{{route('website.subject.detatils',Crypt::encrypt($data['subjects'][$key]['id']))}}">
                                        <i class="fa fa-external-link mr-2" aria-hidden="true"></i>
                                        {{$data['subjects'][$key]['subject_name']}}(
                                        <i class="fa fa-inr" aria-hidden="true"></i>
                                        {{number_format($data['subjects'][$key]['subject_amount'],2,'.','')
                                        }}
                                        )
                                    </a>
                                </label>
                            </div>
                        @endif
                    @endforeach

                    {{-- Addons Code Started  --}}
                    @if (!$related_addon_items->isEmpty())
                        <div class="mb-3 mt-4">
                            <h5 for="">Addons</h5>
                        </div> 
                    
                        
                    
                        <div class="addons-div">
                            <input type="hidden" id="isAddonsSelected" name="is_addons_selected" value=0>
                            @forelse ($related_addon_items as $addon_key => $item)
                                <div class="addon-details">
                                    <div class="addon-image">
                                        @if ($item->type == 'pdf')
                                            <img src="{{asset('asset_website/img/pdf-icon.png')}}" alt="pdf-icon">
                                            <div class="addon-name">
                                                <p>{{$item->name}}</p>
                                                <p><i class="fa fa-inr text-success" aria-hidden="true"></i> {{$item->price}}</p>
                                            </div>
                                        @else
                                            <img src="{{asset($item->file_path)}}" alt="addon image">
                                            <div class="addon-name">
                                                <p>{{$item->name}}</p>
                                                <p><i class="fa fa-inr text-success" aria-hidden="true"></i> {{$item->price}}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="addon-select">
                                        <div class="round1">
                                            <input type="checkbox" class="addons-value largerCheckbox" value="{{$item->id}}" name="addons[]"
                                            data-price="{{$item->price}}"
                                            onclick="checkedSubject()"/>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                
                            @endforelse
                        </div>
                    @endif
                    {{-- Addons Code Ended --}}
                    <hr>
                    <div class="total">
                        <p class=""><b>Total</b></p>
                        <span class="course-price1 mr-2" id="total_price"><i class="fa fa-inr" aria-hidden="true"></i>
                            {{number_format($data['subjectamount'], 2, '.', '')}}</span>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block add-to-cart" id="add-to-cart"
                        name="buynow" value="0">Add to cart</button>
                    <button type="submit" class="btn btn-warning btn-lg btn-block buy-now" id="buy-now" name="buynow"
                        value="1" style="color: white;">Buy Now</button>
                </div>
            </div>

        </div>
    </form>
</section>
@section('scripts')
<script>
    $(document).ready(function() {
        var courseType= $("#radio-card-2").val();
        $(".chapter_value").each(function(index) {
            var subject_id=@json($subject_id);
            if(subject_id==$(this).attr('value')){
                $(this).prop("checked", true); 
            }else{
                $(this).prop("checked", false);
                $(this).prop("disabled", false);
            }
        });
        
        $(".owl-carousel").owlCarousel({
            loop:true,
            margin:10,
            responsiveClass:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:2,
                    nav:false
                },
                1200:{
                    items:3,
                    nav:true,
                    loop:false
                }
            }
        });
     });
   function changeCourse(value){
        if(value==1){
            $("#message-for-custom-package").html(``);
            $("#full_course").prop("checked", true);
            $(".chapter_value").each(function(index) {
                $(this).prop("checked", true);
                $(this).prop("readonly", true);
         
           });
           checkedSubject();
        }else{
            
            var messageForCustomPackage=` <button type="button" class="btn btn-secondary btn-lg btn-block">Please Selete Subjects for custom your package</button>`;
            $("#message-for-custom-package").html(messageForCustomPackage);
            $(".chapter_value").each(function(index) {
                $(this).prop("checked", false);
                $(this).prop("disabled", false);
                
           });
           
           checkedSubject();
        }
   }
   function checkedSubject(){
    
    var total_subject=@json($total_subject);
    var totalAmount=0.00;
   
    var count=0; var addon_count = 0;
      $(".chapter_value").each(function(index) {
          if(this.checked==true){     
            count+=1;    
            totalAmount= parseFloat(totalAmount) + parseFloat($(this).attr('data-price'));
          }
         
      });

      $(".addons-value").each(function(index) {
          if(this.checked==true){    
            addon_count+=1; 
            totalAmount= parseFloat(totalAmount) + parseFloat($(this).attr('data-price'));
          }
         
      });
      
      if(count==total_subject){
        $("#radio-card-1").prop("checked", true); 
      }else{
        $("#radio-card-2").prop("checked", true);
      }

      if(addon_count > 0){
        $('#isAddonsSelected').val(1);
      }
     
      var amount=`<i class="fa fa-inr" aria-hidden="true"></i> &nbsp;`;
     
      $("#total_price").html(amount+totalAmount.toFixed(2));
      const box = document.getElementById('add-to-cart');
      const buynow=document.getElementById('buy-now');

      

      if( (count==0) && (addon_count == 0)  ){
        box.style.display = 'none';
        buynow.style.display='none';
      }else{
        if(( count == 0) && (!(addon_count == 0))){
            alert('Oops! Select at least one subject to proceed further.');
            $(".addons-value").prop('checked', false);
            $("#total_price").html(amount+'0.00');
            box.style.display = 'none';
            buynow.style.display='none';
        }else{
            box.style.display = 'block';
            buynow.style.display='block';
        }
        
      }  


      


    }
</script>



@endsection