<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('HRM.RosterDP.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'roster_data_processing' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/policy_tracker/hrm/roster_data_processing') ?>" id="list"><?php echo "&nbsp&nbsp Roster Data Processing &nbsp&nbsp"; ?></a>
    <?php endif; ?>

     <?php if ($this->auth->has_permission('HRM.MachineDP.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'machine_data_processing' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/policy_tracker/hrm/machine_data_processing') ?>" id="list"><?php echo "&nbsp&nbsp Machine Data Processing &nbsp&nbsp"; ?></a>
    <?php endif; ?>

    <?php if ($this->auth->has_permission('HRM.AttendanceDP.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'attendance_data_processing' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/policy_tracker/hrm/attendance_data_processing') ?>" id="list"><?php echo "&nbsp&nbsp Attendance Data Processing &nbsp&nbsp"; ?></a>
    <?php endif; ?>
</div>

