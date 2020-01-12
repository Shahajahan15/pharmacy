
<div class="breadcrumb" >
    <?php if ($this->auth->has_permission('Report.UserCollection.Print')) : ?>
        <button type="button" class="btn btn-info search-print" href="<?php echo site_url(SITE_AREA .'/pharmacy_money_receipt_cash_collection/report/index') ?>">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </button>
    <?php endif; ?>
</div>
