<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Lib.Zone.Area.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/area/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('show_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Lib.Zone.Area.Create')) : ?>
        <a class="<?php echo $this->uri->segment(4) == 'area_create' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/area/library/area_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('create_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

