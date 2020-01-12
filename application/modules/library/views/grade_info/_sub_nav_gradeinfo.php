<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Library.Grade.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_gradelist' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/grade_info/library/show_gradelist') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Library.Grade.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'grade_create' || $this->uri->segment(4) == 'grade_edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/grade_info/library/grade_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

