<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i><?php echo app('translator')->get('label.TASK_LIST'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[77][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('task/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_TASK'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'task/filter','class' => 'form-horizontal')); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="search"><?php echo app('translator')->get('label.SEARCH'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::text('search',Request::get('search'), ['class' => 'form-control tooltips', 'title' => 'Project name', 'placeholder' => 'Project name', 'list'=>'search', 'autocomplete'=>'off']); ?> 
                                <datalist id="search">
                                    <?php if(!empty($nameArr)): ?>
                                    <?php $__currentLoopData = $nameArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($title->title); ?>"></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::select('status',  $status, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']); ?>

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
                </div>
                <?php echo Form::close(); ?>

                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th><?php echo app('translator')->get('label.ORDER'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class="td-actions text-center"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($targetArr->isNotEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(++$sl); ?></td>
                            <td><?php echo e($target->title); ?></td>
                            <td><?php echo e($target->order); ?></td>
                            <td class="text-center">
                                <?php if($target->status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td width="10%">
                                <div class="text-center">
                                    <?php if(!empty($userAccessArr[77][3])): ?>
                                    <div class="pull-left">
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="<?php echo e(URL::to('task/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a> 
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($userAccessArr[77][4])): ?>
                                    <div>
                                        <?php echo e(Form::open(array('url' => 'task/' . $target->id.'/'.Helper::queryPageStr($qpArr)))); ?>

                                        <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                        <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
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
                            <td colspan="8"><?php echo app('translator')->get('label.NO_PROJECT_FOUND'); ?></td>
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
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/task/index.blade.php ENDPATH**/ ?>