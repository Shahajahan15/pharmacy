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
	//echo '<pre>'; print_r($records);die();
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


<div id='search_result'>
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
					<th>Patient Name</th>
					<th>Patient Code</th>
					<th>Discount Type</th>
					<th>discount_start_date</th>
					<th>discount_end_date</th>
					<th>Discount Details</th>
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
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record->id; ?>" /></td>
					<?php endif;?>
					<td><?php echo $sl+=1; ?></td>
					<td><?php echo $record->patient_name; ?></td>
					
				<?php if ($can_edit) : ?>
					<?php if($record->has_overall_discount==2){?>
						<td><?php echo anchor(SITE_AREA . '/patient_discount_setup/library/ItemBaseEdit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' .$record->p_code); ?></td>
					<?php }else{?>
						<td><?php echo anchor(SITE_AREA . '/patient_discount_setup/library/ServiceBaseEdit/' . $record->id, '<span class="glyphicon glyphicon-pencil"></span>' .$record->p_code); ?></td>
					<?php } ?>
				<?php else : ?>
					<td><?php e($record->p_code); ?></td>
				<?php endif; ?>

					<td><?php echo ($record->has_overall_discount==2)? '<span class=" status btn btn-xs btn-warning disabled">Specific Item</span>' : '<span class="btn btn-xs btn-danger disabled status ">ovarall</span>'; ?></td>
					
						<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->discount_start_date)))?></td> 
						<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->discount_end_date)))?></td> 
					
					
					<td><span class="btn btn-xs btn-info" id="discount_details_info" discount_id="<?php echo $record->id; ?>">Details</span></td>

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
<?php  //echo $this->pagination->create_links(); ?>
</div>