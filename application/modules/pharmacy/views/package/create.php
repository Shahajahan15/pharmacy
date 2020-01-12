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

<style type="text/css">

    .table tbody tr td{
        margin: 0;
        padding:1px;
    }
    .table thead tr th{
        margin: 0;
        padding:1px;
    }.table tfoot tr th{
        margin: 0;
        padding:1px;
    }
    .plus-minus{
        width:20px;
        height: 25px;
    }
    .panel-title{
    }
    .style-border{
            border: 3px solid #3c8dbc;
            padding: 5px;
            background: whitesmoke;
            margin: 2px;
        }

</style>

<div class="box box-primary">
    
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal pharmacy-sale-form"'); ?>


    <fieldset>
    	<legend>Package info</legend>
    	<div class="col-sm-12 col-md-6 col-lg-3">
        	<div class="form-group">
        		<label class="control-label">Package Name<span style="color:red">*</span></label>
        		<input type="text" name="package_name" class="form-control package_name" value="<?php echo isset($package->package_name)? $package->package_name:'' ?>" required="">
        	</div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
        	<div class="form-group">
        		<label class="control-label">Package Price<span style="color:red">*</span></label>
        		<input type="text" name="package_price" class="form-control package_price" value="<?php echo isset($package->package_price)? $package->package_price:'' ?>" required="" readonly="">
        	</div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-3">
        	<div class="form-group">
        		<label class="control-label">Discount Taka</label>
        		<input type="text" name="discount_taka" class="form-control discount_taka on-focus-selected" value="<?php echo isset($package->package_discount)?$package->package_discount:'0'?>" required="">
        	</div>
        </div>
    </fieldset>



    <fieldset>
        <legend>Pharmacy Package</legend>
        
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                <div class="form-group">
                    <!-- <span>
                        <input type="type" class="form-control canteen_purchase_product searbox-clean on-focus-selected" placeholder="Search Product by Name.....">
                    </span>
                    <div class="autocomplete_box"></div> -->

                    <select class="medicine-auto-complete"></select>
                </div>
            </div>
        </div>
        

     </fieldset>


     <fieldset>
         <legend>Submit Purchase Requisition</legend>
         <table class="table">
             <thead>
                 <tr class="info">
                     <th width="13%">Products Name</th>                     
                     <th width="10%">price</th>                     
                     <th width="5%">-</th>
                 </tr>
             </thead>
             
             <tbody id="package-products">

             <?php if(isset($package_products)){ foreach($package_products as $products){                   

                ?>
                <tr class="success">
                    <td><?php echo $products->product_name; ?><input type="hidden" name="product_id[]" class="product_id" value="<?php echo $products->product_id; ?>"></td>
                    <td>
                        <div class="input-group"><input type="text" readonly="" name="unit_price[]" value="<?php echo $products->unit_price; ?>" class="form-control unit_price" required=""><span class="input-group-btn"><button class="btn btn-xs" type="button" style="padding:3px;width:50px"></button></span></div>
                    </td>

                    <td>
                        <button type="button" class="remove-pack-pro"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </td>
                </tr>


             <?php } } ?>             	
                
             </tbody>
             <tfoot>
             	<tr class="warning">
             		<th>Total Price</th>
             		<th class="package_total_price"><?php echo isset($net_total)?$net_total:'0:00'?></th>
             		<th></th>
             	</tr>
             </tfoot>
         </table>
			
			
     </fieldset>




     <fieldset>
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm" value="Submit"  />
                <input type="reset" name="" class="btn btn-warning btn-sm sale-cancel" value="Reset" />                
            </div>
     </fieldset>
        	
    <?php echo form_close(); ?>    
</div>


