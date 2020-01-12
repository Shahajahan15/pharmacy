<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Library.Shift.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/shift/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('show_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Library.Shift.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'shift_create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/shift/library/shift_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('create_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

