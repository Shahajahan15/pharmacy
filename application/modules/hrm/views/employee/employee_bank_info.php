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

if (isset($emp_bank_details)){
	 $emp_bank_details = (array) $emp_bank_details;
}

?>
<style>
.padding-left-div{margin-left:23px; }
</style>

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
	<div class="row">
    <fieldset>
    <legend>Employee Bank Information</legend>
		
			<div class="col-sm-6  col-md-6 col-lg-6 padding-left-div">
			
				<!-- -----------Employee BANK_NAME ------------------- --> 
                <div class="form-group <?php echo form_error('BANK_NAME') ? 'error' : ''; ?>">
                    <label><?php echo lang('BANK_NAME');?><span class="required">*</span></label>
                    
					<select class="form-control chosenCommon chosen-single" name="BANK_NAME" id="BANK_NAME" required="" tabindex="1" required="">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						foreach($banklist as $V_banklist)
						{
							echo "<option value='".$V_banklist->id."'";
																
							if(isset($emp_bank_details['EMP_BANK_ID']))
							{							
								if($emp_bank_details['EMP_BANK_ID'] == $V_banklist->id){ echo "selected";}
							}
													
							echo ">".$V_banklist->bank_name."</option>";
						}
						?>									
					</select>
					
                    <span class='help-inline'><?php echo form_error('BANK_NAME'); ?></span>
                </div>

				<!-- ----------- Employee BANK ACCOUNT_NAME ------------- --> 
                <div class="form-group <?php echo form_error('ACCOUNT_NAME') ? 'error' : ''; ?>">
                    <label><?php echo lang('ACCOUNT_NAME');?><span class="required">*</span></label>
                    <input type='text' name='ACCOUNT_NAME' value="<?php echo set_value('ACCOUNT_NAME', isset($emp_bank_details['ACCOUNT_NAME']) ? $emp_bank_details['ACCOUNT_NAME'] : ''); ?>" id='ACCOUNT_NAME' class="form-control" maxlength="100"  placeholder="<?php echo lang('ACCOUNT_NAME')?>" required="" tabindex="2"/>
                    <span class='help-inline'><?php echo form_error('ACCOUNT_NAME'); ?></span>
                </div>
				
			</div> 
			
			<div class="col-sm-5 col-md-5 col-lg-5 padding-left-div">
				
				<!-- ----------- Employee BANK BRANCH ---------------- -->               
				<div class="form-group <?php echo form_error('BANK_BRANCH') ? 'error' : ''; ?>">
                    <label><?php echo lang('BANK_BRANCH');?><span class="required">*</span></label>
					<select class="form-control chosenCommon chosen-single" name="BRANCH_ID" id="BRANCH_ID" required="" tabindex="4">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php 
						foreach($branch_list as $V_branch_list)
						{
							echo "<option value='".$V_branch_list->id."'";
																
							if(isset($emp_bank_details['BRANCH_ID']))
							{							
								if($emp_bank_details['BRANCH_ID'] == $V_branch_list->id){ echo "selected";}
							}
													
							echo ">".$V_branch_list->branch_name."</option>";
						}
						?>									
					</select>									
                    <span class='help-inline'><?php echo form_error('BANK_BRANCH'); ?></span>
                </div>
				
				<!-- -----------Employee BANK ACCOUNT_NO ------------------- --> 
                <div class="form-group <?php echo form_error('ACCOUNT_NO') ? 'error' : ''; ?>">
                    <label><?php echo lang('ACCOUNT_NO');?><span class="required">*</span></label>
                    <input type='text' name='ACCOUNT_NO' value="<?php echo set_value('ACCOUNT_NO', isset($emp_bank_details['ACCOUNT_NO']) ? $emp_bank_details['ACCOUNT_NO'] : ''); ?>" id='ACCOUNT_NO' class="form-control" maxlength="20"  placeholder="<?php echo lang('ACCOUNT_NO')?>" required="" tabindex="3"/>
                    <span class='help-inline'><?php echo form_error('ACCOUNT_NO'); ?></span>
                </div>
				
			</div>
      
	</fieldset>
	<div class="row">	
		<fieldset>			
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
				<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save').' '.lang('bf_action_next'); ?>"/>
				<?php echo lang('bf_or'); ?>
					<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default btn-sm"'); ?>
					
				</div>
			</fieldset>
        </div>
        
	</div>
    <?php echo form_close(); ?>

</div>

<script>
	// $(document).ready(function(){
	// 	/*       get employee name by department name     */
	// 	$("#BANK_NAME").on("change",function(){
	// 			var ci_csrf_token = $("input[name='ci_csrf_token']").val();
	// 			var id = $(this).val();
	// 			var sendData = {id: id, ci_csrf_token: ci_csrf_token}
	// 			//console.log(sendData);
	// 			var targetUrl = siteURL +"employee/hrm/getBranchNameByBankId";
	// 			$("#BRANCH_ID").html('<option>Loading...</option>');
	// 			$.ajax({
	// 	        	url: targetUrl,
	// 	        	type: "POST",
	// 	        	data: sendData,
	// 	        	dataType: "json",
	// 	        	success: function (response) {
	// 	        		console.log(response);
	// 	        		$("#BRANCH_ID").html(response);
	// 	        }, error: function (jqXHR) {
	// 	        	console.log(jqXHR);
	// 	            showMessages('Unknown Error!!!', 'error');
	// 	        }
	// 	    });
	// 	});
	// });
</script>