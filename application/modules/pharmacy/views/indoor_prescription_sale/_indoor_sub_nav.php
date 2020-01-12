<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('Pharmacy.IP.Sale.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/indoor_prescription_sale/pharmacy/show_list') ?>" id="list"><?php echo "&nbsp&nbspPending List&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
   
</div>
