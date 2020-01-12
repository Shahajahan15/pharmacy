<?php

$total = 0;

if (isset($prev_page_stock)) {
    $total += $prev_page_stock;
}

?>
<div class="admin-box">

    <?php if (strtolower($current_user->role_name) == 'administrator') : ?>
    <div class="clearfix form-group">
        <label class="col-md-2">Select A Store</label>
        <div class="col-md-4">
            <select id="main_store_select" class="form-control " name="store_id" data-href="<?php echo site_url('admin/store_stock_main/report/datewise') ?>">
                <option value="" disabled>-- Select A Store --</option>
                <?php foreach ($stores as $store_op) : ?>
                <option value="<?php echo $store_op['STORE_ID'] ?>" <?php echo $store_op['STORE_ID'] == $store_id ? 'selected':'' ?>><?php echo $store_op['STORE_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <hr/>

    <?php endif; ?>

    <?php echo report_header() ?>
	<div class="text-center">
        <h3>Main Store Stock Report</h3>

        <h3>Store: <span><?php echo $store->STORE_NAME ?></span></h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php endif; ?>
	</div>

	<br/>
	<br/>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<table class="table table-striped table-bordered report-table">
			<thead>
				<tr>
					<th>Date</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
					<th>Previous Stock</th>
					<th>Received</th>
					<th>Issued</th>
                    <th>Current Stock</th>
					<th>Current Value</th>
					<th>Details</th>
				</tr>
			</thead>
			<tbody>

                <?php if (isset($prev_page_stock)): ?>
                <tr>
                    <td colspan="7" class="text-right"><strong>Previous Page Total Amount</strong></td>
                    <td><?php echo number_format($prev_page_stock,'2') ?></td>
                    <td></td>
                </tr>
                <?php endif; ?>

                <?php if (!empty($records)) : ?>
				<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo date('d/m/Y',strtotime($record['date'])); ?></td>
                    <td><?php echo $record['product_code']; ?></td>
                    <td><?php echo $record['product_name']; ?></td>
					<td><?php echo round($record['prev_stock']) ?: 0; ?></td>
					<td><?php echo round($record['stock_in']) ?: 0; ?></td>
					<td><?php echo round($record['stock_out']) ?: 0; ?></td>
                    <td><?php echo round($record['last_stock']) ?: 0; ?></td>
					<td><?php echo number_format($record['current_value'],2) ?: 0; ?></td>
					<td>
						<a href="<?php echo site_url('admin/store_stock_main/report/datewise_details/'.$record['store_id'].'/'.$record['product_id'].'/'.$record['date']) ?>" class="btn btn-primary btn-xs main_store_details" >Details</a>
					</td>
				</tr>
                <?php $total += $record['current_value']; ?>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7" class="text-right"><strong>Total</strong></td>
					<td><?php echo $total ?></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
    </div>

</div>

<?php echo $pagination; ?>