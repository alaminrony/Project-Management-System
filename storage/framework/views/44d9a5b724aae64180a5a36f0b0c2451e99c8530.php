<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i><?php echo app('translator')->get('label.USER_LIST'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[1][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('user/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_USER'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'user/filter','class' => 'form-horizontal')); ?>

                <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup"><?php echo app('translator')->get('label.USER_GROUP'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('user_group',  $groupList, Request::get('user_group'), ['class' => 'form-control js-source-states', 'list'=>'userGroup','autocomplete'=>'off']); ?>


                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="search"><?php echo app('translator')->get('label.NAME'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::text('search',  Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Username', 'placeholder' => 'Username','list'=>'userName','autocomplete'=>'off']); ?>

                            <datalist id="userName">
                                <?php if(!empty($nameArr)): ?>
                                <?php $__currentLoopData = $nameArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($userName->username); ?>"></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="department"><?php echo app('translator')->get('label.DEPARTMENT'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('department',  $userDepartmentOption, Request::get('department'), ['class' => 'form-control js-source-states','id'=>'department']); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="designation"><?php echo app('translator')->get('label.DESIGNATION'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('designation',  $designationList, Request::get('designation'), ['class' => 'form-control js-source-states','id'=>'designation']); ?>

                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('status',$status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']); ?>

                        </div>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                        </button>
                    </div>
                </div>
                <?php echo Form::close(); ?>

                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.EMPLOYEE_ID'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.USER_GROUP'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.DEPARTMENT'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.USERNAME'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class="td-actions text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center vcenter"><?php echo e(++$sl); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->employee_id); ?></td>
                            <td class="text-center vcenter">
                                <?php if (!empty($target->photo)) { ?>
                                    <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($target->photo); ?>" alt="<?php echo e($target->full_name); ?>"/>
                                <?php } else { ?>
                                    <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($target->full_name); ?>"/>
                                <?php } ?>
                            </td>
                            <td class="vcenter"><?php echo e($target->group_name); ?></td>
                            <td class="vcenter"><?php echo e($target->department_name); ?></td>
                            <td class="vcenter"><?php echo e($target->designation_name); ?></td>
                            <td class="vcenter"><?php echo e($target->first_name.' '.$target->last_name); ?></td>
                            <td class="vcenter"><?php echo e($target->username); ?></td>
                            <td class="text-center vcenter">
                                <?php if($target->status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center vcenter">
                                <div>
                                    <?php if(!empty($userAccessArr[1][3])): ?>
                                    <div class="pull-left">
                                        <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="<?php echo e(URL::to('user/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[1][4])): ?>
                                    <div class="pull-right">
                                        <?php echo e(Form::open(array('url' => 'user/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'class' => 'delete-form-inline'))); ?>

                                        <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                        <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <?php echo e(Form::close()); ?>

                                    </div>

                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12" class="vcenter"><?php echo app('translator')->get('label.NO_USER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/user/index.blade.php ENDPATH**/ ?>