<form id="mcqForm">
    @csrf
    @forelse($mcqRandom as $key => $item)
        <ol class="pl15" type="" style="list-style-type: none;">
            <li>
                <h4 data-brackets-id="3991" class="small-heading-black mb20">{{$mcqRandom->firstItem() + $key}}. {{$item->question}}</h4>
                <div>
                    <ul class="list-inline pl-0">
                        <input type="hidden" name="setId" id="setId" value="{{$item->set_id}}">
                        <li>
                            <input type="radio" id="test1" name="mcq-group" value="{{$item->option_1}}">
                            <label for="test1"> {{$item->option_1}} </label>
                        </li>
                        <li>
                            <input type="radio" id="test2" name="mcq-group" value="{{$item->option_2}}">
                            <label for="test2">  {{$item->option_2}} </label>
                        </li>
                        <li>
                            <input type="radio" id="test3" name="mcq-group" value="{{$item->option_3}}">
                            <label for="test3">  {{$item->option_3}} </label>
                        </li>
                        <li>
                            <input type="radio" id="test4" name="mcq-group" value="{{$item->option_4}}">
                            <label for="test4"> {{$item->option_4}} </label>
                        </li>
                    </ul>
                </div>
            </li>
        </ol>
        <div class="check-result text-center" style="display:none;">
            <button class="btn btn-success">Check Result</button>
        </div>
    @empty
        <div class="text-center">
            <div id="mcqResult">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" id="mcqSubmitBtn">Submit</button>
            </div>
            <script>
                document.getElementById('saveOptions').style.display = "none";
            </script>
        </div>
    @endforelse
</form>

<div class="mcq-page-link">
   
    @if ($mcqRandom != null)
         <a href="{{ $mcqRandom->nextPageUrl() }}" class="knowledge-link" id="saveOptions">Next</a>
    @endif
   
</div>