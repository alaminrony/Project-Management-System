<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i><?php echo app('translator')->get('label.EDIT_COMPANY'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::model($target, ['route' => array('company.update', $target->id), 'method' => 'PATCH','class' => 'form-horizontal','files'=>true] ); ?>

            <?php echo Form::hidden('filter', Helper::queryPageStr($qpArr)); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                         <div class="form-group">
                            <label class="control-label col-md-4" for="name"><?php echo app('translator')->get('label.NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('name', null, ['id'=> 'name', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="shortName"><?php echo app('translator')->get('label.SHORT_NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('short_name', null, ['id'=> 'shortName', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('short_name')); ?></span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-md-4" for="address"><?php echo app('translator')->get('label.COMPANY_ADDRESS'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::textarea('address', null, ['id'=> 'address', 'class' => 'form-control','rows'=>2,'cols'=>30]); ?> 
                                <span class="text-danger"><?php echo e($errors->first('address')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="contactNumber"><?php echo app('translator')->get('label.CONTACT_NUMBER'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('contact_no', null, ['id'=> 'contactNumber', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('contact_no')); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="email"><?php echo app('translator')->get('label.EMAIL'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::email('email', null, ['id'=> 'email', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-4" for="industry"><?php echo app('translator')->get('label.INDUSTRY'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('industry',$industries, null, ['class' => 'form-control js-source-states', 'id' => 'industry']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('industry')); ?></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-4" for="industry"><span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <img src="<?php echo e(asset('public/image/'.$target->logo)); ?>" height="100px" weight="100px;"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="logo"><?php echo app('translator')->get('label.LOGO'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::file('logo', null, ['id'=> 'logo', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('logo')); ?></span>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="control-label col-md-4" for="logo"><?php echo app('translator')->get('label.COMPANY_TYPE'); ?> :<span class="text-danger"></span></label>
                            <div class="col-md-8">
                                <?php echo Form::radio('type','1',false,['id'=>'mCompany']); ?>&nbsp;<?php echo Form::label('mCompany','Mother Company'); ?>

                                <?php echo Form::radio('type','2',false,['id'=>'sConcern']); ?>&nbsp;<?php echo Form::label('sConcern','Sister Concern'); ?> 
                                <span class="text-danger"><?php echo e($errors->first('type')); ?></span>
                            </div>
                        </div>
                        <div id="companyShow">
                            
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="order"><?php echo app('translator')->get('label.ORDER'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('order', $orderList,null, ['class' => 'form-control js-source-states', 'id' => 'order']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('order')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']); ?>

                                <span class="text-danger"><?php echo e($errors->first('status')); ?></span>
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
                        <a href="<?php echo e(URL::to('/company'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click','input[name="type"]',function(){
           var companyType = $(this).val();
           if(companyType == 2){
               $.ajax({
                   url:"<?php echo e(route('company.getCompanyType')); ?>",
                   type:"post",
                   dataType:"html",
                   headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                   success:function(data){
                       if(data != ''){
                          $('#companyShow').html(data);
                          $(".js-source-states").select2();
                       }
                   }
               });
           }else{
               $('#companyShow').html(''); 
           }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/company/edit.blade.php ENDPATH**/ ?>