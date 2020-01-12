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

if (isset($bank_details))
{
	$bank_details = (array) $bank_details;	
}

?>

<div class="row box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form", class=""'); ?>
<fieldset class="box-body">
	<div class="col-sm-12 col-md-6 col-lg-6 col-md-offset-3">  
		
		<!-- New bank Create English-->
		<div class="form-group <?php echo form_error('bank_name') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_new_bank').' '. lang('ENGLISH'), 'bank_name', array('class' => 'control-label') ); ?>
			<div class='control'>
				<span id="checkDuplicateBankName" style="color:#F00; font-size:14px;"></span>
				<input type='text' name='bank_name' id='bank_name' value="<?php echo set_value('bank_name', isset($bank_details['BANK_NAME']) ? $bank_details['BANK_NAME'] : '');  ?>"  class="form-control"  maxlength="100"  tabindex="1" placeholder="If not created, create bank" onkeyup="getBankName(this)" >
				<span class='help-inline'><?php echo form_error('bank_name'); ?></span>
			</div>
		</div>
		
		<!-- New bank Create Bengali -->
		<div class="form-group <?php echo form_error('bank_name_bengali') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_new_bank').' ' .lang('BENGALI'), 'bank_name_bengali', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='bank_name_bengali' id='bank_name_bengali' value="<?php echo set_value('bank_name_bengali', isset($bank_details['BANK_NAME_BENGALI']) ? $bank_details['BANK_NAME_BENGALI'] : '');  ?>" class="form-control bn_language"  maxlength="100"  tabindex="2" >
				<span class='help-inline'><?php echo form_error('bank_name_bengali'); ?></span>
			</div>
		</div>
		
		<!-- Exists Bank List -->
		<div class="form-group <?php echo form_error('bank_id') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_banks'), 'bank_id', array('class' => 'control-label') ); ?>
			<div class='control'>   
				<select name="bank_id" id="bank_id" class="form-control" tabindex="3">   
						<option value=""><?php echo lang('bf_msg_selete_one')?></option>
						<?php foreach($banklist as $v_banklist)
						{
						   echo "<option value='". $v_banklist->ID ."'";
						   
						   if(isset($bank_details['ID'])){
							  if($bank_details['ID']==$v_banklist->ID){echo "selected";}
						   }
						   
						   echo ">".$v_banklist->BANK_NAME."</option>";
						}
						?>
				</select>
				<span class='help-inline'><?php echo form_error('bank_id'); ?></span>
			</div>
		</div>
		
		<!-- Bank Branch Name English -->	
		<div class="form-group <?php echo form_error('branch_name') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_bank_branch').' '. lang('ENGLISH'), 'branch_name', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='branch_name' id='branch_name' value="<?php echo set_value('branch_name', isset($bank_details['BRANCH_NAME']) ? $bank_details['BRANCH_NAME'] : ''); ?>" class="form-control"  maxlength="100"  tabindex="4">
				<span class='help-inline'><?php echo form_error('branch_name'); ?></span>
			</div>
		</div>
		
		<!-- Bank Branch Name Bengali -->	
		<div class="form-group <?php echo form_error('branch_name_bengali') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_bank_branch').' '.lang(BENGALI), 'branch_name_bengali', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='branch_name_bengali' id='branch_name_bengali' value="<?php echo set_value('branch_name_bengali', isset($bank_details['BRANCH_NAME_BENGALI']) ? $bank_details['BRANCH_NAME_BENGALI'] : ''); ?>" class="form-control bn_language"  maxlength="100"  tabindex="5">
				<span class='help-inline'><?php echo form_error('branch_name_bengali'); ?></span>
			</div>
		</div>
						
		<!------------Bank Branch Address English----------->
		<div class="form-group <?php echo form_error('address') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_bank_address').' '.lang('ENGLISH'), 'address', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='address' id='address' value="<?php echo set_value('address', isset($bank_details['ADDRESS']) ? $bank_details['ADDRESS'] : ''); ?>" class="form-control"  maxlength="150" tabindex="6"/>
				<span class='help-inline'><?php echo form_error('address'); ?></span>
			</div>
		</div>
		
		<!------------Bank Branch Address Bengali ----------->
		<div class="form-group <?php echo form_error('address_bengali') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_bank_address').' '. lang('BENGALI'), 'address_bengali', array('class' => 'control-label') ); ?>
			<div class='control'>
				<input type='text' name='address_bengali' id='address_bengali' value="<?php echo set_value('address_bengali', isset($bank_details['ADDRESS_BENGALI']) ? $bank_details['ADDRESS_BENGALI'] : ''); ?>" class="form-control bn_language"  maxlength="150" tabindex="7"/>
				<span class='help-inline'><?php echo form_error('address_bengali'); ?></span>
			</div>
		</div>
		
		<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">
			<?php echo form_label(lang('library_bank_status'). lang('bf_form_label_required'), 'status', array('class' => 'control-label') ); ?>
			<div class='control'>
				<select name="status" class="form-control" required tabindex="8">
					<option value=""><?php echo lang('bf_msg_selete_one')?></option> 
					<option value="1" <?php  if(isset($bank_details['STATUS'])){if($bank_details['STATUS']==1){echo "selected";}} ?>>Active </option>	
					<option value="0" <?php  if(isset($bank_details['STATUS'])){if($bank_details['STATUS']==0){echo "selected";}} ?>>In Active </option>					
				</select>
				<span class='help-inline'><?php echo form_error('status'); ?></span>
			</div>
		</div>
		
		<div class="box-footer pager">
			<?php echo anchor(SITE_AREA .'/bank_setup/library/create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>					
			&nbsp;			
			<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
		</div>               

	</div>
</fieldset>

<?php echo form_close(); ?>

</div>
