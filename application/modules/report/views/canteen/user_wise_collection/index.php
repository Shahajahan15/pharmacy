
<div class="box" id="print_id">
<style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<div class="col-md-8 col-md-offset-2">
<?php if ($canteen_collectin) : ?>
<?php echo report_header() ?>
    <div class="text-center">
            <h4>User Wise Cash Collection(Canteen)</h4>

            <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
            <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
            <?php else: ?>
               
            <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered report-table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Collection</th>
                    <th>Return</th>
                    <th>Cash</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $tot_collection_amount = 0;
                    $tot_return_amount = 0;
                    foreach ($canteen_collectin as $key => $val) : 
                       $tot_collection_amount += $val['collectin_amount'];
                       $tot_return_amount += $val['return_amount'];
                ?>
                    <tr style="background: #B0C4DE">
                        <td><?php echo $val['user_name']; ?></td>     
                        <td><?php echo round($val['collectin_amount']); ?></td>    
                        <td><?php echo round($val['return_amount']); ?></td>
                        <td><?php echo round($val['collectin_amount'] - $val['return_amount']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th><?php echo round($tot_collection_amount); ?></th>
                    <th><?php echo round($tot_return_amount); ?></th>
                    <th><?php echo round($tot_collection_amount - $tot_return_amount); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php else: ?>
    <h4 style="text-align: center;margin: 155px auto;">No Collection Found.</h4>
<?php endif; ?>
    </div>
</div>