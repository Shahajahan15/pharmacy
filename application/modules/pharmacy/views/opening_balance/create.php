<div class="box box-primary">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php echo form_open($this->uri->uri_string(), 'role="form" class="nform-horizontal" data-toggle="validator"'); ?>
               
    <fieldset>
        <legend>Product Add From Hare</legend>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
                <div class="form-group">
                    <span>
                        <input type="type" class="form-control product-name-search searbox-clean" placeholder="Search Product by Name.....">
                    </span>
                    <div class="autocomplete_box"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="control-label">Category</label>
                <select name="" id="category" class="form-control">
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat){?>
                    <option value="<?php echo $cat->id; ?>" <?php if(isset($requisition)){ echo ($cat->id==$requisition->category_id)? 'selected':''; } ?>><?php echo $cat->category_name; ?></option>
                    <?php } ?>
                    
                </select>
            </div>
        </div>
         <div class="col-sm-12 col-md-4 col-lg-4">
            <div class="form-group">
                <label class="control-label">Sub Category</label>
                <select name="" id="sub_category" class="form-control">
                    <option value="">Select Sub Category</option>
                    
                </select>
            </div>
        </div>
         <div class="col-sm-12 col-md-5 col-lg-5">
            <div class="form-group">
                <label class="control-label">Product</label>
                <select name="" id="product" class="form-control">
                    <option value="">Select Product</option>                    
                </select>
            </div>
        </div>
    </fieldset>
           
			<fieldset>
					<legend>Products</legend>
					
					<div class="col-md-12 table-responsive">
					<table class="table c-table">
			             <thead>
			                 <tr class="info">
			                     <th width="20%">Product Name</th>
			                     <th width="5%">Stock</th>
			                     <th width="10%">Quantity</th>
			                     <th width="15%">Specification</th>
			                     <th width="15%">Product Serials</th>
			                     <th width="10%">Unit Price</th>
			                     <th width="10%">Total Price</th>
			                     <th width="1%"><button type="button" class="all-remove btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button></th>
			                 </tr>
			             </thead>
			             <tbody id="sale_data"> 
			             	<?php if(isset($products)){ foreach($products as $product){?>
			             		<tr class="success">
			             			<td>
			             				<?php echo $product->product_name; ?><input type="hidden" class="product_id" name="product_id[]" value="<?php echo $product->product_id; ?>" /> 
			             			</td>
			             			<td class="stock-qnty"><?php echo $product->stock; ?></td>
			             			<td>
			             				<input type="number" class="qnty form-control selected real-number" required="" name="qnty[]" value="<?php echo $product->qnty; ?>" /> 
			             			</td>
			             			<td>
			             				<input type="number" class="specification form-control selected real-number" name="specification[]" value="<?php echo $product->specification; ?>" /> 
			             			</td>
			             			<td>
			             				<?php if(isset($product->serials) && count($product->serials)){ ?>
			             				<select class="form-control product-serials" name="serials[<?php echo $product->product_id; ?>][]" multiple>
											<?php foreach($product->serials as $serial){?>
												<option value="<?php echo $serial->id; ?>">
												<?php echo $serial->serial; ?></option>

											<?php } ?>		
										</select>
										<p style="color:red;font-size: 10px;padding: 0;margin: 0" class="serial-valid-message"></p>

			             				<?php }elseif($product->is_serial==1){?>

			             				<select class="form-control product-serials" name="serials[<?php echo $product->product_id; ?>][]" multiple>
			             					
			             				</select>
			             				<p style="color:red;font-size: 10px;padding: 0;margin: 0" class="serial-valid-message"></p>

			             				<?php }else{ ?>

			             					<select class="form-control" name="serials[<?php echo $product->id;?>][]" readonly="">
			             						<option value=""></option>
			             					</select>

			             				<?php } ?>
			             			</td>
			             			<td>
			             				<input type="text" class="unit_price form-control selected decimal" required="" name="unit_price[]" value="<?php echo $product->unit_price; ?>" /> <p style="color:red;font-size: 10px;padding: 0;margin: 0" class="unit_price-valid-message"></p></td>

			             			<td>
			             				<input type="text" class="total_price form-control selected decimal" required="" name="total_price[]" value="<?php echo $product->qnty*$product->unit_price; ?>" /> 
			             			</td>
			             			<td>
			             				<button type="button" class="remove-product"><i class="fa fa-times" aria-hidden="true"></i></button>
			             			</td>


			             		</tr>
				            <?php } }?>
			             </tbody>
			         </table>
				
            </div> 
            </fieldset>	
			
		<fieldset>
			

			
			<!--
			
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Total Receive</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">						
						<div id="sandbox-container" class="control">                    
						<input class="form-control selected decimal" id="total_receive" type="text" name="total_paid" maxlength="60" value="0" placeholder="Total Receive" title="Total Receive" style="text-align: right;" required="">                  
							<span class="help-inline"></span>
						</div>
					</div>					
				</div>				
			</div>
			-->

			<!-- Discount amount -->
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Discount</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">						
						<div id="sandbox-container" class="control">                    
						<input class="form-control decimal" id="discount" type="text" name="discount" maxlength="60" value="<?php echo isset($record)?$record->discount:''; ?>" placeholder="Discount" title="Discount" style="text-align: right;">
							<span class="help-inline"></span>
						</div>
					</div>					
				</div>				
			</div>

			<!-- Transportation Cost -->
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Transportation Cost</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">						
						<div id="sandbox-container" class="control">                    
						<input class="form-control decimal" id="transportation_cost" type="text" name="transportation_cost" maxlength="60" value="<?php echo isset($record)?$record->transportation_cost:''; ?>" placeholder="Transportation Cost" title="Transportation Cost" style="text-align: right;">
							<span class="help-inline"></span>
						</div>
					</div>					
				</div>				
			</div>

			<!-- Test fee -->
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Test Fee</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">						
						<div id="sandbox-container" class="control">                    
						<input class="form-control decimal" id="test_fee" type="text" name="test_fee" maxlength="60" value="<?php echo isset($record)?$record->test_fee:''; ?>" placeholder="Test Fee" title="Transportation Cost" style="text-align: right;">
							<span class="help-inline"></span>
						</div>
					</div>					
				</div>				
			</div>

			<!-- Other cost -->
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Other Cost</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">						
						<div id="sandbox-container" class="control">                    
						<input class="form-control decimal" id="other_cost" type="text" name="other_cost" maxlength="60" value="<?php echo isset($record)?$record->test_fee:''; ?>" placeholder="Other Cost" title="Transportation Cost" style="text-align: right;">
							<span class="help-inline"></span>
						</div>
					</div>					
				</div>				
			</div>

			
			<!-- total_recivable -->
			<div class="col-md-12">				
				<div class="col-md-7">
					<div class="form-group">						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Net Receivable</label>
					</div>
				</div>
				
				
				<div class="col-md-3">					
					<div class="form-group ">					
						<div id="sandbox-container" class="control">                    
							<input class="form-control" id="total_receivable" type="text" name="total_receivable" maxlength="60" value="<?php echo isset($record)?$record->total_bill:''; ?>" placeholder="Net Recevable" title="Total Amount" readonly="" style="text-align: right;">
							<span class="help-inline"></span>
						</div>
					</div>												
				</div>				
			</div>  
			
			
			
			<!-- total_due amount -->
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Due Amount</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">						
						<div id="sandbox-container" class="control">                    
						<input class="form-control decimalmask" id="due_amount" type="text" name="due_amount" maxlength="60" value="<?php echo isset($record->total_paid)?$record->total_bill-$record->total_paid:isset($record->total_bill)?$record->total_bill:''; ?>" placeholder="Due Amount" title="Due Amount" readonly="" style="text-align: right;">                  
							<span class="help-inline"></span>
						</div>
					</div>					
				</div>				
			</div>


			<!-- Sale by-->
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="form-group">	
						
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">	
						<label>Sale By</label>
					</div>
				</div>
				
				<div class="col-md-3">					
					<div class="form-group ">
						<select class="form-control" name="sale_by" id="sale_by" required="">
                            <option value="">Select Saller</option>
                                <?php foreach($sallers as $saller){?>
                                <option value="<?php echo $saller->id;?>"><?php echo $saller->employee_name;?></option>
                                <?php }?>
                        </select>
					</div>
				</div>					
			</div>
				
	</fieldset>
			
            
        <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="pager">
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="Save"  />
                    <?php echo lang('bf_or'); ?>
                    <button type="reset" class="btn btn-warning">Reset</button>             
                </div>   
            </div>
        </fieldset>

    <?php echo form_close(); ?>
    </div>
</div>



<script type="text/javascript">

$(document).ready(function(){

	<?php if(isset($print_page)){?>

	var jsonObj=<?php echo json_encode($print_page) ?>;	
	print_view(jsonObj);
	<?php unset($print_page); } ?>
})

	
</script>