<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> {!!__('label.VIEW_BUG_DETAILS')!!}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <td><b>@lang('label.TITLE')</b></td>
                <td>{{ $target->bug_title }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.PROJECT_NAME')</b></td>
                <td>{{ $target->project_name }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTING_DATE')</b></td>
                <td>{{ Helper::printDate($target->reporting_date) }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTING_MEDIUM')</b></td>
                <td>{{ $target->medium_name }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.REPORTED_BY')</b></td>
                <td>{{ $target->reported_by }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.SEVERITY_LEVEL')</b></td>
                <td>{{ $target->category_level }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.UNIQUE_CODE')</b></td>
                <td>{{ $target->unique_code }}</td>
            </tr>
            <tr>
                <td><b>@lang('label.STATUS')</b></td>
                <td>
                    <div id="progressBarMargin">
                        @if($target->status == '1')
                        <span class="label label-sm label-success">@lang('label.OPEN')</span>
                        @else
                        <span class="label label-sm label-warning">@lang('label.CLOSED')</span>
                        @endif
                    </div>
                    <div>
                        <div class="progress" id="progressBackground">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:{{!empty($latestProgress->progress) ? $latestProgress->progress :''}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{!empty($latestProgress->progress) ? $latestProgress->progress :''}} %</div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td><b>@lang('label.ATTACHMENT')</b></td>
                <td>
                    <?php
                    $bugFilesArr = json_decode($target->files);
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



    <div class="modal-header clone-modal-header">
        <h3 class="modal-title text-center">
            @lang('label.BUG_TRACKING')
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <div class="portlet-body">
                            <div class="mt-timeline-2">
                                @if($followUps->isNotEmpty())
                                <div class="mt-timeline-line border-grey-steel"></div>
                                <ul class="mt-container">
                                    @foreach($followUps as $followUp)
                                    <?php
                                    $icon = $background = '';
                                    if ($followUp->status == '1') {
                                        $icon = 'icon-loop';
                                        $background = 'bg-blue bg-font-blue border-grey-steel';
                                    } elseif ($followUp->status == '2') {
                                        $icon = 'icon-calendar';
                                        $background = 'bg-green-turquoise bg-font-green-turquoise border-grey-steel';
                                    } elseif ($followUp->status == '3') {
                                        $icon = 'icon-close';
                                        $background = 'bg-red bg-font-red';
                                    }
                                    ?>
                                    <li class="mt-item">
                                        <div class="mt-timeline-icon {{ $background }}">
                                            <i class="{{ $icon }}"></i>
                                        </div>
                                        <div class="mt-timeline-content">
                                            <div class="mt-content-container">
                                                @if($followUp->status == '1')
                                                <strong>@lang('label.STATUS'): </strong><span>{!! "In Progress" !!}</span><br/>
                                                <strong>@lang('label.PROGRESS_BY'): </strong><span>{!! !empty($followUp->action_taken_by) ? $followUp->action_taken_by : '' !!}</span><br/>
                                                <strong>@lang('label.PROGRESS_AT'): </strong><span>{!! !empty($followUp->date_time) ? Helper::formatDate($followUp->date_time) : '' !!}</span><br/>
                                                <strong>@lang('label.PROGRESS'): </strong><span>{!! !empty($followUp->progress) ? $followUp->progress : '' !!} %</span><br/>
                                                <strong>@lang('label.REMARKS'): </strong><span>{!! !empty($followUp->remarks) ? $followUp->remarks : '' !!}</span>

                                                @elseif($followUp->status == '2')
                                                <strong>@lang('label.HAULT_BY'): </strong><span>{!! !empty($followUp->action_taken_by) ? $followUp->action_taken_by : '' !!}</span><br/>
                                                <strong>@lang('label.HAULT_AT'): </strong><span>{!! !empty($followUp->date_time) ? Helper::formatDate($followUp->date_time) : '' !!}</span><br/>
                                                <strong>@lang('label.REMARKS'): </strong><span>{!! !empty($followUp->remarks) ? $followUp->remarks : '' !!}</span>
                                                @elseif($followUp->status == '3')
                                                <strong>@lang('label.CLOSED_BY'): </strong><span>{!! !empty($followUp->action_taken_by) ? $followUp->action_taken_by : '' !!}</span><br/>
                                                <strong>@lang('label.CLOSED_AT'): </strong><span>{!! !empty($followUp->date_time) ? Helper::formatDate($followUp->date_time) : '' !!}</span><br/>
                                                <strong>@lang('label.REMARKS'): </strong><span>{!! !empty($followUp->remarks) ? $followUp->remarks : '' !!}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach

                                </ul>
                                @else
                                <div class="alert alert-danger">
                                    @lang('label.NO_BUG_TRACKING_IS_AVAILABLE')       
                                </div>
                                @endif
                            </div>
                        </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer2 bg-default">
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        </div>
    </div>
</div>

</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>



