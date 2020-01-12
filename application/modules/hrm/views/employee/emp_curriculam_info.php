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
		if (isset($employee_curriculum)){
		$employee_curriculum = (array) $employee_curriculum;
		}
		$id = isset($employee_curriculum['id']) ? $employee_curriculum['id'] : '';
		
	?>

<!--<style>
.form-group .form-control, .control-group .form-control{ width: 98%;}
.row .form-control{ padding:5px 5px;}
.marginRight{ margin-right:40px; margin-bottom:3px; clear:both;}
#doc_timeschedule_chamber, #doc_timeschedule_specialization{ width:95%; margin-left:15px; margin-right:15px; 	color:"#C0C0C0"; height:38px;}
</style>-->

<div class="box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal" id="curriculum_form"'); ?>
		<div class="row">
        <fieldset>
            <legend>Employee Curriculum Info</legend>
					<table class="table table-bordered" style="margin-bottom: 0px;">
						<thead>
							<tr class="active">
								<th width="20%">
								<?php echo lang('emp_qualification_exam_name').lang('bf_form_label_required');?>
								</th>
								
								<th width="20%">
								<?php echo lang('emp_qualification_board_name').lang('bf_form_label_required');?>
								</th>
								
								<th width="15%">
								<?php echo lang('emp_qualification_pass_yaer').lang('bf_form_label_required');?>
								</th>
								
								<th width="10%">
								<?php echo lang('emp_qualification_score').lang('bf_form_label_required');?>
								</th>
								
								<th width="10%">
								<?php echo lang('emp_qualification_cgpa').lang('bf_form_label_required');?>
								</th>
								
								<th width="20%">
								<?php echo lang('emp_qualification_exam_result').lang('bf_form_label_required');?>
								</th>
								
								<th width="5%"><?php echo lang('emp_action');?>
								</th>
							</tr>
						</thead>
						
	<input type="hidden" name="empId" id="empId" value="<?php if(isset($employeeId)){echo $employeeId;}?>" required="" />
						
						
					<tbody>
						<tr class="info"> 
							<td>
								<select name="emp_qualification_exam_name" id="emp_qualification_exam_name" class="form-control" tabindex="1">
									   <option value="0"><?php echo lang('bf_msg_selete_one');?></option>
										<?php 
										foreach($exam_name as $key ){
											$key = (object) $key;
										echo "<option value='".$key->id."'";
										
										echo ">".$key->exam_name."</option>";
										}
										?>
								</select>
								<span class='help-inline'><?php echo form_error('EXAMCODE_ID'); ?></span>
							</td>
							
							<td>
								<select name="emp_qualification_board_name" id="emp_qualification_board_name" class="form-control" tabindex="2">
									   <option value="0"><?php echo lang('bf_msg_selete_one');?></option>
									 <?php 
										foreach($exam_board as $key ){
											$key = (object) $key;
										echo "<option value='".$key->id."'";
										
										echo ">".$key->exam_board."</option>";
										}
									   ?>
										
								</select>
								<span class='help-inline'><?php echo form_error('BOARD_UNIV'); ?></span>
							</td>
							
							<td>
								<input class="form-control" id='emp_qualification_pass_yaer'  name='emp_qualification_pass_yaer' type='text'  maxlength="30" placeholder="<?php echo lang('emp_qualification_pass_yaer')?>" required="" tabindex="3"/>
								<span class='help-inline'><?php echo form_error('PASS_YEAR'); ?></span>
							
							</td>
							
							<td>
								<input class="form-control" id='emp_qualification_score'  name='emp_qualification_score' type='text'  maxlength="30" placeholder="<?php echo lang('emp_qualification_score')?>" required="" tabindex="3"/>
								<span class='help-inline'><?php echo form_error('SCORE'); ?></span>
							
							</td>
							
							<td>
								<input class="form-control" id='emp_qualification_cgpa'  name='emp_qualification_cgpa' type='text'  maxlength="30" placeholder="<?php echo lang('emp_qualification_cgpa')?>" required="" tabindex="4"/>
								<span class='help-inline'><?php echo form_error('EARNED_SCORE'); ?></span>
							</td>
							
							<td>
								<select name="emp_qualification_exam_result" id="emp_qualification_exam_result" class="form-control" tabindex="5" required="">
									   <option value=""><?php echo lang('bf_msg_selete_one');?></option>
									   <?php 
										foreach($division_class as $key => $val){
										echo "<option value='".$key."'";										
										echo ">".$val."</option>";
										}										
										?>
								</select>
								<span class='help-inline'><?php echo form_error('CLASS_DIVISION'); ?></span>
							
							</td>
							<td>
								<span onclick="addEducation()" id="add_employee_curriculum" class="btn btn-primary-add"> ADD </span>
							</td>
						  </tr>
						  
						</tbody>
					</table>     		        
        </fieldset>
        <div class="row">
				<fieldset>
					<legend>Educational Qualification</legend>
				<table class="table table-striped report-table" style="margin-bottom: 0px;">
					<tbody id="employeeEducationInnerHTML" >
						
					</tbody>			
				
				</table>
					
				</fieldset>
           </div>   
			
           <div class="row">	
		<fieldset>			
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"> 
						<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save').lang('bf_action_next'); ?>"/>
						<?php echo lang('bf_or'); ?>
						<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-warning btn-sm"'); ?>
					</div>
				</fieldset>
            </div>
        </div>

    <?php echo form_close(); ?>
	
</script>
</div>