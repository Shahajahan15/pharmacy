<div class="breadcrumb" >

	<?php  if ($this->auth->has_permission('Hrm.Salary.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/employee_salary/hrm/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".'List'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>

    
	<?php  if ($this->auth->has_permission('Hrm.Salary.Add')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'create' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/employee_salary/hrm/create') ?>" id="list"><?php echo "&nbsp&nbsp".'Create'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>

</div>