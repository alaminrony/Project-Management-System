<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" tabindex="-1" class="btn red pull-right">@lang('label.CLOSE')</button>
        <h4 class="modal-title">
            <i class="fa fa-pencil-square-o"></i> {{__('label.REASSIGNED_MEMBER')}}
        </h4>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '', 'class' => 'form-horizontal form-row-seperated', 'id'=>'reassignedFormData')) }}
    {{csrf_field()}}
    <div class="modal-body">
        {!!Form::hidden('bug_id',$bugID,['id'=>'bugId'])!!}
        <div class="form-group">
            <label class="col-md-4 control-label" for="memberID">@lang('label.MEMBER'):</label>
            <div class="col-md-6">
                {!! Form::select('reassigned_id',$personArr,'',['class'=>'form-control js-source-states','id'=>'memberID']) !!}
                <div class="text-danger" id="errorReassigned_id"></div>
            </div>
        </div>
        <div id="showMemberInfo">
          
        </div>
    </div>
    <div class=" text-center modal-footer">
        <button type="button" class="btn btn-primary" id="storeReassigned">{{__('label.SAVE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<link href="{{asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
{{ Form::close() }}
