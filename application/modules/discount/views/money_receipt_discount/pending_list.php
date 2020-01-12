<?php
$num_columns	= 7;
$has_records	= isset($records) && count($records);
?>
<style>

#search{ margin-right:7px; }
</style>
	<?php echo form_open($this->uri->uri_string()); ?>


		<table class="table table-striped">
			<thead>
				<tr>

					<th>Service Name</th>
					<th>MR.No.</th>
					<th>Patient Id</th>
					<th>Patient Name</th>
					<th>MR. Discount</th>
					<th>Approve Discount</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
					$record = (object) $record;

					$discount_info = mrDiscountInfo($record->service_id, $record->source_id);
				?>
				<tr>
                    <td><?php e($discount_info->service_name) ?></td>
					<td><?php e($discount_info->mr_no) ?></td>
					<td><?php e($discount_info->patient_code) ?></td>
					<td><?php e($discount_info->patient_name) ?></td>
					<td><?php e($record->mr_discount) ?></td>
					<td><input type="text" name="mr_approve_discount" class="mr_approve_discount" value="<?php echo $record->mr_discount; ?>"><input type="hidden" name="net_bill" class="net_bill" value="<?php echo $record->net_bill; ?>"></td>
					<td>
						<p class="btn btn-info btn-xs approve-now" id="<?php echo $record->id; ?>">Approve</p>
						<p class="btn btn-warning btn-xs cancel-now" id="<?php echo $record->id; ?>">Cancel</p>
					</td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
				<td colspan="<?php echo $num_columns;?> ">
				<?php echo lang("bf_msg_no_records_found")?>
                </td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
	echo form_close();
	//echo $this->pagination->create_links();
	?>