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

if (isset($emp_transfer_details)){
	 $emp_transfer_details = (array) $emp_transfer_details;
	//print_r($emp_transfer_details);
}

if(isset($_REQUEST['employee_id']) && (int)$_REQUEST['employee_id'] > 0){
	$src_emp['EMP_ID '] = (int)$_REQUEST['employee_id'];
}

?>



<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", id="employeeTransferForm" class="form-horizontal"'); ?>
    <fieldset class="box-body">	
		<div class="col-sm-12 col-md-12 col-lg-12">			
			<!-- Start Search -->
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				  <div class="panel panel-primary">
					
					<div class="panel-heading" role="tab" id="headingOne">
					  <div class="panel-title">
							<span class="glyphicon glyphicon-plus"></span>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							Search Panel
						</a>
					  </div>		   
					</div>
					
					
					<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
					  <div class="panel-body">					
						  <div class="row">				  
						  <div class="col-md-3">					
							<input type="text" class="form-control" name="employee_emp_id" id="employee_emp_id" value="<?php echo set_value('employee_emp_id', isset($src_emp['EMP_ID ']) ? $src_emp['EMP_ID '] : '');?>" placeholder="<?php echo lang("empid");?>" title="<?php echo lang("empid");?>">				
						  </div>			  		 
						  
						  <div class="col-md-1 padding-left-div">	
						  <button type="button" name="employeeSearch" id="employeeSearch" class="btn btn-default pull-right"><span class="glyphicon glyphicon-search"> </span></button>
						  </div>
						  
						</div>
						
					  </div>
					</div>
				  </div>		  
				</div>
			</div>
				<!-- End Search -->
				
				
				
				<!-- employee search result-->
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-3 col-md-3 col-lg-3">					
						<!-- ----------- Employee name---------------- --> 
						<div class="form-group <?php echo form_error('EMP_ID') ? 'error' : ''; ?>">
							<label><?php echo lang('EMP_NAME').lang('bf_form_label_required');?></label>
							<input type='text' name='' id='employeeName' class="form-control"  value="<?php if(isset($emp_transfer_details['EMP_NAME'])) echo $emp_transfer_details['EMP_NAME']; ?>" readonly=""/>
							<input type='hidden' name='EMP_ID' id='EMP_ID' class="form-control"  value="<?php if(isset($emp_transfer_details['EMP_ID'])) echo $emp_transfer_details['EMP_ID']; ?>"/>
							 <span class='help-inline'><?php echo form_error('EMP_ID'); ?></span>
						</div>						
					</div>
					
					
					<div class="col-sm-3 col-md-3 col-lg-3">					
						<!------------- Present work station -------------> 
						<div class="form-group <?php echo form_error('PRESENT_BRANCH_NAME') ? 'error' : ''; ?>">
							<label><?php echo lang('PRESENT_BRANCH_NAME');?></label>                    
							<input type='text' class="form-control"  name='PRESENT_BRANCH_NAME' id='PRESENT_BRANCH_NAME'  value="<?php if(isset($emp_transfer_details['BRANCH_NAME'])) echo $emp_transfer_details['BRANCH_NAME']; ?>" readonly=""/>							
							
						</div>				
						
					</div>				
					
					<div class="col-sm-3 col-md-3 col-lg-3">					
						<!------------- Present work station -------------> 
						<div class="form-group <?php echo form_error('PRESENT_DEPARTMENT_NAME') ? 'error' : ''; ?>">
							<label><?php echo lang('PRESENT_DEPARTMENT_NAME');?></label>						
							<input type='text' class="form-control"  name='PRESENT_DEPARTMENT_NAME' id='PRESENT_DEPARTMENT_NAME'  value="<?php if(isset($emp_transfer_details['department_name'])) echo $emp_transfer_details['department_name']; ?>" readonly=""/>							
						</div>							
					</div>
					
					
					<div class="col-sm-3 col-md-3 col-lg-3">				
						<!-------------present Designation -------------> 
						<div class="form-group <?php echo form_error('BEFORE_DESIGNATION_ID') ? 'error' : ''; ?>">
							<label><?php echo lang('PRESENT_DESIGNATION_NAME');?></label>
							<input type='text' class="form-control" name='' id='DESIGNATION_NAME'  value="<?php if(isset($emp_transfer_details['DESIGNATION_NAME'])) echo $emp_transfer_details['DESIGNATION_NAME']; ?>" readonly=""/>						
						</div>
					
					</div>			
				</div>
				<!-- employee search result end-->					
				
				<div class="detailsContainer">					
					<?php echo $this->load->view('employee_transfer_row',$_REQUEST,TRUE)?>			
				</div>
			
						
		
        <div class="col-md-12"> 
			<div class="col-md-12"> 
				<div class="col-md-10 box-footer pager">
					<?php echo anchor(SITE_AREA .'/employee_transfer/hrm/transfer_create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>
					 &nbsp;
					 <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
                   
                    
				</div>
			</div>
        </div>
    
	 
	</fieldset>
    <?php echo form_close(); ?>
</div>