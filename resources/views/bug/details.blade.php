@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.BUG_DETAILS')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => array('bug.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal','files'=>true]) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="projectId">@lang('label.PROJECT') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::text('project_id',$target->project_name,['class' => 'form-control', 'id' => 'projectId','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportedBy">@lang('label.REPORTED_BY') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <div id="showReportedBy">
                                    {!! Form::text('reported_by',$target->reported_by,['class' => 'form-control', 'id' => 'reportedBy','readonly']) !!} 
                                    <span class="text-danger">{{ $errors->first('reported_by') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportingDate">@lang('label.REPORTING_DATE') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::text('reporting_date',Helper::printDate($target->reporting_date),['class' => 'form-control', 'id' => 'reportingDate','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('reporting_date') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportingMedium">@lang('label.REPORTING_MEDIUM') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::text('reporting_medium',$target->medium_name, ['class' => 'form-control', 'id' => 'reportingMedium','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('reporting_medium') }}</span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="severityLevel">@lang('label.SEVERITY_LEVEL') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <div>
                                    {!! Form::text('severity_level',$target->category_level, ['class' => 'form-control', 'id' => 'severityLevel','readonly']) !!} 
                                    <span class="text-danger">{{ $errors->first('severity_level') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="severityLevel">@lang('label.UNIQUE_CODE') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <div>
                                    {!! Form::text('unique_code',$target->unique_code, ['class' => 'form-control', 'id' => 'severityLevel','readonly']) !!} 
                                    <span class="text-danger">{{ $errors->first('severity_level') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::text('status',$target->status == 1 ? __('label.OPEN') : __('label.CLOSE'), ['class' => 'form-control', 'id' => 'status','readonly']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportedBy">@lang('label.ATTACHMENT') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
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

                                    <?php }
                                } ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <a href="{{ URL::to('/bug'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle green"> <i class="fa fa-arrow-circle-left"></i> @lang('label.OK')</a>

                        <a href="{{ URL::to('/bug'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<div class="loader">
    <center>
        <img class="loading-image" src="{{url('public/image/preloader/126.gif')}}" alt="loading..">
    </center>
</div>

<link rel="stylesheet" href="{{asset('public/css/jquery.fileupload.css')}}">
<script src="{{asset('public/js/jquery.fileupload.js')}}"></script>
<script>
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.github.io' ?
            '//jquery-file-upload.appspot.com/' : 'server/php/';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo('#files');
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                    );
        }
    }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
@stop