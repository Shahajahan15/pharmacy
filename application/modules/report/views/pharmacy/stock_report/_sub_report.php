<div class="breadcrumb" >
    <?php if ($this->auth->has_permission('Report.PharStock.Print')) : ?>
        <button type="button" class="btn btn-info search-print" href="<?php echo site_url(SITE_AREA .'/pharmacy_wise_stock/report/index') ?>">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </button>
    <?php endif; ?>
</div>





















