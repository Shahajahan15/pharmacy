<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('Pharmacy.Sub.Sale.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'customer_sale' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/sub_pharmacy_sale/pharmacy/customer_sale') ?>" id="create_new"><?php echo "&nbsp&nbsp"."New"."&nbsp&nbsp"; ?></a>

	<a class="<?php echo $this->uri->segment(4) == 'sub_pharmacy_sale_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/sub_pharmacy_sale/pharmacy/sub_pharmacy_sale_list') ?>" id="create_new"><?php echo "&nbsp&nbsp"."List"."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
   
</div>
