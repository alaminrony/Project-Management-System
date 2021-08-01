<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" tabindex="-1" class="btn red pull-right">@lang('label.CLOSE')</button>
        <h4 class="modal-title">
            <i class="fa fa-pencil-square-o"></i> {{__('label.CREATE_FOLLOW_UP')}}
        </h4>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '', 'class' => 'form-horizontal form-row-seperated', 'id'=>'FollowUpData')) }}
    {{csrf_field()}}
    <div class="modal-body">

        <div id="showSkillInfo" >
            <div class="form-group">
                {!!Form::hidden('bug_id',$bug_id)!!}
                <label class="col-md-4 control-label" for="statusId">@lang('label.STATUS'): <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    {!! Form::select('status',$status,null,['class'=>'form-control js-source-states','id'=>'statusId']) !!}
                    <div id="errorStatus" class="text-danger"></div>
                </div>
            </div>

            <div id="showProgress" style="display: none;">
                <div class="form-group">
                    <label class="col-md-4 control-label" for="progress">@lang('label.PROGRESS'):</label>
                    <div class="col-md-6">
                        {!! Form::number('progress','',['class'=>'form-control','id'=>'progress','tabindex'=>'3']) !!}
                        <div id="errorProgress" class="text-danger"></div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label" for="remarks">@lang('label.REMARKS'): <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    {!! Form::textarea('remarks','',['class'=>'form-control','id'=>'remarks', 'rows' => 5, 'cols' =>40,'tabindex'=>'4']) !!}
                    <div id="errorRemarks" class="text-danger"></div>
                </div>
            </div>
        </div>

    </div>
    <div class=" text-center modal-footer">
        <button type="button" class="btn btn-primary" id="storeFollowUp">{{__('label.SAVE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
{{ Form::close() }}
<link href="{{asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>

