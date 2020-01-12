<?php

$sourceType = function($source_id) {
    $type = '';
    if ($source_id == 1) {
        $type = 'Issue to Employee';
    } elseif ($source_id == 2) {
        $type = 'Received from Store';
    }
    return $type;
};

?><div class="admin-box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Department Product Stock Report</h3>

        <h4>Product: <?php echo $product->product_name ?></h4>

        <h5>Overall Stock: <?php echo $product->quantity ?></h5>

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
					<th>Department Name</th>
					<th>Source</th>
                    <th>Issued</th>
                    <th>Received</th>
				</tr>
			</thead>
			<tbody>
			    <?php if (!empty($records)) : ?>
				<?php foreach ($records as $record) : ?>
				<tr>
					<td><?php echo date('d/m/Y H:i', strtotime($record['date'])); ?></td>
					<td><?php echo $record['department_name']; ?></td>
                    <td><?php
                        echo $sourceType($record['source']);
                        if (!empty($record['employee_name']))
                            echo ' -> '.$record['employee_name'] .' ('.$record['employee_code'].')';
                    ?></td>
                    <td><?php echo $record['source'] == 1 ? $record['quantity'] : ''; ?></td>
                    <td><?php echo $record['source'] == 2 ? $record['quantity'] : ''; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php else: ?>
				<tr>
					<td colspan="6"><?php echo lang('bf_msg_records_not_found'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
    </div>

    <?php echo $this->pagination->create_links(); ?>
</div>