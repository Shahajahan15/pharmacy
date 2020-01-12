<div class="text-center">
		<h2>Memorial Hospital</h2>
<h5>Patient Serial Report</h5>
  <?php if (isset($from_date) && !empty($from_date)&& !empty($to_date)):?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date))?> to <?php echo date('d/m/Y',strtotime($to_date)) ?></h6>
        <?php endif; ?>        
	</div>

<div class="admin-box">
	
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped">
			<thead>
				<tr>					
					<th>Schedule Date</th>
					<th>Serial No.</th>
					<th>Token No.</th>
					<th>Doctor Name</th>
					<th>Patient ID</th>
					<th>Patient Name</th>
					<th>Source</th>
					<th>Contact No.</th>
				</tr>
			</thead>
			<tbody>
			    <?php if (!empty($records)) : ?>
				<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo $record['schedule_date']; ?></td>
					<td><?php echo $record['serial_no']; ?></td>
					<td><?php echo $record['token_no']; ?></td>
					<td><?php echo $record['doctor_full_name']; ?><br/><?php echo '<em>'.$record['department_name'].'</em>'?></td>
					<td><?php echo $record['patient_id'] ?></td>
					<td><?php echo $record['patient_name'] ?></td>
					<td><?php echo $serial_sources[$record['serial_source']]; ?></td>
					<td><?php echo $record['contact_no']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="8"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
    
    <?php echo $this->pagination->create_links(); ?>
</div>