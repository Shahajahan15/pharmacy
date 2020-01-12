
<div class="text-center">
		<h2>Memorial Hospital</h2>
<h4>Patient Registration Report</h4>
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
				    <th>Patient ID</th>
					<th>Patient Name</th>				
					<th>Age</th>
					<th>Sex</th>
					<th>Registered on</th>		
					<th>Register Fee</th>			
					<th>Contact No.</th>
				</tr>
			</thead>
			<tbody>
			    <?php if (!empty($records)) : ?>
				<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo $record['patient_id']; ?></td>
					<td><?php echo $record['patient_name']; ?></td>
					<td><?php echo $record['age']; ?></td>
					<td><?php echo $sexs[$record['sex']]; ?></td>
					 <td><?php echo date('d-m-Y',strtotime(str_replace('/','-',
                                $record['registered_date'])))?></td>
					<td><?php echo $record['register_fee_paid'] == '1' ? '<span class="label label-primary">Yes</span>' : '<span class="label label-danger">No</span>'; ?></td>
					<td><?php echo $record['contact_no']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="7"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>
    
    <?php echo $this->pagination->create_links(); ?>
</div>