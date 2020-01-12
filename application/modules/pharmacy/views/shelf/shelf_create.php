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

if (isset($shelf_details)) {
    $shelf_details = (array) $shelf_details;
}
$id = isset($shelf_details['id']) ? $shelf_details['id'] : '';
?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
    	<legend>Shelf Add</legend>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="<?php echo form_error('company_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_shelf_name') . lang('bf_form_label_required'), 'pharmacy_shelf_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_shelf_name' type='text' name='pharmacy_shelf_name' maxlength="100" value="<?php echo set_value('pharmacy_shelf_name', isset($shelf_details['self_name']) ? $shelf_details['self_name'] : ''); ?>"  placeholder="<?php echo lang('pharmacy_shelf_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_shelf_name'); ?></span>
                    </div>
                </div>
            </div>		
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">     
            <div class="<?php echo form_error('pharmacy_shelf_des') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_shelf_des'), 'pharmacy_shelf_des', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_shelf_des' type='text' name='pharmacy_shelf_des' value="<?php echo set_value('pharmacy_shelf_des', isset($shelf_details['self_desc']) ? $shelf_details['self_desc'] : ''); ?>"  placeholder="<?php echo lang('pharmacy_shelf_des') ?>" />
                        <span class='help-inline'><?php echo form_error('pharmacy_shelf_des'); ?></span>
                    </div>
                </div>
            </div>		
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
			    	<div class="form-group <?php echo form_error('pharmacy_shelf_status') ? 'error' : ''; ?>">
				  <?php echo form_label(lang('pharmacy_shelf_status'),'store_company_status',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<select name="pharmacy_shelf_status" id="pharmacy_shelf_status" class="form-control">
						<option value="1" <?php if(isset($shelf_details)){echo (($shelf_details['status'] == 1)? "selected" : "");} ?>>Active</option>
						<option value="0" <?php if(isset($shelf_details)){echo (($shelf_details['status'] == 0)? "selected" : "");} ?>>Inactive</option>
					</select>
                    <span class='help-inline'><?php echo form_error('pharmacy_shelf_status'); ?></span>
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
            </fieldset>

    <?php echo form_close(); ?>

</div>
