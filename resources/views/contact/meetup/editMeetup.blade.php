<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-pencil-square-o"></i> {!!__('label.UPDATE_MEET_UP')!!}</h4>
    </div>
    <div class="modal-body">
        {!! Form::open(['url' => '','method'=>'post','id'=>'updateMeetUpForm']) !!}
        
        {!!Form::hidden('meetup_id',$meetUp->id,['class'=>'form-control']) !!}
        <div class="form-group">
            {!!Form::label('updateLocation','Location')!!} <span class="text-danger">*</span>
            {!!Form::text('location',$meetUp->location,['class'=>'form-control','id'=>'updateLocation']) !!}
            <div id="errorLocation" class="text-danger"></div>
        </div>
        <div class="form-group">
            {!!Form::label('updateDate','Date')!!} <span class="text-danger">*</span>
            {!!Form::text('date',$meetUp->date,['class'=>'form-control','id'=>'updateDate','readonly']) !!}
            <div id="errorDate" class="text-danger"></div>
        </div>
       
        <div class="form-group">
            {!!Form::label('updatePurpose','Purpose')!!} <span class="text-danger"></span>
            {!! Form::textarea('purpose',$meetUp->purpose, ['class'=>'form-control','id'=>'updatePurpose','rows' => 2, 'cols' => 54]) !!}
            <div id="errorPurpose" class="text-danger"></div>
        </div>
        
    </div>
    <div class="modal-footer">
         <button type="button" class="btn btn-primary" id="updateMeetUp">{{__('label.UPDATE')}}</button>
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