<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" tabindex="-1" class="btn red pull-right">@lang('label.CLOSE')</button>
        <h4 class="modal-title">
            {{ empty($contact_id)? __('label.ADD_RECRUIT_SKILL') : __('label.UPDATE_RECRUIT_SKILL')}}
        </h4>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '', 'class' => 'form-horizontal form-row-seperated', 'id'=>'recruitSkillInformation')) }}
    {{csrf_field()}}
    <div class="modal-body">
        <div class="form-group">
            <label class="col-md-4 control-label" for="recSkillId">@lang('label.RECRUIT_SKILL'): <span class='text-danger'>*</span></label>
            <div class="col-md-6">
                {!! Form::select('rec_skill_id',$specialties,'', ['class' => 'form-control js-source-states', 'id' => 'recSkillId','tabindex'=>'1','data-width'=>'100%']) !!}
            </div>
        </div>
        <div id="showSkillInfo" >
            <div class="form-group">
                <label class="col-md-4 control-label" for="awardInst">@lang('label.AWARD_INST'):</label>
                <div class="col-md-6">
                    {!! Form::text('award_inst','fddfd',['class'=>'form-control','id'=>'awardInst','tabindex'=>'2']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="achievement">@lang('label.ACHIEVEMENT'):</label>
                <div class="col-md-6">
                    {!! Form::text('achievement','fddf',['class'=>'form-control','id'=>'achievement','tabindex'=>'3']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label" for="remarks">@lang('label.REMARKS'):</label>
                <div class="col-md-6">
                    {!! Form::textarea('remarks','fdfd',['class'=>'form-control','id'=>'remarks', 'rows' => 5, 'cols' =>40,'tabindex'=>'4']) !!}
                </div>
            </div>
        </div>

    </div>
    <div class=" text-center modal-footer">
        <button type="button" class="btn btn-primary" id="storeMeetUp">{{__('label.SAVE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<link href="{{asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
{{ Form::close() }}
<script type="text/javascript">

</script>