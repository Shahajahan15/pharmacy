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
					<th>SL</th>
					<th>Patient Name</th>
					<th>Patient Id</th>
					<th>Agent</th>
					<th>Discount By</th>					
					<th>Discount Type</th>
					<th>Service</th>
					<th>Sub Service</th>
					<th>Doctor Dic(%)</th>
					<th>Hospital Dic(%)</th>
					<th>Total Dic</th>
					<th>Approved</th>
				</tr>
			</thead>
			

			<tbody>
				<?php
				if ($records) : 
					$sl=0;
					foreach ($records as $record) :
						$record=(object)$record;
				?>
				<tr>					
					<td><?php echo $sl+=1; ?></td>
					<td><?php echo $record->patient_name; ?></td>	
					<td><?php echo $record->patient_code; ?></td>	
					<td><?php 
						if($record->agent_type == 1): 
							echo "External Doctor";
						elseif ($record->agent_type == 2) :
							echo "Reference";
						elseif ($record->agent_type == 3) :
							echo "Internal Doctor";
						endif;

					?></td>					
					<td><?php echo $record->doctor_name; ?></td>					
					<td><?php echo ($record->discount_type==1)?'<p class="btn btn-xs btn-success">Overall</p>':'<p class="btn btn-xs btn-info">Specific</p>'; ?></td>
					<td><?php echo $record->service_name; ?></td>
					<td><?php echo $record->sub_service_name; ?></td>
					<td><span class="dr_discount"><?php echo $record->dr_discount; ?></span></td>
					<td><input type="text" name="h_discount" class="h_discount" value="<?php echo $record->hospital_discount; ?>"></td>
					<td><span class="tot_discount"><?php echo sprintf("%.2f", ($record->dr_discount + $record->hospital_discount)); ?></span></td>
					<td><?php echo ($record->status==1)?'<p class="btn btn-info btn-xs">Approved</p>':'<p class="btn btn-success btn-xs approved-now" id="'.$record->id.'">Approved Now</p>'; ?></td>


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