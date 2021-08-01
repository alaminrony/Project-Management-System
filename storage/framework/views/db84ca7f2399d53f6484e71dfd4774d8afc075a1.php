<div class="modal-content">
    <div class="modal-header clone-modal-header">
        <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> <?php echo __('label.VIEW_BUG_DETAILS'); ?></h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered">
            <tr>
                <td><b><?php echo app('translator')->get('label.TITLE'); ?></b></td>
                <td><?php echo e($target->bug_title); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.PROJECT_NAME'); ?></b></td>
                <td><?php echo e($target->project_name); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.REPORTING_DATE'); ?></b></td>
                <td><?php echo e(Helper::printDate($target->reporting_date)); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.REPORTING_MEDIUM'); ?></b></td>
                <td><?php echo e($target->medium_name); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.REPORTED_BY'); ?></b></td>
                <td><?php echo e($target->reported_by); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.SEVERITY_LEVEL'); ?></b></td>
                <td><?php echo e($target->category_level); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.UNIQUE_CODE'); ?></b></td>
                <td><?php echo e($target->unique_code); ?></td>
            </tr>
            <tr>
                <td><b><?php echo app('translator')->get('label.STATUS'); ?></b></td>
                <td>
                    <div id="progressBarMargin">
                        <?php if($target->status == '1'): ?>
                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.OPEN'); ?></span>
                        <?php else: ?>
                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.CLOSED'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="progress" id="progressBackground">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:<?php echo e(!empty($latestProgress->progress) ? $latestProgress->progress :''); ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo e(!empty($latestProgress->progress) ? $latestProgress->progress :''); ?> %</div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td><b><?php echo app('translator')->get('label.ATTACHMENT'); ?></b></td>
                <td>
                    <?php
                    $bugFilesArr = json_decode($target->files);
                    $i = 1;
                    if (!empty($bugFilesArr)) {
                        foreach ($bugFilesArr as $bugFiles) {
                            $ext = pathinfo($bugFiles->file, PATHINFO_EXTENSION);
                            ?>
                            <div id="bug-file-show">
                                <?php if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'): ?>
                                <div><a href="<?php echo e(asset('public/uploads/bug/'.$bugFiles->file)); ?>" class="tooltips" title="<?php echo e($bugFiles->title); ?>" download><img src="<?php echo e(asset('public/uploads/bug/'.$bugFiles->file)); ?>" height="50px" width="50px"></a></div>
                                <?php elseif($ext == 'pdf'): ?>
                                <div><a href="<?php echo e(asset('public/uploads/bug/'.$bugFiles->file)); ?>" class="tooltips" title="<?php echo e($bugFiles->title); ?>" download><img src="<?php echo e(asset('public/image/fileIcon/pdf.png')); ?>" height="50px" width="50px"></a></div>
                                <?php elseif($ext == 'doc'): ?>
                                <div><a href="<?php echo e(asset('public/uploads/bug/'.$bugFiles->file)); ?>" class="tooltips" title="<?php echo e($bugFiles->title); ?>" download><img src="<?php echo e(asset('public/image/fileIcon/doc.jpg')); ?>" height="50px" width="50px"></a></div>
                                <?php elseif($ext == 'docx'): ?>
                                <div><a href="<?php echo e(asset('public/uploads/bug/'.$bugFiles->file)); ?>"  class="tooltips" title="<?php echo e($bugFiles->title); ?>" download><img src="<?php echo e(asset('public/image/fileIcon/docx.jpg')); ?>" height="50px" width="50px"></a></div>
                                <?php endif; ?>
                            </div>

                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>

        </table>
    </div>



    <div class="modal-header clone-modal-header">
        <h3 class="modal-title text-center">
            <?php echo app('translator')->get('label.BUG_TRACKING'); ?>
        </h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                        <div class="portlet-body">
                            <div class="mt-timeline-2">
                                <?php if($followUps->isNotEmpty()): ?>
                                <div class="mt-timeline-line border-grey-steel"></div>
                                <ul class="mt-container">
                                    <?php $__currentLoopData = $followUps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $followUp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $icon = $background = '';
                                    if ($followUp->status == '1') {
                                        $icon = 'icon-loop';
                                        $background = 'bg-blue bg-font-blue border-grey-steel';
                                    } elseif ($followUp->status == '2') {
                                        $icon = 'icon-calendar';
                                        $background = 'bg-green-turquoise bg-font-green-turquoise border-grey-steel';
                                    } elseif ($followUp->status == '3') {
                                        $icon = 'icon-close';
                                        $background = 'bg-red bg-font-red';
                                    }
                                    ?>
                                    <li class="mt-item">
                                        <div class="mt-timeline-icon <?php echo e($background); ?>">
                                            <i class="<?php echo e($icon); ?>"></i>
                                        </div>
                                        <div class="mt-timeline-content">
                                            <div class="mt-content-container">
                                                <?php if($followUp->status == '1'): ?>
                                                <strong><?php echo app('translator')->get('label.STATUS'); ?>: </strong><span><?php echo "In Progress"; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.PROGRESS_BY'); ?>: </strong><span><?php echo !empty($followUp->action_taken_by) ? $followUp->action_taken_by : ''; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.PROGRESS_AT'); ?>: </strong><span><?php echo !empty($followUp->date_time) ? Helper::formatDate($followUp->date_time) : ''; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.PROGRESS'); ?>: </strong><span><?php echo !empty($followUp->progress) ? $followUp->progress : ''; ?> %</span><br/>
                                                <strong><?php echo app('translator')->get('label.REMARKS'); ?>: </strong><span><?php echo !empty($followUp->remarks) ? $followUp->remarks : ''; ?></span>

                                                <?php elseif($followUp->status == '2'): ?>
                                                <strong><?php echo app('translator')->get('label.HAULT_BY'); ?>: </strong><span><?php echo !empty($followUp->action_taken_by) ? $followUp->action_taken_by : ''; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.HAULT_AT'); ?>: </strong><span><?php echo !empty($followUp->date_time) ? Helper::formatDate($followUp->date_time) : ''; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.REMARKS'); ?>: </strong><span><?php echo !empty($followUp->remarks) ? $followUp->remarks : ''; ?></span>
                                                <?php elseif($followUp->status == '3'): ?>
                                                <strong><?php echo app('translator')->get('label.CLOSED_BY'); ?>: </strong><span><?php echo !empty($followUp->action_taken_by) ? $followUp->action_taken_by : ''; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.CLOSED_AT'); ?>: </strong><span><?php echo !empty($followUp->date_time) ? Helper::formatDate($followUp->date_time) : ''; ?></span><br/>
                                                <strong><?php echo app('translator')->get('label.REMARKS'); ?>: </strong><span><?php echo !empty($followUp->remarks) ? $followUp->remarks : ''; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </ul>
                                <?php else: ?>
                                <div class="alert alert-danger">
                                    <?php echo app('translator')->get('label.NO_BUG_TRACKING_IS_AVAILABLE'); ?>       
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer2 bg-default">
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" data-placement="left" class="btn red pull-right tooltips" title="<?php echo app('translator')->get('label.CLOSE_THIS_POPUP'); ?>"><?php echo app('translator')->get('label.CLOSE'); ?></button>
        </div>
    </div>
</div>

</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>



<?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/bugResolution/viewBugModal.blade.php ENDPATH**/ ?>