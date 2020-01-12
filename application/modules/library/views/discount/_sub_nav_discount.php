<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('Discount.Type.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/discount/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    
    <?php  if ($this->auth->has_permission('Discount.Type.Add')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/discount/library/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".'Overall Create'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    <?php  if ($this->auth->has_permission('Discount.Type.Add')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'specific_create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/discount/library/specific_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".'Specific Create'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>

   
</div>

