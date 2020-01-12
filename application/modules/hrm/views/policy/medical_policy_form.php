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
	if (isset($medical_details))
	{
		$medical_policy_details = (array) $medical_policy_details;
	}
		$id = isset($medical_policy_details['id']) ? $medical_policy_details['id'] : '';

?>
	
<style>
	.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
	<fieldset class="box-body">
    <?php echo form_open($this->uri->uri_string(),'id="medicalPolicyInFoFrm", role="form", class="form-horizontal", onsubmit=""' ); ?>
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading" align="center"> 
                    <h3 class="panel-title">Medical Policy</h3>
                </div> <!--panel title end-->
				
				<div class="panel-body">
					
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="row">
						
							<!-- Medical policy Name Start -->
							<div class="col-sm-4 col-md-4col-lg-4 padding-left-div form-group <?php echo form_error('NAME') ? 'error' : ''; ?>">
								 <?php echo form_label(lang('NAME'). lang('bf_form_label_required'), 'NAME', array('class' => 'control-label') ); ?>
								<input type='text' name='NAME' value="<?php echo set_value('NAME', isset($medical_policy_details['NAME']) ? $medical_policy_details['NAME'] : ''); ?>" placeholder="<?php echo lang('NAME')?>" id='NAME' class="form-control" maxlength="100" required  onblur="Check_policyName()" tabindex="1"/>
								<span class='help-inline'><?php echo form_error('NAME'); ?></span>
								<span id="chkMpolicyName"></span>
							</div>
				
						
							<!-- Deduction head Start -->
							<div class="col-sm-4 col-md-4col-lg-4 padding-left-div form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">
								<?php echo form_label(lang('DESCRIPTION'), 'MPDESCRIPTION', array('class' => 'control-label') ); ?>	
								<input type='text' name='DESCRIPTION' value="<?php echo set_value('DESCRIPTION', isset($medical_policy_details['DESCRIPTION']) ? $medical_policy_details['DESCRIPTION'] : ''); ?>" placeholder="<?php echo lang('DESCRIPTION')?>" id='MPDESCRIPTION' class="form-control" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('DESCRIPTION'); ?></span>
							</div>
							
							
							<div class="col-sm-4 col-md-4col-lg-4 padding-left-div form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">
								<?php echo form_label(lang('STATUS'). lang('bf_form_label_required'), 'MPSTATUS', array('class' => 'control-label') ); ?>
								<select class="form-control" name="STATUS" id="MPSTATUS" required="" tabindex="3">

									<?php 
										foreach($status as $key => $val)
										{
											echo "<option value='".$key."'";
											
											echo ">".$val."</option>";
										}
									?>
									
								 </select>
								 <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
							</div>
							
						</div>	
						
					</div>
					<!-- end master part -->
					
					<div class="col-sm-12 col-md-12 col-lg-12"><hr/></div>
					
					<!--details part start -->
					
					<div class="col-sm-12 col-md-12 col-lg-12 detailsContainer">
						<?php echo $this->load->view('policy/medical_details_form', $_REQUEST, true); ?>
					</div> <!-- form main column end -->
					
					<!-- details part end -->
					

					<div class="col-md-12"> 
						<div class="col-md-12 box-footer pager">							
							<input type='hidden' name='MEDICAL_POLICY_MASTER_ID' value=""  id='MEDICAL_POLICY_MASTER_ID' />	
							<a name="reset" class="btn btn-default" onclick="resetMedical()">Reset</a>								
							<a name="" href="javascript:void(0)" onclick="addMedicalInfo()" class="btn btn-primary mlm">Save</a>					
						</div>
					</div>
					
					
				</div> <!-- panel body end -->			
			</div> <!-- panel end -->
		</div> <!-- main column end -->  
    <?php echo form_close(); ?>
	</fieldset>
</div>