<?php

$mainSourceType = function($source_id) {
    $sources = array(
        1 => 'Sale',
        2 => 'Sub Pharmacy Issue',
        3 => 'Sale Return',
    );

    return isset($sources[$source_id]) ? $sources[$source_id] : '';
};

$deptSourceType = function($source_id) {
    $sources = array(
        1 => 'Department Issue',
        2 => 'Normal Sale',
        3 => 'Sale Return',
    );

    return isset($sources[$source_id]) ? $sources[$source_id] : '';
};

?>
<div class="admin-box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Pharmacy Stock &amp; Value Details</h3>

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
                    <th class="text-center" colspan="4">Main Pharmacy</th>
                    <th class="text-center" colspan="5">Sub Pharmacies</th>
                </tr>
                <tr>
                    <th class="text-center">Source</th>
                    <th class="text-center">Issue</th>
                    <th class="text-center">Sale</th>
                    <th class="text-center">Return</th>

                    <th class="text-center">Sub Pharmacy Name</th>
                    <th class="text-center">Source</th>
                    <th class="text-center">Issue</th>
                    <th class="text-center">Sale</th>
                    <th class="text-center">Return</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)) : ?>
                <?php foreach ($records as $record) : ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($record['date'])); ?></td>

                    <td><?php
                        echo $mainSourceType($record['main_source']);
                        if ($record['issue_sub_pharmacy_name'])
                            echo ' -> '. $record['issue_sub_pharmacy_name']
                    ?></td>
                    <td><?php echo $record['main_stock_issue']; ?></td>
                    <td><?php echo $record['main_stock_sale']; ?></td>
                    <td><?php echo $record['main_stock_return']; ?></td>

                    <td><?php echo $record['sub_pharmacy_name']; ?></td>
                    <td><?php
                        echo $deptSourceType($record['sub_source']);
                        // if ($record['employee_name'])
                        //     echo ' -> '. $record['employee_name'] .' ('.$record['employee_code'].')'
                    ?></td>
                    <td><?php echo $record['sub_stock_issue']; ?></td>
                    <td><?php echo $record['sub_stock_sale']; ?></td>
                    <td><?php echo $record['sub_stock_return']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <?php echo $this->pagination->create_links(); ?>

</div>