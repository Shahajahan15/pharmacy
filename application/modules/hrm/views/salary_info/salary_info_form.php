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

if (isset($targetMasterRecords)){
	$targetMasterRecords = (array) $targetMasterRecords;	
}
?>

<style>
	.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(),'id="salary_details", role="form", class="form-horizontal"'); ?>
        <fieldset class="box-body">
            <div class="container">
				
				<div class="row">                
                        
						<!-- Salary rule -->
						<div class="col-lg-3 col-md-3 padding-left-div">
							<div class="form-group <?php echo form_error('hrm_salary_rule') ? 'error' : ''; ?>">
								<?php echo form_label(lang('hrm_salary_rule'). lang('bf_form_label_required'), 'hrm_salary_rule', array('class' => 'control-label' ) ); ?>
								<select class="form-control chosenCommon chosen-single" name="salary_rule" id="salary_rule" required="" tabindex="2">
									<option value="0"><?php echo lang('bf_msg_selete_one');?></option>
									<?php 
									foreach($salaryRuleList as $v_salaryRuleList)
									{
										echo "<option value='".$v_salaryRuleList->MST_ID."'";
																				
										if(isset($targetMasterRecords['SALARY_RULE']))
										{							
											if($targetMasterRecords['SALARY_RULE'] == $v_salaryRuleList->MST_ID){ echo "selected";}
										}										
										echo ">".$v_salaryRuleList->RULE_NAME."</option>";
									}
									?>
									
								</select>
								<span class='help-inline'><?php echo form_error('hrm_salary_rule'); ?></span>
							</div> 
						</div>
						
						
						<!------------ Employee name ----------->
						<div class="col-lg-3 col-md-3 padding-left-div">	
							<div class="form-group <?php echo form_error('hrm_employee_name') ? 'error' : ''; ?>">
								<?php echo form_label(lang('hrm_employee_name'). lang('bf_form_label_required'), 'hrm_employee_name', array('class' => 'control-label' ) ); ?>
								<select class="form-control chosenCommon chosen-single" name="employee_name" id="employee_name" required="" tabindex="1">
									<option value=""><?php echo lang('bf_msg_selete_one');?></option>
									<?php 
								
									foreach($employee_details as $employee_detail)
									{									
										echo "<option value='".$employee_detail->EMP_ID."'";	

										if(isset($targetMasterRecords['EMP_ID']))
										{							
											if($targetMasterRecords['EMP_ID'] == $employee_detail->EMP_ID){ echo "selected";}
										}
										
										echo ">".$employee_detail->EMP_NAME." =>".$employee_detail->EMP_ID."</option>";
									}
								
									?>		
								</select>
								<span class='help-inline'><?php echo form_error('hrm_employee_name'); ?></span>
							</div> 
						</div>	
						
						<!-- salary Amount -->
						<div class="col-lg-2 col-md-2 padding-left-div">
							<div class="form-group <?php echo form_error('hrm_salary_amount') ? 'error' : ''; ?>">              
								<?php echo form_label(lang('hrm_salary_amount'). lang('bf_form_label_required'), 'hrm_salary_amount', array('class' => 'control-label') ); ?>
								<div class='control'>
									<input type='text' name='salary_amount' value="<?php echo set_value('hrm_salary_amount', isset($targetMasterRecords['SALARY_AMOUNT']) ? $targetMasterRecords['SALARY_AMOUNT'] : ''); ?>" class="form-control" id='salary_amount' class="form-control "  placeholder="<?php echo lang('hrm_salary_amount')?>" required="" tabindex="3"/>
									<span class='help-inline'><?php echo form_error('hrm_salary_amount'); ?></span>
									
								</div>								
							</div>
						</div>
						
						<div class="col-lg-2 col-md-2" style="margin-top:25px;">								
							<input type="button" name="calculate" value="Calculate"  class="btn btn-small btn-default" id="calculate"  disabled />
						</div>
					            
				</div><!-- end row --> 
			
			 
				<?php  if(isset($edit)){}else{ ?>
				<div class="col-sm-12 col-md-12 col-lg-12">
					<hr/>
				</div>
            
			
				<div class="row">
					<div class="container">
								  
						<div class="col-sm-6 col-md-3 col-lg-3 text-center">
							<label class="requisition-list"><?php  echo lang('hrm_salary_head'); ?></label>
						</div>                              
						
						<div class="col-sm-6 col-md-2 col-lg-2 text-center">
							<label class="requisition-list"><?php  echo lang('hrm_amount_type'); ?></label>
						</div>
						
						<div class="col-sm-6 col-md-2 col-lg-3 text-center">
							<label class="requisition-list"><?php  echo lang('hrm_fixed_value'); ?></label>
						</div>
						
						<div class="col-sm-6 col-md-1 col-lg-1 text-left">
							<label class="requisition-list"><?php  echo lang('hrm_percent_sign').lang('hrm_percentage_value'); ?></label>
						</div> 
						
						<div class="col-sm-6 col-md-1 col-lg-1 text-left">
							<label class="requisition-list"><?php  echo lang('hrm_calculative_value'); ?></label>
						</div> 
						
					</div>
				</div>
			
				<div class="row"> 
					<!--details part start -->
					<div class="col-sm-12 col-md-12 col-lg-12" id="detailsContainer">
						<?php  echo $this->load->view('salary_info/salary_info_form_manual', $_REQUEST, true); ?>
						<?php  echo $this->load->view('salary_info/salary_info_form_automatic', $_REQUEST, true); ?>
					</div> 
					<!-- details part end -->
				</div> 
				
				<div class="col-sm-12 col-md-12 col-lg-12">
					<hr/>
					<span id="totalSalry" style="margin-left:600px;"> 
						<!-- total salary written by inner html--> 
					</span>
				</div>
				
				<?php } ?>
				
				<div class="col-md-12"> 
					<div class="col-md-12"> 
						<div class="col-md-12 box-footer pager">														
							<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-warning"'); ?>
							<?php echo lang('bf_or'); ?>							
							<a name="save" onclick="saveSalaryInfoData()"  class="btn btn-primary" href="javascript:void(0)"><?php echo lang('bf_action_save'); ?></a> 				
						</div>
					</div>
				</div>
				
			</div> <!-- end Container --> 	
        </fieldset>
    <?php echo form_close(); ?>
</div>
    








