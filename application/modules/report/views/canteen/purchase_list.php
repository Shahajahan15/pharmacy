
<div class="box" id="print_id">
<style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<div class="col-md-8 col-md-offset-2">
<?php if ($records) : ?>
<?php echo report_header() ?>
    <div class="text-center">
            <h4>Purchase Report(Canteen)</h4>

            <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
            <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
            <?php else: ?>
               
            <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered report-table">
            <thead>
                <tr>
                    <th>SI</th>
                    <th>Date</th>
                    <th>Purchase No</th>
                    <th>Total Cost</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $sl = 0;
                    $total_price = 0;
                    foreach ($records as $val) : 
                      
                ?>
                    <tr style="background: #B0C4DE">
                        <td><?php echo $sl = $sl + 1;  ?></td>     
                        <td><?php echo $val->purchase_date; ?></td>    
                        <td><?php echo $val->purchase_no; ?></td>
                        <td><?php echo round($val->total_price); ?></td>
                    </tr>
                    <?php $total_price = $total_price+$val->total_price; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?php echo $total_price; ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

<?php else: ?>
    <h4 style="text-align: center;margin: 155px auto;">No Collection Found.</h4>
<?php endif; ?>
    </div>
</div> 
