<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('HRM.punch_card_reader_configure.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/punch_card_reader_configure/hrm/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    
   
</div>

