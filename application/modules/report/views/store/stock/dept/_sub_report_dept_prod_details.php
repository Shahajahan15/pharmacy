<div class="breadcrumb" >
    <?php if ($this->uri->segment(4) == 'dept_prod_details' && $this->auth->has_permission('Report.StoreStockDept.ProdDetails')) : ?>
        <a class="btn btn-info" href="<?php echo site_url('admin/store_stock_dept/report/all') ?>">
    	    <i class="fa fa-list"></i> <?php echo "&nbsp&nbsp"."List"."&nbsp&nbsp"; ?>
        </a>
    <?php endif; ?>

	<?php if ($this->auth->has_permission('Report.StoreStockDept.ProdDetails.PDF')) : ?>
    	<a class="btn btn-primary" href="<?php echo '#'; //site_url(SITE_AREA .'/patient_booth_serial/report/pdf') ?>" id="export-pdf">
    	    <i class="fa fa-circle-o"></i> <?php echo "&nbsp&nbsp"."PDF"."&nbsp&nbsp"; ?>
        </a>
    <?php endif; ?>

    <?php if ($this->auth->has_permission('Report.StoreStockDept.ProdDetails.Excel')) : ?>
        <a class="btn btn-warning" href="<?php echo '#'; //site_url(SITE_AREA .'/patient_booth_serial/report/export') ?>" id="export-excel">
            <i class="fa fa-table"></i> <?php echo "&nbsp&nbsp"."Excel"."&nbsp&nbsp"; ?>
        </a>
    <?php endif; ?>
</div>