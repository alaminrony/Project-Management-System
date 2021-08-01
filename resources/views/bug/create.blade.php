@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.CREATE_NEW_BUG')
            </div>
        </div>

        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'bug/store','class' => 'form-horizontal','files'=>true)) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="title">@lang('label.TITLE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('bug_title','',['class' => 'form-control', 'id' => 'title']) !!} 
                                <span class="text-danger">{{ $errors->first('bug_title') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="projectId">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projectList,null, ['class' => 'form-control js-source-states', 'id' => 'projectId']) !!} 
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportedBy">@lang('label.REPORTED_BY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div id="showReportedBy">
                                    {!! Form::select('reported_by',$reportByList,null, ['class' => 'form-control js-source-states', 'id' => 'reportedBy']) !!} 
                                    <span class="text-danger">{{ $errors->first('reported_by') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportingDate">@lang('label.REPORTING_DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('reporting_date',date('Y-m-d'),['class' => 'form-control', 'id' => 'reportingDate','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('reporting_date') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportingMedium">@lang('label.REPORTING_MEDIUM') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('reporting_medium',$mediumList,null, ['class' => 'form-control js-source-states', 'id' => 'reportingMedium']) !!} 
                                <span class="text-danger">{{ $errors->first('reporting_medium') }}</span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="severityLevel">@lang('label.SEVERITY_LEVEL') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div>
                                    {!! Form::select('severity_level',$bugCatList,null, ['class' => 'form-control js-source-states', 'id' => 'severityLevel']) !!} 
                                    <span class="text-danger">{{ $errors->first('severity_level') }}</span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <h4 style="text-align: center;margin-top: 0px;">@lang('label.FILE_ATTACHMENT')</h4>
                        <div class = "form-group">
                            <div class ="table-responsive">
                                <table class ="table table-bordered" id="dynamic_field">
                                    <tr class="numbar">
                                        <td width="50%"><input type ="text" name="title[]" placeholder="Enter your File Title" class="form-control name_list"/></td>
                                        <td><input type ="file" name="files[]" placeholder="Upload File" class="form-control name_list"/></td>
                                        <td><button type ="button" name="add" id="add" class="btn"><i class="fa fa-plus font-red"></i></button></td>
                                    </tr>
                                </table>
                                <div class="text-danger" id="errorTitle"></div>
                                <div class="text-danger" id="errorFiles"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/bug'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#reportingDate').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
        })

        $(document).on('change', '#projectId', function () {
            var projectId = $(this).val();
            if (projectId != '') {
                $.ajax({
                    url: "{{route('bug.getReportedBy')}}",
                    type: "post",
                    data: {project_id: projectId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        $('.loader').show()
                    },
                    success: function (data) {
                        $('#showReportedBy').html(data.viewReportedBy);
                        $('.loader').hide();
                    }
                });
            }
        });

        var i = 0;
        $(document).on('click', '#add', function () {
            i++;
            $('#dynamic_field').append('<tr id="row' + i + '">' +
                    '<td width="50%"><input type ="text" name="title[]" value="" placeholder="Enter your File Title" class="form-control name_list"/></td>' +
                    '<td><input type ="file" name="files[]" placeholder="Upload File" class="form-control name_list"/></td>' +
                    '<td><button type ="button" name="remove" id="' + i + '" class="btn  btn_remove"><i class="fa fa-times font-red"></i></button></td>' +
                    '</tr>')
        });


        $(document).on('click', '.btn_remove', function () {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });
</script>
@stop
