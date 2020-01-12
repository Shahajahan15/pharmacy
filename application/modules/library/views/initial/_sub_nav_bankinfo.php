<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Library.Bank.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_banklist' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/bank_info/library/show_banklist') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Library.Bank.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'bank_create' || $this->uri->segment(4) == 'bank_edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/bank_info/library/bank_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

