<div class="admin-box" id="print_id">

<a href="<?php echo site_url(SITE_AREA . '/pharmacy_wise_stock/report/index');?>"><button type="button" class="btn btn-success pull-right">
      <div class="glyphicon glyphicon-arrow-left pull-right"></div>
    </button></a>


<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>

    <?php if(!empty($records)): ?>
<div class="text-center">
        <h3>Pharmacy  Stock Details(<?php 
        if(isset($records)){
         echo $records[0]->pharmacy_name;
        }
        else{
          echo "Main Pharmacy";
        }
          ?>)</h3>
        <h4 class="text-center"> Medicine Name:&nbsp;<span style="font-weight: bold;"><?php echo $records['0']->product_name;?></span> &nbsp; <span style="font-weight: bold;"></span>&nbsp; Current Stock : <span style="font-weight: bold;"><?php  if(isset($total_qty_price)){echo $total_qty_price->quantity_level;}?></span></h4>
        <?php if (isset($from_date, $to_date) && !empty($from_date) && !empty($to_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($from_date)) ?> to <?php echo date('d/m/Y',strtotime($to_date)) ?> </h6> 
        <?php endif; ?>
    </div>
  <?php endif?>
    <br/>
    <br/>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered report-table">
            <thead>
                <tr>
                    <th>SL</th>                 
                    <th>Entry Date</th>                  
                    <th>Source Type</th>
                    <th>Sale No.</th>
                  
                    <th>Quantity</th>
                    <th>Type</th>
                    <th>Entry By</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach($records as $key => $record){?>
                <tr>
                <td><?php echo $key+1; ?></td>
                <td><?php echo date('d/m/Y (h:i:sa)',strtotime($record->created_date)); ?></td>
                <td><?php echo $record->source_name; ?></td>
                <td><?php echo $record->sale_no; ?></td>
                <td><?php echo $record->quantity;?></td>
                <td><?php echo $record->stock_type; ?></td>
                <td><?php echo $record->display_name;?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php if ($records) {
      echo $this->pagination->create_links();
    } ?>


