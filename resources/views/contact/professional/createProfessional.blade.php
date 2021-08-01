<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" tabindex="-1" class="btn red pull-right">@lang('label.CLOSE')</button>
        <h4 class="modal-title">
            <i class="fa fa-pencil-square-o"></i> {{__('label.CREATE_PROFESSIONAL_SKILL')}}
        </h4>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '', 'class' => 'form-horizontal form-row-seperated', 'id'=>'createProfessionalFormData')) }}
    {{csrf_field()}}
    <div class="modal-body">

        <div id="showSkillInfo" >
            {!!Form::hidden('contact_id',$contact_id)!!}
            <div class="form-group">
                <label class="col-md-4 control-label" for="organization_name">@lang('label.ORGANIZATION_NAME'):</label>
                <div class="col-md-6">
                    {!! Form::text('organization_name','',['class'=>'form-control','id'=>'organization_name','tabindex'=>'3']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="experience_year">@lang('label.EXPERIENCE_YEAR'):</label>
                <div class="col-md-6">
                    {!! Form::text('experience_year','',['class'=>'form-control','id'=>'experience_year','tabindex'=>'2']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="expertise_area">@lang('label.EXPERTISE'):</label>
                <div class="col-md-6">
                    {!! Form::text('expertise_area','',['class'=>'form-control','id'=>'expertise_area','tabindex'=>'2']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="">@lang('label.CURRENT_WORKING'):</label>
                <div class="col-md-6 radio-btn-margin">
                    {!! Form::radio('current_working',0,true,['id'=>'YES']) !!}&nbsp;{!!Form::label('YES','Yes')!!}
                    {!! Form::radio('current_working',1,false,['id'=>'NO']) !!}&nbsp;{!!Form::label('NO','No')!!}
                </div>
            </div>
            
        </div>

    </div>
    <div class=" text-center modal-footer">
        <button type="button" class="btn btn-primary" id="storeProfessional">{{__('label.SAVE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<link href="{{asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
{{ Form::close() }}
<script>
$(document).ready(function () {
    $('#createDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
});
</script>