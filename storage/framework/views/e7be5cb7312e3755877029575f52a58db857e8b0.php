<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> 
            <?php echo __('label.VIEW_TASK_WISE_PROJECT'); ?>

        </h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th><?php echo app('translator')->get('label.NAME'); ?></th>
                    <th><?php echo app('translator')->get('label.CLIENT'); ?></th>
                    <th><?php echo app('translator')->get('label.PROJECT_STATUS'); ?></th>
                    <th><?php echo app('translator')->get('label.TENTATIVE_DATE'); ?></th>
                    <th><?php echo app('translator')->get('label.DEAD_LINE'); ?></th>
                    <th><?php echo app('translator')->get('label.ORDER'); ?></th>
                    <th><?php echo app('translator')->get('label.IMAGE'); ?></th>
                    <th class="text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
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
                    <td><?php echo e($target->name); ?></td>
                    <td><?php echo e($target->company_name); ?></td>
                    <td><?php echo e($target->status_name); ?></td>
                    <td><?php echo e(Helper::printDate($target->tentative_date)); ?></td>
                    <td><?php echo e(Helper::printDate($target->dead_line)); ?></td>
                    <td><?php echo e($target->order); ?></td>
                    <td>
                        <img src="<?php echo e(asset('public/image/'.$target->upload_file)); ?>" alt="<?php echo e($target->name); ?> file" height="50px;" width="50px;"/>
                    </td>
                    <td class="text-center">
                        <?php if($target->status == '1'): ?>
                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                        <?php else: ?>
                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                        <?php endif; ?>
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
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
    </div>
</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>



<?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/admin/viewProjectModal.blade.php ENDPATH**/ ?>