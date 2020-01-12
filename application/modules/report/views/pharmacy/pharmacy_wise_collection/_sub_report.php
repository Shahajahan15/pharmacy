<div class="breadcrumb" >
    <?php if ($this->auth->has_permission('Report.pharmacywise.Print')) : ?>
        <button type="button" class="btn btn-info search-print" onclick="printDiv('pcash_id')">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </button>
    <?php endif; ?>
</div>













