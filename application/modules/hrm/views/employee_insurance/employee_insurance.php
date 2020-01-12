
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

if (isset($employee_insurance_details))
{
	$employee_insurance_details = (array) $employee_insurance_details;
}
$id = isset($employee_insurance_details['EMP_INSURANCE_ID']) ? $employee_insurance_details['EMP_INSURANCE_ID'] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
		
			<div class="col-sm-2 col-md-2 col-lg-2"> 
			</div>
			
            <div class="col-sm-8 col-md-8 col-lg-8">     
                 
				
				<!-- ----------- Employee ---------------- --> 
				<div class="form-group <?php echo form_error('EMP_ID') ? 'error' : ''; ?>">
					<?php echo form_label(lang('EMP_ID'). lang('bf_form_label_required'), 'EMP_ID', array('class' => 'control-label col-sm-4') ); ?>
					<select class="form-control" name="EMP_ID" id="EMP_ID" required="" onchange="getEmployeeInfo(this.value)" tabindex="1">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
							if($employee_list){
								foreach($employee_list as $row){
								$row = (object) $row;
								echo "<option value='".$row->EMP_ID."'";
								
								if(isset($employee_insurance_details['EMP_ID'])){
									if(trim($employee_insurance_details['EMP_ID'])==$row->EMP_ID){echo "selected ";}
								}
								echo ">".$row->EMP_NAME."(ID-".$row->EMP_ID.")"."</option>";
								}
							}
						?>	
					 </select>
					 <span class='help-inline'><?php echo form_error('EMP_ID'); ?></span>
				</div>
								
				<!-- ----------- Bank  ------------ --> 
				<div class="form-group <?php echo form_error('BANK_ID') ? 'error' : ''; ?>">
					<?php echo form_label(lang('BANK_ID'). lang('bf_form_label_required'), 'BANK_ID', array('class' => 'control-label col-sm-4') ); ?>					
					<select class="form-control" name="BANK_ID" id="BANK_ID" required="" tabindex="2">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
							if($bank_list){
								foreach($bank_list as $row){
								$row = (object) $row;
								echo "<option value='".$row->ID."'";							
								if(isset($employee_insurance_details['BANK_ID'])){
									if(trim($employee_insurance_details['BANK_ID'])==$row->ID){echo "selected ";}
								}
								echo ">".$row->BANK_NAME."</option>";
								}
							}
						?>	
					 </select>
					 <span class='help-inline'><?php echo form_error('BANK_ID'); ?></span>
				</div>
								
				<!-- ----------- Insurance Type  ---------------- --> 
				<div class="form-group <?php echo form_error('BANK_INSURANCE_ID') ? 'error' : ''; ?>">
					<?php echo form_label(lang('BANK_INSURANCE_ID'). lang('bf_form_label_required'), 'BANK_INSURANCE_ID', array('class' => 'control-label col-sm-4') ); ?>					
					<select class="form-control" name="BANK_INSURANCE_ID" id="BANK_INSURANCE_ID" required="" tabindex="3">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
							if($insurance_type_list){
								foreach($insurance_type_list as $row){
								$row = (object) $row;
								echo "<option value='".$row->INSURANCE_TYPE_ID."'";							
								if(isset($employee_insurance_details['BANK_INSURANCE_ID'])){
									if(trim($employee_insurance_details['BANK_INSURANCE_ID'])==$row->INSURANCE_TYPE_ID){echo "selected ";}
								}
								echo ">".$row->INSURANCE_TYPE_NAME. " (" . $row->INSURANCE_TYPE_CODE. ")" ."</option>";
								}
							}
						?>	
					</select>
					<span class='help-inline'><?php echo form_error('BANK_INSURANCE_ID'); ?></span>
				</div>
								
				<!------------- Company Name -------------> 
				<div class="form-group <?php echo form_error('COMPANY_NAME') ? 'error' : ''; ?>">
					<?php echo form_label(lang('COMPANY_NAME'). lang('bf_form_label_required'), 'COMPANY_NAME', array('class' => 'control-label col-sm-4') ); ?>					             
					<input type='text' name='COMPANY_NAME' value="<?php echo set_value('COMPANY_NAME', isset($employee_insurance_details['COMPANY_NAME']) ? $employee_insurance_details['COMPANY_NAME'] : ''); ?>"  placeholder="<?php echo lang('COMPANY_NAME')?>" id='COMPANY_NAME' class="form-control" tabindex="4"/>					
                    <span class='help-inline'><?php echo form_error('COMPANY_NAME'); ?></span>
				</div>
				
				<!------------- Policy No -------------> 
				<div class="form-group <?php echo form_error('POLICY_NO') ? 'error' : ''; ?>">
					<?php echo form_label(lang('POLICY_NO'). lang('bf_form_label_required'), 'POLICY_NO', array('class' => 'control-label col-sm-4') ); ?>						                   
					<input type='text' name='POLICY_NO' value="<?php echo set_value('POLICY_NO', isset($employee_insurance_details['POLICY_NO']) ? $employee_insurance_details['POLICY_NO'] : ''); ?>"  placeholder="<?php echo lang('POLICY_NO')?>" id='POLICY_NO' class="form-control" tabindex="5"/>					
                    <span class='help-inline'><?php echo form_error('POLICY_NO'); ?></span>
				</div>
												
				<div class="box-footer pager">
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/company_info/library/show_companylist', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>               	
                </div> 

            </div>
			
        </fieldset>

    <?php echo form_close(); ?>

</div>

<div class="col-sm-2 col-md-2 col-lg-2"> 
</div>






