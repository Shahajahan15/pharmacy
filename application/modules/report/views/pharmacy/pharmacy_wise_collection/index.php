<style type="text/css">
    .not_display{
        display: none;
    }
</style>
<div class="box" id="pcash_id">
    <style type="text/css">
        @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
        @media print
           {
            .display{
                display: none;
            }
           }
    </style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3 style="font-weight: bold;">Pharmacy Wise Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
          <?php echo date('d/m/Y');?>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
            <div class="table-responsive">
                <table class="table table-bordered report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pharmacy Name</th>
                            <th>Cash Receive</th>
                            <th>Due Cash Receive</th>
                            <th>Total Cash Receive</th>
                            <th>Return Amount</th>
                            <th>Net Cash</th>
                        </tr>
                    </thead>

                <?php if ($pharmacy_id==200 && $role==1) : ?>
                    <tbody>
                        <?php
                        $t_paid_amount = 0;
                        $t_due_paid_amount = 0;
                        $t_cash_receive = 0;
                        $t_return_amount = 0;
                        $t_net_cash = 0;


                        //echo '<pre>'; print_r($records); exit();

                        foreach ($records as $key => $val) :
                            $t_paid_amount += $val->paid_amount;
                            $t_due_paid_amount += $val->due_paid_amount;
                            $t_cash_receive += $val->cash_receive;
                            $t_return_amount += $val->return_amount;
                            $t_net_cash += $val->net_cash;
                            $cls = (($key % 2) == 0) ? "background: #B0C4DE" : "background: #E4E4E4";
                        ?>

                                <tr style="<?php echo $cls; ?>">
                                    <td><?php echo $key+1; ?></td>
                                    <td class="display"><a href="<?php echo site_url(SITE_AREA . '/pharmacy_wise_collection/report/details/').$val->pharmacy_id ?>"><?php echo $val->pharmacy_name; ?></a></td>
                                    <td class="not_display"><?php echo $val->pharmacy_name; ?></td>
                                    <td><?php echo $val->paid_amount; ?></td>
                                    <td><?php echo $val->due_paid_amount; ?></td>
                                    <td><?php echo $val->cash_receive; ?></td>
                                    <td><?php echo $val->return_amount; ?></td>
                                    <td><?php echo $val->net_cash; ?></td>
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
                    <tbody>
                    <?php
                    $t_paid_amount = 0;
                    $t_due_paid_amount = 0;
                    $t_cash_receive = 0;
                    $t_return_amount = 0;
                    $t_net_cash = 0;


                    //echo '<pre>'; print_r($records); exit();

                    foreach ($records as $key => $val) :
                        $t_paid_amount += $val->paid_amount;
                        $t_due_paid_amount += $val->due_paid_amount;
                        $t_cash_receive += $val->cash_receive;
                        $t_return_amount += $val->return_amount;
                        $t_net_cash += $val->net_cash;
                        $cls = (($key % 2) == 0) ? "background: #B0C4DE" : "background: #E4E4E4";
                        ?>
                        <?php if ($pharmacy_id==$val->pharmacy_id)
                    {
                        ?>
                        <tr style="<?php echo $cls; ?>">
                            <td><?php echo $key+1; ?></td>
                            <td class="display"><a href="<?php echo site_url(SITE_AREA . '/pharmacy_wise_collection/report/details/').$val->pharmacy_id ?>"><?php echo $val->pharmacy_name; ?></a></td>
                            <td class="not_display"><?php echo $val->pharmacy_name; ?></td>
                            <td><?php echo $val->paid_amount; ?></td>
                            <td><?php echo $val->due_paid_amount; ?></td>
                            <td><?php echo $val->cash_receive; ?></td>
                            <td><?php echo $val->return_amount; ?></td>
                            <td><?php echo $val->net_cash; ?></td>
                        </tr>

                        <?php
                    }else
                    { ?>

                    <?php }
                        ?>



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
                <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>