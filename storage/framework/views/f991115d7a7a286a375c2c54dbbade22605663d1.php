<?php $__env->startSection('data_count'); ?>	
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-key"></i><?php echo app('translator')->get('label.UPDATE_PASSWORD'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => 'changePassword', 'class' => 'form-horizontal')); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">


                        <!--div class="form-group">
                            <label class="control-label col-md-4" for="currentPassword"><?php echo app('translator')->get('label.CURRENT_PASSWORD'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::password('current_password', ['id'=> 'currentPassword', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('current_password')); ?></span>

                            </div>
                        </div-->

                        <div class="form-group">
                            <label class="control-label col-md-4" for="password"><?php echo app('translator')->get('label.NEW_PASSWORD'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::password('password', ['id'=> 'password', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span>
                                    <?php echo app('translator')->get('label.COMPLEX_PASSWORD_INSTRUCTION'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="confPassword"><?php echo app('translator')->get('label.CONF_PASSWORD'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('conf_password')); ?></span>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('/dashboard')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/user/changePassword.blade.php ENDPATH**/ ?>