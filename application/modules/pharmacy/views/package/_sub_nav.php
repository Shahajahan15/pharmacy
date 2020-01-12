<div class="breadcrumb" >
	<a class="<?php echo $this->uri->segment(4) == 'index' ? 'btn btn-primary disabled' : 'btn btn-primary' ?>" href="<?php echo site_url(SITE_AREA .'/pharmacy_package/pharmacy/index') ?>" id="list"><?php echo "&nbsp&nbsp".'List'."&nbsp&nbsp"; ?></a>

        <a class="<?php if($this->uri->segment(4) == 'create' || $this->uri->segment(4) == 'edit') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>" href="<?php echo site_url(SITE_AREA .'/pharmacy_package/pharmacy/create') ?>" id="create_new"><?php echo "&nbsp&nbsp".'New'."&nbsp&nbsp"; ?></a>

   
</div>

