<div class="admin-box">

    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Pharmacy Stock and Value</h3>
    </div>

    <br/>
    <br/>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered report-table">
            <thead>
                <tr>
                    <!-- <th>Product Code</th> -->
                    <th>Product Name</th>
                    <th>Main Pharmacy</th>
                    <th>Sub Pharmacies</th>
                    <th>Total</th>
                    <th>Current Value</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)) : ?>
                <?php foreach ($records as $record) : ?>
                <tr>
                    <!-- <td><?php //echo $record['product_code']; ?></td> -->
                    <td><?php echo $record['product_name']; ?></td>
                    <td><?php echo $record['main_stock']; ?></td>
                    <td><?php echo $record['sub_stock']; ?></td>
                    <td><?php echo $record['total_stock']; ?></td>
                    <td><?php echo number_format($record['current_value'],2); ?></td>
                    <td>
                        <a href="<?php echo site_url('admin/pharmacy_stock_ledger/report/details/'.$record['product_id']) ?>" class="btn btn-primary btn-xs" >Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5"><?php echo lang('bf_msg_records_not_found'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo $this->pagination->create_links(); ?>
</div>
