@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.EDIT_POINT_OF_CONTACT')
            </div>
        </div>
        <div class="portlet-body form">
            {{ Form::open(['route' => array('pointOfContact.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal']) }}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                       <div class="form-group">
                            <label class="control-label col-md-4" for="projectId">@lang('label.PROJECT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_id',$projectList,!empty($target->project_id) ? true : false, ['class' => 'form-control js-source-states', 'id' => 'projectId']) !!} 
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="pocLevel1">@lang('label.POC_LEVEL_1') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('poc_level_1',$clientList,!empty($target->poc_level_1) ? $target->poc_level_1 : null, ['class' => 'form-control js-source-states', 'id' => 'pocLevel1']) !!} 
                                <span class="text-danger">{{ $errors->first('poc_level_1') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="pocLevel2">@lang('label.POC_LEVEL_2') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::select('poc_level_2',$clientList,!empty($target->poc_level_2) ? $target->poc_level_2 : null,['class' => 'form-control js-source-states', 'id' => 'pocLevel2']) !!} 
                                <span class="text-danger">{{ $errors->first('poc_level_2') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="pocLevel3">@lang('label.POC_LEVEL_3') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::select('poc_level_3',$clientList,!empty($target->poc_level_3) ? $target->poc_level_3 : null, ['class' => 'form-control js-source-states', 'id' => 'pocLevel3']) !!} 
                                <span class="text-danger">{{ $errors->first('poc_level_3') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')],$target->status, ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
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
                        <a href="{{ URL::to('/pointOfContact'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
@stop