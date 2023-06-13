<div class="row">
    <div class="container">
        <div class="panel-group subject-content" id="accordion" role="tablist" aria-multiselectable="true">
            @foreach($subject->lesson as $key=>$lesson)
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h5 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseLesson{{$key}}"
                            aria-expanded="true" aria-controls="collapseLesson{{$key}}">
                            <i class="more-less glyphicon glyphicon-plus"></i>
                            {{$key+1}}. Lesson : {{$lesson->name}}
                        </a>

                    </h5>
                </div>
                <div id="collapseLesson{{$key}}" class="panel-collapse collapse show" role="tabpanel"
                    aria-labelledby="headingOne">
                    <div class="panel-body" style="position:relative; left:40px;">
                        @if($lesson->type==1)
                        <i class="fa fa-file" aria-hidden="true"></i> {{$lesson->name}}<br>
                        @elseif($lesson->type==2)
                        <i class="fa fa-play" aria-hidden="true"></i> {{$lesson->name}}<br>
                        @else
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i> {{$lesson->name}}<br>
                        @endif
                        @if($lesson->topics->count()>0)
                        @foreach($lesson->topics as $key=>$topic)
                        @if($topic->type==1)
                        <i class="fa fa-file" aria-hidden="true"></i> {{$topic->name}}<br>
                        @elseif($topic->type==2)
                        <i class="fa fa-play" aria-hidden="true"></i> {{$topic->name}}<br>
                        @else
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i> {{$topic->name}}<br>
                        @endif
                        @if($topic->subTopics->count()>0)
                        @foreach($topic->subTopics as $key=>$sub_topic)
                        @if($sub_topic->type==1)
                        <i class="fa fa-file" aria-hidden="true"></i> {{$sub_topic->name}}<br>
                        @elseif($sub_topic->type==2)
                        <i class="fa fa-play" aria-hidden="true"></i> {{$sub_topic->name}}<br>
                        @else
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i> {{$sub_topic->name}}<br>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div><!-- panel-group -->


    </div>
</div>