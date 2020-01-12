<?php
$validation_errors = validation_errors();

if ($validation_errors) :
    ?>
    <div class="alert alert-block alert-error fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <h4 class="alert-heading">Please fix the following errors:</h4>
        <?php echo $validation_errors; ?>
    </div>
    <?php
endif;
//print_r($customer_details);
//print_r($supplier_details);
//die;
?>


<div class="box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal pharmacy-subCustomer-create-form"'); ?>

    <fieldset>
        <legend>Sub Customer Add</legend> 
        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 c_name">
            <div class="<?php echo form_error('pharmacy_sub_customer_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <label><?php e(lang('pharmacy_sub_customer_name')); ?><span
                        class="required"><?php e(lang('bf_star_mark')); ?></span></label>
                        <input tabindex="2" class="form-control"  id='pharmacy_sub_customer_name' name='pharmacy_sub_customer_name' type='text' required="" placeholder="Sub Customer Name"
                        value="<?php echo set_value('pharmacy_sub_customer_name'); ?>">
                    </div>
                </div>
            </div>
    <!--     <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 c_name">
            <div class="<?php echo form_error('pharmacy_sub_customer_add') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <label><?php e(lang('pharmacy_sub_customer_name_add')); ?><span
                            class="required"><?php e(lang('bf_star_mark')); ?></span></label>
                            <input tabindex="2" class="form-control"  id='pharmacy_sub_customer_name' name='pharmacy_sub_customer_name_add' type='text' required=""
                           value="<?php echo set_value('pharmacy_sub_customer_name_add'); ?>">
                </div>
            </div>
        </div> -->
        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 c_name">
            <div class="<?php echo form_error('pharmacy_sub_customer_name_phn') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <label><?php e(lang('pharmacy_sub_customer_name_phn')); ?><span
                        class="required"><?php e(lang('bf_star_mark')); ?></span></label>
                        <input tabindex="2" class="form-control"  id='pharmacy_sub_customer_name_phn' name='pharmacy_sub_customer_name_phn' type='text' required="" placeholder="Phone"
                        value="<?php echo set_value('pharmacy_sub_customer_name_phn'); ?>">
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="text-center">
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <button type="reset" class="btn btn-warning">Reset</button>

                </div> 
            </div>
        </fieldset>

        <?php echo form_close(); ?>

    </div>  