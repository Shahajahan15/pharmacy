
<div class="box" id="pd_id">

<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>

    <div class="text-center">
        <h3 style="font-weight: bold;">User Wise Collection</h3>
        <h4><?php echo ($records) ? $records[0]->pharmacy_name : ""; ?></h4>

       <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
    <div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report-table">
        
        <thead>
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Cash Receive</th>
                <th>Due Cash Receive</th>
                <th>Total Cash Receive</th>
                <th>Return Taka</th>
                <th>Net Cash</th>
            </tr>
        </thead>
        <?php if ($records) : ?>
        <tbody>
            <?php 
                $t_paid_amount = 0;
                $t_due_paid_amount = 0;
                $t_cash_receive = 0;
                $t_return_amount = 0;
                $t_net_cash = 0;
                foreach($records as $key => $val) :
                    $t_paid_amount += $val->paid_amount;
                    $t_due_paid_amount += $val->due_paid_amount;
                    $t_cash_receive += $val->cash_receive;
                    $t_return_amount += $val->return_amount;
                    $t_net_cash += $val->net_cash;
                    $cls = (($key % 2) == 0) ? "background: #B0C4DE" : "background: #E4E4E4";
            ?>
            <tr style="<?php echo $cls; ?>">
                <td><?php echo $key+1; ?></td>
                <td><?php echo $val->display_name; ?></td>
                <td><?php echo $val->paid_amount; ?></td>
                <td><?php echo $val->due_paid_amount; ?></td>
                <td><?php echo $val->cash_receive; ?></td>
                <td><?php echo $val->return_amount; ?></td>
                <td><?php echo $val->net_cash; ?></td></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th><?php echo $t_paid_amount; ?></th>
                <th><?php echo $t_due_paid_amount; ?></th>
                <th><?php echo $t_cash_receive; ?></th>
                <th><?php echo $t_return_amount; ?></th>
                <th><?php echo $t_net_cash; ?></th>
            </tr>
        </tfoot>
        <?php else: ?>
        <tr>
            <td colspan="14" style="text-align: center;font-size: 20px;padding: 132px;">No records found</td>
        </tr>
     <?php endif; ?>
        
    </table>
    </div>
    </div>

    </div>
    
</div>