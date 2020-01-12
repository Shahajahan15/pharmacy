<?php
//extract($sendData);
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

if (isset($company_details)) {
    $company_details = (array)$company_details;
}
$id = isset($company_details['id']) ? $company_details['id'] : '';
?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Sub Customer Add</legend>
        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
            <div class="<?php echo form_error('customer_id') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_customer_name') . lang('bf_form_label_required'), 'store_company_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    <select name="customer_id" id="customer_id" class="form-control"
                                required="">
                            <option value=""><?php echo lang('pharmacy_sub_customer_select_one'); ?></option>
                            <?php foreach ($customer as $row) : ?>
                                <option value="<?php echo $row->id; ?>"<?php if(isset($company_details)) 
                                { echo (($company_details['customer_id'] == $row->id) ? "selected" : ''); }?>>
                                <?php echo $row->customer_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('customer_id'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
            <div class="<?php echo form_error('sub_customer_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_sub_customer_name') . lang('bf_form_label_required'), 'store_company_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='sub_customer_name' type='text' name='sub_customer_name'
                               maxlength="100"
                               value="<?php echo set_value('sub_customer_name', isset($company_details['sub_customer_name']) ? $company_details['sub_customer_name'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_sub_customer_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('customer_id'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group <?php echo form_error('sub_customer_phone') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_sub_customer_phone') . lang('bf_form_label_required'), 'sub_customer_phone', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='sub_customer_phone' type='number' name='sub_customer_phone'
                           maxlength="100"
                           value="<?php echo set_value('sub_customer_phone', isset($company_details['sub_customer_phone']) ? $company_details['sub_customer_phone'] : ''); ?>"
                           placeholder="<?php echo lang('pharmacy_sub_customer_phone') ?>" required/>
                    <span class='help-inline'><?php echo form_error('customer_id'); ?></span>
                </div>
            </div>
        </div>
        
        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_sub_customer_status'), 'status', array('class' => 'control-label')); ?>
                <div class='control'>
                    <select name="status" id="status" class="form-control">
                        <option value="1" <?php if (isset($company_details)) {
                            echo(($company_details['status'] == 1) ? "selected" : "");
                        } ?>>Active
                        </option>
                        <option value="0" <?php if (isset($company_details)) {
                            echo(($company_details['status'] == 0) ? "selected" : "");
                        } ?>>Inactive
                        </option>
                    </select>
                    <span class='help-inline'><?php echo form_error('status'); ?></span>
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm"
                       value="<?php echo lang('bf_action_save'); ?>"/>
                &nbsp;
                <button type="reset" class="btn btn-warning">Reset</button>

            </div>
    </fieldset>

    <?php echo form_close(); ?>

</div>
