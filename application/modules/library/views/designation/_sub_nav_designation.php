<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Lib.Designation.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/designation/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Lib.Designation.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'designation_create' || $this->uri->segment(4) == 'designation_edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/designation/library/designation_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>


