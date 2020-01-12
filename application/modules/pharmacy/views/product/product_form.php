<?php
extract($sendData);
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

<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

<div class="row box box-primary">		  
    <fieldset class="box-body">
	<div class="col-sm-12 col-md-3 col-lg-3">  
        <div class="container">
			
			<div class="row"> 
				<!------------Product Category Name----------->
				<div class="col-md-12">
					<div class="form-group <?php echo form_error('store_Product_Category_Name') ? 'error' : ''; ?>">
						<?php echo form_label(lang('store_Product_Category_Name') . lang('bf_form_label_required'), 'store_Product_Category_Name', array('class' => 'control-label')); ?>
						<select class="form-control chosenCommon chosen-single" name="store_Product_Category_Name" id="store_Product_Category_Name" required="" tabindex="1">
							<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>

							<?php
							foreach ($p_catergory as $p_catergory) 
							{
								echo "<option value='" . $p_catergory->STORE_PRODUCT_CATEGORY_ID . "'";
								if (isset($targetProductDetails->STORE_PRODUCT_CATEGORY_ID)) {
									if (trim($targetProductDetails->STORE_PRODUCT_CATEGORY_ID) == $p_catergory->STORE_PRODUCT_CATEGORY_ID) {
										echo "selected";
									}
								}
								echo ">" . $p_catergory->STORE_PRODUCT_CATEGORY_NAME . "</option>";
							}
							?>  
							
						</select>
						<span class='help-inline'><?php echo form_error('store_Product_Category_Name'); ?></span>
					</div> 
				</div>	
							
				<!------------ Account Head ----------->
				<div class="col-md-12">
					<div class="form-group <?php echo form_error('store_Account_head_Id') ? 'error' : ''; ?>">
						<?php echo form_label(lang('store_account_head_Id') . lang('bf_form_label_required'), 'store_Account_head_Id', array('class' => 'control-label')); ?>
						<select class="form-control chosenCommon chosen-single" name="store_Account_head_Id" id="store_Account_head_Id" required="" tabindex="5">
							<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
							<?php
							foreach ($account_head_list as $account_head_list) 
							{
								echo "<option value='" . $account_head_list->head_id . "'";
								if (isset($targetProductDetails->ACCOUNT_HEAD_ID)) 
								{
									if (trim($targetProductDetails->ACCOUNT_HEAD_ID) == $account_head_list->head_id) 
									{
										echo "selected";
									}
								}
								echo ">" . $account_head_list->head_name . "</option>";
							}
							?>  
						</select>
						<span class='help-inline'><?php echo form_error('store_Account_head_Id'); ?></span>
					</div>
				</div>
				
				<!-- ----------- Product Shelf ---------------- --> 
				<div class="col-md-12">	
					<div class="form-group <?php echo form_error('Store_Product_location') ? 'error' : ''; ?>">
						<label><?php echo lang('store_shelf'); ?></label>
						<input type='text' name='Store_Product_location' value="<?php echo set_value('Store_Product_location', isset($targetProductDetails->STORE_PRODUCT_LOCATION) ? $targetProductDetails->STORE_PRODUCT_LOCATION : ''); ?>" id='Store_Product_location' class="form-control" placeholder="<?php echo lang('store_shelf') ?>" tabindex="9"/>
						<span class='help-inline'><?php echo form_error('Store_Product_location'); ?></span>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="row">
						<!-- option weight start -->
						<div class="col-md-8">        
							<div class="form-group <?php echo form_error('store_product_weight') ? 'error' : ''; ?>">
								<label><?php echo lang('store_product_weight'); ?></label>
								<input type='text' name='weight' value="<?php echo set_value('store_product_weight', isset($targetProductDetails->WEIGHT) ? $targetProductDetails->WEIGHT : ''); ?>" id='store_product_weight' class="form-control" placeholder="<?php echo lang('store_product_weight') ?>" tabindex="16"/>
								<span class='help-inline'><?php echo form_error('store_product_weight'); ?></span>
							</div>
						</div> 
						<!-- option weight end -->	

						<!-- measurement unit  start -->
						<div class="col-md-4">
							<div class="form-group <?php echo form_error('weight_unit_id') ? 'error' : ''; ?>">
								<?php echo form_label(lang('store_measurement_unit_id'), 'weight_unit_id'); ?>
								<select class="form-control" name="weight_unit_id" id="weight_unit_id" tabindex="17" >
									<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
									<?php
									if (isset($weight_units)) 
									{
										foreach ($weight_units as $v_weight_units) 
										{
											echo "<option value='" . $v_weight_units->id . "'";
											
											if(isset($targetProductDetails->WEIGHT_UNIT_ID))
											{
												if(trim($targetProductDetails->WEIGHT_UNIT_ID) == $v_weight_units->id)
												{
													echo "selected";
												}									
											}
											
											echo ">" . $v_weight_units->unit_name . "</option>";
										}
									}
									?>
								</select>
								<span class='help-inline'><?php echo form_error('weight_unit_id'); ?></span>
							</div>
						</div> 
						<!-- measurement unit  end -->	
					</div>
				</div>
				
			</div>
        </div>
    </div>
	
    <div class="col-sm-12 col-md-3 col-lg-3"> 
        <div class="container">
			<div class="row"> 
				
				<!------------ Generic Name -----------> 
				<div class="col-md-12"> 					
					<div class="form-group <?php echo form_error('generic_name') ? 'error' : ''; ?>">    
						<label><?php echo lang('store_generic_name'); ?></label>
						<select class=" form-control chosenCommon chosen-single " name="generic_id" id="generic_name" style="width:100%" tabindex="2" >
							<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
							<?php
						   
							foreach ($generic_name_list as $v_units) 
							{
								echo "<option value='" . $v_units->GENERIC_ID . "'";
								
								if (isset($targetProductDetails->GENERIC_ID)) 
								{
									if (trim($targetProductDetails->GENERIC_ID) == $v_units->GENERIC_ID) 
									{
										echo "selected";
									}
								}
								
								echo ">" . $v_units->GENERIC_NAME . "</option>";
							}
							
							?>
								
						</select>
						<span class='help-inline'><?php echo form_error('generic_name'); ?></span>
					</div> 
				</div>	
				
				<!-- Required Level --> 
				<div class="col-md-12">
					<div class="form-group <?php echo form_error('required_level') ? 'error' : ''; ?>">              
						<?php echo form_label(lang('store_product_required_level'), 'required_level', array('class' => 'control-label')); ?>
						<div class='control'>
							<input type='text' name='required_level' value="<?php echo set_value('required_level', isset($targetProductDetails->REQUIRED_LEVEL) ? $targetProductDetails->REQUIRED_LEVEL : ''); ?>" id='required_level' class="form-control" placeholder="<?php echo lang('store_product_required_level') ?>" tabindex="6"/>
							<span class='help-inline'><?php echo form_error('required_level'); ?></span>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="row">				
						<!-- ----------- Depreciation ---------------- --> 
						<div class="col-md-6">                                
							<div class="form-group <?php echo form_error('Store_Product_Depreciation') ? 'error' : ''; ?>">
								<label><?php echo lang('store_product_depreciation'); ?></label>
								<input type='checkbox' name='activeDepreciation' id='activeDepreciation' value="1" tabindex="10"/>  
								<input type='text' name='depreciation' value="<?php echo set_value('Store_Product_Depreciation', isset($targetProductDetails->DEPRECIATION) ? $targetProductDetails->DEPRECIATION : ''); ?>" id='Store_Product_Depreciation' class="form-control" placeholder="<?php echo lang('store_product_depreciation') ?>" disabled tabindex="11"/>                       
								<span class='help-inline'><?php echo form_error('Store_Product_Depreciation'); ?></span>
							</div>
						</div> 
						
						<!-- ----------- Depreciation Type ---------------- -->     
						<div class="col-md-5" style="margin-top:30px; margin-left:10px;">                            
							<div class="form-group <?php echo form_error('Store_Product_Depreciation') ? 'error' : ''; ?>">                       
								<input type="checkbox" name="depreciationValueType"  value="1" <?php if(isset($targetProductDetails->DEPRECIATION_VALUE_TYPE) == 1) {echo "checked";} ?> id ="depreciationValueType" disabled tabindex="12" > Flat Rate
								<span class='help-inline'><?php echo form_error('Store_Product_Depreciation'); ?></span>
							</div>
						</div> 					
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="row">
						<!-- option Power start -->
						<div class="col-md-8">
							<!-- ----------- Power ---------------- -->               
							<div class="form-group <?php echo form_error('Store_Product_Power') ? 'error' : ''; ?>">
								<label><?php echo lang('store_product_power'); ?></label>
								<input type='text' name='power' value="<?php echo set_value('Store_Product_Power', isset($targetProductDetails->POWER) ? $targetProductDetails->POWER : ''); ?>" id='Store_Product_Power' class="form-control" placeholder="<?php echo lang('store_product_power') ?>" tabindex="18"/>
								<span class='help-inline'><?php echo form_error('Store_Product_Power'); ?></span>
							</div>
						</div> 
						<!-- option Power end -->	
						
						<!-- measurement unit  start -->
						<div class="col-md-4">
							<div class="form-group <?php echo form_error('power_unit_id') ? 'error' : ''; ?>">
								<?php echo form_label(lang('store_measurement_unit_id'), 'power_unit_id'); ?>
								<select class="form-control " name="power_unit_id" id="power_unit_id" tabindex="19" >
									<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
									<?php
									if (isset($power_units)) 
									{
										foreach ($power_units as $v_power_units) 
										{
											echo "<option value='" . $v_power_units->id . "'";
											
											if(isset($targetProductDetails->POWER_UNIT_ID))
											{
												if(trim($targetProductDetails->POWER_UNIT_ID) == $v_power_units->id)
												{
													echo "selected";
												}
												
											}
																		
											echo ">" . $v_power_units->unit_name . "</option>";
										}
									}
									?>
								</select>
								<span class='help-inline'><?php echo form_error('power_unit_id'); ?></span>
							</div>
						</div> 
						<!-- measurement unit  end -->	
					</div>
				</div>
			
			</div>
        </div>
    </div>
	
    <div class="col-sm-12 col-md-3 col-lg-3">  
        <div class="container">
            <div class="row">
                
				<!------------Product Name----------->
				<div class="col-md-12">	
					<div class="form-group <?php echo form_error('store_Product_Name') ? 'error' : ''; ?>">
						<span id="chkProduct_NameExists" style="color:red;"></span>
						<?php echo form_label(lang('store_product_name') . lang('bf_form_label_required'), 'store_Product_Name', array('class' => 'control-label')); ?>
						<div class='control'>
							<input type='text' name='store_Product_Name' value="<?php echo set_value('store_Product_Name', isset($targetProductDetails->STORE_PRODUCT_NAME) ? $targetProductDetails->STORE_PRODUCT_NAME : ''); ?>" id='store_Product_Name' class="form-control"   maxlength="45"  placeholder="<?php echo lang('store_product_name') ?>" required="" onblur="product_name_check()" tabindex="2"/>
							<span class='help-inline'><?php echo form_error('store_Product_Name'); ?></span>
						</div>
					</div>
				</div>
				
				<!-- Yearly required quantity -->
				<div class="col-md-12">	
					<div class="form-group <?php echo form_error('yearly_required_qty') ? 'error' : ''; ?>">
						<label><?php echo lang('store_product_yearly_indent'); ?></label>
						<input type='text' name='yearly_required_qty' value="<?php echo set_value('yearly_required_qty', isset($targetProductDetails->YEARLY_REQUIRED_QTY) ? $targetProductDetails->YEARLY_REQUIRED_QTY : ''); ?>" id='yearly_required_qty' class="form-control" placeholder="<?php echo lang('store_product_yearly_indent') ?>" tabindex="7"/>                       
						<span class='help-inline'><?php echo form_error('yearly_required_qty'); ?></span>
					</div>
				</div>
				
				
				<!-- option width start -->
				<div class="col-md-8">							                          
					<div class="form-group <?php echo form_error('store_product_width') ? 'error' : ''; ?>">
						<label><?php echo lang('store_product_width'); ?></label>
						<input type='text' name='width' value="<?php echo set_value('store_product_width', isset($targetProductDetails->WIDTH) ? $targetProductDetails->WIDTH : ''); ?>" id='store_sroduct_width' class="form-control" placeholder="<?php echo lang('store_product_width') ?>" tabindex="13"/>
						<span class='help-inline'><?php echo form_error('store_product_width'); ?></span>
					</div>						
				</div> 
				<!-- option width End -->	

				<!-- measurement unit  start -->
				<div class="col-md-4">
					<div class="form-group <?php echo form_error('width_unit_id') ? 'error' : ''; ?>">
						<?php echo form_label(lang('store_measurement_unit_id'), 'width_unit_id'); ?>
						<select class="form-control" name="width_unit_id" id="width_unit_id" tabindex="14" >
							<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
							<?php
							if (isset($lenght_units)) 
							{

								foreach ($lenght_units as $v_lenght_units) 
								{
									echo "<option value='" . $v_lenght_units->id . "'";
									if(isset($targetProductDetails->WIDTH_UNIT_ID))
									{
										if(trim($targetProductDetails->WIDTH_UNIT_ID) == $v_lenght_units->id)
										{
											echo "selected";
										}									
									}
									echo ">" . $v_lenght_units->unit_name . "</option>";
								}
							}
							?>
						</select>
						<span class='help-inline'><?php echo form_error('Store_Measurement_Unit_Id'); ?></span>
					</div>
				</div> 
				<!-- measurement unit  end -->  
				
				
				
				<!-- option Capacity start -->
                <div class="col-md-8">
                    <!-- ----------- Capacity ---------------- -->               
                    <div class="form-group <?php echo form_error('Store_Product_Capacity') ? 'error' : ''; ?>">
                        <label><?php echo lang('store_product_capacity'); ?></label>
                        <input type='text' name='capacity' value="<?php echo set_value('Store_Product_Capacity', isset($targetProductDetails->CAPACITY) ? $targetProductDetails->CAPACITY : ''); ?>" id='Store_Product_Capacity' class="form-control" placeholder="<?php echo lang('store_product_capacity') ?>" tabindex="20"/>
                        <span class='help-inline'><?php echo form_error('Store_Product_Capacity'); ?></span>
                    </div>
                </div> 
                <!-- option Capacity end -->

                <!-- measurement unit  start -->
                <div class="col-md-4">
                    <div class="form-group <?php echo form_error('capacity_unit_id') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('store_measurement_unit_id'), 'capacity_unit_id'); ?>
                        <select class="form-control" name="capacity_unit_id" id="capacity_unit_id" tabindex="21" >
                            <option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
                            <?php
                            if (isset($power_units))
								{
                                foreach ($power_units as $v_power_units) 
								{									
                                    echo "<option value='" . $v_power_units->id . "'";									
									if (isset($targetProductDetails->CAPACITY_UNIT_ID))
									{
										if (trim($targetProductDetails->CAPACITY_UNIT_ID) == $v_power_units->id) 
										{
											echo "selected";
										}
									}									
                                    echo ">" . $v_power_units->unit_name . "</option>";									
                                }
                            }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('capacity_unit_id'); ?></span>
                    </div>
                </div> 
                <!-- measurement unit  end -->  
				
            </div>
        </div>		
    </div>
	
	
    <div class="col-sm-12 col-md-3 col-lg-3">  
        <div class="container">
			<div class="row">
				
				<!-- ------ Product Code -------- -->
				<div class="col-md-12">					
					<div class="form-group <?php echo form_error('store_Product_Code') ? 'error' : ''; ?>">              
						<?php echo form_label(lang('store_product_code') . lang('bf_form_label_required'), 'store_Product_Code', array('class' => 'control-label')); ?>
						<div class='control'>
							<input type='text' name='store_Product_Code' value="<?php echo set_value('store_Product_Code', isset($targetProductDetails->STORE_PRODUCT_CODE) ? $targetProductDetails->STORE_PRODUCT_CODE : ''); ?>" id='store_Product_Code' class="form-control"   maxlength="15"  placeholder="<?php echo lang('store_product_code') ?>" required="" tabindex="3"/>
							<span class='help-inline'><?php echo form_error('store_Product_Code'); ?></span>
						</div>
					</div>
				</div>
				
				<!-- Indent Period -->
				<div class="col-md-12">	
					<div class="form-group <?php echo form_error('indent_period') ? 'error' : ''; ?>">
						<label><?php echo lang('store_product_indent_period'); ?></label>
						<input type='text' name='indent_period' value="<?php echo set_value('indent_period', isset($targetProductDetails->INDENT_PERIOD) ? $targetProductDetails->INDENT_PERIOD : ''); ?>" id='indent_period' class="form-control" placeholder="day" tabindex="8"/>                       
						<span class='help-inline'><?php echo form_error('indent_period'); ?></span>
					</div>
				</div>
				
				
				<div class="col-md-12">
					<div class="row">
						<!-- option Height start -->
						<div class="col-md-8">       		                        
							<div class="form-group <?php echo form_error('store_product_height') ? 'error' : ''; ?>">
								<label><?php echo lang('store_product_height'); ?></label>
								<input type='text' name='height' value="<?php echo set_value('store_product_height', isset($targetProductDetails->HEIGHT) ? $targetProductDetails->HEIGHT : ''); ?>" id='store_product_height' class="form-control" placeholder="<?php echo lang('store_product_height') ?>" tabindex="14"/>
								<span class='help-inline'><?php echo form_error('store_product_height'); ?></span>
							</div>		       
						</div> 
						<!-- option Height End -->	
						
						<!-- measurement unit  start -->
						<div class="col-md-4">
							<div class="form-group <?php echo form_error('height_unit_id') ? 'error' : ''; ?>">
								<?php echo form_label(lang('store_measurement_unit_id'), 'height_unit_id'); ?>
								<select class="form-control" name="height_unit_id" id="height_unit_id" tabindex="15" >
									<option value=""><?php echo lang('bf_msg_selete_one'); ?></option>
									<?php
									if (isset($lenght_units)) 
									{

										foreach ($lenght_units as $v_lenght_units) 
										{
											echo "<option value='" . $v_lenght_units->id . "'";
											
											if(isset($targetProductDetails->HEIGHT_UNIT_ID))
											{
												if(trim($targetProductDetails->HEIGHT_UNIT_ID) == $v_lenght_units->id)
												{
													echo "selected";
												}
												
											}
											
											echo ">" . $v_lenght_units->unit_name . "</option>";
										}
									}
									?>
								</select>
								<span class='help-inline'><?php echo form_error('height_unit_id'); ?></span>
							</div>
						</div> 
						<!-- measurement unit  end -->  					
					</div>
				</div>
				
				
				<div class="col-md-12">
					<?php
					// Change the values in this array to populate your drop down as required
					$options = array(
						'1' => 'Active',
						'0' => 'In Active',
					);
					echo form_dropdown('bf_status', $options, set_value('bf_status', isset($product_category_details['STATUS']) ? $product_category_details['STATUS'] : '1'), lang('bf_status'), "class='form-control'", '', "class='control-label'");
					?> 
				</div>
				
				
			</div>	
		</div>
    </div>
	
	
	<div class="col-lg-12,col-md-12">
		<div class="row">		
			<div class="col-md-12 box-footer pager">
				<?php echo anchor(SITE_AREA . '/product/store/show_product_list', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
				 &nbsp;
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
			</div>  
		</div>
	</div>
	</fieldset>
</div>


<?php echo form_close(); ?>
