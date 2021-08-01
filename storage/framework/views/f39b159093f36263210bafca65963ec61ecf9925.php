<?php $__env->startSection('data_count'); ?>	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i><?php echo app('translator')->get('label.CREATE_USER'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => 'user', 'files'=> true, 'class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('filter', Helper::queryPageStr($qpArr)); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId"><?php echo app('translator')->get('label.USER_GROUP'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('group_id',$groupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('group_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="departmentId"><?php echo app('translator')->get('label.DEPARTMENT'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('department_id', $departmentList, null, ['class' => 'form-control js-source-states', 'id' => 'departmentId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('department_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="designationId"><?php echo app('translator')->get('label.DESIGNATION'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('designation_id', $designationList, null, ['class' => 'form-control js-source-states', 'id' => 'designationId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('department_id')); ?></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="employeeId"><?php echo app('translator')->get('label.EMPLOYEE_ID'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('employee_id', null, ['id'=> 'employeeId', 'class' => 'form-control integer-only']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('employee_id')); ?></span>

                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="firstName"><?php echo app('translator')->get('label.FIRST_NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('first_name', null, ['id'=> 'firstName', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('first_name')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="lastName"><?php echo app('translator')->get('label.LAST_NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('last_name', null, ['id'=> 'lastName', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('last_name')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="nickName"><?php echo app('translator')->get('label.NICK_NAME'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::text('nick_name', null, ['id'=> 'nickName', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('nick_name')); ?></span>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userEmail"><?php echo app('translator')->get('label.EMAIL'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::email('email', null, ['id'=> 'userEmail', 'class' => 'form-control']); ?>

                                <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone"><?php echo app('translator')->get('label.PHONE'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control integer-only']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('user_phone')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userName"><?php echo app('translator')->get('label.USERNAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('username', null, ['id'=> 'userName', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('username')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="password"><?php echo app('translator')->get('label.PASSWORD'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::password('password', ['id'=> 'password', 'class' => 'form-control','autocomplete' => 'off']); ?> 
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
                                <?php echo Form::password('conf_password', ['id'=> 'confPassword', 'class' => 'form-control','autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('conf_password')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']); ?>

                                <span class="text-danger"><?php echo e($errors->first('status')); ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <!--photo uplooad option-->
                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo"><?php echo app('translator')->get('label.PHOTO'); ?> :</label>
                            <div class="col-md-8">
                                <!-- input file -->
                                <div class="box">
                                    <input type="file" name="prev_photo" id="photo">
                                    <span class="text-danger"><?php echo e($errors->first('photo')); ?></span>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span> <?php echo app('translator')->get('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION'); ?>
                                    </div>
                                </div>				
                            </div>				
                        </div>
                        <!--photo crop option-->
                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <div class="col-md-4">
                                    <!-- input file -->
                                    <img class="cropped" src="" alt="" width="200px">
                                    <input type="hidden" name="crop_photo" id="cropImg" value="">

                                    <div class="box">
                                        <div class="options hide">
                                            <input type="hidden" class="img-w" value="300" min="255" max="255" />
                                        </div>
                                    </div>
                                </div>			
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="photo"></label>	
                            <div class="col-md-4">
                                <!-- leftbox -->
                                <div class="result"></div>
                                <!-- crop btn -->
                                <button class="c-btn crop btn-danger hide" type="button"><?php echo app('translator')->get('label.CROP'); ?></button>
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
                        <a href="<?php echo e(URL::to('/user'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {

//    crop image
    let result = document.querySelector('.result'),
            img_result = document.querySelector('.img-result'),
            img_w = document.querySelector('.img-w'),
            img_h = document.querySelector('.img-h'),
            options = document.querySelector('.options'),
            crop = document.querySelector('.crop'),
            cropped = document.querySelector('.cropped'),
            dwn = document.querySelector('.download'),
            upload = document.querySelector('#photo'),
            cropper = '';
    var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];
    // on change show image with crop options
    upload.addEventListener('change', function (e) {

        if (e.target.files.length) {
            // start file reader
            const reader = new FileReader();
            var file = e.target.files[0]; // Get your file here
            var fileExt = file.type.split('/')[1]; // Get the file extension
            if (fileTypes.indexOf(fileExt) !== -1) {
                reader.onload = function (e) {
                    console.log(e.target.result);
                    if (e.target.result) {
                        // create new image
                        let img = document.createElement('img');
                        img.id = 'image';
                        img.src = e.target.result
                        // clean result before
                        result.innerHTML = '';
                        // append new image
                        result.appendChild(img);
                        // show crop btn and options
                        crop.classList.remove('hide');
                        options.classList.remove('hide');
                        // init cropper
                        cropper = new Cropper(img);
                    }
                };
                reader.readAsDataURL(file);
            } else {
                alert('File not supported');
                return false;
            }
        }
    });
    // crop on click
    crop.addEventListener('click', function (e) {
        e.preventDefault();
        // get result to data uri
        let imgSrc = cropper.getCroppedCanvas({
            width: img_w.value // input value
        }).toDataURL();
        // remove hide class of img
        cropped.classList.remove('hide');
        //	img_result.classList.remove('hide');
        // show image cropped
        cropped.src = imgSrc;
        $('#cropImg').val(imgSrc);
        //console.log(imgSrc);return;

    });

//crop image end
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/user/create.blade.php ENDPATH**/ ?>