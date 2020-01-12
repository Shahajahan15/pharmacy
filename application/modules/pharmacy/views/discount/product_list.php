<?php
$validation_errors = validation_errors();
if ($validation_errors) :
    ?>
    <div class="alert alert-block alert-error fade in">
        <a class="close" data-dismiss="alert">&times;</a>
        <h4 class="alert-heading">Please fix the following errors:</h4>
        <?php echo $validation_errors; ?>
    </div>
    <?php
endif;

?>

<?php

$has_records = isset($records);
?>


<!-- Modal -->
<?php echo form_open($this->uri->uri_string(), 'role="form", class="single-discount-form"'); ?>
<div id="changeDiscount" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Discount</h4>
      </div>
      <div class="modal-body">
      	
        	Discount Percent<input type="text" name="discount" class="form-control" required="">
        	<span class="discount_submit_data">
        		
        	</span>        	
       
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </div>

  </div>
</div>
	<?php echo form_close(); ?>



<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped store_payment">
            <thead>
                <tr class="info">			
                    <th>SL</th>
                    <th>Product</th>                    
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Discount</th>
                    <th>After Discount(SP)</th>
                    <th>From</th>
                    <th>To</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if ($has_records) :               
                foreach ($records as $record) : $record=(object)$record; ?>


                <tr>
                	<td><?php echo $sl+=1; ?></td>
                	<td><?php echo $record->product_name; ?></td>                	
                	<td><?php echo $record->purchase_price; ?></td>
                	<td><?php echo $record->sale_price; ?></td>
                	<td><?php echo $record->discount_parcent; ?></td>

                	<td>                		
                		
                	<?php
                	 	$sale_price=$record->sale_price-(($record->sale_price*$record->discount_parcent)/100); 
                	 	if($sale_price<$record->purchase_price){
                	 		echo '<span style="width:100px" class="btn btn-danger btn-xs change_discount" discount_id="'.$record->discount_id.'" id="'.$record->id.'">'.$sale_price.'</span>';
                	 	}elseif($sale_price==$record->purchase_price){
                	 		echo '<span style="width:100px" class="btn btn-warning btn-xs change_discount" discount_id="'.$record->discount_id.'" id="'.$record->id.'">'.$sale_price.'</span>';
                	 	}else{
                	 		echo '<span style="width:100px" class="btn btn-success btn-xs change_discount" discount_id="'.$record->discount_id.'" id="'.$record->id.'">'.$sale_price.'</span>';
                	 	}
                	 ?></td>

                	<td><?php echo $record->discount_from; ?></td>
                	<td><?php echo $record->discount_to; ?></td>
                </tr>
                


                        
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4"><?php echo lang('bf_msg_records_not_found'); ?></td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>
