<?php
$records		= $records = $this->salary_rule_mst_model->select('*')->find_all_by('IS_DELETED',0);	
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('library.salary_head.Delete');
$can_edit		= $this->auth->has_permission('library.salary_head.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>

<div class="col-md-12">				          
<?php echo form_open($this->uri->uri_string(),'id="showListForm"');  ?>
	<table class="table table-striped" id="salaryRuleInnerHTML">
		<thead>
			<tr>
				<?php if ($can_delete && $has_records) : ?>
				<th width="5%" class="column-check"><input class="check-all" type="checkbox" /></th>
				<?php endif;?>					
			
				<th width="20%"><?php echo lang('hrm_salary_rule_name'); ?></th>					
				<th width="50%"><?php echo lang('hrm_salary_rule_description'); ?></th>					
				<th width="20%"><?php echo lang('bf_status'); ?></th>					
			</tr>
		</thead>
		
		<tbody>
			<?php
			if ($has_records) :
				foreach ($records as $record) :
			?>
			<tr>
				<?php if ($can_delete) : ?>
				<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->MST_ID; ?>" /></td>
				<?php endif;?>
				
			<?php if ($can_edit) : ?>
				<td><a href="javascript:void(0);" onclick='editSalaryRule(<?php e($record->MST_ID); ?>)'><?php echo '<span class="glyphicon glyphicon-pencil"></span>' .  $record->RULE_NAME; ?></a></td>					
			<?php else : ?>
				
			<?php endif; ?>				
				<td><?php e($record->RULE_DESCRIPTION); ?></td>
				<td><?php if($record->STATUS==1){ e("Active");}else{e("In Active");} ?></td>
			</tr>
			<?php
				endforeach;
			else:
			?>
			<tr>					
				<td colspan="<?php echo $num_columns; ?>"><?php echo lang('bf_msg_records_not_found'); ?></td>
			</tr>
			<?php endif; ?>
		</tbody>
		
		<?php if ($has_records) : ?>
		<tfoot>
			<?php if ($can_delete) : ?>
			<tr>
				<td colspan="<?php echo $num_columns; ?>">
					<?php echo lang('bf_with_selected'); ?>
					<input type="button" name="delete" id="delete-me" class="btn btn-danger" value="<?php echo lang('bf_action_delete'); ?>" onclick='deleteSalaryRule(<?php e($record->MST_ID);?>)' />						
				</td>
			</tr>
			<?php endif; ?>
		</tfoot>
		<?php endif; ?>			
	</table>
<?php echo form_close(); ?>								
</div> 	



	


		