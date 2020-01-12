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
//print_r($company_details);
//print_r($supplier_details);
//die;
if (isset($records)) {
    $records = (array) $records;
}
$id = isset($records['id']) ? $records['id'] : '';
?>


<div class="box-primary row">
<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal pharmacy-supplier-create-form"'); ?>
 
    		<fieldset>
    			<legend>Supplier Add</legend> 
    			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">     
            <div class="<?php echo form_error('pharmacy_supplier_company_name') ? 'error' : ''; ?>">
                <div class='form-group'>
                    <?php echo form_label(lang('pharmacy_supplier_company_name') . lang('bf_form_label_required'), 'pharmacy_supplier_company_name', array('class' => 'control-label ')); ?>
                    <div class='control'>
                    	<select name="company_id" id="company_id" class="form-control">
                    		<option value=""><?php echo lang('select_a_one'); ?></option>
                    		<?php foreach ($company_details as $row){
                                  echo "<option value='" . $row->id . "'";
                            if (isset($records)) {
                                if ($row->id == $records['company_id']) {
                                    echo "selected";
                                }
                            }
                            echo ">" . $row->company_name . "</option>";
                            }
                            ?>
                    	</select>
                        <span class='help-inline'><?php echo form_error('pharmacy_supplier_company_name'); ?></span>
                    </div>
                </div>
            </div>		
            </div> 
    			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3"> 
            <div class="form-group <?php echo form_error('pharmacy_supplier_name') ? 'error' : ''; ?>">
<?php echo form_label(lang('pharmacy_supplier_name') . lang('bf_form_label_required'), 'pharmacy_supplier_name', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_supplier_name' type='text' name='pharmacy_supplier_name' maxlength="300" value="<?php echo set_value('pharmacy_supplier_name', isset($records['supplier_name']) ? $records['supplier_name'] : ''); ?>" placeholder="<?php echo lang('pharmacy_supplier_name') ?>" required/>
                    <span class='help-inline'><?php echo form_error('pharmacy_supplier_name'); ?></span>
                </div>
            </div>
			</div> 
    			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">   
            <div class="<?php echo form_error('pharmacy_supplier_code') ? 'error' : ''; ?>">
                <div class='form-group'>
<?php echo form_label(lang('pharmacy_supplier_code') . lang('bf_form_label_required'), 'pharmacy_supplier_code', array('class' => 'control-label')); ?>
                    <div class='control'>                                                                                                                                                                                              
                        <input class="form-control" id='pharmacy_supplier_code' type='number' name='pharmacy_supplier_code' maxlength="100" value="<?php echo set_value('pharmacy_supplier_code', isset($records['supplier_code']) ? $records['supplier_code'] : ''); ?>"  placeholder="<?php echo lang('pharmacy_supplier_code') ?>" required/>
                        <span class='help-inline'><?php echo form_error('pharmacy_supplier_code'); ?></span>
                    </div>
                </div>
            </div>
			</div>
			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3"> 
            <div class="form-group <?php echo form_error('pharmacy_supplier_contact_person') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_supplier_contact_person') . lang('bf_form_label_required'), 'pharmacy_supplier_contact_person', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_supplier_contact_person' type='text' name='pharmacy_supplier_contact_person' maxlength="300" value="<?php echo set_value('pharmacy_supplier_contact_person', isset($records['contact_person']) ? $records['contact_person'] : ''); ?>" placeholder="<?php echo lang('pharmacy_supplier_contact_person') ?>" required/>
                    <span class='help-inline'><?php echo form_error('pharmacy_supplier_contact_person'); ?></span>
                </div>
            </div>		
            </div>	
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3"> 	
            <div class="form-group <?php echo form_error('pharmacy_supplier_contact_no_1') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_supplier_contact_no_1') . lang('bf_form_label_required'), 'pharmacy_supplier_contact_no_1', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_supplier_contact_no_1' type='text' name='pharmacy_supplier_contact_no_1' maxlength="300" value="<?php echo set_value('pharmacy_supplier_contact_no_1', isset($records['contact_no1']) ? $records['contact_no1'] : ''); ?>" placeholder="<?php echo lang('pharmacy_supplier_contact_no_1') ?>" required/>
                    <span class='help-inline'><?php echo form_error('pharmacy_supplier_contact_no_1'); ?></span>
                </div>
            </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3"> 
            <div class="form-group <?php echo form_error('bf_form_label_required') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_supplier_contact_no_2') . lang('bf_form_label_required'), 'pharmacy_supplier_contact_no_2', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_supplier_contact_no_2' type='text' name='pharmacy_supplier_contact_no_2' maxlength="300" value="<?php echo set_value('pharmacy_supplier_contact_no_2', isset($records['contact_no2']) ? $records['contact_no2'] : ''); ?>" placeholder="<?php echo lang('pharmacy_supplier_contact_no_2') ?>" required/>
                    <span class='help-inline'><?php echo form_error('bf_form_label_required'); ?></span>                                                                                                                                                               
                </div>
            </div> 	
			</div>
			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3"> 
            <div class="form-group <?php echo form_error('pharmacy_supplier_email') ? 'error' : ''; ?>">
                <?php echo form_label(lang('pharmacy_supplier_email') . lang('bf_form_label_required'), 'pharmacy_supplier_email', array('class' => 'control-label')); ?>
                <div class='control'>
                    <input class="form-control" id='pharmacy_supplier_email' type='text' name='pharmacy_supplier_email' maxlength="300" value="<?php echo set_value('pharmacy_supplier_email', isset($records['email']) ? $records['email'] : ''); ?>" placeholder="<?php echo lang('pharmacy_supplier_email_place') ?>" required/>
                    <span class='help-inline'><?php echo form_error('pharmacy_supplier_email'); ?></span>
                </div>
            </div>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
			    	<div class="form-group <?php echo form_error('pharmacy_supplier_status') ? 'error' : ''; ?>">
				  <?php echo form_label(lang('pharmacy_supplier_status'),'pharmacy_supplier_status',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<select name="pharmacy_supplier_status" id="pharmacy_supplier_status" class="form-control">
						<option value="1" <?php if(isset($records)){echo (($records['status'] == 1)? "selected" : "");} ?>>Active</option>
						<option value="0" <?php if(isset($records)){echo (($records['status'] == 0)? "selected" : "");} ?>>Inactive</option>
					</select>
                    <span class='help-inline'><?php echo form_error('pharmacy_supplier_status'); ?></span>
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