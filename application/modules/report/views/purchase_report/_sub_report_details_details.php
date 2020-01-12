<div class="breadcrumb" >
      <?php if ($this->auth->has_permission('Report.MedicinePayment.Print')) : ?>
        <button type="button" class="btn btn-info search-print" href="<?php echo site_url(SITE_AREA .'/pharmacy_purchase_report/report/per_pay_details_details') ?>">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </button>
       <a href="<?php echo site_url(SITE_AREA .'/pharmacy_purchase_report/report/index') ?>"> <button type="button" class="btn btn-info" >
            <i class="fa fa-arrow-left"></i> <?php echo "&nbsp&nbsp"."Back"."&nbsp&nbsp"; ?>
        </button></a>
      <?php endif; ?>
</div>
