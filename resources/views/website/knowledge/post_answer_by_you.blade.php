@forelse($answered_by_user as $answer)
    @php $encrypt_id = Crypt::encryptString($answer->knowledgeForumPost->id)@endphp
    <li>
        <div class="answer-describtion">
            <p class="small-text-heading">
                Posted: {{ $answer->knowledgeForumPost->created_at->diffForHumans() }},
                </p>
            <h4 class="small-heading-black">
                {{ $answer->knowledgeForumPost->question }}</h4>
            <p class="text-justify">
                {!! $answer->knowledgeForumPost->description !!}</p>
            <a href="{{ $answer->knowledgeForumPost->links }}" target="_blank"
                class="post-link">{{ $answer->knowledgeForumPost->links }}</a>
            <div class="answer-btn-box">
                <ul class="list-inline answer-btn-list">
                    <li>
                        <a href="{{ route('website.knowledge.details.post', ['id' => $encrypt_id]) }}">
                            {{ $answer->knowledgeForumPost->total_comments }}
                            Comment</a>
                    </li>
                    <li><a href="javascript:void(0)">{{ $answer->knowledgeForumPost->total_views }}
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