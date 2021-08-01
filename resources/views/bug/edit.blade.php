@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.EDIT_BUG')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(['route' => array('bug.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal','files'=>true]) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class=" col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="title">@lang('label.TITLE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('bug_title',$target->bug_title,['class' => 'form-control', 'id' => 'title']) !!} 
                                <span class="text-danger">{{ $errors->first('bug_title') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="projectId">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projectList,$target->project_id,['class' => 'form-control js-source-states', 'id' => 'projectId']) !!} 
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportedBy">@lang('label.REPORTED_BY') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div id="showReportedBy">
                                    {!! Form::select('reported_by',$reportByList,$target->reported_by,['class' => 'form-control js-source-states', 'id' => 'reportedBy']) !!} 
                                    <span class="text-danger">{{ $errors->first('reported_by') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportingDate">@lang('label.REPORTING_DATE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('reporting_date',$target->reporting_date,['class' => 'form-control', 'id' => 'reportingDate','readonly']) !!} 
                                <span class="text-danger">{{ $errors->first('reporting_date') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reportingMedium">@lang('label.REPORTING_MEDIUM') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('reporting_medium',$mediumList,$target->reporting_medium, ['class' => 'form-control js-source-states', 'id' => 'reportingMedium']) !!} 
                                <span class="text-danger">{{ $errors->first('reporting_medium') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="severityLevel">@lang('label.SEVERITY_LEVEL') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div>
                                    {!! Form::select('severity_level',$bugCatList,$target->severity_level, ['class' => 'form-control js-source-states', 'id' => 'severityLevel']) !!} 
                                    <span class="text-danger">{{ $errors->first('severity_level') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <h4 style="text-align: center;margin-top: 0px;">@lang('label.FILE_ATTACHMENT')</h4>
                        <div class = "form-group">
                            <?php
                            $filesArr = json_decode($target->files);
                            ?>

                            <div class ="table-responsive">
                                <table class ="table table-bordered" id="edit_dynamic_field">
                                    @if(!empty($filesArr))
                                    <?php $i = 0; ?>
                                    @foreach($filesArr as $files)
                                    <?php
                                    $i++;
                                    $ext = pathinfo($files->file, PATHINFO_EXTENSION);
                                    ?>
                                    <tr id="dynamicRow{{$i}}">
                                        <td width="50%"><input type ="text" name="title[]" value="{{$files->title}}" class="form-control name_list"/></td>
                                        <td><input type ="file" name="files[]" placeholder="Upload File" class="form-control name_list"/></td>
                                        @if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg')
                                        <td><img src="{{asset('public/uploads/bug/'.$files->file)}}" height="50px" width="50px" alt=""></td>
                                        @elseif($ext == 'pdf')
                                        <td><img src="{{asset('public/image/fileIcon/pdf.png')}}" height="50px" width="50px" alt=""></td>
                                        @elseif($ext == 'doc')
                                        <td><img src="{{asset('public/image/fileIcon/doc.jpg')}}" height="50px" width="50px" alt=""></td>
                                        @elseif($ext == 'docx')
                                        <td><img src="{{asset('public/image/fileIcon/docx.jpg')}}" height="50px" width="50px" alt=""></td>
                                        @else
                                        <td></td>
                                        @endif
                                        <?php if ($i == 1) { ?>
                                            <td><button type ="button" name="add" id="add" class="btn "><i class="fa fa-plus font-red"></i></button></td>
                                        <?php } else { ?>
                                            <td><button type ="button" name="remove" data-id="{{$i}}" class="btn  row-remove"><i class="fa fa-times font-red"></i></button></td>
                                        <?php } ?>
                                    </tr>
                                    @endforeach
                                    @endif
                                </table>

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
<div class="loader">
    <center>
        <img class="loading-image" src="{{url('public/image/preloader/126.gif')}}" alt="loading..">
    </center>
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
                        console.log(data.viewReportedBy);
                        $('#showReportedBy').html(data.viewReportedBy);
                        $('.loader').hide();
                    }
                });
            }
        });

        var i = 0;
        $(document).on('click', '#add', function () {
            i++;
            $('#edit_dynamic_field').append('<tr id="row' + i + '">' +
                    '<td width="50%"><input type ="text" name="title[]" value="" placeholder="Enter your File Title" class="form-control name_list"/></td>' +
                    '<td><input type ="file" name="files[]" placeholder="Upload File" class="form-control name_list"/></td>' +
                    '<td></td>' +
                    '<td><button type ="button" name="remove" id="' + i + '" class="btn  btn-remove"><i class="fa fa-times font-red"></i></button></td>' +
                    '</tr>')
        });


        $(document).on('click', '.btn-remove', function () {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

        $(document).on('click', '.row-remove', function () {
            var rowId = $(this).attr('data-id');
            $('#dynamicRow' + rowId).remove();
        });

    });
</script>

@stop