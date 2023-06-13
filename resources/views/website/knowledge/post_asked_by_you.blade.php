@forelse ($questions_asked_by_user as $question)
<li>
    @php $enc_id = Crypt::encryptString($question->id)@endphp
    <div class="answer-describtion">
        <p class="small-text-heading">Posted: {{ $question->created_at->diffForHumans() }}
        </p>
        <h4 class="small-heading-black">{{ $question->question }}</h4>
        <p class="text-justify">{!! $question->description !!}</p>
        <a href="{{ $question->links }}" class="post-link" target="_blank">{{ $question->links }}</a>
        <div class="answer-btn-box">
            <ul class="list-inline answer-btn-list">
                <li><a
                        href="{{ route('website.knowledge.details.post', ['id' => $enc_id]) }}">{{ $question->total_comments }}
                        Comment</a></li>
                <li><a href="javascript:void(0)">{{ $question->total_views }}
                        Views</a></li>
            </ul>
        </div>
    </div>
</li>
@empty
    <div style="position: absolute;left: 40%;margin-top:20px;"> 
        <i class="fa fa-check-circle-o" aria-hidden="true" style="color:green;font-size:22px;"></i>
        &nbsp; You are all caught up. 
    </div>
@endforelse