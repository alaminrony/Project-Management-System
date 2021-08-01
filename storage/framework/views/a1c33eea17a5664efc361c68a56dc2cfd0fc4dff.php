<html>
    <head>
        <title></title>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/pdf.css')); ?>" rel="stylesheet" type="text/css" media="all"/>
    </head>
    <body>

        <div class="header">
            <h3 class="text-center">Bug List</h3>
        </div>

        <div class="row">
            <div class="text-center">
                <div class="col-md-3 print-4-header">
                    <label><?php echo app('translator')->get('label.FORM_DATE'); ?> <?php echo e(!empty($request->fromDate) ? $request->fromDate : 'N/A'); ?></label>
                </div>
                <div class="col-md-3 print-4-header">
                    <label><?php echo app('translator')->get('label.TO_DATE'); ?> <?php echo e(!empty($request->toDate) ? $request->toDate : 'N/A'); ?></label>
                </div>
                <div class="col-md-3 print-4-header">
                    <label><?php echo app('translator')->get('label.PROJECT_NAME'); ?> <?php echo e(!empty($projects[$request->projectId]) && $request->projectId > 0 ? $projects[$request->projectId] : 'All'); ?></label>
                </div>
                <div class="col-md-3 print-4-header">
                    <label><?php echo app('translator')->get('label.CREATED_BY'); ?> <?php echo e(!empty($createdBy[$request->createdBy]) && $request->createdBy > 0 ? $createdBy[$request->createdBy] : 'All'); ?></label>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead style="background-color: #29CD98;">
                <tr class="center">
                    <th><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th><?php echo app('translator')->get('label.TITLE'); ?></th>
                    <th><?php echo app('translator')->get('label.PROJECT_NAME'); ?></th>
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

        <div class="row">
            <div class="col-md-4">
                <p>Created By: <?php echo e(Auth::user()->first_name); ?> <?php echo e(Auth::user()->last_name); ?></p>
            </div>
            
            <div class="col-md-8 print-footer">
                <p>Project Management System. Develop By &copy; <a href="http://alaminrony.tk/" target='_blank'>alaminrony</a></p>
            </div>
        </div>

    </body>
    <script src="<?php echo e(asset('public/js/jquery.min.js')); ?>"></script>
    <script>
$(document).ready(function () {
    window.print();
});
    </script>

</html>





<?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/bugReport/printBugReport.blade.php ENDPATH**/ ?>