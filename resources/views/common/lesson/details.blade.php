@if($lesson->topics()->exists() || $lesson->Sets()->exists())


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">

                    <a class="nav-item nav-link active" id="tabMenu" data-toggle="tab" href="#nav-video" role="tab"
                        aria-controls="nav-profile" aria-selected="false">Video <span
                            class="badge rounded-pill bg-danger" style="color: aliceblue">
                            {{$lesson->topics->where('type',2)->count()}}
                        </span></a>

                    <a class="nav-item nav-link" id="tabMenu" data-toggle="tab" href="#nav-practice-test" role="tab"
                        aria-controls="nav-contact" aria-selected="false">MCQ Practice Test <span
                            class="badge rounded-pill bg-danger" style="color: aliceblue">
                            {{$lesson->Sets->count()}}
                        </span></a>

                </div>
            </nav>
            <br><br>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-video" role="tabpanel" aria-labelledby="nav-video-tab">
                    <div style="overflow-x:auto;">
                        <table class="table table-striped" id="lessonTableVideo">
                            <thead>
                                <tr>
                                    <th> #No </th>
                                    <th> Lesson Name </th>
                                    <th> Recources Topics </th>
                                    <th> Type </th>
                                    <th> Recources </th>

                                    <th> Thumbnail image </th>
                                    <th> Video Duration </th>
                                    <th>Total Watch Time</th>
                                    <th> Preview </th>
                                    <th> Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $videocount=1; @endphp
                                @foreach($lesson->topics->where('type',2) as $key=>$topic)
                                <tr>
                                    <td>{{$videocount++}}</td>
                                    <td> {{$topic->parentLesson->name}}</td>
                                    <td> {{$topic->name}}</td>
                                    <td> @if($topic->type==1)pdf @elseif($topic->type==2) video @else
                                        article @endif </article>
                                    </td>
                                    <td> @if($topic->type==1)<a href="{{asset($topic->lessonAttachment->img_url)}}"
                                            target="_blank">
                                            {{-- {{basename($topic->lessonAttachment->img_url)}} --}}
                                            Preview
                                        </a>
                                        @elseif($topic->type==2) <a
                                            href="{{asset($topic->lessonAttachment->video_origin_url)}}"
                                            target="_blank">
                                            {{-- {{ substr($topic->lessonAttachment->video_origin_url, 0,40)}} --}}
                                            Click to view
                                        </a> @else NA @endif
                                    </td>

                                    <td> @if($topic->type==2)<a
                                            href="{{asset($topic->lessonAttachment->video_thumbnail_image)}}"
                                            target="_blank">
                                            {{-- {{substr($topic->lessonAttachment->video_thumbnail_image,0,10)}} --}}
                                            Image
                                        </a>
                                        @else NA @endif
                                    </td>
                                    <td>{{$topic->lessonAttachment->video_duration ?? "00:00:00"}}</td>
                                    <td>{{videoWatchTime($user->id,$topic->assign_subject_id,$topic->id)}}</td>
                                    <td>
                                        @if ($topic->preview==0)
                                        No
                                        @else
                                        Yes
                                        @endif
                                    </td>
                                    <td>@if($topic->status==1)
                                        Active
                                        @else
                                        InActive @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-practice-test" role="tabpanel" aria-labelledby="nav-practice-test">
                    <div style="overflow-x:auto;">
                        <table class="table table-striped" id="lessonTableMcq">
                            <thead>
                                <tr>
                                    <th> #No </th>
                                    <th> Set Name </th>
                                    <th> Total Question </th>
                                    <th> Status </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lesson->Sets as $key=>$set)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td> {{$set->set_name}}</td>
                                    <td> {{$set->question->count()}}</td>
                                    <td> @if($set->is_activate==1)Active @else InActive @endif</td>
                                    {{-- <td>@if($topic->status==1)Active @else InActive @endif</td> --}}
                                    <td>
                                        @if(auth()->user()->hasRole('Teacher'))
                                        <a href="{{route('teacher.view.mcq.attempt',[Crypt::encrypt($set->id),Crypt::encrypt($user->id)])}}"
                                            title="View Details"><i class="mdi mdi-eye"></i></a>
                                        @endif
                                        @if(auth()->user()->hasRole('Admin'))
                                        <a href="{{route('admin.view.mcq.attempt',[Crypt::encrypt($set->id),Crypt::encrypt($user->id)])}}"
                                            title="View Details"><i class="mdi mdi-eye"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div style="overflow-x:auto;">
                        <table class="table table-striped" id="lessonTableArticle">
                            <thead>
                                <tr>
                                    <th>#No</th>
                                    <th> Lesson Name </th>
                                    <th> Recources Topics </th>
                                    <th> Type </th>
                                    <th> Preview </th>
                                    <th> Status </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $articlecount=1; @endphp
                                @foreach($lesson->topics->where('type',3) as $key=>$topic)
                                <tr>
                                    <td>{{$articlecount++}}</td>
                                    <td> {{$topic->parentLesson->name}}</td>
                                    <td> {{$topic->name}}</td>
                                    <td> @if($topic->type==1)pdf @elseif($topic->type==2) video @else
                                        article @endif </article>
                                    </td>

                                    <td>
                                        @if ($topic->preview==0)
                                        No
                                        @else
                                        Yes
                                        @endif
                                    </td>
                                    <td>@if($topic->status==1)
                                        Active
                                        @else
                                        InActive @endif
                                    </td>
                                    <td><a href="{{route('admin.course.management.lesson.edit',Crypt::encrypt($topic->id))}}"
                                            title="Edit Lesson"><i class="mdi mdi-grease-pencil"></i></a>
                                        <a href="{{route('admin.course.management.lesson.view',Crypt::encrypt($topic->id))}}"
                                            title="View Details"><i class="mdi mdi-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-pdf" role="tabpanel" aria-labelledby="nav-pdf-tab">
                    <div style="overflow-x:auto;">
                        <table class="table table-striped" id="lessonTable">
                            <thead>
                                <tr>
                                    <th>#No</th>
                                    <th> Lesson Name </th>
                                    <th> Recources Topics </th>
                                    <th> Type </th>
                                    <th> Recources </th>
                                    <th> Preview</th>
                                    <th> Status </th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1; @endphp
                                @foreach($lesson->topics->where('type',1) as $key=>$topic)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td> {{$topic->parentLesson->name}}</td>
                                    <td> {{$topic->name}}</td>
                                    <td> @if($topic->type==1)pdf @elseif($topic->type==2) video @else
                                        article @endif </article>
                                    </td>
                                    <td> @if($topic->type==1)<a target="_blank"
                                            href="{{asset($topic->lessonAttachment->img_url)}}" target="_blank">
                                            {{-- {{basename($topic->lessonAttachment->img_url)}} --}}
                                            Click to view
                                        </a>
                                        @elseif($topic->type==2) <a
                                            href="{{asset($topic->lessonAttachment->video_origin_url)}}"
                                            target="_blank">
                                            {{-- {{ substr($topic->lessonAttachment->video_origin_url, 0,40)}} --}}
                                            Click to view
                                        </a> @else NA @endif
                                    </td>



                                    <td>
                                        @if ($topic->preview==0)
                                        No
                                        @else
                                        Yes
                                        @endif
                                    </td>
                                    <td>@if($topic->status==1)
                                        Active
                                        @else
                                        InActive
                                        @endif
                                    </td>
                                    {{-- <td><a href="" title="Edit Lesson"><i class="mdi mdi-grease-pencil"></i></a>
                                        <a href="" title="View Details"><i class="mdi mdi-eye"></i></a>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


@endif
