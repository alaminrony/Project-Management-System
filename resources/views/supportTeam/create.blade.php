@extends('layouts.default.master')
@section('data_count')
<style>
    input[name=team_manager_id] {
        width: 20px;
        height: 20px;
    }
</style>
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.SUPPORT_TEAM')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal', 'id'=>'supportTeamForm')) !!}
            {!! Form::hidden('page') !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="project_id">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projectList,null, ['class' => 'form-control js-source-states', 'id' => 'project_id']) !!} 
                                <span class="text-danger" id="errorProjectId"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="showSupportMember">
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="saveSupportTeam">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/supportTeam') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
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
                    url: "{{route('supportTeam.getSupportedPerson')}}",
                    type: "post",
                    data: {project_id: projectId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (date) {
                        $('#showSupportMember').html(date.viewData);
                    }
                });
            } else {
                $('#showSupportMember').html('');

            }
        });

        $(document).on('click', '#saveSupportTeam', function () {
            var formData = new FormData($('#supportTeamForm')[0]);
            if (formData != '') {
                $.ajax({
                    url: "{{route('supportTeam.store')}}",
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
                        $("#support_persons_id").text('');
                        $("#team_manager_id").text('');
                        if (data.errors) {
                            $("#errorProjectId").text(data.errors.project_id);
                            $("#support_persons_id").text(data.errors.support_persons_id);
                            $("#team_manager_id").text(data.errors.team_manager_id);
                        }
                        if (data == 'success') {
                            toastr["success"]("@lang('label.SUPPORT_TEAM_CREATED_SUCCESSFULLY')");
                        }
                    }
                });
            }
        });
    });

</script>
@stop
