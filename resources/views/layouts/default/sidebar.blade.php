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
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/dashboard')}}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> @lang('label.DASHBOARD')</span>
                </a>
            </li>

            <!-- User Group wise common feature set up -->
            @if(in_array(1, $adminSetupMenu))
            <li <?php
            $current = ( in_array($controllerName, array('user', 'usergroup', 'aclusergrouptoaccess', 'occupation', 'company', 'industry', 'speciality', 'designation', 'weakness', 'contact', 'department', 'project', 'projectrole', 'task', 'projectstatus', 'projectteam', 'tasktoproject', 'membertotask', 'bugcategory', 'reportingmedium', 'pointofcontact', 'bug', 'supportteam', 'bugresolution', 'bugreport','employeereport'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">@lang('label.ADMINISTRATIVE_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(in_array(1, $accessControlMenu))
                    <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess', 'usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="info">@lang('label.ACCESS_CONTROL')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[69][15]))
                            <li <?php $current = ( in_array($controllerName, array('aclusergrouptoaccess')) && ($routeName != 'aclusergrouptoaccess/moduleaccesscontrol' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/aclUserGroupToAccess/userGroupToAccess')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[69][1]))
                            <li <?php $current = ($routeName == 'aclusergrouptoaccess/moduleaccesscontrol' ) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('aclUserGroupToAccess/moduleAccessControl/')}}" class="nav-link">
                                    <span class="title">@lang('label.MODULE_WISE_ACCESS')</span>
                                </a>
                            </li>
                            @endif
                            @if(!empty($userAccessArr[68][1]))
                            <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/userGroup')}}" class="nav-link ">
                                    <span class="title">@lang('label.USER_GROUP')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[1][1]))
                    <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/user')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.USER')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[3][1]))
                    <li <?php $current = ( in_array($controllerName, array('designation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/designation')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.DESIGNATION')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[4][1]))
                    <li <?php $current = ( in_array($controllerName, array('occupation'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/occupation')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.OCCUPATION')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[5][1]))
                    <li <?php $current = ( in_array($controllerName, array('company'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/company')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.COMPANY')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[71][1]))
                    <li <?php $current = ( in_array($controllerName, array('industry'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/industry')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.INDUSTRY')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[73][1]))
                    <li <?php $current = ( in_array($controllerName, array('speciality'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/speciality')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.SPECIALITY')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[74][1]))
                    <li <?php $current = ( in_array($controllerName, array('weakness'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/weakness')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.WEAKNESS')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[2][1]))
                    <li <?php $current = ( in_array($controllerName, array('department'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/department')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.DEPARTMENT')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[72][1]))
                    <li <?php $current = ( in_array($controllerName, array('projectstatus'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/projectStatus')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.PROJECT_STATUS')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[75][1]))
                    <li <?php $current = ( in_array($controllerName, array('project'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/project')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.PROJECT')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[76][1]))
                    <li <?php $current = ( in_array($controllerName, array('projectrole'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/projectRole')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.PROJECT_ROLE')</span>
                        </a>
                    </li>
                    @endif
                    @if(!empty($userAccessArr[77][1]))
                    <li <?php $current = ( in_array($controllerName, array('task'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/task')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.PROJECT_TASK')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[78][1]))
                    <li <?php $current = ( in_array($controllerName, array('projectteam'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/projectTeam')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.PROJECT_TEAM')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[79][2]))
                    <li <?php $current = ( in_array($controllerName, array('tasktoproject'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/taskToProject')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.TASK_TO_PROJECT')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[80][2]))
                    <li <?php $current = ( in_array($controllerName, array('membertotask'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/memberToTask')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.MEMBER_TO_TASK')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[81][1]))
                    <li <?php $current = ( in_array($controllerName, array('bugcategory'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/bugCategory')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.BUG_CATEGORY')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[82][1]))
                    <li <?php $current = ( in_array($controllerName, array('reportingmedium'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/reportingMedium')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.REPORTING_MEDIUM')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[83][1]))
                    <li <?php $current = ( in_array($controllerName, array('pointofcontact'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/pointOfContact')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.POC')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[84][1]))
                    <li <?php $current = ( in_array($controllerName, array('bug'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/bug')}}" class="nav-link ">
                            <i class="fa fa-bug"></i>
                            <span class="title">@lang('label.BUG')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[85][2]))
                    <li <?php $current = ( in_array($controllerName, array('supportteam'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/supportTeam')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.SUPPORT_TEAM')</span>
                        </a>
                    </li>
                    @endif

                    @if(!empty($userAccessArr[86][1]))
                    <li <?php $current = ( in_array($controllerName, array('bugresolution'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/bugResolution')}}" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">@lang('label.BUG_RESOLUTION')</span>
                        </a>
                    </li>
                    @endif

                    <li <?php $current = (in_array($controllerName, array('aclusergrouptoaccess', 'usergroup'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-users"></i>
                            <span class="info">@lang('label.REPORT')</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            @if(!empty($userAccessArr[87][1]))
                            <li <?php $current = ( in_array($controllerName, array('bugreport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/bugReport')}}" class="nav-link ">
                                    <i class="icon-users"></i>
                                    <span class="title">@lang('label.BUG_REPORT')</span>
                                </a>
                            </li>
                            @endif
                            
                           @if(!empty($userAccessArr[88][1]))
                            <li <?php $current = ( in_array($controllerName, array('employeereport'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                <a href="{{url('/employeeReport')}}" class="nav-link ">
                                    <i class="icon-users"></i>
                                    <span class="title">@lang('label.EMPLOYEE_REPORT')</span>
                                </a>
                            </li>
                           @endif
                        </ul>
                    </li>
                    
                </ul>
            </li>
            @endif
        </ul>
    </div>
</div>