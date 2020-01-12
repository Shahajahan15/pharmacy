<style>
    .mControl { max-width: 150px; min-width: 100px;}
    .fieldset{ margin: 0px; padding: 0px;}
    .panel-heading { padding: 5px 10px !important}
    .form-group{
        width:initial;
    }
</style>

<div class="row">
    <?php echo form_open($this->uri->uri_string(), ' name="search_box", class="common_search_form"'); ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if(isset($search_box)) $this->load->view('search_panel');  ?>
        <div id="messages" style="display: none;">
            <div class='alert alert-block fade in notification'><a data-dismiss='alert' class='close' href='#'>×</a><div id="text"></div></div>
        </div>
        <div id="search_result">

            <?php
            if(isset($list_view)) {
                echo $this->load->view($list_view);
            }
            ?>
        </div>
    </div>
    <?php echo form_close() ?>
</div>