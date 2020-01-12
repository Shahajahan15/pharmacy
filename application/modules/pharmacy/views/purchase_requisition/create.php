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
    
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
     <fieldset>
        <legend>Purchase Requisition Create</legend>
        
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                <!-- <div class="form-group">
                    <span>
                        <input type="type" class="form-control medicine_name-search searbox-clean" placeholder="Search Product by Name.....">
                    </span>
                    <div class="autocomplete_box"></div>
                </div> -->

                                <div class="form-group">
                                    <select tabindex="5" class="medicine-auto-complete form-control"></select>
                                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Requisition Date</label>
                <input type="text" disabled="" class="form-control"  name="" value="<?php echo date('d/m/Y'); ?>">
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Category</label>
                <select name="" id="pharmacy_category" class="form-control chosenCommon">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat){?>
                    <option value="<?php echo $cat->id; ?>" <?php if(isset($requisition)){ echo ($cat->id==$requisition->category_id)? 'selected':''; } ?>><?php echo $cat->category_name; ?></option>
                    <?php } ?>
                    
                </select>
            </div>
        </div>
         <!--<div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Sub Category</label>
                <select name="" id="pharmacy_sub_category" class="form-control">
                    <option value="">Select Sub Category</option>
                    
                </select>
            </div>
        </div> -->
         <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group">
                <label class="control-label">Product</label>
                <select name="" id="pharmacy_product" class="form-control chosenCommon">
                    <option value="">Select Product</option>                    
                </select>
            </div>
        </div>

     </fieldset>


     <fieldset>
         <legend>Submit Purchase Requisition</legend>
         <table class="table">
             <thead>
                 <tr class="info">
                     <th width="20%">Category</th>
                    <!-- <th width="20%">Sub Category</th> -->
                     <th width="20%">Products</th>
                     <th width="10%">Stock</th>
                     <th width="10%">Quantiy(Req.)</th>
                     <th width="5%">
                        <button type="button" class="remove-all confirm-alert">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                     </th>
                 </tr>
             </thead>
             
             <tbody id="requistion_submit-data">
             	<?php 
             		if ($stock_less_product && count($stock_less_product) > 0): 
             		foreach ($stock_less_product as $row) :
             	?>
             	<tr class="success">
             		
             		<td><?php echo $row->category_name; ?><input type="hidden" name="category_id[]" value="<?php echo $row->category_id; ?>"> </td>
             		<td><?php echo $row->subcategory_name; ?><input type="hidden" name="sub_category_id[]" value="<?php echo $row->sub_category_id; ?>"> </td>
             		<td><?php echo $row->product_name; ?><input type="hidden" name="product_id[]" class="product_id" value="<?php echo $row->id; ?>"> </td>
             		<td><?php echo $row->stock; ?></td>
             		<td><input type="text" name="requisition_quantity[] class=" form-control"="" required=""></td>
             		<td><button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
             	</tr>
                <?php endforeach; endif; ?>
                
             </tbody>
         </table>
     </fieldset>


     <fieldset>
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
               <!-- <input type="submit" onclick="return confirmMassege()" name="save" class="btn btn-primary btn-sm" value="Submit"  /> -->
                <button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="Submit">Submit</button>
                <input type="reset" name="" class="btn btn-warning btn-sm" value="Reset" />                
            </div>
     </fieldset>
        	
    <?php echo form_close(); ?>


</div>
