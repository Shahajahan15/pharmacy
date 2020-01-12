<section id="attendance_form">
<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>	
	<fieldset>
		<div class="col-md-12">
			<div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
				<div class="form-group">
					<label class="control-label">Attendance Date</label>
					<input type="text" name="attendance_date" class="form-control datepickerCommon attendance_date" required="" value="<?php echo date('d/m/Y',strtotime($curr_date))?>" id="attendance_date">
				</div>
			</div>
		</div>
		
		<table class="table">
			<thead>
				<tr class="active">
					<th>SL</th>
					<th>Photo</th>
					<th>Code</th>
					<th>Employee Name</th>
					<th>Designation</th>
					<th>Department</th>
					<th width="15%">Present Status</th>
					<th width="15%">Leave Type</th>
				</tr>
			</thead>
			<tbody>
				<?php $sl=0; foreach($employees as $employee){?>
					<tr class="success">
						<td><?php echo $sl+=1; ?></td>
						<td>
							<img src="<?php echo $img = ($employee->photo == "")? base_url("assets/images/profile/default.png") : base_url("assets/images/employee_img/" . $employee->photo); ?>" class="img-circle"  width="40" height="40">
						</td>
						<td><?php echo $employee->emp_code; ?></td>
						<td><?php echo $employee->employee_name; ?>
							<input type="hidden" name="emp_id[]" class="employee_id" value="<?php echo $employee->id; ?>">
							
						</td>
						<td><?php echo $employee->designation_name; ?></td>
						<td><?php echo $employee->department_name; ?></td>
						<td>
							<select name="present_status[]" class="form-control present_status">
								<?php foreach($attendance_status as $key=>$status){?>
									<option value="<?php echo $key; ?>"><?php echo $status; ?></option>									
								<?php }?>
							</select>
						</td>
						<td class="leave-options">
							For leave only
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</fieldset>

	<fieldset>
	   <div class="text-center">
	       <input type="submit" name="save" class="btn btn-primary btn-sm confirm" value="Submit"  />
	       <input type="reset" name="" class="btn btn-warning btn-sm" value="Reset" />                
	   </div>
	</fieldset>


 <?php echo form_close(); ?>

 <script type="text/javascript">
 	$(document).ready(function(){
 		$('.attendance_date').trigger('focusout');
 	})
 </script>

 </section>