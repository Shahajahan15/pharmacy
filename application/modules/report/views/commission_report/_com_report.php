<div class="breadcrumb" >
    <?php //if ($this->auth->has_permission('Report.Commission.Print')) : ?>
        <a class="btn btn-info"  id="export-print"onclick="printDiv('print_id')">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </a>
    <?php// endif; ?>

        <a class="btn btn-primary" href="<?php echo site_url(SITE_AREA .'/commission_report/report/index') ?>" id="export-pdf">
            <i class="fa fa-arrow-left"></i> <?php echo "&nbsp&nbsp"."BACK"."&nbsp&nbsp"; ?>
        </a>



</div>