
<div class="box" id="print_id">
<a href="<?php echo site_url(SITE_AREA . '/pharmacy_wise_collection/report/index');?>"><button type="button" class="btn btn-success pull-right">
      <div class="glyphicon glyphicon-arrow-left pull-right"></div>
    </button></a>
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3 style="font-weight: bold;">User Details Collection</h3>

        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6>
        <?php else: ?>
            <h6>Date <?php echo date('d/m/Y'); ?> </h6>
        <?php endif; ?>
    </div>
    <div class="row">
    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-8 col-lg-offset-2">
    <div class="table-responsive">
    <table class="table table-bordered report table">
        
        <thead>
            <tr class="active">
                <th>Date</th>
                <th>Tot Bill</th>
                <th>Tot Normal Discount</th>
                <th>Tot Service Discount</th>
                <th>Tot Less Discount</th>
                <th>Tot Paid</th>
                <th>Tot Due</th>
            </tr>
        </thead>
        
        <tbody>
            <?php 
             foreach($pharmacy_wise_details_collection as $record){
               
                ?>
            <tr>
                <td><?php echo custom_date_format($record->created_date);?></td>
                <td><?php echo $record->tot_bill?></td>
                <td><?php echo $record->tot_normal_discount?></td>
                <td><?php echo $record->tot_service_discount?></td>
                <td><?php echo $record->tot_less_discount?></td>
                <td><?php echo $record->tot_paid?></td>
                <td><?php echo $record->tot_due?></td>
              
            </tr>
         <?php }?>
         
        </tbody>
       
        
    </table>
    </div>
    </div>

    </div>
    
</div>