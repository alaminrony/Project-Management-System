<?php $__env->startSection('data_count'); ?>
<!-- First Div START:: for Status Wise Bug Count -->
<!-- END THEME PANEL -->
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="index.html">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Dashboard</span>
        </li>
    </ul>
    <div class="page-toolbar">
        <div id="" class="pull-right tooltips btn btn-sm">
            <i class="icon-calendar"></i>&nbsp;
            <span class="hidden-xs">Today is </span><?php echo Helper::formatDate(date('Y-m-d H:i:s')); ?>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Admin Dashboard
<!--    <small>statistics, charts, recent events and reports</small>-->
</h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <i class="fa fa-bookmark-o"></i>
                    <span data-counter="counterup" data-value="<?php echo e(!empty($totalProject) ? $totalProject : ''); ?>">0</span>
                </div>
                <div class="desc"><?php echo app('translator')->get('label.TOTAL_PROJECT'); ?></div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <i class="fa fa-bug"></i>
                    <span data-counter="counterup" data-value="<?php echo e(!empty($totalBug) ? $totalBug :''); ?>">0</span></div>
                <div class="desc"><?php echo app('translator')->get('label.TOTAL_BUG'); ?></div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <span data-counter="counterup" data-value="<?php echo e(!empty($totalUsers) ?$totalUsers : ''); ?>">0</span>
                </div>
                <div class="desc"><?php echo app('translator')->get('label.TOTAL_USERS'); ?></div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-globe"></i>
            </div>
            <div class="details">
                <div class="number">
                    <i class="fa fa-male"></i>
                    <span data-counter="counterup" data-value="<?php echo e(!empty($totalContact)? $totalContact : ''); ?>"></span></div>
                <div class="desc"><?php echo app('translator')->get('label.TOTAL_CONTACT_PERSON'); ?></div>
            </div>
        </a>
    </div>
</div>
<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->
<div class="row">
    <div class="col-lg-4 col-xs-12 col-sm-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-red-sunglo hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo app('translator')->get('label.STATUS_WISE_BUG_CHART'); ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="chart">

                        </div>   
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
    <div class="col-lg-8 col-xs-12 col-sm-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-red-sunglo hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?php echo app('translator')->get('label.TASK_WISE_PROJECT_CHART'); ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="chart1">

                        </div>   
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bubble font-dark hide"></i>
                    <span class="caption-subject font-hide bold uppercase"><?php echo app('translator')->get('label.RECENT_USER'); ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <?php if($recentUsers->isNotEmpty()): ?>
                    <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <!--begin: widget 1-1 -->
                        <div class="mt-widget-1">
                            <div class="mt-icon">
                                <a href="#">
                                    <i class="icon-plus"></i>
                                </a>
                            </div>
                            <div class="mt-img">
                                <?php if (!empty($recentUser->photo)) { ?>
                                    <img width="80" height="90" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($recentUser->photo); ?>" alt="<?php echo e($recentUser->full_name); ?>"/>
                                <?php } else { ?>
                                    <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($recentUser->full_name); ?>"/>
                                <?php } ?>
                            </div>
                            <div class="mt-body">
                                <h3 class="mt-username"><?php echo e($recentUser->user_name); ?></h3>
                                <p class="mt-user-title"><?php echo e($recentUser->designation_name); ?></p>
                            </div>
                        </div>
                        <!--end: widget 1-1 -->
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-microphone font-dark hide"></i>
                    <span class="caption-subject bold font-dark uppercase"> Recent Projects</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <?php if($recentProject->isNotEmpty()): ?>
                    <?php $__currentLoopData = $recentProject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4">
                        <div class="mt-widget-4">
                            <div class="mt-img-container">
                                <img src="<?php echo e(asset('public/image/'.$project->upload_file)); ?>" alt="<?php echo e($project->name); ?> file" height="50px;" width="50px;"/>
                            </div>
                            <div class="mt-container bg-purple-opacity">
                                <div class="mt-head-title"><?php echo e($project->name); ?></div>
                                <div class="mt-footer-button">
                                    <p class="bg-warning text-black"><?php echo e($project->company_name); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <div class="modal fade" id="bugDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="bugDetailsShow">

                </div>
            </div>
        </div>

        <div class="modal fade" id="taskWiseProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="taskWiseProjectShow">

                </div>
            </div>
        </div>

        <script src="<?php echo e(asset('public/js/apexchart.js')); ?>"></script>
        <script src="<?php echo e(asset('public/js/ohlc.js')); ?>"></script>
        <script>
        var colors = ['#449DD1', '#F86624', '#EA3546', '#662E9B', '#C5D86D'];
        var options = {
        series: [{
        name: "<?php echo app('translator')->get('label.NUMBER_OF_BUG'); ?>",
                data: [
<?php
if (!empty($numberOfOpenBug)) {
    echo $numberOfOpenBug;
}
?>,
<?php
if (!empty($numberOfOpenBug)) {
    echo $numberOfProgressBug;
}
?>,
<?php
if (!empty($numberOfHaultBug)) {
    echo $numberOfHaultBug;
}
?>,
<?php
if (!empty($numberOfClosedBug)) {
    echo $numberOfClosedBug;
}
?>,
                ]
        }],
                chart: {
                height: 350,
                        type: 'bar',
                        events: {
                        dataPointSelection: function (event, chartContext, config) {
                        console.log(config);
                                var indexNumber = config.dataPointIndex;
                                $.ajax({
                                url: "<?php echo e(route('dashboard.getBugDetails')); ?>",
                                        type: "POST",
                                        dataType: "json",
                                        headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                        index_number: indexNumber,
                                        },
                                        beforeSend: function () {
                                        //                        App.blockUI({
                                        //                            boxed: true
                                        //                        });
                                        },
                                        success: function (data) {
                                        $('#bugDetailsShow').html(data.viewData);
                                                $("#bugDetailsModal").modal('show');
                                                // App.unblockUI();
                                        },
                                        error: function (jqXhr, ajaxOptions, thrownError) {
                                        // App.unblockUI();
                                        }
                                }); //ajax

                        }
                        }
                },
                fill: {
                type: 'gradient',
                        gradient: {
                        shade: 'light',
                                type: "horizontal",
                                shadeIntensity: 0.25,
                                gradientToColors: undefined,
                                inverseColors: true,
                                opacityFrom: 0.95,
                                opacityTo: 0.95,
                                stops: [50, 0, 100]
                        },
                },
                colors: colors,
                plotOptions: {
                bar: {
                dataLabels: {
                position: 'top', // top, center, bottom
                },
                        columnWidth: '35%',
                        distributed: true,
                        endingShape: 'rounded'
                }
                },
                dataLabels: {
                enabled: true,
                        formatter: function (val) {
                        return val;
                        },
                        offsetY: - 20,
                        style: {
                        fontSize: '12px',
                                colors: ["#304758"]
                        }
                },
                legend: {
                show: false
                },
                xaxis: {
                categories: [
                        ['Open'],
                        ['In Progress'],
                        ['Hault'],
                        ['Closed'],
                ],
                        labels: {
                        style: {
                        colors: colors,
                                fontSize: '12px'
                        }
                        }
                }
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
        //Code for Second Chart
        var options = {
        series: [{
        name: "<?php echo app('translator')->get('label.NUMBER_OF_PROJECT'); ?>",
                data: [
<?php
if (!empty($taskArr)) {
    foreach ($taskArr as $taskId => $title) {
        ?>
                        <?php echo e((!empty($taskId) && isset($arrayTaskCount[$taskId])) ? $arrayTaskCount[$taskId] : '0'); ?>,
        <?php
    }
}
?>
                ],
        }],
                chart: {
                height: 350,
                        type: 'bar',
                        events: {
                        dataPointSelection: function (event, chartContext, config) {
                        var index = config.dataPointIndex;
                                var taskId = config.w.config.id[index];
                                $.ajax({
                                url: "<?php echo e(route('dashboard.getTaskWiseProject')); ?>",
                                        type: "POST",
                                        dataType: "json",
                                        headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                        task_id: taskId,
                                        },
                                        beforeSend: function () {
                                        //                        App.blockUI({
                                        //                            boxed: true
                                        //                        });
                                        },
                                        success: function (data) {
                                        $('#taskWiseProjectShow').html(data.viewData);
                                                $("#taskWiseProjectModal").modal('show');
                                                // App.unblockUI();
                                        },
                                        error: function (jqXhr, ajaxOptions, thrownError) {
                                        // App.unblockUI();
                                        }
                                }); //ajax

                        }
                        }
                },
                fill: {
                type: 'gradient',
                        gradient: {
                        shade: 'light',
                                type: "horizontal",
                                shadeIntensity: 0.25,
                                gradientToColors: undefined,
                                inverseColors: true,
                                opacityFrom: 0.95,
                                opacityTo: 0.95,
                                stops: [50, 0, 100]
                        },
                },
                colors: colors,
                plotOptions: {
                bar: {
                dataLabels: {
                position: 'top', // top, center, bottom
                },
                        columnWidth: '35%',
                        distributed: true,
                        endingShape: 'rounded'
                }
                },
                dataLabels: {
                enabled: true,
                        formatter: function (val) {
                        return val;
                        },
                        offsetY: - 20,
                        style: {
                        fontSize: '12px',
                                colors: ["#304758"]
                        }
                },
                legend: {
                show: false
                },
                xaxis: {
                categories: [
<?php
if (!empty($taskArr)) {
    foreach ($taskArr as $title) {
        ?>
                        "<?php echo e($title); ?>",
        <?php
    }
}
?>

                ],
                        labels: {
                        rotate: - 55,
                                style: {
                                colors: colors,
                                        fontSize: '12px'
                                }
                        }
                },
                id: [
<?php foreach ($taskArr as $taskId => $title) { ?>
                    "<?php echo e($taskId); ?>",
<?php } ?>
                ],
        };
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();

        </script>
        <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\contactDirectory\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>