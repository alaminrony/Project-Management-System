<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo e(URL::to('/dashboard')); ?>">
                <img src="<?php echo e(URL::to('/')); ?>/public/img/logo.png" alt="logo" height="50px"/> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                 
                <li class="dropdown dropdown-extended dropdown-notification show-tooltip" id="header_notification_bar" data-container="body"  data-original-title="New Pending Bug." data-toggle="tooltip" data-placement="bottom" title="" >
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                        <i class="icon-bell" ></i>
                        <span class="badge badge-default"><?php echo e($notification); ?></span>

                    </a>

                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3><?php echo app('translator')->get('label.YOU_HAVE'); ?>
                                <span class="bold"><?php echo e($notification); ?></span> <?php echo app('translator')->get('label.NEW_PENDING_BUG'); ?>.
                               <span class="bold"></span> <?php echo app('translator')->get('label.CLICK_TO_VIEW_DETAILS'); ?>
                            </h3>
                        </li>
                       
                        <li>
                            <ul class="dropdown-menu-list"  data-handle-color="#637283">
                                <li>
                                    <a href="<?php echo e(url('bugResolution')); ?>">
                                        <span class="details">
                                            <span class="badge badge-success req-number"><?php echo e($notification); ?></span>
                                            <?php echo app('translator')->get('label.NEW_PENDING_BUG'); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                
                <li class="dropdown dropdown-user">

                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php
                        $user = Auth::user(); //get current user all information
                        if (!empty($user->photo)) {
                            ?>
                            <img alt="<?php echo e($user['username']); ?>" class="img-circle" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($user->photo); ?>" />
                        <?php } else { ?>
                            <img alt="<?php echo e($user['username']); ?>" class="img-circle" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" />
                        <?php } ?>
                        <span class="username username-hide-on-mobile"><?php echo app('translator')->get('label.WELCOME'); ?> <?php echo e($user->username); ?></span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?php echo e(url('changePassword')); ?>">
                                <i class="icon-key"></i><?php echo app('translator')->get('label.CHANGE_PASSWORD'); ?></a>
                        </li>

                        <!--                        <li>
                                                    <a href="#" class="tooltips" title="My Profile">
                                                        <i class="icon-user"></i><?php echo app('translator')->get('label.MY_PROFILE'); ?></a>
                                                </li>-->
                        <!--li>
                            <a href="app_calendar.html">
                                <i class="icon-calendar"></i> My Calendar </a>
                        </li-->
                        <!--li>
                            <a href="app_inbox.html">
                                <i class="icon-envelope-open"></i> My Inbox
                                <span class="badge badge-danger"> 3 </span>
                            </a>
                        </li-->
                        <!--li>
                            <a href="app_todo.html">
                                <i class="icon-rocket"></i> My Tasks
                                <span class="badge badge-success"> 7 </span>
                            </a>
                        </li-->
                        <li class="divider"> </li>

                        <li>
                            <a class="tooltips"  title="Logout" href="<?php echo e(route('logout')); ?>"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> <?php echo app('translator')->get('label.LOGOUT'); ?>
                            </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a class="tooltips" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" title="Logout">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.show-tooltip').tooltip();
        $('.tooltips').tooltip();
    });
</script><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/layouts/default/topNavbar.blade.php ENDPATH**/ ?>