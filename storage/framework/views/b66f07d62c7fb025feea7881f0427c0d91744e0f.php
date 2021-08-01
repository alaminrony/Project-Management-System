<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bug"></i><?php echo app('translator')->get('label.BUG_REPORT'); ?>
            </div>
            <div class="pull-right" style="margin-top: 3px;">
                <a href="<?php echo e(URL::to('bugReport?generate=true&fromDate='.Request::get('fromDate').'&toDate='.Request::get('toDate').'&projectId='.Request::get('projectId').'&createdBy='.Request::get('createdBy').'&view=pdf')); ?>"  title="Download PDF File" class="btn btn-warning tooltips rounded-0 pdf" ><i class="fa fa-file-pdf-o"></i></a>
                <a href="<?php echo e(URL::to('bugReport?generate=true&fromDate='.Request::get('fromDate').'&toDate='.Request::get('toDate').'&projectId='.Request::get('projectId').'&createdBy='.Request::get('createdBy').'&view=print')); ?>"  title="view Print" target="_blank" class="btn btn-info tooltips rounded-0 print"><i class="fa fa-print"></i></a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'bugReport/filter','class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <?php echo Form::hidden('generate','true'); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup"><?php echo app('translator')->get('label.FROM_DATE'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::text('from_date',Request::get('fromDate'),['class' => 'form-control datepicker', 'id'=>'fromDate','placeholder'=>'yyyy-mm-dd','readonly']); ?>

                            <span class="text-danger"><?php echo e($errors->first('from_date')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="department"><?php echo app('translator')->get('label.TO_DATE'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::text('to_date',Request::get('toDate'),['class' => 'form-control datepicker','id'=>'toDate','placeholder'=>'yyyy-mm-dd','readonly']); ?>

                            <span class="text-danger"><?php echo e($errors->first('to_date')); ?></span>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="designation"><?php echo app('translator')->get('label.PROJECT'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('project_id',$projects, Request::get('projectId'), ['class' => 'form-control js-source-states','id'=>'projectId']); ?>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="createdBy"><?php echo app('translator')->get('label.CREATED_BY'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('created_by',$createdBy,Request::get('createdBy'),['class' => 'form-control js-source-states','id'=>'createdBy']); ?>

                            <span class="text-danger"><?php echo e($errors->first('all-empty')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form">
                        <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!-- End Filter -->


            <?php if(!empty($generate) && $generate == 'true'): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="center">
                            <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th><?php echo app('translator')->get('label.TITLE'); ?></th>
                            <th><?php echo app('translator')->get('label.PROJECT'); ?></th>
                            <th><?php echo app('translator')->get('label.REPORTING_DATE'); ?></th>
                            <th><?php echo app('translator')->get('label.REPORTING_MEDIUM'); ?></th>
                            <th><?php echo app('translator')->get('label.REPORTED_BY'); ?></th>
                            <th><?php echo app('translator')->get('label.CREATED_BY'); ?></th>
                            <th><?php echo app('translator')->get('label.SEVERITY_LEVEL'); ?></th>
                            <th><?php echo app('translator')->get('label.UNIQUE_CODE'); ?></th>
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
                            <td><?php echo e($target->bug_title); ?></td>
                            <td><?php echo e($target->project_name); ?></td>
                            <td><?php echo e(Helper::printDate($target->reporting_date)); ?></td>
                            <td><?php echo e($target->medium_name); ?></td>
                            <td><?php echo e($target->reported_by); ?></td>
                            <td><?php echo e($target->created_by); ?></td>
                            <td><?php echo e($target->category_level); ?></td>
                            <td><?php echo e($target->unique_code); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="10"><?php echo app('translator')->get('label.NO_PROJECT_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>	
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/bugReport/index.blade.php ENDPATH**/ ?>