<div class="breadcrumb" >
    <?php if ($this->auth->has_permission('Report.pharmacywise.Print')) : ?>
        <button type="button" class="btn btn-info search-print" onclick="printDiv('pd_id')">
            <i class="fa fa-print"></i> <?php echo "&nbsp&nbsp"."Print"."&nbsp&nbsp"; ?>
        </button>
    <?php endif; ?>
	<a href="<?php echo site_url('admin/pharmacy_wise_collection/report/index'); ?>"> 
		<button type="button" class="btn btn-info">
	       <i class="fa fa-arrow-left"></i> &nbsp;&nbsp;Back&nbsp;&nbsp;       
	   </button>
	</a>
</div>













