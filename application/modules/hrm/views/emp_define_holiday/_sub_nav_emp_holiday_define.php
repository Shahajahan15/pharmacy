<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('HRM.Employee_Define_Holiday.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/employee_define_holiday/hrm/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('HRM.Employee_Define_Holiday.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'emp_holiday_define') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/employee_define_holiday/hrm/emp_holiday_define') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

