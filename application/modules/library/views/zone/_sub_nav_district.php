<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Lib.Zone.District.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/district/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('show_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Lib.Zone.District.Create')) : ?>
        <a class="<?php echo $this->uri->segment(4) == 'district_create' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/district/library/district_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('create_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

