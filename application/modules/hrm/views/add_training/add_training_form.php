<style>
.searchPanelDiv{
	max-height:200px;
	overflow-y:auto;
}
.table-responsive
{
    overflow-x: auto;
}
.padding-left{
	margin-left:25px;
}
#add_training_div{
	margin-top:10px;
}
</style>

<?php if(isset($SendData)) extract($SendData); ?>
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

?>

<div class="row box box-primary">
	<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
	<fieldset class="box-body">
		<div class="col-sm-12 col-md-12 col-lg-12">
			
			<div class="panel-group " id="accordion" role="tablist" aria-multiselectable="true">
                <?php echo $this->load->view('emp_search_for_add_training', $_REQUEST, TRUE); ?>
			</div>
			 
			 
			<div class="col-md-12 " id= "add_training_div">
				<span>     
					<div class="row">
						<!-- training_list of employees -->
						<div class="col-sm-5 col-md-5 col-lg-5 padding-left">				   
							<div class="form-group">	
								<?php echo form_label(lang('hrm_employee_training_list')); ?>
								
									<select class="form-control" name="trainings_of_employees[]" required="">
										<option value=""><?php echo lang('bf_msg_selete_one');?></option>									
										<?php 											
											foreach($employees_trainings as $employees_training)
											{									
												echo "<option value='".$employees_training->TRAINING_TYPE_ID."'";	
												/*
												if(isset($record->SALARY_HEAD))
												{							
													if($record->SALARY_HEAD == $salary_head->SALARY_HEAD_ID){ echo "selected";}
												}
												*/
												
												echo ">".$employees_training->TRAINING_TYPE_NAME."</option>";
											}									
										?>
									</select>
							
								<span class='help-inline'><?php echo form_error('trainings_of_employees'); ?></span>
							</div>						
						</div>
						<div class="col-sm-2 col-md-2 col-lg-2 "></div>	
						<div class="col-sm-2 col-md-2 col-lg-2 "></div>	
						<div class="col-sm-1 col-md-1 col-lg-1 ">  
							<div class="form-group">
								<label style=""> &nbsp; </label>
								<?php if(isset($removeRow) && $removeRow){?> 
										<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus btn-xs" onclick="removeRow(this);" href="javascript:void(0)"> </a>
								<?php } else { ?>	
										<a name="clicktoMinus" class="btn btn-primary glyphicon glyphicon-plus btn-xs" onclick="addRow(this)" href="javascript:void(0)"> </a> 										
									<?php 
								} 
								?>
							</div>			
						</div>
						
					</div>		
				</span>		
						
						
					
			</div>
				
			<div class="col-md-12 "> 
				<div class="col-md-12 box-footer pager">
					<?php echo anchor(SITE_AREA .'/add_training/hrm/create', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
					&nbsp; <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>" id="Save" />
				</div>
			</div> 
				
		</div>
		
    </fieldset>
	<!-- End Search -->			
	
	<?php echo form_close(); ?>
</div>

