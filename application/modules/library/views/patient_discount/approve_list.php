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
					<?php endif;?>					
					<th>SL</th>
					<th>Patient Name</th>
					<th>Patient Code</th>
					<th>Discount Type</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Discount Type</th>
					<th>Discount Details</th>
					<th>Action</th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
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
					<?php endif;?>
					<td><?php echo $sl+=1; ?></td>
					<td><?php echo $record->patient_name; ?></td>
					<td><?php echo $record->p_code;?></td>

					<td><?php echo ($record->has_overall_discount==2)? '<span class=" status btn btn-xs btn-warning disabled">Specific Item</span>' : '<span class="btn btn-xs btn-danger disabled status ">ovarall</span>'; ?></td>
					
						<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->discount_start_date)))?></td> 
						<td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record->discount_end_date)))?></td> 
					<td><?php echo ($record->discount_type==1)? '<span class=" status btn btn-xs btn-info disabled">Parcent(%)</span>' : '<span class="btn btn-xs btn-danger disabled status ">Amount</span>'; ?></td>
					<td><span class="btn btn-xs btn-info" id="discount_details_info" discount_id="<?php echo $record->id; ?>">Details</span></td>
					<td><span class="btn btn-xs btn-success approved-now" id="<?php echo $record->id?>">Approve</span></td>

					<td><span class="btn btn-xs btn-danger approve-cancel" id="<?php echo $record->id?>">Cancel</span></td>

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