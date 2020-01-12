<?php
//extract($sendData);
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



<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('HRM.Employee.Delete');
$can_edit		= $this->auth->has_permission('HRM.Employee.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
//$src_emp = (array) $src_emp;
?>
<div id='search_result'>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">	
	<?php echo form_open($this->uri->uri_string()); ?>
		
	
	
		<table class="table table-striped" id="myTable">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th width="" class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>  
					<th>SL</th>               
					<th>EMP CODE</th>
					<th><?php echo lang('hrm_employee_name'); ?></th>
					<th>Emp Photo</th>
					<th><?php echo lang('hrm_employee_sex'); ?></th>					
					<th><?php echo lang('employee_mobile_no'); ?></th>	
					<th><?php echo lang('hrm_employee_type'); ?></th>
					<th><?php echo lang('hrm_employee_designation'); ?></th>	
                    <th><?php echo lang('employee_emp_department'); ?></th>
					<th><?php echo lang('employee_joining_date'); ?></th>					
					<th><?php echo lang('employee_status'); ?></th>	
					<th>View</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('bf_msg_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
					$record = (object) $record;
				?>
				<tr>
						<?php if ($can_delete) : ?>
						<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->EMP_ID; ?>" />
						</td>
						<?php endif;?>
						
					<?php if ($can_edit) : ?>
						<td><?php echo $sl+=1;?></td>
					<td>
						<?php echo anchor(SITE_AREA . '/employee/hrm/employee_tab/' . $record->EMP_ID, '<span class="glyphicon glyphicon-pencil"></span>' .$record->EMP_CODE); ?>
					</td>
					<?php else : ?>
					<?php endif; ?> 					              
					<td><?php e($record->EMP_NAME); ?></td>
						
					<td>                    
                      <img src="<?php echo $img = ($record->EMP_PHOTO == "")? base_url("assets/images/profile/default.png") : base_url("assets/images/employee_img/" . $record->EMP_PHOTO); ?>"class="img-circle"  width="80" height="80">
					 </td>
					<td><?php if(isset($sex[$record->GENDER])) { e($sex[$record->GENDER]); } ?></td>
					<td></td>

                  
					<td><?php e($record->emp_type);  ?></td>

					<td><?php e($record->DESIGNATION_NAME);  ?></td>
					<td><?php e($record->department_name);  ?></td>
					<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->JOINNING_DATE)))?></td>
					<td><?php if($record->STATUS==1){ e("Active");}else{e("Inactive");} ?></td>
					<td>
                      <span class="btn btn-info btn-xs" href="/employee/hrm/view_employee.<?php echo $record->EMP_ID; ?>" style="text-decoration:none;"  onclick="window.open('<?php echo site_url(SITE_AREA . '/employee/hrm/view_employee/'.$record->EMP_ID); ?>')" >view</span>
                    </td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_no_records_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close();
	?>
    </div>
</div>
<?php echo $this->pagination->create_links();
?>
</div>