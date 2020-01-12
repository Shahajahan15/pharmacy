
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url()."themes/admin/css/bootstrap.css"; ?>" print;
</style>
    <?php echo report_header() ?>
    <div class="text-center">
        <h3>Test Wise Collection</h3>

      <?php if (isset($first_date, $second_date) && $first_date != date('Y-m-d 00:00:00')  && !empty($first_date) && !empty($second_date)) : ?>
        <h6>Date from <?php echo date('d/m/Y',strtotime($first_date)) ?> to <?php echo date('d/m/Y',strtotime($second_date)) ?> </h6>
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
                <th>Date</th>
                <th>Test Name</th>
                <th>No of Test</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total Price</th>

            </tr>
        </thead>
        
        <tbody>
       <?php 
      $total_test = 0;
      $total_price = 0;
      $total_discount = 0;
       foreach($records as $record){?>
           <tr>
          
           <td><?php echo $record->test_date;?></td>
           <td><?php echo $record->test_name;?></td>
           <td><?php echo $record->total;?></td>
           <td><?php echo $record->test_taka;?></td>
           <td><?php echo $discount= ($record->discount+$record->d_discount_amount);?></td>
           <td><?php echo $total_p=($record->total*$record->test_taka)-$discount;?></td>

           </tr>
           <?php 
          $total_test = $total_test + $record->total;
          $total_price = $total_price + $total_p;
          $total_discount = $total_discount + $discount;

         }?>
       
          <tr>
            <td colspan="2">
            <strong>Total:</strong>  
            </td>
            <td>
              <?php echo $total_test;?>
            </td> 
            <td>
              
            </td>
            <td>
              <?php echo $total_discount;?>
            </td>
            <td>
              <?php echo $total_price;?>
            </td> 
          </tr>
        </tbody>
       
        
    </table>
          

    </div>
    </div>

    </div>
</div>
  <?php echo $this->pagination->create_links();?>


