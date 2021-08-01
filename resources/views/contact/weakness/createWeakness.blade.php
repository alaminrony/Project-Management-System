<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" tabindex="-1" class="btn red pull-right">@lang('label.CLOSE')</button>
        <h4 class="modal-title">
            <i class="fa fa-pencil-square-o"></i> {{__('label.CREATE_WEAKNESS')}}
        </h4>
    </div>
    {{ Form::open(array('role' => 'form', 'url' => '', 'class' => 'form-horizontal form-row-seperated', 'id'=>'createWeaknessFormData')) }}
    {{csrf_field()}}
    <div class="modal-body">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center vcenter">
            <div class="md-checkbox has-success">
                 {!!Form::hidden('contact_id',$contact_id)!!}
                {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check all-check']) !!}
                <label for="checkAll">
                    <span class="inc"></span>
                    <span class="check mark-caheck"></span>
                    <span class="box mark-caheck"></span>
                </label>
            </div>
            </th>
            <th class="vcenter">@lang('label.WEAKNESS_NAME')</th>
            </tr>
            </thead>
            <tbody>
                @if($allWeakness->isNotEmpty())
                @foreach($allWeakness as $Weakness)
                <tr>
                    <td class="text-center vcenter">
                        <div class="md-checkbox has-success">
                            {!! Form::checkbox('weakness[]',$Weakness->id,false,['id' =>$Weakness->id,'class'=> 'md-check bf-check']) !!}
                            <label for="{{$Weakness->id}}">
                                <span class="inc"></span>
                                <span class="check mark-caheck"></span>
                                <span class="box mark-caheck"></span>
                            </label>
                        </div>
                    </td>
                    <td class="vcenter">{{$Weakness->name}}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="vcenter" colspan="4">@lang('label.WEAKNESS_NOT_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class=" text-center modal-footer">
        <button type="button" class="btn btn-primary" id="storeWeakness">{{__('label.SAVE')}}</button>
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="@lang('label.CLOSE_THIS_POPUP')">@lang('label.CLOSE')</button>
    </div>
</div>
{{ Form::close() }}

<link href="{{asset('public/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#checkAll').on("click", function () { //'check all' change
        if ($(this).prop('checked')) {
            $('.bf-check').prop("checked", true);
        }
        else {
            $('.bf-check').prop("checked", false);
        }
    });
});
</script>   
