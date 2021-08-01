@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.EDIT_PROJECT')
            </div>
        </div>
        <div class="portlet-body form">
            {{ Form::open(['route' => array('project.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal','files'=>true]) }}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                           <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name',$target->name, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="client">@lang('label.CLIENT') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::select('client_id',$clientList,$target->client_id,['id'=> 'client', 'class' => 'form-control js-source-states']) !!} 
                                <span class="text-danger">{{ $errors->first('client_id') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="client">@lang('label.PROJECT_STATUS') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::select('project_status',$projectStatusList,$target->project_status,['id'=> 'project_status', 'class' => 'form-control js-source-states']) !!} 
                                <span class="text-danger">{{ $errors->first('project_status') }}</span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-md-4" for="tentative_date">@lang('label.TENTATIVE_DATE') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::text('tentative_date',$target->tentative_date, ['id'=> 'tentative_date', 'class' => 'form-control','rows'=>2,'cols'=>30]) !!} 
                                <span class="text-danger">{{ $errors->first('tentative_date')}}</span>
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label class="control-label col-md-4" for="dead_line">@lang('label.DEAD_LINE') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::text('dead_line',$target->dead_line, ['id'=> 'dead_line', 'class' => 'form-control','rows'=>2,'cols'=>30]) !!} 
                                <span class="text-danger">{{ $errors->first('dead_line')}}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('order',$orderList,$target->order,['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="upload_file">@lang('label.UPLOAD_FILE') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <img src="{{asset('public/image/'.$target->upload_file)}}" alt="{{$target->name}} file" height="100px;" width="100px;"/>
                                {!! Form::file('upload_file', null, ['id'=> 'upload_file', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('upload_file') }}</span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')],$target->status,['class' => 'form-control', 'id' => 'status']) !!}
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
                        <a href="{{ URL::to('/company'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#tentative_date,#dead_line').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight:true,
            autoclose:true,
        });
    });
</script>
@stop