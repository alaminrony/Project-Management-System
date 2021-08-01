<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-pencil-square-o"></i> {!!__('label.UPDATE_MEET_UP')!!}</h4>
    </div>
    <div class="modal-body">
        {!! Form::open(['url' => '','method'=>'post','id'=>'updateRemarksFormData']) !!}
        
        {!!Form::hidden('id',$editData->id,['class'=>'form-control']) !!}
        <div class="form-group">
            {!!Form::label('updateDate','Date')!!} <span class="text-danger">*</span>
            {!!Form::text('date',$editData->date,['class'=>'form-control','id'=>'updateDate','readonly']) !!}
            <div id="errorDate" class="text-danger"></div>
        </div>
       
        <div class="form-group">
            {!!Form::label('remarksUpdate','Remarks')!!} <span class="text-danger"></span>
            {!! Form::textarea('remarks',$editData->remarks, ['class'=>'form-control','id'=>'remarksUpdate','rows' => 2, 'cols' => 54]) !!}
            <div id="errorPurpose" class="text-danger"></div>
        </div>
        
    </div>
    <div class="modal-footer">
         <button type="button" class="btn btn-primary" id="updateRemarks">{{__('label.UPDATE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
    {!! Form::close() !!}
</div>

<script>
  $(document).ready(function(){
    $('#updateDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
  });
</script>