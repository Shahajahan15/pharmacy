<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('pharmacy.purchase.return.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'receive_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/product_return/pharmacy/receive_list') ?>" id="list"><?php echo "&nbsp&nbsp"."Receved List"."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    
   <?php  if ($this->auth->has_permission('pharmacy.PReturn.confirm.View')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'return_request_list') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/product_return/pharmacy/return_request_list') ?>" id="create_new"><?php echo "&nbsp&nbsp"."Confirm List"."&nbsp&nbsp"; ?></a>
    <?php endif; ?> 
   
</div>
