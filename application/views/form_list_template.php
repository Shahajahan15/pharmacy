<style>
    .mControl { max-width: 150px; min-width: 100px;}
    .fieldset{ margin: 0px; padding: 0px;}
    .panel-heading { padding: 5px 10px !important}
    .form-group{
        width:initial;
    }
</style>

<div class="row">
    <div id="bf-form-view" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-form-action-url="<?php echo isset($form_action_url) ? $form_action_url : '' ?>">
        <?php //echo form_open(isset($form_action_url) ? $form_action_url : $this->uri->uri_string(), ' name="search_box" class="nform-horizontal"'); ?>
            <div id="bf-form-section">
                <div class="text-center">
                    <i class="fa fa-spin fa-spinner fa-5x"></i>
                </div>
            </div>
        <?php //echo form_close() ?>
    </div>

    <div id="bf-list-view" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-list-action-url="<?php echo isset($list_action_url) ? $list_action_url : '' ?>">
        <?php echo form_open(isset($list_action_url) ? $list_action_url : $this->uri->uri_string(), ' name="search_box", class="common_search_form"'); ?>
            <?php if(isset($search_box)) $this->load->view('search_panel');  ?>
            <div id="messages" style="display: none;">
                <div class='alert alert-block fade in notification'><a data-dismiss='alert' class='close' href='#'>×</a>
                    <div id="text"></div>
                </div>
            </div>
            <div id="search_result">
                <div class="text-center">
                    <i class="fa fa-spin fa-spinner fa-5x"></i>
                </div>
            </div>
        <?php echo form_close() ?>
    </div>
</div>