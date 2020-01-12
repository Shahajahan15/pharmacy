<?php $__env->startSection('content-header'); ?>
    <h1>Dashboard</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <section class="content">
            <div class="row">

                <div class="col-lg-3 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-plus bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-green">TK: <?php echo e($cashIn); ?></small></h4> <span class="tile-label-dash">Cash In</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-user bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua"><?php echo e($opd); ?></small></h4> <span class="tile-label-dash">OPD Patients</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-user bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua"><?php echo e($ipd); ?></small></h4> <span class="tile-label-dash">IPD Patients</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-user bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua"><?php echo e($emergency); ?></small></h4> <span class="tile-label-dash">Emergency Patients</span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </section>
    </div>
    <hr>

    <div class="row panel">
        <div class="col-lg-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Counter Wise Cash Collection</li>
                </ul>
                <canvas id="cash_collection_chart" ></canvas>
            </div>
        </div>        
        <div class="col-lg-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Hospital Bed Status</li>
                </ul>
                <canvas id="bed_chart" ></canvas>
            </div>
        </div>
        

        <!-- Cash collection bar chart in days & monthly -->
        <div class="col-lg-6 hide">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Daily Cash collection</li>
                </ul>
                <canvas id="cash_chart_days" ></canvas>
            </div>
        </div>
        <div class="col-lg-6 hide">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Monthly Cash collection</li>
                </ul>
                <canvas id="cash_chart_months" ></canvas>
            </div>
        </div>

        <div class="col-lg-6 hide">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Daily Ticket Sales</li>
                </ul>
                <canvas id="ticket_chart_days" ></canvas>
            </div>
        </div>
        <div class="col-lg-6 hide">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Monthly Ticket Sales</li>
                </ul>
                <canvas id="ticket_chart_months" ></canvas>
            </div>
        </div>

        <div class="col-lg-6 hide">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Daily Admitted Patients</li>
                </ul>
                <canvas id="patient_chart_days" ></canvas>
            </div>
        </div>
        <div class="col-lg-6 hide">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Monthly Admitted Patients</li>
                </ul>
                <canvas id="patient_chart_months" ></canvas>
            </div>
        </div>

    </div>

<script>
    var cashDailyChartData = <?php echo $cashDailyChartData; ?>;
    var cashMonthlyChartData = <?php echo $cashMonthlyChartData; ?>;
    var ticketDailyChartData = <?php echo $ticketDailyChartData; ?>;
    var ticketMonthlyChartData = <?php echo $ticketMonthlyChartData; ?>;
    var patientDailyChartData = <?php echo $patientDailyChartData; ?>;
    var patientMonthlyChartData = <?php echo $patientMonthlyChartData; ?>;
    var bedStatus = <?php echo json_encode($bedStatus); ?>;
    var cashCollection = <?php echo $cashCollection; ?>;
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>