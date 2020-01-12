<div class="breadcrumb" >
	<?php  if ($this->auth->has_permission('Pharmacy.Indoor.Issue.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/indoor_requisition_issue/pharmacy/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".'Requisition List'."&nbsp&nbsp"; ?></a>
    <?php endif; ?>

    <?php  if ($this->auth->has_permission('Pharmacy.Indoor.Issue.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'pending_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/indoor_requisition_issue/pharmacy/pending_list') ?>" id="list"><?php echo "&nbsp&nbsp".'Some Issue List'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>

    <?php  if ($this->auth->has_permission('Pharmacy.Indoor.Issue.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'issue_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/indoor_requisition_issue/pharmacy/issue_list') ?>" id="list"><?php echo "&nbsp&nbsp".'Complete Issue List'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>
    
   
</div>
