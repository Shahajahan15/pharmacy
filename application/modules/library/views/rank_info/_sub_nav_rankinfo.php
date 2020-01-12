<div class="breadcrumb" >
	<?php if ($this->auth->has_permission('Library.Rank.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_ranklist' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/rank_info/library/show_ranklist') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    
    <?php if ($this->auth->has_permission('Library.Rank.Create')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'rank_create' || $this->uri->segment(4) == 'rank_edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/rank_info/library/rank_create') ?>" id="create_new"><?php echo "&nbsp&nbsp".lang('bf_action_new')."&nbsp&nbsp"; ?></a>
    <?php endif; ?>
   
</div>

