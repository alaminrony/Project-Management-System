<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-5">
                @if(!empty($userInfo->photo))
                <img width="140" height="150" src="{{URL::to('/')}}/public/uploads/user/{{$userInfo->photo}}" alt="{{ $userInfo->full_name}}"/>
                @else
                <img width="150" height="150" src="{{URL::to('/')}}/public/image/5e12c32277f4c.20200106.jpg" alt="{{ $userInfo->full_name}}"/>
                @endif
            </div>
            <div class="col-md-7">
                <table class="table table-borderless">
                    <tr>
                        <td><b>@lang('label.NAME')</b></td>
                        <td>{{$userInfo->first_name.' '.$userInfo->last_name}}</td>
                    </tr>
                    <tr>
                        <td><b>@lang('label.EMPLOYEE_ID')</b></td>
                        <td>{{$userInfo->employee_id}}</td>
                    </tr>
                    <tr>
                        <td><b>@lang('label.DEPARTMENT')</b></td>
                        <td>{{$userInfo->department_name}}</td>
                    </tr>
                    <tr>
                        <td><b>@lang('label.DESIGNATION')</b></td>
                        <td>{{$userInfo->designation_name}}</td>
                    </tr>
                </table>

            </div>
        </div>

    </div>

    <div class="col-md-6">
        <table class="table table-borderless">
            <tr>
                <td><b>@lang('label.TITLE'):</b></td>
                <td>{{ $bugDetails->bug_title }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.PROJECT'):</b></td>
                <td>{{ $bugDetails->project_name }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTING_DATE'):</b></td>
                <td>{{ Helper::printDate($bugDetails->reporting_date) }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTING_MEDIUM'):</b></td>
                <td>{{ $bugDetails->medium_name }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTED_BY'):</b></td>
                <td>{{ $bugDetails->reported_by }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.SEVERITY_LEVEL'):</b></td>
                <td>{{ $bugDetails->category_level }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.UNIQUE_CODE'):</b></td>
                <td>{{ $bugDetails->unique_code }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.PROGRESS'):</b></td>
                <td>
                    <div class="progress" id="progressBackground">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:{{!empty($latestProgress->progress) ? $latestProgress->progress :''}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{!empty($latestProgress->progress) ? $latestProgress->progress :''}} %</div>
                    </div>
                </td>

            </tr>
            <tr>
                <td><b>@lang('label.ATTACHMENT'):</b></td>
                <td>
                    <?php
                    $bugFilesArr = json_decode($bugDetails->files);
                    $i = 1;
                    if (!empty($bugFilesArr)) {
                        foreach ($bugFilesArr as $bugFiles) {
                            $ext = pathinfo($bugFiles->file, PATHINFO_EXTENSION);
                            ?>
                            <div id="bug-file-show">
                                @if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}" class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/uploads/bug/'.$bugFiles->file)}}" height="50px" width="50px"></a></div>
                                @elseif($ext == 'pdf')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}" class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/image/fileIcon/pdf.png')}}" height="50px" width="50px"></a></div>
                                @elseif($ext == 'doc')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}" class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/image/fileIcon/doc.jpg')}}" height="50px" width="50px"></a></div>
                                @elseif($ext == 'docx')
                                <div><a href="{{asset('public/uploads/bug/'.$bugFiles->file)}}"  class="tooltips" title="{{$bugFiles->title}}" download><img src="{{asset('public/image/fileIcon/docx.jpg')}}" height="50px" width="50px"></a></div>
                                @endif
                            </div>

                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>
