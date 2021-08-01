@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.ASSIGN_TASK_TO_PROJECT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal', 'id'=>'taskToProjectForm')) !!}
            {!! Form::hidden('page') !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="project_id">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projects,null, ['class' => 'form-control js-source-states', 'id' => 'project_id']) !!} 
                                <span class="text-danger" id="errorProjectId"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="showTasks">

                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="saveTaskToProject">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/taskToProject') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('change', '#project_id', function () {
            var projectId = $(this).val();
            if (projectId != '') {
                $.ajax({
                    url: "{{route('taskToProject.getTasks')}}",
                    type: "post",
                    data: {project_id: projectId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (date) {
                        $('#showTasks').html(date.taskData);
                    }
                });
            } else {
                $('#showTasks').html('');
            }
        });

        $(document).on('click', '#saveTaskToProject', function () {
            var formData = new FormData($('#taskToProjectForm')[0]);
            if (formData != '') {
                $.ajax({
                    url: "{{route('taskToProject.store')}}",
                    type: "post",
                    data: formData,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#errorProjectId").text('');
                        $("#errorTasks").text('');
                        if (data.errors) {
                            $("#errorProjectId").text(data.errors.project_id);
                            $("#errorTasks").text(data.errors.task_id);
                        }
                        if (data == 'success') {
                            toastr["success"]("@lang('label.TASKS_HAVE_BEEN_ASSIGN_TO_PROJECT_SUCCESSFULLY')");
                        }
                    }
                });
            }
        });
    });
</script>
@stop
