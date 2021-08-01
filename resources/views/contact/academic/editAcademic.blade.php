<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" tabindex="-1" class="btn red pull-right">@lang('label.CLOSE')</button>
         <h4 class="modal-title">
            <i class="fa fa-pencil-square-o"></i> {{__('label.CREATE_ACADEMIC_QUALIFICATION')}}
        </h4>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '', 'class' => 'form-horizontal form-row-seperated', 'id'=>'updateAcademicFormData')) }}
    {{csrf_field()}}
    <div class="modal-body">
        
        <div id="showSkillInfo" >
            <div class="form-group">
                {!!Form::hidden('id',$editData->id)!!}
                <label class="col-md-4 control-label" for="degree_name">@lang('label.DEGREE_NAME'):</label>
                <div class="col-md-6">
                    {!! Form::text('degree_name',$editData->degree_name,['class'=>'form-control','id'=>'degree_name','tabindex'=>'2']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="institute">@lang('label.INSTITUTE'):</label>
                <div class="col-md-6">
                    {!! Form::text('institute',$editData->institute,['class'=>'form-control','id'=>'institute','tabindex'=>'3']) !!}
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-4 control-label" for="batch">@lang('label.BATCH'):</label>
                <div class="col-md-6">
                    {!! Form::text('batch',$editData->batch,['class'=>'form-control','id'=>'batch','tabindex'=>'3']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="remarks">@lang('label.REMARKS'):</label>
                <div class="col-md-6">
                    {!! Form::textarea('remarks',$editData->remarks,['class'=>'form-control','id'=>'remarks', 'rows' => 5, 'cols' =>40,'tabindex'=>'4']) !!}
                </div>
            </div>
        </div>

    </div>
    <div class=" text-center modal-footer">
        <button type="button" class="btn btn-primary" id="updateAcademic">{{__('label.UPDATE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<link href="{{asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
{{ Form::close() }}
<script>
  $(document).ready(function(){
    $('#createDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
  });
</script>