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
        <legend>Company Add</legend>
        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
            <div class="<?php echo form_error('pharmacy_company_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_company_name') . lang('bf_form_label_required'), 'store_company_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_company_name' type='text' name='pharmacy_company_name'
                               maxlength="100"
                               value="<?php echo set_value('pharmacy_company_name', isset($company_details['company_name']) ? $company_details['company_name'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_company_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('company_name'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
            <div class="<?php echo form_error('pharmacy_company_ceo_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_company_ceo_name') . lang('bf_form_label_required'), 'store_company_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_company_ceo_name' type='text' name='pharmacy_company_ceo_name'
                               maxlength="100"
                               value="<?php echo set_value('pharmacy_company_ceo_name', isset($company_details['company_ceo_name']) ? $company_details['company_ceo_name'] : ''); ?>"
                               placeholder="<?php echo lang('pharmacy_company_ceo_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('company_ceo_name'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="form-group <?php echo form_error('pharmacy_company_code') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_company_code') . lang('bf_form_label_required'), 'pharmacy_company_code', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_company_code' type='text' name='pharmacy_company_code'
                           maxlength="100"
                           value="<?php echo set_value('pharmacy_company_code', isset($company_details['company_code']) ? $company_details['company_code'] : ''); ?>"
                           placeholder="<?php echo lang('pharmacy_company_code') ?>" required/>
                    <span class='help-inline'><?php echo form_error('company_code'); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xs-8 col-sm-8 col-md-5 col-lg-5">
            <div class="form-group <?php echo form_error('pharmacy_company_location') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_company_location') . lang('bf_form_label_required'), 'store_company_location', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_company_location' type='text'
                           name='pharmacy_company_location' maxlength="100"
                           value="<?php echo set_value('pharmacy_company_location', isset($company_details['company_location']) ? $company_details['company_location'] : ''); ?>"
                           placeholder="<?php echo lang('pharmacy_company_location') ?>" required/>
                    <span class='help-inline'><?php echo form_error('company_location'); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="form-group <?php echo form_error('pharmacy_company_country') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_company_country') . lang('bf_form_label_required'), 'pharmacy_company_country', array('class' => 'control-label')); ?>
                <div class='control'>
                    <select name="pharmacy_company_country" id="pharmacy_company_country" class="form-control"
                            required="">
                        <option value="1" <?php if (isset($company_details)) {
                            echo(($company_details['company_country'] == 1) ? "selected" : "");
                        } ?>>Bangladesh
                        </option>
                    </select>
                    <span class='help-inline'><?php echo form_error('company_country'); ?></span>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            <div class="form-group <?php echo form_error('pharmacy_company_status') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_company_status'), 'pharmacy_company_status', array('class' => 'control-label')); ?>
                <div class='control'>
                    <select name="pharmacy_company_status" id="pharmacy_company_status" class="form-control">
                        <option value="1" <?php if (isset($company_details)) {
                            echo(($company_details['status'] == 1) ? "selected" : "");
                        } ?>>Active
                        </option>
                        <option value="0" <?php if (isset($company_details)) {
                            echo(($company_details['status'] == 0) ? "selected" : "");
                        } ?>>Inactive
                        </option>
                    </select>
                    <span class='help-inline'><?php echo form_error('pharmacy_company_status'); ?></span>
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
