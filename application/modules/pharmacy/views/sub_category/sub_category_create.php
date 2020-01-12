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

if (isset($sub_category_details)) {
    $sub_category_details = (array) $sub_category_details;
}
$id = isset($sub_category_details['id']) ? $sub_category_details['id'] : '';
?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
    	<legend>Sub Category Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
            <div class="<?php echo form_error('company_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_sub_category_name') . lang('bf_form_label_required'), 'pharmacy_sub_category_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                        <input class="form-control" id='pharmacy_sub_category_name' type='text' name='pharmacy_sub_category_name' maxlength="100" value="<?php echo set_value('pharmacy_sub_category_name', isset($sub_category_details['subcategory_name']) ? $sub_category_details['subcategory_name'] : ''); ?>"  placeholder="<?php echo lang('pharmacy_sub_category_name') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_sub_category_name'); ?></span>
                    </div>
                </div>
            </div>		
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    				<div class="form-group <?php echo form_error('pharmacy_category_name') ? 'error' : ''; ?>">
					<?php echo form_label(lang('pharmacy_category_name') . lang('bf_form_label_required'), 'pharmacy_category_name', array('class' => 'control-label ')); ?>
                <div class='control'>  
                    <select  name="pharmacy_category_name" id="pharmacy_category_name" class="form-control" title="<?php echo lang('pharmacy_category_name') ?>" required>
                        <option value=""><?php echo lang('bf_msg_selete_one') ?></option>
                        <?php
                        foreach ($category_name as $row) {
                            echo "<option value='" . $row->id . "'";
                            if (isset($sub_category_details)) {
                                if ($row->id == $sub_category_details['category_id']) {
                                    echo "selected";
                                }
                            }
                            echo ">" . $row->category_name . "</option>";
                        }
                        ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('storeCompanyId'); ?></span>
                </div>
            </div>
    			</div>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
			    	<div class="form-group <?php echo form_error('pharmacy_sub_category_status') ? 'error' : ''; ?>">
				  <?php echo form_label(lang('pharmacy_sub_category_status'),'pharmacy_sub_category_status',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<select name="pharmacy_sub_category_status" id="pharmacy_sub_category_status" class="form-control">
						<option value="1" <?php if(isset($sub_category_details)){echo (($sub_category_details['status'] == 1)? "selected" : "");} ?>>Active</option>
						<option value="0" <?php if(isset($sub_category_details)){echo (($sub_category_details['status'] == 0)? "selected" : "");} ?>>Inactive</option>
					</select>
                    <span class='help-inline'><?php echo form_error('pharmacy_sub_category_status'); ?></span>
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
