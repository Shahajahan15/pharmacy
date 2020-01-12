
<div class="box" id="print_id">
<a href="<?php echo site_url(SITE_AREA . '/pharmacy_wise_collection/report/index');?>"><button type="button" class="btn btn-success pull-right">
      <div class="glyphicon glyphicon-arrow-left pull-right"></div>
    </button></a>

    <style type="text/css">
    @import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
<?php echo report_header() ?>
<div class="text-center">
    <h3 style="font-weight: bold;">User Wise Collection</h3>
    <h5></h5>

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

                <tbody>
                    <?php 
                    $total_collect=0;
                    $total_due_collect=0;
                    $total_return_amount=0;
                    $sl=0;foreach($sum_collection2 as $record){

                        $total_collect +=$record['total_collect'];
                        $total_due_collect +=$record['total_due'];
                        $total_return_amount +=$record['total_return_amount']; ?>

                        <tr>

                            <td><?php echo $sl+=1; ?></td>
                            <td><?php echo $record['username'];?></td>
                            <td><?php echo round($record['total_collect']);?></td>
                            <td><?php echo round($record['total_due']);?></td>
                            <td><?php echo round($record['total_due'] + $record['total_collect']); ?></td>
                            <td><?php echo round($record['total_return_amount']);?></td>
                            <td><?php echo round(($record['total_collect'] + $record['total_due']) - $record['total_return_amount']);?></td>

                        </tr>
                        <?php }?>
                        <td colspan="2"><span class="pull-right"><strong>Total=</strong></span></td>       
                        <td colspan=""><strong><?php echo round($total_collect);?><strong></td>
                            <td colspan=""><strong><?php echo round($total_due_collect);?><strong></td>
                                <td colspan=""><strong><?php echo round($total_collect + $total_due_collect);?><strong></td>
                                   <td colspan=""><strong> <?php echo round($total_return_amount);?><strong></td>
                                       <td colspan=""><strong> <?php echo round(($total_collect + $total_due_collect)-$total_return_amount);?><strong></td>
                                       </tbody>
                                   </table>
                               </div>
                           </div>

                       </div>

                   </div>