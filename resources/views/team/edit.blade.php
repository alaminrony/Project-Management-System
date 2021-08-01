@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.EDIT_TASK')
            </div>
        </div>
        <div class="portlet-body form">
            {{ Form::open(['route' => array('projectTeam.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal']) }}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                       <div class="form-group">
                            <label class="control-label col-md-4" for="project_id">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projects,$target->project_id, ['class' => 'form-control js-source-states', 'id' => 'project_id']) !!} 
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="member_id">@lang('label.TEAM_MEMBER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('member_id',$members,$target->member_id,['class' => 'form-control js-source-states', 'id' => 'member_id']) !!} 
                                <span class="text-danger">{{ $errors->first('member_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="role_id">@lang('label.ROLE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('role_id',$roles,$target->role_id, ['class' => 'form-control js-source-states', 'id' => 'role_id']) !!} 
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="engagement_date">@lang('label.ENGAGEMENT_DATE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('engagement_date',$target->engagement_date, ['class' => 'form-control', 'id' => 'engagement_date']) !!}
                                <span class="text-danger">{{ $errors->first('engagement_date') }}</span>
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
                        <a href="{{ URL::to('/projectTeam'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script>
    $(document).ready(function(){
       $('#engagement_date').datepicker({
           format:'yyyy-mm-dd',
           todayHighlight:true,
           autoclose:true,
       });
    });
</script>
@stop