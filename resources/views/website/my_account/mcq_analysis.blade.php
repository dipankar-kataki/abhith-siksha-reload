@extends('layout.website.website')

@section('title', 'My Account')

@section('head')
<link href="{{asset('asset_website/css/my_account.css')}}" rel="stylesheet">
<style>
    main {
        margin: 0;
    }
</style>
@endsection

@section('content')

<div class="mcq-head  d-flex">
    <div class="mcq-head-text d-flex">
        <div class="mcq-head-icon mr-3">
            <img src="{{asset('asset_website/img/mcq.png')}}" alt="">
        </div>
        <div class="mcq-header-text">
            <h3>Multiple Choice Questions For Class
                <span>Board</span>
            </h3>
            <p></p>
        </div>
    </div>

</div>
{{-- <div class="container-fluid" id="mcq-question">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4>Question 1: What is your favourite language?</h4>
                    <form action="" class="mcq-options d-flex">
                        <div class="mcq-option-text">
                            <h5>Option:</h5>
                        </div>
                        <div class="mcq-option-div">
                            <div class="options">
                                <input type="radio" id="html" name="fav_language" value="HTML">
                                <label for="html">HTML</label>
                            </div>
                            <div class="options">
                                <input type="radio" id="css" name="fav_language" value="CSS">
                                <label for="css">CSS</label>
                            </div>
                            <div class="options">
                                <input type="radio" id="javascript" name="fav_language" value="JavaScript">
                                <label for="javascript">JavaScript</label>
                            </div>
                            <div class="options">
                                <input type="radio" id="jQuery" name="fav_language" value="jQuery">
                                <label for="jQuery">jQuery</label>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="container-fluid" id="mcq_result">
    <h4>MCQ Test</h4>
    <div class="row">
        <div class="col-md-6">
            {{-- <div class="d-flex progress-main-div">
                <div class="circleOne">
                    <div class="circular-progress">
                        <span class="progress-value"></span>
                    </div>
                </div>
                <div class="circular_text">
                    <div class="watched d-flex">
                        <span class="dot mr-2"></span>
                        <p>Correct</p>
                    </div>
                    <div class="not-watched d-flex">
                        <span class="dot mr-2"></span>
                        <p>Incorrect</p>
                    </div>
                    <div class="unattempt d-flex">
                        <span class="dot mr-2"></span>
                        <p>Unattempted</p>
                    </div>
                </div>
            </div> --}}

            <canvas id="myChart"></canvas>

        </div>
        <div class="col-md-6">
            <div class="total-div">
                <div class="totalQuestion">
                    <h3>Total Question: <span>{{$data['total_question']}}</span></h3>
                </div>
                <div class="attempted-div d-flex">
                    <div class="col-md-6 attempted">
                        <h3>Attempted: <span>{{$data['attempted_question']}}</span></h3>
                    </div>
                    <div class="col-md-6 unattempted">
                        <h3>Unattempted: <span>{{$data['total_question']-$data['attempted_question']}}</span></h3>
                    </div>
                </div>
                <div class="answered d-flex">
                    <div class="col-md-6 correct">
                        <h3>Correct: <span>{{$data['correct_attempted']}}</span></h3>
                    </div>
                    <div class="col-md-6 incorrect">
                        <h3>Incorrect: <span>{{$data['incorrect_attempted']}}</span></h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6" id="mcq-result">
            <div class="row">
                <div class="col-md-12">
                    <div class="totalQuestion">
                        <h3>Total Question: <span>9</span></h3>
                    </div>
                </div>
                <div class="col-md-6 random">
                    <div class="attempted">
                        <h3>Attempt: <span>9</span></h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="unattempted">
                        <h3>Unattempted: <span>9</span></h3>
                    </div>
                </div>
                <div class="col-md-6 random">
                    <div class="correct">
                        <h3>Correct: <span>9</span></h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="incorrect">
                        <h3>Incorrect: <span>9</span></h3>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
</div>




@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var alldata=@json($data);
    
    var correct=alldata.correct_attempted;
    var Incorrect=alldata.incorrect_attempted;
    var Unattempted=alldata.total_question-(alldata.correct_attempted+alldata.incorrect_attempted);
   const data = {
  labels: [
    'Correct',
    'Incorrect',
    'Unattempted'
  ],
  datasets: [{
    label: 'MCQ Report',
    data: [correct, Incorrect, Unattempted],
    backgroundColor: [
      'rgb(47, 224, 53)',
      'rgb(255, 99, 132)',
      'rgb(255, 205, 86)'
    ],
    hoverOffset: 4
  }]
};
  
const config = {
  type: 'doughnut',
  data: data,
  options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
};
</script>
<script>
    const myChart = new Chart(
       document.getElementById('myChart'),
      config
    );
</script>
@endsection