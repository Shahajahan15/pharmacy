<?php $__env->startSection('content-header'); ?>
    <h1>Dashboard</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">


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