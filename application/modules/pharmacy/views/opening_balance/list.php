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

$has_records = isset($records) && is_array($records) && count($records);
?>
<div id='search_result'>
<div class="admin-box">
    <div class="col-sm-12 col-md-12 col-lg-12">
<?php echo form_open($this->uri->uri_string()); ?>
        <table class="table table-striped m_ph_payment">
            <thead>
                <tr>
                    <th>Category Id</th>
                    <th>Company Name</th>
                    <th>Product Name</th>
                    <th>Product Opening Qnty</th>   
                    <th>Action</th>          
                    
                </tr>
            </thead>
            <tbody>
        <?php foreach($records as $record){?>
          <tr>
              <td><?php echo $record->category_name; ?></td>
              <td><?php echo $record->company_name;?></td>
              <td><?php echo $record->product_name;?></td>  
               <td>
                                <input type="hidden" name="product_id[]" class="product_id form-control" value="<?php echo $record->product_id; ?>">
                                <input type="hidden" name="op_id[]" class="op_id form-control" value="<?php echo $record->id; ?>">
                                <input type="hidden" name="updated_qnty[]" class="updated_qnty form-control" value="<?php echo $record->updated_qnty; ?>">
                                <input type="hidden" name="pharmacy_id[]" class="pharmacy_id form-control" value="<?php echo $record->pharmacy_id; ?>">
                                <input type="hidden" name="qnty_c[]" value="<?php echo $record->qnty;?>" class="qnty_c form-control real-number">
                                <input type="text" name="qnty[]" value="<?php echo $record->qnty;?>" class="qnty form-control real-number">
                                <p class="qnty-message" style="color:red"></p>
                            </td>
           <td> <button type="button" class="btn btn-xs submit-op-balance-update">update</button></td>
             
          </tr>
               <?php } ?>        
                          
                            
                    
   
            </tbody>
        </table>
<?php echo form_close(); ?>
    </div>
</div>
</div>
<?php echo $this->pagination->create_links();?>



