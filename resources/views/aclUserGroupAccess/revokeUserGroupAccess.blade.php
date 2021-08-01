<div class="modal-header">
    <div class="col-md-6">
        <h3 class="modal-title"><strong>@lang('label.REVOKE_ALL_ACCESS') Of {{ $userGroupName }}</strong></h3>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn dark btn-outline pull-right tooltips" data-placement="left" data-dismiss="modal" title="{{__('label.CLICK_HERE_TO_CLOSE')}}">@lang('label.CLOSE')</button>
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 class="bold">@lang('label.USER_ACCESS_LIST') Of  {{ $userGroupName }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 class="label label-md label-danger">@lang('label.HAVE_A_LOOK_BEFORE_DECIDE_TO_REVOKE_ACCESS_LIST')</h3>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="table-responsive col-md-12" style="max-height: 600px;">
                        <table class="table table-bordered table-hover module-access-view" id="revokeDataTable">
                            <thead>
                                <tr class="info">
                                    <th class="text-center vcenter" rowspan="2">@lang('label.MODULE_ID')</th>
                                    <th class="text-center vcenter" rowspan="2">@lang('label.MODULES')</th>
                                    <th class="text-center vcenter" colspan="{{ count($accessList)+2 }}">@lang('label.ACCESS_LIST')</th>
                                </tr>
                                <tr class="info">
                                    @if(!empty($accessList))
                                    @foreach($accessList as $accessId => $accessName)

                                    <th class="text-center vcenter">
                                        {!!  $accessName !!} ({!! $accessId !!})
                                    </th>
                                    @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($moduleArr))
                                @foreach($moduleArr as $moduleId => $moduleName)
                                <tr>
                                    <td class="text-center vcenter">
                                        {!! $moduleId !!}
                                    </td>
                                    <td class="text-center vcenter">
                                        <label for="{{ 'module_'.$moduleId }}">  {!! $moduleName !!} </label>
                                    </td>
                                    @if(!empty($accessList))
                                    @foreach($accessList as $accessId => $accessName)
                                    <?php
                                    $glyphicon = $textColor = '';
                                    if (isset($moduleToGroupAccessListArr[$moduleId][$accessId])) {
                                        $glyphicon = 'glyphicon glyphicon-ok';
                                        $textColor = 'text-primary';
                                    } else {
                                        $glyphicon = '';
                                        $textColor = '';
                                    }
                                    ?>
                                    <td class="text-center vcenter">
                                        <span class="{!! $glyphicon.' '. $textColor !!}"> </span>
                                    </td>
                                    @endforeach <!-- EOF --Foreach -->
                                    @endif
                                </tr>
                                @endforeach <!-- EOF --Foreach -->
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn red tooltips" type="button" id="btnRevokeFinalize" data-user-group-name="{{ $userGroupName }}" title="{{__('label.CLICK_TO_REVOKE_ALL_ACCESS')}}">
        <i class="fa fa-times-circle"></i> @lang('label.REVOKE_ALL_ACCESS')
    </button>
    <button type="button" class="btn dark btn-outline tooltips" data-dismiss="modal" title="{{__('label.CLICK_HERE_TO_CLOSE')}}">@lang('label.CLOSE')</button>
</div>

<script type="text/javascript">
    $(function () {
        $("#revokeDataTable").tableHeadFixer();
        $(".tooltips").tooltip();
    });
</script>