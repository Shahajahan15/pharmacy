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

<?php

if(isset($_POST)){
            unset($_POST);
        }
        
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

<div class="row box box-primary">
	<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal purchase-order-form"'); ?>
	<fieldset>
		<legend>Order Placed</legend>
		<div class="col-sm-12 col-md-4 col-lg-4">			
			<div class="form-group">
				<label class="control-label">Select Supplier</label>		
				<div class="input-group">
					<select class="form-control pharmacy_supplier_auto" name="supplier_id" required="">
						
					</select>
					<div class="input-group-addon btn supplier-list">
                   		<i class="fa fa-list"></i>
                	</div>
					<div class="input-group-addon btn btn-success btn-xs add-new-supplier" style="color:white">
                   		<i class="fa fa-plus"></i>
                	</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-8 col-lg-8">
			<div class="form-group">
				<label class="control-label">Select Product</label>
				<select class="form-control" name="requisition_approve_id" id="requisition_approve_product">
					<option value="" style="font-size: 15px;">Select Product</option>
					<?php 
					$sl=0;
					foreach($records as $record){
						$record=(object)$record; 
				 	?>
					<option style="font-size: 15px;" value="<?php echo $record->id; ?>"><?php echo $record->product_name.'{ Last price='.$record->purchase_price.'TK/unit, Apprve-qnty='.$record->approve_qnty.'}'     ; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="col-md-4 col-sm-12 col-lg-4">
			<div class="form-group">
				<label class="label-control">Supply From</label>
				<input type="text" name="supply_from" class="form-control datepickerCommon"  required="" value="<?php echo date('d/m/Y');?>">
			</div>
		</div>

		<div class="col-md-4 col-sm-12 col-lg-4">
			<div class="form-group">
				<label class="label-control">Supply To</label>
				<input type="text" name="supply_to" class="form-control datepickerCommon" required="" >
			</div>
		</div>
	</fieldset>

	<fieldset>
         <legend>Work-Order</legend>
         <table class="table">
             <thead>
                 <tr class="info">
                     <th width="25%">Product</th>
                     <th width="10%">Approve quantity</th>
                     <th width="10%">Already Ordered Qnty</th>
                     <th width="10%">Ordered Pending Qnty</th>
                     <th width="10%">Last Purchase Price</th>
                     <th width="10%">Order Qnty</th>
                     <th width="10%">Unit Price</th>
                     <th width="12%">Total Price</th>
                     <th width="5%">-</th>
                 </tr>
             </thead>
             <tbody id="work_order_submit-data">
             	<?php foreach($records as $record){
						$record=(object)$record; 
				 ?>

	             <tr class="success">
	             	<td><?php echo $record->category_name ."&nbsp; >> &nbsp;". $record->product_name;?>
	             		<input type="hidden" name="product_id[]" class="product_id" value="<?php echo $record->product_id;?>">
	             		<input type="hidden" name="product_requisition_id[]" value="<?php echo $record->id;?>">
	             	</td>
	             	<td><?php echo $record->approve_qnty; ?></td>
	             	<td><?php echo $record->purchase_order_qnty; ?></td>
	             	<td class="order-pending-qnty">
	             		<?php echo $record->approve_qnty-$record->purchase_order_qnty; ?>
	             	</td>
	             	<td><?php echo $record->purchase_price;?></td>
	             	<td>
	             		<input type="text" name="order_qnty[]" class="form-control order_qnty on-focus-selected" value="<?php echo $record->approve_qnty-$record->purchase_order_qnty; ?>" required="">
	             	</td>
	             	<td>
	             		<input type="text" name="order_unit_price[]" class="form-control order_unit_price on-focus-selected" value="<?php echo $record->purchase_price;?>" required="">
	             	</td>
	             	<td>
	             		<input type="text" name="order_total_price[]" value="<?php echo $record->purchase_price*($record->approve_qnty-$record->purchase_order_qnty);?>" class="form-control order_total_price" required="">
	             	</td>
	             	<td>
	             		<button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button>
	             	</td>
	             </tr>
	             <?php } ?>
             </tbody>
         </table>
     </fieldset>


     <fieldset>
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
               <!--  <input type="submit" onclick="return confirmMassege()" name="save" class="btn btn-primary btn-sm" value="Submit"  /> -->
                <button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="Submit">Submit</button>

                <input type="reset" name="" class="btn btn-primary btn-sm" value="Reset" />                
            </div>
     </fieldset>

	<?php echo form_close(); ?>

</div>


<script type="text/javascript">

$(document).ready(function(){

	<?php if(isset($print_page)){?>

	var jsonObj=<?php echo json_encode($print_page) ?>;	
	print_view(jsonObj);
	<?php unset($print_page); } ?>
})

	
</script>