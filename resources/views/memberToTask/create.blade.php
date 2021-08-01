@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.MEMBER_TO_TASK')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal','id'=>'memberToTaskFormData')) !!}
            {!! Form::hidden('page') !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="projectId">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projects,null, ['class' => 'form-control js-source-states', 'id' => 'projectId']) !!} 
                                <span class="text-danger" id="errorProjectId"></span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="memberId">@lang('label.PROJECT_MEMBER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <div id="showMember">
                                    {!! Form::select('member_id',$memberList,null, ['class' => 'form-control js-source-states', 'id' => 'memberId']) !!} 
                                    <span class="text-danger" id="errorMemberId"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="showTasks">

                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="saveMemToTask">
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
        $(document).on('change', '#projectId', function () {
            var projectId = $(this).val();
            $('#showTasks').html('');
            if (projectId != '') {
                $.ajax({
                    url: "{{route('memberToTask.getTasks')}}",
                    type: "post",
                    data: {project_id: projectId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        $(".loader").show();
                    },
                    success: function (data) {
                        $('.js-source-states').select2();
                        $('#showMember').html(data.viewMember);
                        $(".loader").hide();
                    }
                });
            } else {
                $('#showMember').html('{!! Form::select('member_id',$memberList,'',['class' => 'form - control js - source - states', 'id' => 'memberId']) !!}');
            }
        });
        $(document).on('change', '#memberId', function () {
            var memberID = $(this).val();
            var projectId = $('#projectId').val();

            if (memberID != '') {
                $.ajax({
                    url: "{{route('memberToTask.getSingleUserTasks')}}",
                    type: "post",
                    data: {member_id: memberID, project_id: projectId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                     beforeSend: function () {
                        $(".loader").show();
                    },
                    success: function (data) {
                        $('#showTasks').html(data.viewTasks);
                        $(".loader").hide();
                    }
                });
            } else {
                $('#showTasks').html('');
            }
        });
        $(document).on('click', '#saveMemToTask', function () {
            var formData = new FormData($('#memberToTaskFormData')[0]);
            if (formData != '') {
                $.ajax({
                    url: "{{route('memberToTask.store')}}",
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
                        $("#errorMemberId").text('');
                        $("#errorTasks").text('');
                        if (data.errors) {
                            $("#errorProjectId").text(data.errors.project_id);
                            $("#errorMemberId").text(data.errors.member_id);
                            $("#errorTasks").text(data.errors.task_id);
                        }
                        if (data == 'success') {
                            toastr["success"]("@lang('label.MEMBER_ASSIGN_INTO_TASK_SUCCESSFULLY')");
                        }
                    }
                });
            }
        });
    });
</script>
@stop

