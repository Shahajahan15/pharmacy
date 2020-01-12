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

if (isset($records)){
	$record = (object) $records;
}

?>



<?php
$num_columns	= 6;
$can_delete		= true; //$this->auth->has_permission('Lib.Discount.Delete');
$can_edit		= true; //$this->auth->has_permission('Lib.Discount.Edit');
$has_records	= isset($records);
?>
<style type="text/css">
	.status{
		width:80px;
	}
</style>

<!-- Modal -->
<div id="view_details_discount" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Discount Details</h4>
      </div>
      <div class="modal-body" id="details_discount_table">
       
      </div>
    </div>

  </div>
</div>



<div class="admin-box">
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th class="column-check"><input class="check-all" type="checkbox" /></th>
					<?php endif;?>					
					<th>SL</th>
					<th>Patient Type</th>
					<th>Patient Sub Type</th>
					<th>Discount Type</th>
					<th>Service</th>
					<th>Sub Service</th>
					<th>Discount(%)</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Status</th>
					<th>Campaign</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan="<?php echo $num_columns; ?>">
						<?php echo lang('bf_with_selected'); ?>
						<input type="submit" name="delete" id="delete-me" class="btn btn-danger confirm" value="<?php echo lang('bf_action_delete'); ?>"/>
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($records) : 
					$sl=0;
					foreach ($records as $record) :
						$record=(object)$record;
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
					<?php endif;?>
					<td><?php echo $sl+=1; ?></td>
					<td><?php echo $record->type_name; ?></td>
					<td><?php echo $record->sub_type_name; ?></td>
					<td><?php echo ($record->discount_type==1)?'<p class="btn btn-xs btn-success">Overall</p>':'<p class="btn btn-xs btn-info">Specific</p>'; ?></td>
					<td><?php echo $record->service_name; ?></td>
					<td><?php echo $record->sub_service_name; ?></td>
					
					
				<?php if ($can_edit) : ?>
						<td><?php echo anchor(SITE_AREA . '/discount/library/edit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' .$record->discount); ?></td>
				<?php else : ?>
					<td><?php e($record->discount); ?></td>
				<?php endif; ?>
					
					<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->date_start)))?></td> 
					
					<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->date_end)))?></td> 
					<td><?php echo ($record->status==1)?'Active':'Inactive'; ?></td>
					<td><?php echo ($record->is_campaign==1)?'Campaign':'Not Campaign'; ?></td>


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
		</table>
	<?php echo form_close(); ?>
    </div>
</div>