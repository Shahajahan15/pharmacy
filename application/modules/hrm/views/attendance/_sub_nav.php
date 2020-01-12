<div class="breadcrumb" >

	<?php  //if ($this->auth->has_permission('hrm.Attendance.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/attendance/hrm/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".'List'."&nbsp&nbsp"; ?></a>
    <?php  //endif; ?>

    
	<?php  //if ($this->auth->has_permission('hrm.Attendance.Create')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'take_attendance' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/attendance/hrm/take_attendance') ?>" id="list"><?php echo "&nbsp&nbsp".'Create'."&nbsp&nbsp"; ?></a>
    <?php  //endif; ?>

</div>
