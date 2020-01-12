<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('HRM.Employee_Insurance.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_employee_insurance_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/employee_insurance/hrm/show_employee_insurance_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('HRM.Employee_Insurance.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'emp_insurance_create') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/employee_insurance/hrm/emp_insurance_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

