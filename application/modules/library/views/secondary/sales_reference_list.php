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

if (isset($exam_exam))
{
	$exam_exam = (array) $exam_exam;
}
$id = isset($exam_exam['id']) ? $exam_exam['id'] : '';
?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 90%;
    }
</style>

<?php
$num_columns	= 8;
$can_delete		= $this->auth->has_permission('Lib.Reference.Delete');
$can_edit		= $this->auth->has_permission('Lib.Reference.Edit');
$has_records	= isset($records) && is_array($records) && count($records);
?>
<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th width="1%" class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>										
                    <th width="31%"><?php echo lang('doctor_ref_doc_or_org_name'); ?></th>
                    <th width="8%"><?php echo lang('doctor_ref_type'); ?></th>
                    <th width="25%"><?php echo lang('doctor_ref_doc_quali'); ?></th>
					<th width="10%"><?php echo lang('doctor_ref_doc_or_org_phone'); ?></th>
                    <th width="10%"><?php echo lang('doctor_ref_doc_or_org_mobile'); ?></th>
                    <th width="8%"><?php echo lang('doctor_ref_doc_or_org_comission'); ?></th>
                    <th width="7%"><?php echo lang('doctor_ref_doc_or_org_status'); ?></th>

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
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td>
					<?php echo anchor(SITE_AREA . '/sales_reference/library/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' . $record->ref_name); ?>
					</td>
				<?php else : ?>	
                <td><?php e($record->ref_name); ?></td>				
                
				<?php endif; ?>
				<td><?php if($record->ref_type==1){ e("Doctor");}elseif($record->ref_type==2){ e("Organization");}else{e("Others");} ?></td>
                <td><?php e($record->ref_quali); ?></td>
               
				<td><?php e($record->ref_phone); ?></td>
                <td><?php e($record->ref_mobile); ?></td>
                <td align="right"><?php e($record->ref_commission); ?></td>
				<td><?php if($record->ref_status==1){ e("Active");}else{e("In Active");} ?></td>
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
	<?php echo form_close(); ?>
    </div>
</div>