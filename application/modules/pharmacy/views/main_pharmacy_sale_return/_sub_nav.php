<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('Pharmacy.MSaleReturn.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/main_pharmacy_sale_return/pharmacy/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
   
</div>
