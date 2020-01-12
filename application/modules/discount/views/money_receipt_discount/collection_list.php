<?php
$num_columns	= 7;
$has_records	= isset($records) && count($records);
?>
<style>

#search{ margin-right:7px; }
</style>
	<?php echo form_open($this->uri->uri_string()); ?>


		<table class="table table-striped pending_list">
			<thead>
				<tr>

					<th>Service Name</th>
					<th>MR.No.</th>
					<th>Patient Id</th>
					<th>Patient Name</th>
					<th>MR. Discount</th>
					<th>Approve Discount</th>
					<th>Approve By</th>
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
					<td><?php echo $record->mr_approve_discount; ?></td>
					<td><?php echo ($record->emp_name) ? $record->emp_name :  "NILL" ?></td>
					<td>
						<p class="btn btn-info btn-xs collection-show" id="<?php echo $record->id; ?>">Collection</p>
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