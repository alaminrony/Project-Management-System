<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bug"></i><?php echo app('translator')->get('label.BUG_LEST'); ?>
            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[84][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('bug/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_BUG'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'bug/filter','class' => 'form-horizontal')); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="bugTitle"><?php echo app('translator')->get('label.TITLE'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::select('title_id',$bugTitleArr, Request::get('title'), ['class' => 'form-control js-source-states','id'=>'bugTitle']); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="projectId"><?php echo app('translator')->get('label.PROJECT'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::select('project_id',$bugProjectArr, Request::get('status'), ['class' => 'form-control js-source-states','id'=>'projectId']); ?>

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
                            <th><?php echo app('translator')->get('label.TITLE'); ?></th>
                            <th><?php echo app('translator')->get('label.PROJECT_NAME'); ?></th>
                            <th><?php echo app('translator')->get('label.REPORTING_DATE'); ?></th>
                            <th><?php echo app('translator')->get('label.REPORTING_MEDIUM'); ?></th>
                            <th><?php echo app('translator')->get('label.REPORTED_BY'); ?></th>
                            <th><?php echo app('translator')->get('label.SEVERITY_LEVEL'); ?></th>
                            <th><?php echo app('translator')->get('label.UNIQUE_CODE'); ?></th>
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
                            <td><?php echo e($target->bug_title); ?></td>
                            <td><?php echo e($target->project_name); ?></td>
                            <td><?php echo e(Helper::printDate($target->reporting_date)); ?></td>
                            <td><?php echo e($target->medium_name); ?></td>
                            <td><?php echo e($target->reported_by); ?></td>
                            <td><?php echo e($target->category_level); ?></td>
                            <td><?php echo e($target->unique_code); ?></td>
                            <td width="10%">
                                <div class="text-center">
                                    <div class="pull-left">
                                        <a type="button" class="btn btn-xs btn-success openBugModal tooltips" data-toggle="modal" title="View Bug" data-target="#viewBugModal" data-id="<?php echo e($target->id); ?>">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <?php if(!empty($userAccessArr[84][3])): ?>
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="<?php echo e(URL::to('bug/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(!empty($userAccessArr[84][4])): ?>
                                    <div>
                                        <?php echo e(Form::open(array('url' => 'bug/' . $target->id.'/'.Helper::queryPageStr($qpArr)))); ?>

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
                            <td colspan="10"><?php echo app('translator')->get('label.NO_PROJECT_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>

<!--view Email Modal -->
<div class="modal fade" id="viewBugModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="bugModalShow">
        </div>
    </div>
</div>
<!--end Email Modal -->


<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.openBugModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "<?php echo e(route('bug.viewDetails')); ?>",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#bugModalShow').html(data.viewBug);
                    }
                });

            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/bug/index.blade.php ENDPATH**/ ?>