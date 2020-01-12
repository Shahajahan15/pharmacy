<div class="breadcrumb" >

	
	<?php if ($this->auth->has_permission('pharmacy.Product_purchase.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/purchase_order/pharmacy/show_list') ?>" id="list"><?php echo "&nbsp&nbsp Approved List &nbsp&nbsp"; ?></a>
    <?php endif; ?>

    <?php if ($this->auth->has_permission('pharmacy.Product_purchase.View')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'ordered_list' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/purchase_order/pharmacy/ordered_list') ?>" id=""><?php echo "&nbsp&nbsp Ordered List &nbsp&nbsp"; ?></a>
    <?php endif; ?>


    <?php if ($this->auth->has_permission('pharmacy.Product_purchase.Add')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/purchase_order/pharmacy/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

