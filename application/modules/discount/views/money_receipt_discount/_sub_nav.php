<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Discount.MoneyReceipt.View')) :?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/money_receipt_discount/discount/show_list') ?>" id="show_list"><?php echo "&nbsp&nbspList&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    
    <?php  if ($this->auth->has_permission('Discount.MoneyReceipt.Add')) : ?>
        <a class="<?php echo $this->uri->segment(4) == 'add' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/money_receipt_discount/discount/add') ?>" id="add"><?php echo "&nbsp&nbspNew&nbsp&nbsp"; ?></a>
    <?php  endif; ?>

    <?php  if ($this->auth->has_permission('Discount.MoneyReceipt.Approve')) : ?>
        <a class="<?php echo $this->uri->segment(4) == 'pending_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/money_receipt_discount/discount/pending_list') ?>" id="pending_list"><?php echo "&nbsp&nbspPending&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    <?php  if ($this->auth->has_permission('Discount.MoneyReceipt.Collection')) : ?>
        <a class="<?php echo $this->uri->segment(4) == 'collection_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/money_receipt_discount/discount/collection_list') ?>" id="collection_list"><?php echo "&nbsp&nbspCollection&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
   
</div>