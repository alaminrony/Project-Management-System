<?php $__env->startSection('data_count'); ?>
<style>
    input[name=team_manager_id] {
        width: 20px;
        height: 20px;
    }
</style>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i><?php echo app('translator')->get('label.SUPPORT_TEAM'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => '','class' => 'form-horizontal', 'id'=>'supportTeamForm')); ?>

            <?php echo Form::hidden('page'); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="project_id"><?php echo app('translator')->get('label.PROJECT'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('project_id',$projectList,null, ['class' => 'form-control js-source-states', 'id' => 'project_id']); ?> 
                                <span class="text-danger" id="errorProjectId"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="showSupportMember">
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="button" id="saveSupportTeam">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('/supportTeam')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>

            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>

<script>
    $(document).ready(function () {
        $(document).on('change', '#project_id', function () {
            var projectId = $(this).val();
            if (projectId != '') {
                $.ajax({
                    url: "<?php echo e(route('supportTeam.getSupportedPerson')); ?>",
                    type: "post",
                    data: {project_id: projectId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (date) {
                        $('#showSupportMember').html(date.viewData);
                    }
                });
            } else {
                $('#showSupportMember').html('');

            }
        });

        $(document).on('click', '#saveSupportTeam', function () {
            var formData = new FormData($('#supportTeamForm')[0]);
            if (formData != '') {
                $.ajax({
                    url: "<?php echo e(route('supportTeam.store')); ?>",
                    type: "post",
                    data: formData,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#errorProjectId").text('');
                        $("#support_persons_id").text('');
                        $("#team_manager_id").text('');
                        if (data.errors) {
                            $("#errorProjectId").text(data.errors.project_id);
                            $("#support_persons_id").text(data.errors.support_persons_id);
                            $("#team_manager_id").text(data.errors.team_manager_id);
                        }
                        if (data == 'success') {
                            toastr["success"]("<?php echo app('translator')->get('label.SUPPORT_TEAM_CREATED_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/supportTeam/create.blade.php ENDPATH**/ ?>