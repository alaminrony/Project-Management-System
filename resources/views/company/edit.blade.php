@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i>@lang('label.EDIT_COMPANY')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('company.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal','files'=>true] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                         <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="shortName">@lang('label.SHORT_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('short_name', null, ['id'=> 'shortName', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('short_name') }}</span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-md-4" for="address">@lang('label.COMPANY_ADDRESS') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::textarea('address', null, ['id'=> 'address', 'class' => 'form-control','rows'=>2,'cols'=>30]) !!} 
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="contactNumber">@lang('label.CONTACT_NUMBER') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::text('contact_no', null, ['id'=> 'contactNumber', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('contact_no') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="email">@lang('label.EMAIL') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::email('email', null, ['id'=> 'email', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-4" for="industry">@lang('label.INDUSTRY') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::select('industry',$industries, null, ['class' => 'form-control js-source-states', 'id' => 'industry']) !!} 
                                <span class="text-danger">{{ $errors->first('industry') }}</span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-4" for="industry"><span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <img src="{{asset('public/image/'.$target->logo)}}" height="100px" weight="100px;"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="logo">@lang('label.LOGO') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!! Form::file('logo', null, ['id'=> 'logo', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('logo') }}</span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-md-4" for="logo">@lang('label.COMPANY_TYPE') :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                {!!Form::radio('type','1',false,['id'=>'mCompany'])!!}&nbsp;{!!Form::label('mCompany','Mother Company')!!}
                                {!!Form::radio('type','2',false,['id'=>'sConcern'])!!}&nbsp;{!!Form::label('sConcern','Sister Concern')!!} 
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            </div>
                        </div>
                        <div id="companyShow">
                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('order', $orderList,null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                <span class="text-danger">{{ $errors->first('order') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
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
        $(document).on('click','input[name="type"]',function(){
           var companyType = $(this).val();
           if(companyType == 2){
               $.ajax({
                   url:"{{route('company.getCompanyType')}}",
                   type:"post",
                   dataType:"html",
                   headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                   success:function(data){
                       if(data != ''){
                          $('#companyShow').html(data);
                          $(".js-source-states").select2();
                       }
                   }
               });
           }else{
               $('#companyShow').html(''); 
           }
        });
    });
</script>
@stop