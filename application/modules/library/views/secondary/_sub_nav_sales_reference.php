<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Lib.Reference.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/sales_reference/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    <?php if ($this->auth->has_permission('Lib.Reference.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'ref_create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/sales_reference/library/ref_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

