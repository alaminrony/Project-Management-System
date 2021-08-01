<?php
$controllerName = Request::segment(1);
$controllerName = Request::route()->getName();
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());


//Admin setup menus
$adminSetupMenu = [
    !empty($userAccessArr[1][1]) ? 1 : '', !empty($userAccessArr[2][1]) ? 1 : '', !empty($userAccessArr[3][1]) ? 1 : ''
    , !empty($userAccessArr[4][1]) ? 1 : '', !empty($userAccessArr[5][1]) ? 1 : '', !empty($userAccessArr[6][1]) ? 1 : ''
    , !empty($userAccessArr[7][1]) ? 1 : '', !empty($userAccessArr[8][1]) ? 1 : '', !empty($userAccessArr[9][1]) ? 1 : ''
    , !empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : '', !empty($userAccessArr[12][1]) ? 1 : ''
    , !empty($userAccessArr[13][1]) ? 1 : '', !empty($userAccessArr[14][1]) ? 1 : '', !empty($userAccessArr[15][1]) ? 1 : ''
    , !empty($userAccessArr[16][1]) ? 1 : '', !empty($userAccessArr[17][1]) ? 1 : '', !empty($userAccessArr[18][1]) ? 1 : ''
    , !empty($userAccessArr[19][1]) ? 1 : '', !empty($userAccessArr[20][1]) ? 1 : '', !empty($userAccessArr[20][10]) ? 1 : ''
    , !empty($userAccessArr[21][1]) ? 1 : '', !empty($userAccessArr[22][1]) ? 1 : '', !empty($userAccessArr[23][1]) ? 1 : ''
    , !empty($userAccessArr[24][1]) ? 1 : '', !empty($userAccessArr[25][1]) ? 1 : '', !empty($userAccessArr[26][1]) ? 1 : ''
    , !empty($userAccessArr[27][1]) ? 1 : '', !empty($userAccessArr[28][1]) ? 1 : '', !empty($userAccessArr[29][1]) ? 1 : ''
    , !empty($userAccessArr[30][1]) ? 1 : '', !empty($userAccessArr[31][1]) ? 1 : '', !empty($userAccessArr[32][1]) ? 1 : ''
    , !empty($userAccessArr[33][1]) ? 1 : '', !empty($userAccessArr[34][1]) ? 1 : '', !empty($userAccessArr[35][1]) ? 1 : ''
    , !empty($userAccessArr[36][1]) ? 1 : '', !empty($userAccessArr[37][1]) ? 1 : '', !empty($userAccessArr[66][1]) ? 1 : ''
    , !empty($userAccessArr[67][1]) ? 1 : '', !empty($userAccessArr[68][1]) ? 1 : ''
    , !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[69][15]) ? 1 : ''
];
$accessControlMenu = [!empty($userAccessArr[68][1]) ? 1 : '', !empty($userAccessArr[69][1]) ? 1 : '', !empty($userAccessArr[69][15]) ? 1 : ''];
$manufacturerMenu = [!empty($userAccessArr[10][1]) ? 1 : '', !empty($userAccessArr[11][1]) ? 1 : ''];
$substanceMenu = [!empty($userAccessArr[15][1]) ? 1 : '', !empty($userAccessArr[16][1]) ? 1 : ''];
$hazardMenu = [!empty($userAccessArr[18][1]) ? 1 : '', !empty($userAccessArr[19][1]) ? 1 : ''];
$productMenu = [!empty($userAccessArr[20][1]) ? 1 : '', !empty($userAccessArr[20][10]) ? 1 : ''];

//product checkin menus
$productCheckInMenu = [!empty($userAccessArr[38][2]) ? 1 : '', !empty($userAccessArr[39][2]) ? 1 : '', !empty($userAccessArr[40][1]) ? 1 : ''];

//adjustment menus
$adjustmentMenu = [!empty($userAccessArr[41][2]) ? 1 : '', !empty($userAccessArr[42][1]) ? 1 : '', !empty($userAccessArr[43][1]) ? 1 : ''];

//recipe menus
$recipeMenu = [!empty($userAccessArr[44][1]) ? 1 : '', !empty($userAccessArr[45][1]) ? 1 : ''];

//production menus
$productionMenu = [
    !empty($userAccessArr[46][1]) ? 1 : '', !empty($userAccessArr[47][2]) ? 1 : ''
    , !empty($userAccessArr[48][1]) ? 1 : '', !empty($userAccessArr[49][1]) ? 1 : '', !empty($userAccessArr[50][1]) ? 1 : ''
];

//substore demand menus
$substoreDemandMenu = [!empty($userAccessArr[51][2]) ? 1 : '', !empty($userAccessArr[52][1]) ? 1 : '', !empty($userAccessArr[53][1]) ? 1 : ''];

//report menus
$reportsMenu = [
    !empty($userAccessArr[54][1]) ? 1 : '', !empty($userAccessArr[55][1]) ? 1 : '', !empty($userAccessArr[56][1]) ? 1 : ''
    , !empty($userAccessArr[57][1]) ? 1 : '', !empty($userAccessArr[58][1]) ? 1 : '', !empty($userAccessArr[59][1]) ? 1 : ''
    , !empty($userAccessArr[60][1]) ? 1 : '', !empty($userAccessArr[61][1]) ? 1 : '', !empty($userAccessArr[62][1]) ? 1 : ''
    , !empty($userAccessArr[63][1]) ? 1 : '', !empty($userAccessArr[64][1]) ? 1 : '', !empty($userAccessArr[65][1]) ? 1 : ''
];
$checkInReportMenu = [!empty($userAccessArr[55][1]) ? 1 : '', !empty($userAccessArr[56][1]) ? 1 : ''];
$consumptionReportMenu = [!empty($userAccessArr[58][1]) ? 1 : '', !empty($userAccessArr[59][1]) ? 1 : ''];
$productStatusReportMenu = [!empty($userAccessArr[61][1]) ? 1 : '', !empty($userAccessArr[62][1]) ? 1 : ''];
$substoreDemandReportMenu = [!empty($userAccessArr[64][1]) ? 1 : '', !empty($userAccessArr[65][1]) ? 1 : ''];
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" >
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/dashboard')); ?>" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> <?php echo app('translator')->get('label.DASHBOARD'); ?></span>
                </a>
            </li>

            <!-- User Group wise common feature set up -->
            <?php if(in_array(1, $adminSetupMenu)): ?>
            <li <?php
            $current = ( in_array($controllerName, array('user', 'usergroup', 'aclusergrouptoaccess', 'occupation', 'company', 'industry', 'speciality', 'designation', 'weakness', 'contact', 'department', 'project', 'projectrole', 'task', 'projectstatus', 'projectteam', 'tasktoproject', 'membertotask', 'bugcategory', 'reportingmedium', 'pointofcontact', 'bug', 'supportteam', 'bugresolution', 'bugreport','employeereport'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title"><?php echo app('translator')->get('label.ADMINISTRATIVE_SETUP'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(in_array(1, $accessControlMenu)): ?>
                    <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess', 'usergroup'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info"><?php echo app('translator')->get('label.ACCESS_CONTROL'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[69][15])): ?>
                            <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess')) && ($routeName != 'aclusergrouptoaccess/moduleaccesscontrol' )) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/aclUserGroupToAccess/userGroupToAccess')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.USER_GROUP_ACCESS'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[69][1])): ?>
                            <li <?php $current = ($routeName == 'aclusergrouptoaccess/moduleaccesscontrol' ) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('aclUserGroupToAccess/moduleAccessControl/')); ?>" class="nav-link">
                                    <span class="title"><?php echo app('translator')->get('label.MODULE_WISE_ACCESS'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty($userAccessArr[68][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/userGroup')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.USER_GROUP'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[1][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/user')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.USER'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[3][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('designation'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/designation')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.DESIGNATION'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[4][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('occupation'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/occupation')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.OCCUPATION'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[5][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('company'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/company')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.COMPANY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[71][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('industry'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/industry')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.INDUSTRY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[73][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('speciality'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/speciality')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.SPECIALITY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[74][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('weakness'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/weakness')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.WEAKNESS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[2][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('department'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/department')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.DEPARTMENT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[72][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('projectstatus'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/projectStatus')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.PROJECT_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[75][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('project'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/project')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.PROJECT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[76][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('projectrole'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/projectRole')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.PROJECT_ROLE'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(!empty($userAccessArr[77][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('task'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/task')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.PROJECT_TASK'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[78][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('projectteam'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/projectTeam')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.PROJECT_TEAM'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[79][2])): ?>
                    <li <?php $current = ( in_array($controllerName, array('tasktoproject'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/taskToProject')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.TASK_TO_PROJECT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[80][2])): ?>
                    <li <?php $current = ( in_array($controllerName, array('membertotask'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/memberToTask')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.MEMBER_TO_TASK'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[81][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('bugcategory'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/bugCategory')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.BUG_CATEGORY'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[82][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('reportingmedium'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/reportingMedium')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.REPORTING_MEDIUM'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[83][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('pointofcontact'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/pointOfContact')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.POC'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[84][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('bug'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/bug')); ?>" class="nav-link ">
                            <i class="fa fa-bug"></i>
                            <span class="title"><?php echo app('translator')->get('label.BUG'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[85][2])): ?>
                    <li <?php $current = ( in_array($controllerName, array('supportteam'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/supportTeam')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.SUPPORT_TEAM'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(!empty($userAccessArr[86][1])): ?>
                    <li <?php $current = ( in_array($controllerName, array('bugresolution'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/bugResolution')); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title"><?php echo app('translator')->get('label.BUG_RESOLUTION'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li <?php $current = (in_array($controllerName, array('aclusergrouptoaccess', 'usergroup'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-users"></i>
                            <span class="info"><?php echo app('translator')->get('label.REPORT'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <?php if(!empty($userAccessArr[87][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('bugreport'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/bugReport')); ?>" class="nav-link ">
                                    <i class="icon-users"></i>
                                    <span class="title"><?php echo app('translator')->get('label.BUG_REPORT'); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                           <?php if(!empty($userAccessArr[88][1])): ?>
                            <li <?php $current = ( in_array($controllerName, array('employeereport'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('/employeeReport')); ?>" class="nav-link ">
                                    <i class="icon-users"></i>
                                    <span class="title"><?php echo app('translator')->get('label.EMPLOYEE_REPORT'); ?></span>
                                </a>
                            </li>
                           <?php endif; ?>
                        </ul>
                    </li>
                    
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/layouts/default/sidebar.blade.php ENDPATH**/ ?>