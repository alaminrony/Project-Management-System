<div class="form-body">
    <div class="row">
        <div class="table-responsive col-md-12" style="max-height: 600px;">
            <table class="table table-bordered table-hover module-access-view" id="dataTable">
                <thead>
                    <tr  class="info">
                        <th  class="text-center vcenter" rowspan="2">
                            <div class="md-checkbox text-center vcenter">
                                <?php echo Form::checkbox('all_module',1,false, ['id' => 'allModule', 'class'=> 'md-check all-module-check']); ?>

                                <label for="allModule">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>   
                        </th>
                        <th  class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.MODULE_ID'); ?></th>
                        <th  class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.MODULES'); ?></th>
                        <th class="text-center vcenter" colspan="<?php echo e(count($accessList)+2); ?>"><?php echo app('translator')->get('label.ACCESS_LIST'); ?></th>
                    </tr>
                    <tr  class="info">
                        <?php if(!empty($accessList)): ?>
                        <?php $__currentLoopData = $accessList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accessId => $accessName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <th class="text-center">
                            <div class="md-checkbox">
                                <?php echo Form::checkbox('access['.$accessId.']',$accessId,false, ['id' => 'access_'.$accessId, 'class'=> 'md-check m-access','disabled']); ?>

                                <label for="<?php echo e('access_'.$accessId); ?>">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                            <div class="text-center">
                                <?php echo $accessName; ?> (<?php echo $accessId; ?>)
                            </div>

                        </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="access-check">
                    <?php if(!empty($moduleArr)): ?>
                    <?php $__currentLoopData = $moduleArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduleId => $moduleName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <?php echo Form::checkbox('module['.$moduleId.']',$moduleId,$checked, ['id' => 'module_'.$moduleId, 'class'=> 'md-check module']); ?>

                                <label for="<?php echo e('module_'.$moduleId); ?>">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </td>
                        <td class="text-center vcenter">
                            <?php echo $moduleId; ?>

                        </td>
                        <td class="text-center vcenter">
                            <label for="<?php echo e('module_'.$moduleId); ?>">  <?php echo $moduleName; ?> </label>
                        </td>
                        <?php if(!empty($accessList)): ?>
                        <?php $__currentLoopData = $accessList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accessId => $accessName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-center vcenter">
                            <?php if(isset($moduleToGroupAccessListFinalArr[$moduleId][$accessId])): ?>
                            <?php
                            $checked = '';
                            if ($moduleToGroupAccessListFinalArr[$moduleId][$accessId] == 2) {
                                $checked = "checked";
                            }
                            ?>
                            <div class="md-checkbox text-center vcenter">
                                <?php echo Form::checkbox('module_access['.$moduleId.']['.$accessId.']', 1, $checked, ['id' => 'moduleAccess_'.$moduleId.'_'.$accessId,'class'=> 'md-check module-to-access',$disabled]); ?>

                                <label for="<?php echo e('moduleAccess_'.$moduleId.'_'.$accessId); ?>">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> 
                                </label>
                            </div>
                            <?php else: ?>
                            &nbsp;
                            <?php endif; ?>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <!-- EOF --Foreach -->
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <!-- EOF --Foreach -->
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-8">
            <button class="btn btn-circle green" type="button" id="btnSubmit">
                <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
            </button>
            <?php if(!$userGroupToAccessArr->isEmpty() && $groupId != 1 && !empty($userAccessArr[69][4])): ?>
            <button type="button" class="btn btn-circle red" id="btnRevoke" data-target="#userGroupWiseAccessList" data-toggle="modal" >
                <i class="fa fa-times-circle"></i> <?php echo app('translator')->get('label.REVOKE_ALL_ACCESS'); ?>
            </button>
            <?php endif; ?>
            <a href="<?php echo e(URL::to('/aclUserGroupToAccess/userGroupToAccess')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
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

</script><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/aclUserGroupAccess/showGroupAccessForm.blade.php ENDPATH**/ ?>