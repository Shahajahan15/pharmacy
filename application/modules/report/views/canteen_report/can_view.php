<style type="text/css">
    table.table tbody tr td {vertical-align: middle;}
</style>
<div class="box" id="print_id">
<style type="text/css">
@import "<?php echo base_url().'themes/admin/css/bootstrap.css'; ?>" print;
</style>
   <?php echo report_header() ?>
    <div class="text-center">
        <h3>Cafetaria Stock Report</h3>
        <h5><strong>Total Current Stock : </strong><?php echo $total_quantity; ?></h5>
        <h5><strong>Total Sale Price : </strong><?php echo $total_amount; ?><strong> TK</strong></h5>

      
        
    </div>
    <div class="table-responsive">
    <table class="table table-bordered ">
        
        <thead>
            <tr>
            	<th>SL</th>
                <th>Company Name</th>
                <th>Category Name</th>
                <th>Product Name</th>
                <th>Current Stock</th>
                <th>Unit Sale Price</th>
                <th>Total Stock Price</th>
 
                
            </tr>
        </thead>
        <tbody>
      


     <?php $sl=1;foreach($records as $record){?>
        <tr>
            <td><?php echo $sl++;?></td>
            <td><?php echo $record->company_name;?></td>
            <td><?php echo $record->category_name;?></td>
            <td><?php echo $record->product_name;?></td>
            <td><?php echo round($row1 = $record->quantity_level);?></td>
            <td><?php echo round($row2 = $record->sale_price);?></td>
            <td><?php echo round($row1*$row2);?></td>

  
        </tr>
 
     <?php }?>
         
        
        </tbody>
         
    </table>
   
    
    </div>
</div>
 <?php echo $this->pagination->create_links(); ?>