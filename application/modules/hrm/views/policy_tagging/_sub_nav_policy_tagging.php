<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('HRM.Policy_Tracker.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/policy_tracker/hrm/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('HRM.Policy_Tracker.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/policy_tracker/hrm/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

