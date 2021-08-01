<?php echo $__env->make('layouts.default.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body id="addFullMenuClass" class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
    <div class="page-wrapper">
        <?php echo $__env->make('layouts.default.topNavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="clearfix"> </div>
        <div class="loader">
            <center>
                <img class="loading-image" src="<?php echo e(asset('public/image/preloader/126.gif')); ?>" alt="loading..">
            </center>
        </div>
        <div class="page-container">
            <?php echo $__env->make('layouts.default.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <?php echo $__env->yieldContent('data_count'); ?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        <?php echo $__env->make('layouts.default.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="quick-nav-overlay"></div>
    <?php echo $__env->make('layouts.default.footerScript', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/layouts/default/master.blade.php ENDPATH**/ ?>