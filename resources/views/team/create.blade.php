@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.CREATE_TEAM')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'projectTeam/store','class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="project_id">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projects,null, ['class' => 'form-control js-source-states', 'id' => 'project_id']) !!} 
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="member_id">@lang('label.TEAM_MEMBER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('member_id',$members,null,['class' => 'form-control js-source-states', 'id' => 'member_id']) !!} 
                                <span class="text-danger">{{ $errors->first('member_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="role_id">@lang('label.ROLE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('role_id',$roles,null, ['class' => 'form-control js-source-states', 'id' => 'role_id']) !!} 
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="engagement_date">@lang('label.ENGAGEMENT_DATE') :</label>
                            <div class="col-md-8">
                                {!! Form::text('engagement_date',date('Y-m-d'), ['class' => 'form-control', 'id' => 'engagement_date']) !!}
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
