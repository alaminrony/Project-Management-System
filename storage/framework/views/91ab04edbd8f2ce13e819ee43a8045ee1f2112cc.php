<?php $__env->startSection('login_content'); ?>
<!-- BEGIN LOGIN FORM -->
<form class="login-form" method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN LOGO -->
            <div class="logo">
                <a href="#">
                    <img src="<?php echo e(URL::to('/')); ?>/public/img/login_logo.png" alt="logo"/>
                </a>
            </div>
            <!-- END LOGO -->

        </div>
    </div>

    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9"><?php echo app('translator')->get('label.USERNAME'); ?></label>
        <input id="userName" type="text" class="form-control form-control-solid placeholder-no-fix <?php echo e($errors->has('username') ? ' is-invalid' : ''); ?>" placeholder="Username" name="username" value="<?php echo e(old('username')); ?>" required>

        <?php if($errors->has('username')): ?>
        <span class="invalid-feedback">
            <strong class="text-danger"><?php echo e($errors->first('username')); ?></strong>
        </span>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input id="password" type="password" class="form-control form-control-solid placeholder-no-fix<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="Password" name="password" required>

        <?php if($errors->has('password')): ?>
        <span class="invalid-feedback">
            <strong class="text-danger"><?php echo e($errors->first('password')); ?></strong>
        </span>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn green uppercase">Login</button>
        <!--label class="rememberme check mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="remember" value="1" />Remember
            <span></span>
        </label>
        <a href="<?php echo e(route('password.request')); ?>" id="forget-password" class="forget-password">Forgot Password?</a-->
    </div>
	
   
</form>
<!-- END LOGIN FORM -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/auth/login.blade.php ENDPATH**/ ?>