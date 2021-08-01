<?php $__env->startSection('data_count'); ?>
<!-- BEGIN CONTENT BODY -->
<!-- BEGIN PORTLET-->
<?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- END PORTLET-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-content">
            <div class="col-md-2">
                <br>
                <!-- PORTLET MAIN -->
                <ul class="list-unstyled profile-nav">
                    <li>
                        <img src="<?php echo e(asset('public/image/'.$contactDetails->image)); ?>" class="text-center img-responsive pic-bordered border border-default recruit-profile-photo-full"
                             alt="" style="width: 250px;height: auto;" />
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
                                    <td class="fit bold info"><?php echo app('translator')->get('label.FIRST_NAME'); ?></td>
                                    <td><?php echo e($contactDetails->first_name); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.TOTAL_EXPERIENCE_YEAR'); ?></td>
                                    <td><?php echo e(!empty($totalYearExperience) ? $totalYearExperience : ''); ?> Years</td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.LAST_NAME'); ?></td>
                                    <td><?php echo e($contactDetails->last_name); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.EXPERTISE'); ?></td>
                                    <td><?php echo e(!empty($expertiseStr) ? $expertiseStr : ''); ?></td>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.OCCUPATION'); ?></td>
                                    <td><?php echo e($contactDetails->occupation_name); ?></td>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.EXPERIENCE_CURR_ORG'); ?></td>

                                    <td>
                                        <?php if($currOrganization->isNotEmpty()): ?>
                                        <?php $__currentLoopData = $currOrganization; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currOrg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e(!empty($currOrg->organization_name) ? $currOrg->organization_name : ''); ?>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>

                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.COMPANY'); ?></td>
                                    <td><?php echo e($contactDetails->company_name); ?></td>

                                </tr>
                                <tr>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.DESIGNATION'); ?></td>
                                    <td><?php echo e($contactDetails->designation_name); ?></td>

                                </tr>
                                <tr>
                                    <?php
                                    $firstContact = "";
                                    $noOfContact = 0;
                                    if (!empty($contactDetails->contact_number)) {
                                        $contactArray = explode(',', $contactDetails->contact_number);
                                        $firstContact = $contactArray[0];
                                        $noOfContact = sizeof($contactArray);
                                    }
                                    ?>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.CONTACT_NUMBER'); ?></td>
                                    <td>
                                        <?php echo e($firstContact); ?>

                                        <?php if($noOfContact > 1): ?>
                                        <a type="button" class="openViewModal" data-toggle="modal" title="View Contact" data-target="#viewContactModal" data-id="<?php echo e($contactDetails->id); ?>">...</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <?php
                                    $firstEmail = "";
                                    $noOfEmail = 0;
                                    if (!empty($contactDetails->email)) {
                                        $emailArray = explode(',', $contactDetails->email);
                                        $firstEmail = $emailArray[0];
                                        $noOfEmail = sizeof($emailArray);
                                    }
                                    ?>
                                    <td class="fit bold info"><?php echo app('translator')->get('label.EMAIL'); ?></td>
                                    <td>
                                        <?php echo e($firstEmail); ?>

                                        <?php if($noOfEmail > 1): ?>
                                        <a type="button" class="openEmailModal" data-toggle="modal" title="View Email Address" data-target="#viewEmailModal" data-id="<?php echo e($contactDetails->id); ?>">...</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!--</div>-->
            </div>
        </div>
    </div>
    <!--Start Meet up-->
    <div class="col-md-12">
        <br>
        <div class="column sortable ">
            <div class="portlet portlet-sortable box title-header">
                <div class="portlet-title ui-sortable-handle">
                    <div class="caption">
                        <i class="fa fa-cogs green-color-style-color" id="white-color"></i><?php echo e(__('label.MEET_UP')); ?>

                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                    <div class="actions">
                        <a class="btn border border-default tooltips title-header-button" id="openMeetupModal" type="button" data-toggle="modal" data-target="#createMeetupModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.ADD_MEET_UP'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('label.ADD')); ?></a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"> <?php echo e(__('label.SL')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.DATE')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.LOCATION')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.PURPOSE')); ?> </th>
                                    <th> <?php echo e(trans('label.ACTION')); ?> </th>
                                </tr>
                            </thead>
                            <?php $i=1; ?>
                            <?php if($meetUpDetails->isNotEmpty()): ?>
                            <?php $__currentLoopData = $meetUpDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meetUp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fit"><?php echo e($i++); ?></td>
                                <td class="vcenter"><?php echo e(Helper::printDate($meetUp->date)); ?></td>
                                <td class="vcenter"><?php echo e($meetUp->location); ?></td>
                                <td class="vcenter"><?php echo e($meetUp->purpose); ?></td>
                                <td width="8%">
                                    <div>
                                        <div class="pull-left left-button">
                                            <a class="btn btn-primary btn-xs editMeetUp" data-toggle="modal" data-target="#editMeetupModal" data-id="<?php echo e($meetUp->id); ?>" title="Edit" >
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="pull-right right-button">
                                            <?php echo e(Form::open(array('url' => 'contact/deleteMeetup/' . $meetUp->id))); ?>

                                            <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                            <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php echo e(Form::close()); ?>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6"><?php echo e(__('label.NO_DATA_FOUND')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end Meet up-->

    <!--Specialty-->
    <div class="col-md-6">
        <br>
        <div class="column sortable ">
            <div class="portlet portlet-sortable box title-header">
                <div class="portlet-title ui-sortable-handle">
                    <div class="caption">
                        <i class="fa fa-cogs green-color-style-color" id="white-color"></i><?php echo e(__('label.SPECIALTY')); ?>

                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                    <div class="actions">
                        <?php if($contactSpecialities->isEmpty()): ?>
                        <a class="btn border border-default tooltips title-header-button" id="openSpecialtyModal" type="button" data-toggle="modal" data-target="#createSpecialtyModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.ADD_SPECIALTY'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('label.ADD')); ?></a>
                        <?php else: ?>
                        <a class="btn border border-default tooltips title-header-button" id="editSpecialty" type="button" data-toggle="modal" data-target="#editSpecialtyModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.EDIT_SPECIALTY'); ?>"><i class="fa fa-edit"></i> <?php echo e(__('label.EDIT')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"> <?php echo e(__('label.SL')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.SPECIALTY')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.SHORT_NAME')); ?> </th>
                                </tr>
                            </thead>
                            <?php $i=1; ?>
                            <?php if($contactSpecialities->isNotEmpty()): ?>
                            <?php $__currentLoopData = $contactSpecialities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speciality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fit"><?php echo e($i++); ?></td>
                                <td class="vcenter"><?php echo e(!empty($speciality->name) ? $speciality->name : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($speciality->short_name) ? $speciality->short_name : ''); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6"><?php echo e(__('label.NO_DATA_FOUND')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sepcialty-->

    <!--Weekness Start-->
    <div class="col-md-6">
        <br>
        <div class="column sortable ">
            <div class="portlet portlet-sortable box title-header">
                <div class="portlet-title ui-sortable-handle">
                    <div class="caption">
                        <i class="fa fa-cogs green-color-style-color" id="white-color"></i><?php echo e(__('label.WEAKNESS')); ?>

                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                    <div class="actions">
                        <?php if($contactWeakness->isEmpty()): ?>
                        <a class="btn border border-default tooltips title-header-button" id="openWeaknessModal" type="button" data-toggle="modal" data-target="#createWeaknessModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.ADD_WEAKNESS'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('label.ADD')); ?></a>
                        <?php else: ?>
                        <a class="btn border border-default tooltips title-header-button" id="editWeakness" type="button" data-toggle="modal" data-target="#editWeaknessModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.EDIT_WEAKNESS'); ?>"><i class="fa fa-edit"></i> <?php echo e(__('label.EDIT')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"> <?php echo e(__('label.SL')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.WEAKNESS')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.SHORT_NAME')); ?> </th>
                                </tr>
                            </thead>
                            <?php $i=1; ?>
                            <?php if($contactWeakness->isNotEmpty()): ?>
                            <?php $__currentLoopData = $contactWeakness; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weakness): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fit"><?php echo e($i++); ?></td>
                                <td class="vcenter"><?php echo e(!empty($weakness->name) ? $weakness->name : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($weakness->short_name) ? $weakness->short_name : ''); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6"><?php echo e(__('label.NO_DATA_FOUND')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Weekness-->

    <!--Academic Start-->
    <div class="col-md-12">
        <br>
        <div class="column sortable ">
            <div class="portlet portlet-sortable box title-header">
                <div class="portlet-title ui-sortable-handle">
                    <div class="caption">
                        <i class="fa fa-cogs green-color-style-color" id="white-color"></i><?php echo e(__('label.ADD_ACADEMIC_QUALIFICATION')); ?>

                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                    <div class="actions">
                        <a class="btn border border-default tooltips title-header-button" id="openAcademicModal" type="button" data-toggle="modal" data-target="#createAcademicModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.ADD_ACADEMIC_QUALIFICATION'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('label.ADD')); ?></a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"> <?php echo e(__('label.SL')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.DEGREE_NAME')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.INSTITUTE')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.BATCH')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.REMARKS')); ?> </th>
                                    <th> <?php echo e(trans('label.ACTION')); ?> </th>
                                </tr>
                            </thead>
                            <?php $i=1; ?>
                            <?php if($academicQualification->isNotEmpty()): ?>
                            <?php $__currentLoopData = $academicQualification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $academic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fit"><?php echo e($i++); ?></td>
                                <td class="vcenter"><?php echo e(!empty($academic->degree_name) ? $academic->degree_name : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($academic->institute) ? $academic->institute : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($academic->batch) ? $academic->batch : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($academic->remarks) ? $academic->remarks : ''); ?></td>
                                <td width="8%">
                                    <div>
                                        <div class="pull-left left-button">
                                            <a class="btn btn-primary btn-xs editAcademic" data-toggle="modal" data-target="#editAcademicModal" data-id="<?php echo e($academic->id); ?>" title="Edit" >
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="pull-right right-button">
                                            <?php echo e(Form::open(array('url' => 'contact/deleteAcademic/' . $academic->id))); ?>

                                            <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                            <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php echo e(Form::close()); ?>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6"><?php echo e(__('label.NO_DATA_FOUND')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Academic Section-->

    <!--Professional Start-->
    <div class="col-md-12">
        <br>
        <div class="column sortable ">
            <div class="portlet portlet-sortable box title-header">
                <div class="portlet-title ui-sortable-handle">
                    <div class="caption">
                        <i class="fa fa-cogs green-color-style-color" id="white-color"></i><?php echo e(__('label.PROFESSIONAL_SKILL')); ?>

                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                    <div class="actions">
                        <a class="btn border border-default tooltips title-header-button" id="openProfessionalModal" type="button" data-toggle="modal" data-target="#createProfessionalModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.ADD_PROFESSIONAL_SKILL'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('label.ADD')); ?></a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"> <?php echo e(__('label.SL')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.ORGANIZATION_NAME')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.EXPERIENCE_YEAR')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.EXPERTISE_AREA')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.CURRENT_ORGANIZATION')); ?> </th>
                                    <th> <?php echo e(trans('label.ACTION')); ?> </th>
                                </tr>
                            </thead>
                            <?php $i=1; ?>
                            <?php if($professionalSkill->isNotEmpty()): ?>
                            <?php $__currentLoopData = $professionalSkill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fit"><?php echo e($i++); ?></td>
                                <td class="vcenter"><?php echo e(!empty($professional->organization_name) ? $professional->organization_name : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($professional->experience_year)? $professional->experience_year :''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($professional->expertise_area) ? $professional->expertise_area : ''); ?></td>
                                <td class="vcenter"><?php echo e($professional->current_working == '0' ? __('label.YES') : __('label.NO')); ?></td>
                                <td width="8%">
                                    <div>
                                        <div class="pull-left left-button">
                                            <a class="btn btn-primary btn-xs editProfessional" data-toggle="modal" data-target="#editProfessionalModal" data-id="<?php echo e($professional->id); ?>" title="Edit" >
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="pull-right right-button">
                                            <?php echo e(Form::open(array('url' => 'contact/deleteProfessional/' .$professional->id))); ?>

                                            <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                            <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php echo e(Form::close()); ?>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6"><?php echo e(__('label.NO_DATA_FOUND')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Professional Section-->

    <!--Remarks Start-->
    <div class="col-md-12">
        <br>
        <div class="column sortable ">
            <div class="portlet portlet-sortable box title-header">
                <div class="portlet-title ui-sortable-handle">
                    <div class="caption">
                        <i class="fa fa-cogs green-color-style-color" id="white-color"></i><?php echo e(__('label.REMARKS')); ?>

                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                    </div>
                    <div class="actions">
                        <a class="btn border border-default tooltips title-header-button" id="openRemarksModal" type="button" data-toggle="modal" data-target="#createRemarksModal" data-id="<?php echo e($contactDetails->id); ?>" title="<?php echo app('translator')->get('label.ADD_REMARKS'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('label.ADD')); ?></a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="vcenter"> <?php echo e(__('label.SL')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.DATE')); ?> </th>
                                    <th class="vcenter"> <?php echo e(__('label.REMARKS')); ?> </th>
                                    <th> <?php echo e(trans('label.ACTION')); ?> </th>
                                </tr>
                            </thead>
                            <?php $i=1; ?>
                            <?php if($remarks->isNotEmpty()): ?>
                            <?php $__currentLoopData = $remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fit"><?php echo e($i++); ?></td>
                                <td class="vcenter"><?php echo e(!empty($remark->date) ? Helper::printDate($remark->date) : ''); ?></td>
                                <td class="vcenter"><?php echo e(!empty($remark->remarks) ? $remark->remarks : ''); ?></td>
                                <td width="8%">
                                    <div>
                                        <div class="pull-left left-button">
                                            <a class="btn btn-primary btn-xs editRemarks" data-toggle="modal" data-target="#editRemarksModal" data-id="<?php echo e($remark->id); ?>" title="Edit" >
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="pull-right right-button">
                                            <?php echo e(Form::open(array('url' => 'contact/deleteRemarks/' .$remark->id))); ?>

                                            <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                            <button class="btn btn-xs btn-danger delete tooltips" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php echo e(Form::close()); ?>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6"><?php echo e(__('label.NO_DATA_FOUND')); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Remarks Section-->

</div>


<!-- BEGIN PROFILE CONTENT -->

<!-- START:: Modal Block -->
<!--create Meet Up Modal -->
<div class="modal fade select2-error" id="createMeetupModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="createMeetupModalShow">

        </div>
    </div>
</div>
<!--end Meet Up Modal -->

<!--edit Meet Up Modal -->
<div class="modal fade select2-error" id="editMeetupModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editMeetupModalShow">

        </div>
    </div>
</div>
<!--end Meet Up Modal -->

<!--create Specialty Modal -->
<div class="modal fade" id="createSpecialtyModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div id="createSpecialtyModalShow">

        </div>
    </div>
</div>
<!--end Specialty Modal -->

<!--edit Specialty Modal -->
<div class="modal fade" id="editSpecialtyModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editSpecialtyModalShow">

        </div>
    </div>
</div>
<!--end edit Specialty Modal -->

<!--create Weaknees Modal -->
<div class="modal fade" id="createWeaknessModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div id="createWeaknessModalShow">

        </div>
    </div>
</div>
<!--end Weaknees Modal -->

<!--edit Weaknees Modal -->
<div class="modal fade" id="editWeaknessModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editWeaknessModalShow">

        </div>
    </div>
</div>
<!--end edit Weaknees Modal -->

<!--create Academic Modal -->
<div class="modal fade select2-error" id="createAcademicModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="createAcademicModalShow">

        </div>
    </div>
</div>
<!--end Academic Modal -->

<!--edit Academic Modal -->
<div class="modal fade select2-error" id="editAcademicModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editAcademicModalShow">

        </div>
    </div>
</div>
<!--end Academic Modal -->

<!--create Professional Modal -->
<div class="modal fade" id="createProfessionalModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="createProfessionalModalShow">

        </div>
    </div>
</div>
<!--end Professional Modal -->

<!--edit Professional Modal -->
<div class="modal fade" id="editProfessionalModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editProfessionalModalShow">

        </div>
    </div>
</div>
<!--end Professional Modal -->

<!--create Remarks Modal -->
<div class="modal fade" id="createRemarksModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="createRemarksModalShow">

        </div>
    </div>
</div>
<!--end Remarks Modal -->

<!--edit Remarks Modal -->
<div class="modal fade" id="editRemarksModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editRemarksModalShow">

        </div>
    </div>
</div>
<!--end Remarks Modal -->


<!--view contact Number Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div id="viewModalShow">
        </div>
    </div>
</div>
<!--end contact Number Modal -->



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

        //Meet Up Section Modal
        $(document).on('click', '#openMeetupModal', function (event) {
            event.preventDefault();
            var contactId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(route('contact.openMeetupModal')); ?>",
                type: "post",
                data: {contact_id: contactId},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#createMeetupModalShow').html(data.createMeetupModal);
                    $('.js-example-basic-single').select2();
                }
            });
        });


        $(document).on('click', '#storeMeetUp', function () {
            var createMeetUpData = new FormData($('#createMeetUpForm')[0]);
            $.ajax({
                url: "<?php echo e(route('contact.storeMeetup')); ?>",
                data: createMeetUpData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $("#errorLocation").text('');
                    $("#errorDate").text('');
                    if (data.errors) {
                        $("#errorLocation").text(data.errors.location);
                        $("#errorDate").text(data.errors.date);
                    }
                    if (data == 'success') {
                        toastr["success"]("<?php echo app('translator')->get('label.BUG_LOCKED_SUCCESSFULLY'); ?>");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        $('#createMeetupModal').modal('hide');
                        //swal("Good job!", "You clicked the button!", "success");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 500) {
                        swal("OOPS!", "Internal Server Error Occured [500]!", "error");
                    }
                    else if (xhr.status == 404) {
                        swal("OOPS!", "The requested page not found [404]!", "error");
                    }
                    else if (xhr.status == 0) {
                        swal("OOPS!", "Not connected.\nPlease verify your network connection !", "error");
                    }
                    return false;
                },
            });
        });


        $(document).on('click', '.editMeetUp', function () {
            var meetUpId = $(this).attr('data-id');
            if (meetUpId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.editMeetup')); ?>",
                    type: "post",
                    data: {meetup_id: meetUpId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editMeetupModalShow').html(data.editMeetUpData);
                    }
                });
            }
        });


        $(document).on('click', '#updateMeetUp', function () {
            var updateData = new FormData($('#updateMeetUpForm')[0]);
            if (updateData != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.updateMeetup')); ?>",
                    data: updateData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#errorLocation").text('');
                        $("#errorDate").text('');
                        if (data.errors) {
                            $("#errorLocation").text(data.errors.location);
                            $("#errorDate").text(data.errors.date);
                        }
                        if (data == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#editMeetupModal').modal('hide');
                            toastr["success"]("<?php echo app('translator')->get('label.MEET_UP_HAS_BEEN_UPDATED_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
        //End Meet Up section Modal

        //Specialty Section Modal
        $(document).on('click', '#openSpecialtyModal', function () {
            var contactId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(route('contact.openSpecialtyModal')); ?>",
                type: "post",
                data: {contact_id: contactId},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#createSpecialtyModalShow').html(data.createSpecialtyModal);
                    $(".js-source-states").select2();
                }
            });
        });


        $(document).on('click', '#storeSpecialty', function () {
            var createSpecialtyData = new FormData($('#createSpecialtyFormData')[0]);
            $.ajax({
                url: "<?php echo e(route('contact.storeSpecialty')); ?>",
                data: createSpecialtyData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $("#errorSpecialty").text('');
                    if (data.errors) {
                        $("#errorSpecialty").text(data.errors.specialty_id);
                    }
                    if (data == 'success') {
                        toastr["success"]("<?php echo app('translator')->get('label.SPECIALTY_CREATED_SUCCESSFULLY'); ?>");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        $('#createSpecialtyModal').modal('hide');
                        //swal("Good job!", "You clicked the button!", "success");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 500) {
                        swal("OOPS!", "Internal Server Error Occured [500]!", "error");
                    }
                    else if (xhr.status == 404) {
                        swal("OOPS!", "The requested page not found [404]!", "error");
                    }
                    else if (xhr.status == 0) {
                        swal("OOPS!", "Not connected.\nPlease verify your network connection !", "error");
                    }
                    return false;
                },
            });
        });


        $(document).on('click', '#editSpecialty', function () {
            var contactId = $(this).attr('data-id');
            if (contactId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.editSpecialty')); ?>",
                    type: "post",
                    data: {contact_id: contactId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editSpecialtyModalShow').html(data.editSpecialtyData);
                    }
                });
            }
        });


        $(document).on('click', '#updateSpecialty', function () {
            var updateSpecialtyData = new FormData($('#updateSpecialtyFormData')[0]);
            if (updateSpecialtyData != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.updateSpecialty')); ?>",
                    data: updateSpecialtyData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#errorSpecialty").text('');
                        if (data.errors) {
                            $("#errorSpecialty").text(data.errors.specialty_id);
                        }
                        if (data == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#editSpecialtyModal').modal('hide');
                            toastr["success"]("<?php echo app('translator')->get('label.SPECIALTY_UPDATE_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
        //End Specialty section Modal

        //Weakness Section Modal
        $(document).on('click', '#openWeaknessModal', function () {
            var contactId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(route('contact.openWeaknessModal')); ?>",
                type: "post",
                data: {contact_id: contactId},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#createWeaknessModalShow').html(data.createWeaknessModal);
                    $(".js-source-states").select2();
                }
            });
        });


        $(document).on('click', '#storeWeakness', function () {
            var createWeaknessData = new FormData($('#createWeaknessFormData')[0]);
            $.ajax({
                url: "<?php echo e(route('contact.storeWeakness')); ?>",
                type: 'POST',
                data: createWeaknessData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data == 'success') {
                        toastr["success"]("<?php echo app('translator')->get('label.WEAKNESS_CREATED_SUCCESSFULLY'); ?>");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        $('#createWeaknessModal').modal('hide');
                        //swal("Good job!", "You clicked the button!", "success");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 500) {
                        swal("OOPS!", "Internal Server Error Occured [500]!", "error");
                    }
                    else if (xhr.status == 404) {
                        swal("OOPS!", "The requested page not found [404]!", "error");
                    }
                    else if (xhr.status == 0) {
                        swal("OOPS!", "Not connected.\nPlease verify your network connection !", "error");
                    }
                    return false;
                },
            });
        });


        $(document).on('click', '#editWeakness', function () {
            var contactId = $(this).attr('data-id');
            if (contactId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.editWeakness')); ?>",
                    type: "post",
                    data: {contact_id: contactId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editWeaknessModalShow').html(data.editWeaknessData);
                    }
                });
            }
        });


        $(document).on('click', '#updateWeakness', function () {
            var updateWeaknessData = new FormData($('#updateWeaknessFormData')[0]);
            if (updateWeaknessData != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.updateWeakness')); ?>",
                    data: updateWeaknessData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#errorWeakness").text('');
                        if (data.errors) {
                            $("#errorWeakness").text(data.errors.specialty_id);
                        }
                        if (data == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#editWeaknessModal').modal('hide');
                            toastr["success"]("<?php echo app('translator')->get('label.WEAKNESS_UPDATE_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
        //End Weakness section Modal

        //Academic Qualification Section Modal
        $(document).on('click', '#openAcademicModal', function () {
            var contactId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(route('contact.openAcademicModal')); ?>",
                type: "post",
                data: {contact_id: contactId},
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#createAcademicModalShow').html(data.createAcademicModal);
                    $('.js-example-basic-single').select2();
                }
            });
        });


        $(document).on('click', '#storeAcademic', function () {
            var createAcademicData = new FormData($('#createAcademicFormData')[0]);
            $.ajax({
                url: "<?php echo e(route('contact.storeAcademic')); ?>",
                data: createAcademicData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data == 'success') {
                        toastr["success"]("<?php echo app('translator')->get('label.ACADEMIC_QUALIFICATION_CREATED_SUCCESSFULLY'); ?>");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        $('#createAcademicModal').modal('hide');
                        //swal("Good job!", "You clicked the button!", "success");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 500) {
                        swal("OOPS!", "Internal Server Error Occured [500]!", "error");
                    }
                    else if (xhr.status == 404) {
                        swal("OOPS!", "The requested page not found [404]!", "error");
                    }
                    else if (xhr.status == 0) {
                        swal("OOPS!", "Not connected.\nPlease verify your network connection !", "error");
                    }
                    return false;
                },
            });
        });


        $(document).on('click', '.editAcademic', function () {
            var academicId = $(this).attr('data-id');
            if (academicId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.editAcademic')); ?>",
                    type: "post",
                    data: {academic_id: academicId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editAcademicModalShow').html(data.editAcademicData);
                    }
                });
            }
        });


        $(document).on('click', '#updateAcademic', function () {
            var updateData = new FormData($('#updateAcademicFormData')[0]);
            if (updateData != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.updateAcademic')); ?>",
                    data: updateData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $("#errorLocation").text('');
                        $("#errorDate").text('');
                        if (data.errors) {
                            $("#errorLocation").text(data.errors.location);
                            $("#errorDate").text(data.errors.date);
                        }
                        if (data == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#editMeetupModal').modal('hide');
                            toastr["success"]("<?php echo app('translator')->get('label.ACADEMIC_QUALIFICATION_UPDATED_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
        //End Academic Qualification section Modal

        //Professional Skill Section Modal
        $(document).on('click', '#openProfessionalModal', function () {
            var contactId = $(this).attr('data-id');
            if (contactId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.openProfessionalModal')); ?>",
                    type: "post",
                    data: {contact_id: contactId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#createProfessionalModalShow').html(data.createProfessionalModal);
                    }
                });
            }
        });


        $(document).on('click', '#storeProfessional', function () {
            var createPrefessionalData = new FormData($('#createProfessionalFormData')[0]);
            $.ajax({
                url: "<?php echo e(route('contact.storeProfessional')); ?>",
                data: createPrefessionalData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data == 'success') {
                        toastr["success"]("<?php echo app('translator')->get('label.Professional_SKILL_CREATED_SUCCESSFULLY'); ?>");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        $('#createProfessionalModal').modal('hide');
                        //swal("Good job!", "You clicked the button!", "success");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 500) {
                        swal("OOPS!", "Internal Server Error Occured [500]!", "error");
                    }
                    else if (xhr.status == 404) {
                        swal("OOPS!", "The requested page not found [404]!", "error");
                    }
                    else if (xhr.status == 0) {
                        swal("OOPS!", "Not connected.\nPlease verify your network connection !", "error");
                    }
                    return false;
                },
            });
        });


        $(document).on('click', '.editProfessional', function () {
            var professionalId = $(this).attr('data-id');
            if (professionalId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.editProfessional')); ?>",
                    type: "post",
                    data: {professional_id: professionalId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editProfessionalModalShow').html(data.editProfessionalData);
                    }
                });
            }
        });


        $(document).on('click', '#updateProfessional', function () {
            var updateData = new FormData($('#updateProfessionalFormData')[0]);
            if (updateData != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.updateProfessional')); ?>",
                    data: updateData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#editProfessionalModal').modal('hide');
                            toastr["success"]("<?php echo app('translator')->get('label.PROFESSIONAL_SKILL_UPDATED_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
        //End Professional section Modal

        //Remarks Section Modal
        $(document).on('click', '#openRemarksModal', function () {
            var contactId = $(this).attr('data-id');
            if (contactId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.openRemarksModal')); ?>",
                    type: "post",
                    data: {contact_id: contactId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#createRemarksModalShow').html(data.createRemarksModal);
                    }
                });
            }
        });


        $(document).on('click', '#storeRemarks', function () {
            var createRemarksData = new FormData($('#createRemarksFormDate')[0]);
            $.ajax({
                url: "<?php echo e(route('contact.storeRemarks')); ?>",
                data: createRemarksData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data == 'success') {
                        toastr["success"]("<?php echo app('translator')->get('label.REMARKS_CREATED_SUCCESSFULLY'); ?>");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        $('#createRemarksModal').modal('hide');
                        //swal("Good job!", "You clicked the button!", "success");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 500) {
                        swal("OOPS!", "Internal Server Error Occured [500]!", "error");
                    }
                    else if (xhr.status == 404) {
                        swal("OOPS!", "The requested page not found [404]!", "error");
                    }
                    else if (xhr.status == 0) {
                        swal("OOPS!", "Not connected.\nPlease verify your network connection !", "error");
                    }
                    return false;
                },
            });
        });


        $(document).on('click', '.editRemarks', function () {
            var remarksId = $(this).attr('data-id');
            if (remarksId != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.editRemarks')); ?>",
                    type: "post",
                    data: {remarks_id: remarksId},
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#editRemarksModalShow').html(data.editRemarksData);
                    }
                });
            }
        });


        $(document).on('click', '#updateRemarks', function () {
            var updateData = new FormData($('#updateRemarksFormData')[0]);
            if (updateData != '') {
                $.ajax({
                    url: "<?php echo e(route('contact.updateRemarks')); ?>",
                    data: updateData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data == "success") {
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                            $('#editRemarksModal').modal('hide');
                            toastr["success"]("<?php echo app('translator')->get('label.REMARKS_UPDATED_SUCCESSFULLY'); ?>");
                        }
                    }
                });
            }
        });
        //End Professional section Modal


    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/contact/viewDetails.blade.php ENDPATH**/ ?>