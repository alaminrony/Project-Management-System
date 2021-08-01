<div class="form-body">
    <div class="row">
        <div class="table-responsive col-md-12" style="max-height: 600px;">
            <table class="table table-bordered table-hover module-access-view" id="dataTable">
                <thead>
                    <tr  class="info">
                        <th  class="text-center vcenter" rowspan="2">
                            <div class="md-checkbox text-center vcenter">
                                {!! Form::checkbox('all_module',1,false, ['id' => 'allModule', 'class'=> 'md-check all-module-check']) !!}
                                <label for="allModule">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>   
                        </th>
                        <th  class="text-center vcenter" rowspan="2">@lang('label.MODULE_ID')</th>
                        <th  class="text-center vcenter" rowspan="2">@lang('label.MODULES')</th>
                        <th class="text-center vcenter" colspan="{{ count($accessList)+2 }}">@lang('label.ACCESS_LIST')</th>
                    </tr>
                    <tr  class="info">
                        @if(!empty($accessList))
                        @foreach($accessList as $accessId => $accessName)

                        <th class="text-center">
                            <div class="md-checkbox">
                                {!! Form::checkbox('access['.$accessId.']',$accessId,false, ['id' => 'access_'.$accessId, 'class'=> 'md-check m-access','disabled']) !!}
                                <label for="{{ 'access_'.$accessId }}">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                            <div class="text-center">
                                {!!  $accessName !!} ({!! $accessId !!})
                            </div>

                        </th>
                        @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody class="access-check">
                    @if(!empty($moduleArr))
                    @foreach($moduleArr as $moduleId => $moduleName)
                    <?php
                    $checked = '';
                    $disabled = 'disabled';
                    if (array_key_exists($moduleId, $moduleToGroupAccessListFinalArr)) {
                        if (in_array('2', $moduleToGroupAccessListFinalArr[$moduleId])) {
                            $checked = 'checked';
                            $disabled = '';
                        }
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">
                            <div class="md-checkbox text-center vcenter module-check">
                                {!! Form::checkbox('module['.$moduleId.']',$moduleId,$checked, ['id' => 'module_'.$moduleId, 'class'=> 'md-check module']) !!}
                                <label for="{{ 'module_'.$moduleId }}">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </td>
                        <td class="text-center vcenter">
                            {!! $moduleId !!}
                        </td>
                        <td class="text-center vcenter">
                            <label for="{{ 'module_'.$moduleId }}">  {!! $moduleName !!} </label>
                        </td>
                        @if(!empty($accessList))
                        @foreach($accessList as $accessId => $accessName)
                        <td class="text-center vcenter">
                            @if (isset($moduleToGroupAccessListFinalArr[$moduleId][$accessId]))
                            <?php
                            $checked = '';
                            if ($moduleToGroupAccessListFinalArr[$moduleId][$accessId] == 2) {
                                $checked = "checked";
                            }
                            ?>
                            <div class="md-checkbox text-center vcenter">
                                {!! Form::checkbox('module_access['.$moduleId.']['.$accessId.']', 1, $checked, ['id' => 'moduleAccess_'.$moduleId.'_'.$accessId,'class'=> 'md-check module-to-access',$disabled]) !!}
                                <label for="{{ 'moduleAccess_'.$moduleId.'_'.$accessId }}">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> 
                                </label>
                            </div>
                            @else
                            &nbsp;
                            @endif
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

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-8">
            <button class="btn btn-circle green" type="button" id="btnSubmit">
                <i class="fa fa-check"></i> @lang('label.SUBMIT')
            </button>
            @if(!$userGroupToAccessArr->isEmpty() && $groupId != 1 && !empty($userAccessArr[69][4]))
            <button type="button" class="btn btn-circle red" id="btnRevoke" data-target="#userGroupWiseAccessList" data-toggle="modal" >
                <i class="fa fa-times-circle"></i> @lang('label.REVOKE_ALL_ACCESS')
            </button>
            @endif
            <a href="{{ URL::to('/aclUserGroupToAccess/userGroupToAccess') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
        </div>
    </div>
</div>

<script  type="text/javascript">
    $(function () {
        //if in one column all module wise individual access is checked then on top check box will be checcked
        $('.m-access').each(function () {
            var accessId = $(this).val();
            $('.module').each(function () {
                var moduleId = $(this).val();
                if ($('#moduleAccess_' + moduleId + '_' + accessId + ':checked').length == $('#access_' + accessId).length) {
                    $('#access_' + accessId).attr("checked", "checked");
                } else {
                    $('#access_' + accessId).attr("checked", false);
                }
            });
        });

        if ($('.module:checked').length == $('.module').length) {
            $('.m-access').prop("disabled", false);
        } else {
            $('.m-access').prop("disabled", true);
        }

        //Click on acceess for all module wise individual acceess
        $(".m-access").click(function () {
            var accessId = $(this).val();
            if ($(this).prop('checked')) {
                $('.module').each(function () {
                    var moduleId = $(this).val();
                    $('#moduleAccess_' + moduleId + '_' + accessId).prop("checked", true);
                });
            } else {
                $('.module').each(function () {
                    var moduleId = $(this).val();
                    $('#moduleAccess_' + moduleId + '_' + accessId).prop("checked", false);
                });
            }

        });

        //Click on module for all module wise individual acceess
        $(".module").click(function () {
            var moduleId = $(this).val();
            if ($(this).prop('checked')) {
                $('.m-access').each(function () {
                    var accessId = $(this).val();
                    $('#moduleAccess_' + moduleId + '_' + accessId).prop("disabled", false);
                });
            } else {
                $('.m-access').each(function () {
                    var accessId = $(this).val();
                    $('#moduleAccess_' + moduleId + '_' + accessId).prop("disabled", true);
                    $('#moduleAccess_' + moduleId + '_' + accessId).prop("checked", false);
                    $('#access_' + accessId).prop("checked", false);

                });
            }
            if ($('.module:checked').length == $('.module').length) {
                $('.m-access').prop("disabled", false);
            } else {
                $('.m-access').prop("disabled", true);
            }
        });


        $(".all-module-check").click(function () {
            if ($(this).prop('checked')) {
                $('.module').prop("checked", true);
                $('.m-access').prop("disabled", false);
                $('.module-to-access').prop("disabled", false);
            } else {
                $('.module').prop("checked", false);
                if ($(this).prop('checked')) {
                    $('.module-to-access').prop("disabled", true);
                }
                $('.m-access').prop("disabled", true);
                $('.module-to-access').prop("disabled", true);
            }

        });

        //if all module are checcked then check all will be shown checked
        if ($('.module:checked').length == $('.module').length) {
            $('.all-module-check').prop("checked", true);
        } else {
            $('.all-module-check').prop("checked", false);
        }


    });
    // $("#dataTable").tableHeadFixer({"left": 2});
    $("#dataTable").tableHeadFixer();
    $("#addFullMenuClass").addClass("page-sidebar-closed");
    $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");

</script>