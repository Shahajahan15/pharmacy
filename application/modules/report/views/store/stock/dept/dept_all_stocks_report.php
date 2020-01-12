<div class="admin-box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Department Overall Stock Report</h3>
    </div>

    <br/>
    <br/>

	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class="table table-striped table-bordered report-table">
			<thead>
				<tr>
					<th>Product Code</th>
					<th>Product Name</th>
					<th>Current Stock</th>
					<th>Details</th>
				</tr>
			</thead>
			<tbody>
			    <?php if (!empty($records)) : ?>
				<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo $record['product_code']; ?></td>
					<td><?php echo $record['product_name']; ?></td>
					<td><?php echo $record['quantity_level']; ?></td>
					<td>
						<a href="<?php echo site_url('admin/store_stock_dept/report/prod_details/'.$record['product_id']) ?>" class="btn btn-primary btn-sm" >Details</a>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
    </div>

    <?php echo $this->pagination->create_links(); ?>
</div>