
<div class="box" id="print_id">
<style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<!--Total transection-->
<div class="col-md-10 col-md-offset-1">
    <?php echo report_header() ?>
    <div class="text-center">
            <h4>Total Transectoin</h4>

            <?php if (isset($from_dates, $to_dates) && !empty($from_dates) && !empty($to_dates)) : ?>
            <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
            <?php else: ?>
               
            <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered report-table">
            <thead>
                <tr>
                    <th>Total CashIn</th>
                    <th>Total CashOut</th>
                    <th>Total Transection</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $total_cashin; ?></td>
                    <td><?php echo $total_cashout; ?></td>
                    <td><?php e($total_cashin - $total_cashout); ?></td>
                </tr>
            </tbody> 
            </table>   
</div>
</div>

<!--Cash In Start-->
<div class="col-md-10 col-md-offset-1">
<?php if ($recordes) : ?>
    <div class="text-center">
            <h4>Cash In</h4>

            <?php if (isset($from_dates, $to_dates) && !empty($from_dates) && !empty($to_dates)) : ?>
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
                    <th>T. Type</th>
                    <th>Total Cost</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $sl = 0;
                    $total_price = 0;
                    foreach ($recordes as $val) : 
                      
                ?>
                    <tr style="background: #B0C4DE">
                        <td><?php echo $sl = $sl + 1;  ?></td>     
                        <td><?php echo custom_date_format($val->mydate); ?></td>    
                        <td><?php  if($val->master_id == 0){echo "Due Collection";}
                        elseif ($val->master_id == 999999999999) {
                            echo "Purchase Return";
                        }
                        else{
                         echo "Sale";   
                        }; ?></td>
                        <td><?php echo round($val->amount); ?></td>
                    </tr>
                    <?php $total_price = $total_price+$val->amount; ?>
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
<!--Cash Out Start-->
<div class="col-md-10 col-md-offset-1">
<?php if ($records) : ?>

    <div class="text-center">
            <h4>Cash Out</h4>

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
                    <th>T.Type</th>
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
                        <td><?php echo $val->mydate; ?></td>    
                        <td><?php if($val->type == 0){
                            echo "Purchase Cost";
                        }
                        elseif ($val->type == 1) {
                            echo "Return Sale";
                        }; ?></td>
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
    
<?php endif; ?>
    </div>       
</div> 
