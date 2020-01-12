<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Lib.Exam.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/surgeon_doctor/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    <?php if ($this->auth->has_permission('Lib.Exam.Create')) : ?>
        <a class="<?php echo $this->uri->segment(4) == 'exam_create' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/surgeon_doctor/library/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>


