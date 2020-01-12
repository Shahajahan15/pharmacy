<!-- side bar -->
<div id="mySidenav" class="sidenav">
</div>
<!-- side bar close -->


<div class="box box-primary">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal form_reset sale_submit", data-toggle="validator"'); ?>
            <div class="row">
                <!-- ---*****--- Start Left  DIV ---*****--- -->
                <fieldset>
                	<legend>Customer Add</legend>
                	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px">
								  	<div class="">
								  		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4">

								  			<select class="pharmacy-auto-complete form-control" name="cusomer_id"></select>

								  		</div>
								  	</div>
								  </div>
								  
                                <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
                                    <!-- -----------  age -------------- -->
                                    <div class="<?php echo form_error('pharmacy_customer_type') ? 'error' : ''; ?>">
                                        <div class='form-group'>
                                            <label><?php e(lang('pharmacy_customer_type'));?><span class="required"><?php e(lang('bf_star_mark'));?></span></label>
                                                <select class="form-control" name="customer_type" id="pharmacy_customer_type">
                                                	<option value="1">Customer</option>
                                                	<option value="2">Employee</option>
                                                	<option value="3">Doctor</option>
                                                </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 emp_doctor" style="display: none">
					            <div class="form-group">
					                <label class="control-label ed_label"><?php e(lang('bf_star_mark'));?></label>
					                <select name="emp_id" id="emp_doctor" class="form-control">
					                    
					                </select>
					            </div>
					        </div>
                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 c_name">
                                    <!-- -----------  age -------------- -->
                                    <div class="<?php echo form_error('pharmacy_customer_name') ? 'error' : ''; ?>">
                                        <div class='form-group'>
                                            <label><?php e(lang('pharmacy_customer_name'));?><span class="required"><?php e(lang('bf_star_mark'));?></span></label>
                                                <input class="form-control" id='pharmacy_customer_name'  name='pharmacy_customer_name' type='text' required="" value="ABC"/>
                                        </div>
                                    </div>
                                </div>
                            <!-- -----------  mobile / contact -------------- -->
                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 c_phone">
                            <div class="<?php echo form_error('pharmacy_customer_phone') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label><?php e(lang('pharmacy_customer_phone'));?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"> +880</i>
                                        </div>
                                        <input type="text" id='pharmacy_customer_phone' name='pharmacy_customer_phone' class="form-control" value=""/>
                                    </div>
                                </div>
                            </div>
                            </div>
                </fieldset>
                </div>
                <div class="row">
                	<fieldset>
                	<legend>Madecine Add</legend>
                		<div class="col-sm-12 col-md-12 col-lg-12">
				            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
				                <div class="form-group">

				                <!-- <span>
				                        <input type="type" class="form-control medicine_name-search searbox-clean" placeholder="Search Medicine by Name.....">
				                    </span>
				                    <div class="autocomplete_box"></div> -->
				                    
				                     <select tabindex="5" class="medicine-auto-complete form-control"></select>
				                </div>
				            </div>
				        </div>
                		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">	
                			<div class="form-group">
				                <label class="control-label">Category</label>
				                <label><?php e(lang('pharmacy_categroy'));?></span></label>
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
					        </div>-->


					         <div class="col-sm-12 col-md-6 col-lg-5">
					            <div class="form-group">
					                <label class="control-label">Medicine Name</label>
					                <select name="" id="pharmacy_medicine" class="form-control chosenCommon">
					                    <option value="">Select a medicine name</option>                    
					                </select>
					            </div>
					        </div>

					        <div class="col-sm-12 col-md-2 col-lg-2" style="margin-top: 18px">
					            <div class="form-group">
                                 <button style="padding: 2.3px;margin-left: 10px;" type="button" class="btn btn-sm btn-info getSubPharmacyShortList" onclick="openShortList()"><i class="fa fa-list" aria-hidden="true"></i> Short List</button>
					            </div>
					        </div>

					       
                    <!-- ---*****--- End Right DIV ---*****--- -->
                </fieldset>
            </div>
			<div class="row">
				<fieldset>
					<legend>Medicine Sale</legend>
					<div class="col-md-12 table-responsive">
					<table class="table c-table">
			             <thead>
			                 <tr class="info">
			                     <th>Medicine Name</th>
			                     <th>Price</th>
			                     <th>Stock</th>
			                     <th>N.Discount(%)</th>
			                     <th>N.Discount</th>
			                     <th>S.Discount(%)</th>
			                     <th>S.Discount</th>
			                     <th>T.Discount</th>
			                     <th>Qnty</th>
			                     <th>Sub Total</th>
			                     <th>-</th>
			                 </tr>
			             </thead>
			             <tbody id="sale_data"> 
			             </tbody>
			         </table>
				
            </div> 
            </fieldset>
			</div>		
			
				<div class="row">
					<fieldset>
						<legend>Payment</legend>
					
					<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_price') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_price'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_price" name="pharmacy_total_price" type="text" readonly="" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_paid') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_paid'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_paid" name="pharmacy_total_paid" type="text" autocomplete="off" value="0" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_less_discount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_less_discount'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_less_discount" name="pharmacy_total_less_discount" autocomplete="off" value="0" type="text" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_due') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_due'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_due" name="pharmacy_total_due" type="text" readonly="" />
							</div>
						</div>		
						</div>
					</fieldset>
				</div>
			
            <div class="row">
            	<fieldset>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                	<div class="text-center">
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                    <?php echo lang('bf_or'); ?>      
                    <input type="button" onClick="resetMPForm()" class="btn btn-warning btn-sm" value="Reset">         
                    </div>   
                </div>
                </fieldset>
            </div>

    <?php echo form_close(); ?>
    </div>
    
</div>