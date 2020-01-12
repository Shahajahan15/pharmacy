<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('Pharmacy.Indoor.Sale.Add')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'customer_sale' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/main_pharmacy_sale/pharmacy/customer_sale') ?>" id="create_new"><?php echo "&nbsp&nbsp"."New"."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    <?php  if ($this->auth->has_permission('Pharmacy.Indoor.Sale.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'main_pharmacy_sale_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/main_pharmacy_sale/pharmacy/main_pharmacy_sale_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
   
</div>
