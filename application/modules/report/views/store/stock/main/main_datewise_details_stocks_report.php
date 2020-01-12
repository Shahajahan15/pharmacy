<?php

$sourceType = function($source_id) {
    $sources = array(
		1 => 'Return Products',
		2 => 'Return Product Replace',
        3 => 'Issue to Department',
		4 => 'Purchase Received',
        5 => 'Opening Balance',
    );

	return isset($sources[$source_id]) ? $sources[$source_id] : '';
};

?>

<div class="admin-box">

    <?php echo report_header() ?>
	<div class="text-center">
        <h3>Store Product Stock Report</h3>

        <h4><strong>Store Name:</strong> <span><?php echo $store['STORE_NAME'] ?></span></h4>

		<h5><strong>Product Name:</strong> <span><?php echo $product['product_name'] ?></span></h5>

        <h5><strong>Current Stock:</strong> <span><?php echo $product['quantity'] ?></span></h5>

		<h5><strong>Date:</strong> <span><?php echo date('d/m/Y', strtotime($date)) ?></span></h5>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">

		<table class="table table-striped table-bordered report-table">
			<thead>
				<tr>
					<th>Date &amp; Time</th>
					<th>Source</th>
					<th>Received</th>
					<th>Issued</th>
				</tr>
			</thead>
			<tbody>
			    <?php if (!empty($records)) : ?>
				<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo date('d/m/Y H:i', strtotime($record['created_date'])); ?></td>
					<td><?php
                        echo $sourceType($record['source']);
                        if ($record['issue_department_name']) echo ' -> '. $record['issue_department_name']
                    ?></td>
					<td><?php echo $record['type'] == '1' ? $record['quantity'] : 0; ?></td>
					<td><?php echo $record['type'] == '2' ? $record['quantity'] : 0; ?></td>
				</tr>
				<?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
				<?php endif; ?>
			</tbody>
		</table>

    </div>

    <?php echo $this->pagination->create_links(); ?>

</div>