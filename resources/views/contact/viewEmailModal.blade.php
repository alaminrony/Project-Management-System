<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-envelope"></i> {!!__('label.EMAIL')!!}</h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <td><b>{!!__('label.SL')!!}</b></td>
                <td><b>@lang('label.EMAIL')</b></td>
            </tr>
            @php $i=1; @endphp
            @foreach($emailArray as $email)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$email}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>


