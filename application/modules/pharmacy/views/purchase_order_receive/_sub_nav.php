<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('pharmacy.order.receive.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'order_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/purchase_order_receive/pharmacy/order_list') ?>" id="list"><?php echo "&nbsp&nbsp".'Order List'."&nbsp&nbsp"; ?></a>
    <?php endif; ?>                                                                                                                                         
    
    <?php if ($this->auth->has_permission('pharmacy.order.receive.View')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'receive_list' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/purchase_order_receive/pharmacy/receive_list') ?>" id=""><?php echo "&nbsp&nbsp".'Received List'."&nbsp&nbsp"; ?></a>
    <?php endif; ?>  
   
</div>

