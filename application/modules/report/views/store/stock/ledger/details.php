<?php

$mainSourceType = function($source_id) {
    $sources = array(
        1 => 'Return Products',
        2 => 'Return Product Replace',
        3 => 'Issue to Department',
        4 => 'Purchase Received',
        5 => 'Opening Balance',
    );

    return isset($sources[$source_id]) ? $sources[$source_id] : '';
};

$deptSourceType = function($source_id) {
    $sources = array(
        1 => 'Issue to Employee',
        2 => 'Received from Store',
    );

    return isset($sources[$source_id]) ? $sources[$source_id] : '';
};

?>
<div class="admin-box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Store Stock &amp; Value Details</h3>

        <h5><strong>Product Name:</strong> <span><?php echo $product['product_name'] ?></span></h5>

        <h5><strong>Current Stock:</strong> <span><?php echo $product['quantity'] ?></span></h5>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php endif; ?>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12">

        <table class="table table-striped table-bordered report-table">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">Date</th>
                    <th class="text-center" colspan="3">Main Store</th>
                    <th class="text-center" colspan="4">Department Store</th>
                </tr>
                <tr>
                    <th class="text-center">Source</th>
                    <th class="text-center">Issued</th>
                    <th class="text-center">Received</th>

                    <th class="text-center">Department Name</th>
                    <th class="text-center">Source</th>
                    <th class="text-center">Issued</th>
                    <th class="text-center">Received</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)) : ?>
                <?php foreach ($records as $record) : ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($record['date'])); ?></td>
                    <td><?php
                        echo $mainSourceType($record['main_source']);
                        if ($record['department_name'])
                            echo ' -> '. $record['department_name']
                    ?></td>
                    <td><?php echo $record['main_stock_out']; ?></td>
                    <td><?php echo $record['main_stock_in']; ?></td>
                    <td><?php echo $record['dept_department_name']; ?></td>
                    <td><?php
                        echo $deptSourceType($record['dept_source']);
                        if ($record['employee_name'])
                            echo ' -> '. $record['employee_name'] .' ('.$record['employee_code'].')'
                    ?></td>
                    <td><?php echo $record['dept_stock_out']; ?></td>
                    <td><?php echo $record['dept_stock_in']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <?php echo $this->pagination->create_links(); ?>

</div>