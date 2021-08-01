@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cubes"></i>@lang('label.MODULE_ACCESS_CONTROL')
            </div>
        </div>
        <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 table-responsive" style="max-height: 600px;">
                        <table class="table table-bordered table-hover module-access-view" id="dataTable">
                            <thead>
                                <tr  class="info">
                                    <th  class="text-center vcenter" rowspan="2">@lang('label.MODULE_ID')</th>
                                    <th  class="text-center vcenter" rowspan="2" width="20%">@lang('label.MODULES')</th>
                                    <th class="text-center vcenter" colspan="{{ count($accessList)+1 }}">@lang('label.ACCESS_LIST')</th>
                                </tr>
                                <tr  class="info">
                                    @if(!empty($accessList))
                                    @foreach($accessList as $accessId => $accessName)

                                    <th class="text-center vcenter">
                                        <div class="md-checkbox">
                                             {!!  $accessName !!} ({!! $accessId !!})	
                                        </div>
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
                                    if (isset($moduleAccessListArr[$moduleId][$accessId])) {
                                        $glyphicon = 'glyphicon glyphicon-ok';
                                        $textColor = 'text-primary';
                                    } else {
                                        $glyphicon = 'glyphicon glyphicon-remove';
                                        $textColor = 'text-danger';
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

<script type="text/javascript">
    $(function() {
        $("#dataTable").tableHeadFixer();
		$("#addFullMenuClass").addClass("page-sidebar-closed");
        $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");

	});
</script>	
@stop