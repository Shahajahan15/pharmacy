<div class="breadcrumb" >
	<?php  //if ($this->auth->has_permission('Discount.Setup.View')) : ?>
	<a class="<?php echo $this->uri->segment(4) == 'show_list' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/patient_discount_setup/library/show_list') ?>" id="list"><?php echo "&nbsp&nbsp".lang('bf_action_list')."&nbsp&nbsp"; ?></a>
    <?php  //endif; ?>
    
    <?php  //if ($this->auth->has_permission('Discount.Setup.Add')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/patient_discount_setup/library/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".'Specific Item'."&nbsp&nbsp"; ?></a>
    <?php  //endif; ?>

    <?php  //if ($this->auth->has_permission('Discount.Setup.Add')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'create_servie_base' || $this->uri->segment(4) == 'ServiceBaseEdit') echo 'btn btn-primary'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/patient_discount_setup/library/create_servie_base') ?>" id="create_new_service_base"><?php echo "&nbsp&nbsp".'Service Base'."&nbsp&nbsp"; ?></a>
    <?php  //endif; ?> 

    <?php  //if ($this->auth->has_permission('Discount.Setup.Approve')) : ?>
        <a class="<?php if($this->uri->segment(4) == 'approve_patient_discount') echo 'btn btn-primary'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/patient_discount_approve/library/approve_patient_discount') ?>" id="create_new_aprove"><?php echo "&nbsp&nbsp".'Approve Pending'."&nbsp&nbsp"; ?></a>
    <?php  //endif; ?>
   
</div>


