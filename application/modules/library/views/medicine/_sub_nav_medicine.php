<div class="breadcrumb" >
	<?php // if ($this->auth->has_permission('Library.Company.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_companylist' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/medicine/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php //endif; ?>
    
    <?php // if ($this->auth->has_permission('Library.Company.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'company_create' || $this->uri->segment(4) == 'company_edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/medicine/library/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('create_new')."&nbsp&nbsp"; ?></a>
    <?php // endif; ?>
   
</div>
