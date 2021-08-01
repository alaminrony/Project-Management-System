<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-pencil-square-o"></i> {!!__('label.CREATE_REMARKS')!!}</h4>
    </div>
    <div class="modal-body">
        {!! Form::open(['url' => '','method'=>'post','id'=>'createRemarksFormDate']) !!}
        
        {!!Form::hidden('contact_id',$contact_id,['class'=>'form-control','id'=>'contact_id']) !!}
       <div class="form-group">
            {!!Form::label('createDate','Date')!!} <span class="text-danger"></span>
            {!!Form::text('date',date('Y-m-d'),['class'=>'form-control','id'=>'createDate','readonly']) !!}
            <div id="errorDate" class="text-danger"></div>
        </div>
       
        <div class="form-group">
            {!!Form::label('remarks','Remarks')!!} <span class="text-danger"></span>
            {!! Form::textarea('remarks','', ['class'=>'form-control','id'=>'Remarks','rows' => 2, 'cols' => 54]) !!}
            <div id="errorPurpose" class="text-danger"></div>
        </div>
        
    </div>
    <div class="modal-footer">
         <button type="button" class="btn btn-primary" id="storeRemarks">{{__('label.SAVE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    {!! Form::close() !!}
</div>

<script>
  $(document).ready(function(){
    $('#createDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
  });
</script>