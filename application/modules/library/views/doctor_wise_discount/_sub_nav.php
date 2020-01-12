<div class="breadcrumb" >

	<?php  if ($this->auth->has_permission('Lib.DoctorDiscount.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/doctor_wise_discount/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>

    <?php  if ($this->auth->has_permission('Lib.DoctorDiscount.Add')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'create' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/doctor_wise_discount/library/create') ?>" id="list"><?php echo "&nbsp&nbsp".'Create'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>   

    <?php  if ($this->auth->has_permission('Lib.DoctorDiscount.Approve')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'approved_pending_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/doctor_wise_discount/library/approved_pending_list') ?>" id="list"><?php echo "&nbsp&nbsp".'Pending List'."&nbsp&nbsp"; ?></a>
    <?php  endif; ?>   
   
   
</div>

