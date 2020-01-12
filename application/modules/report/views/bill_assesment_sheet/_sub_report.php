<div class="breadcrumb" >
    <?php if ($this->auth->has_permission('Report.BillAssesmentSheet.Print')) : ?>
        <a class="btn btn-info" id="export-print"  onclick="printDiv('admission_bill')">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </a>
    <?php endif; ?>
    <?php if ($this->auth->has_permission('Report.BillAssesmentSheet.Pdf')) : ?>
        <a class="btn btn-primary" href="<?php echo '#'; //site_url(SITE_AREA .'/patient_booth_serial/report/pdf') ?>" id="export-pdf">
            <i class="fa fa-circle-o"></i> <?php echo "&nbsp&nbsp"."PDF"."&nbsp&nbsp"; ?>
        </a>
    <?php endif; ?>

    <?php if ($this->auth->has_permission('Report.BillAssesmentSheet.Excel')) : ?>
        <a class="btn btn-warning" href="<?php echo '#'; //site_url(SITE_AREA .'/patient_booth_serial/report/export') ?>" id="export-excel">
            <i class="fa fa-table"></i> <?php echo "&nbsp&nbsp"."Excel"."&nbsp&nbsp"; ?>
        </a>
    <?php endif; ?>
</div>