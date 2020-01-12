<div class="breadcrumb">
    <?php if ($this->auth->has_permission('Pharmacy.PurchaseReq.Create')) : ?>
        <a class="<?php if ($this->uri->segment(4) == 'create') echo 'btn btn-primary disabled'; else  echo 'btn btn-primary'; ?>"
           href="<?php echo site_url(SITE_AREA . '/direct_pharmacy_purchase/pharmacy/create') ?>"
           id="create_new"><?php echo "&nbsp&nbsp" . lang('bf_action_new') . "&nbsp&nbsp"; ?></a>
    <?php endif; ?>
    <?php if ($this->auth->has_permission('Pharmacy.PurchaseReq.Create')) : ?>
        <a class="<?php if ($this->uri->segment(4) == 'show_list') echo 'btn btn-info disabled'; else  echo 'btn btn-info'; ?>"
           href="<?php echo site_url(SITE_AREA . '/direct_pharmacy_purchase/pharmacy/show_list') ?>"
           id="list"><?php echo "&nbsp&nbsp List &nbsp&nbsp"; ?></a>
    <?php endif; ?>
</div>