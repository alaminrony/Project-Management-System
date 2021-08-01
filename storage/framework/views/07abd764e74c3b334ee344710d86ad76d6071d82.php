<?php $__env->startSection('data_count'); ?>

<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- BEGIN PROFILE SIDEBAR -->
    <div class="profile-content">
        <div class="col-md-2">
            <br>
            <!-- PORTLET MAIN -->
            <ul class="list-unstyled profile-nav">
                <li>
                    <img src="<?php echo e(asset('public/image/'.$companyInfo->logo)); ?>" class="text-center img-responsive pic-bordered border border-default recruit-profile-photo-full"
                         alt="<?php echo e($companyInfo->name); ?>" style="width: 250px;height: auto;" />
                </li>                                    
            </ul>
        </div>
        <!-- START:: User Basic Info -->
        <div class="col-md-10">
            <br>
            <!--<div class="column sortable ">-->
            <div class="portlet portlet-sortable box green-color-style">
                <div class="portlet-body" style="padding: 8px !important">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0px">
                            <tr>
                                <td class="fit bold info"><?php echo app('translator')->get('label.COMPANY_NAME'); ?></td>
                                <td><?php echo e($companyInfo->name); ?></td>
                                <td class="fit bold info"><?php echo app('translator')->get('label.INDUSTRY'); ?></td>
                                <td><?php echo e($companyInfo->industry_name); ?></td>
                            </tr>

                            <tr>
                                <td class="fit bold info"><?php echo app('translator')->get('label.SHORT_NAME'); ?></td>
                                <td><?php echo e($companyInfo->short_name); ?></td>
                                <td class="fit bold info"><?php echo app('translator')->get('label.COMPANY_TYPE'); ?></td>
                                <td><?php echo e($companyInfo->type == 1 ? __('label.MOTHER_COMPANY') : __('label.SISTER_CONCERN')); ?></td>
                            </tr>

                            <tr>
                                <td class="fit bold info"><?php echo app('translator')->get('label.COMPANY_ADDRESS'); ?></td>
                                <td><?php echo e($companyInfo->address); ?></td>
                                <td class="fit bold info"><?php echo app('translator')->get('label.STATUS'); ?></td>
                                <td>
                                    <?php if($companyInfo->status == '1'): ?>
                                    <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                    <?php else: ?>
                                    <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="fit bold info"><?php echo app('translator')->get('label.CONTACT_NUMBER'); ?></td>
                                <td><?php echo e($companyInfo->contact_no); ?></td>
                                <td class="fit bold info"><?php echo app('translator')->get('label.CREATED_AT'); ?></td>
                                <td><?php echo e(Helper::formatDate($companyInfo->created_at)); ?></td>

                            </tr>

                            <tr>
                                <td class="fit bold info"><?php echo app('translator')->get('label.EMAIL'); ?></td>
                                <td><?php echo e($companyInfo->email); ?></td>
                                <?php if(!empty($companyArr[$companyInfo->mother_company_id])): ?>
                                <td class="fit bold info"><?php echo app('translator')->get('label.MOTHER_COMPANY_OF'); ?></td>
                                <td><?php echo e($companyArr[$companyInfo->mother_company_id]); ?></td>
                                <?php endif; ?>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
            <!--</div>-->
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-bookmark-o"></i><?php echo app('translator')->get('label.CONTACT_LIST'); ?>
                of <?php echo e(!empty($companyInfo->name)? $companyInfo->name : ''); ?>

            </div>
            <div class="actions">
                <?php if(!empty($userAccessArr[8][2])): ?>
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('company/'.$companyId.'/contact/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_CONTACT'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'company/'.$companyId.'/contact/filter','class' => 'form-horizontal')); ?>

                <div class="row">
                    <?php echo Form::hidden('companyId',$companyId); ?>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.NAME'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('name',$nameArr, Request::get('name'), ['class' => 'form-control js-source-states','id'=>'status']); ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('status',$status,Request::get('status'), ['class' => 'form-control js-source-states','id'=>'status']); ?>

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
                            <th colspan="2"><?php echo app('translator')->get('label.FIRST_NAME'); ?></th>
                            <th><?php echo app('translator')->get('label.LAST_NAME'); ?></th>
                            <th><?php echo app('translator')->get('label.OCCUPATION'); ?></th>
                            <th><?php echo app('translator')->get('label.COMPANY'); ?></th>
                            <th><?php echo app('translator')->get('label.DESIGNATION'); ?></th>
                            <th><?php echo app('translator')->get('label.CONTACT_NUMBER'); ?></th>
                            <th><?php echo app('translator')->get('label.EMAIL'); ?></th>
                            <th><?php echo app('translator')->get('label.PHOTO'); ?></th>
                            <th class="text-center"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class="td-actions text-center"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(++$sl); ?></td>
                            <td colspan="2"><?php echo e($target->first_name); ?></td>
                            <td><?php echo e($target->last_name); ?> </td>
                            <td><?php echo e($target->occupation_name); ?></td>
                            <td><?php echo e($target->company_name); ?></td>
                            <td><?php echo e($target->designation_name); ?></td>
                            <?php
                            $firstContact = "";
                            $noOfContact = 0;
                            if (!empty($target->contact_number)) {
                                $contactArray = explode(',', $target->contact_number);
                                $firstContact = $contactArray[0];
                                $noOfContact = sizeof($contactArray);
                            }
                            ?>
                            <td>
                                <?php echo e($firstContact); ?>

                                <?php if($noOfContact > 1): ?>
                                <a type="button" class="openViewModal" data-toggle="modal" title="View Contact" data-target="#viewContactModal" data-id="<?php echo e($target->id); ?>">...</a>
                                <?php endif; ?>
                            </td>
                            <?php
                            $firstEmail = "";
                            $noOfEmail = 0;
                            if (!empty($target->email)) {
                                $emailArray = explode(',', $target->email);
                                $firstEmail = $emailArray[0];
                                $noOfEmail = sizeof($emailArray);
                            }
                            ?>
                            <td>
                                <?php echo e($firstEmail); ?>

                                <?php if($noOfEmail > 1): ?>
                                <a type="button" class="openEmailModal" data-toggle="modal" title="View Email Address" data-target="#viewEmailModal" data-id="<?php echo e($target->id); ?>">...</a>
                                <?php endif; ?>
                            </td>
                            <td><img src="<?php echo e(asset('public/image/'.$target->image)); ?>" alt="<?php echo e($target->first_name); ?>" width="50px" height="50px" /></td>
                            <td class="text-center">
                                <?php if($target->status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td width="9%">
                                <div>
                                    <div class="pull-left">
                                        <?php if(!empty($userAccessArr[8][5])): ?>
                                        <a class="btn btn-xs btn-warning tooltips" title="Details" href="<?php echo e(URL::to('contact/' . $target->id . '/details'.Helper::queryPageStr($qpArr))); ?>">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($userAccessArr[8][3])): ?>
                                        <a class="btn btn-xs btn-primary tooltips" title="Edit" href="<?php echo e(URL::to('contact/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(!empty($userAccessArr[8][4])): ?>
                                    <div class="pull-right">
                                        <?php echo e(Form::open(array('url' => 'company/'.$companyId.'/contact/' .$target->id.'/'.Helper::queryPageStr($qpArr)))); ?>

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
                            <td colspan="12"><?php echo app('translator')->get('label.NO_CONTACT_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>


<!--view contact Number Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="viewModalShow">
        </div>
    </div>
</div>
<!--end view Modal -->

<!--view Email Modal -->
<div class="modal fade" id="viewEmailModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="emailModalShow">
        </div>
    </div>
</div>
<!--end Email Modal -->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.openViewModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.number')); ?>",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#viewModalShow').html(data.viewModal);
                    }
                });

            }
        });

        $(document).on('click', '.openEmailModal', function () {
            var id = $(this).attr('data-id');
            if (id != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.email')); ?>",
                    type: "post",
                    data: {id: id},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#emailModalShow').html(data.viewEmail);
                    }
                });

            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/contact/index.blade.php ENDPATH**/ ?>