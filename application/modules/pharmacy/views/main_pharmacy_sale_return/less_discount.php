
<div class="box-primary box">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal form_reset sale_return_submit", data-toggle="validator"'); ?>            

                <!--***** Start Right DIV -*****-->
                <div class="row">
                	<fieldset>
                	<legend>Sale No</legend>
                		<div class="col-sm-12 col-md-12 col-lg-12">
				            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
				                <div class="form-group">
				                    <span>
				                        <input type="text" class="form-control medicine-sale-no-search searbox-clean" placeholder="Search Sales No.....">
				                    </span>
				                    <div class="autocomplete_box"></div>
				                </div>
				            </div>
				        </div>
                    <!--***** End Right DIV -*****-->
                </fieldset>
            </div>
			<div class="row">
				<fieldset>
					<legend>Medicine Sale Return</legend>
					<div class="col-md-12 table-responsive">
					<table class="table c-table">
			             <thead>
			                 <tr class="info">
			                     <th>Medicine Name</th>
			                     <th>Price</th>
			                     <th>Qnty.</th>
			                     <th>Per Tot.Discount</th>
			                     <th>P.Sub Amount</th>
			                     <th>R.Qnty</th>
			                     <th>R.Sub Amount</th>
			                     <th>-</th>
			                 </tr>
			             </thead>
			             <tbody id="return_sale_data"> 
			             </tbody>
			         </table>
				
            </div> 
            </fieldset>
			</div>		
			
				<div class="row">
					<fieldset>
						<legend>Payment Return</legend>

						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
						<div class="<?php echo form_error('tot_bill') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label>Total Bill<span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control tot_bill on-focus-selected" id="tot_bill" name="tot_bill" type="text" value="0" readonly=""/>
							</div>
						</div>		
						</div>

						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-1">
						<div class="<?php echo form_error('tot_paid') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label>Total Paid<span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control tot_paid on-focus-selected" id="tot_paid" name="tot_paid" type="text" value="0" readonly=""/>
							</div>
						</div>		
						</div>

					<div class="col-xs-4 col-sm-3 col-md-3 col-lg-2">
						<div class="<?php echo form_error('pharmacy_total_less_discount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label>Less Discount </label>
							   <input class="form-control pharmacy_total_less_discount on-focus-selected" id="total_less_dsc" name="pharmacy_total_less_discount" type="text" value="0" readonly=""/>
							</div>
						</div>		
						</div>

						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-1">
						<div class="<?php echo form_error('tot_due') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label>Total Due<span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control tot_due on-focus-selected" id="tot_due" name="tot_due" type="text" value="0" readonly=""/>
							</div>
						</div>		
						</div>
						
					
					<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
						<div class="<?php echo form_error('pharmacy_total_return_amount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_return_amount'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_return_amount" name="pharmacy_total_return_amount" type="text" readonly="" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
						<div class="<?php echo form_error('pharmacy_tot_charge') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_tot_charge'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control pharmacy_tot_charge on-focus-selected" id="pharmacy_tot_charge" name="pharmacy_tot_charge" type="text" value="0"/>
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
						<div class="<?php echo form_error('pharmacy_tot_charge_amount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_tot_charge_amount'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_tot_charge_amount" name="pharmacy_tot_charge_amount" value="0" type="text" readonly="" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
						<div class="<?php echo form_error('pharmacy_total_return_unit_amount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_return_unit_amount'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_return_unit_amount" name="pharmacy_total_return_unit_amount" value="0" type="text" readonly="" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
						<div class="<?php echo form_error('less_amount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e("Less Amount");?></label>
							   <input class="form-control" readonly="" id="less_amount" name="less_amount" value="0" type="text"  />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-2">
						<div class="<?php echo form_error('pharmacy_return_taka') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_return_taka'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control on-focus-selected" id="pharmacy_return_taka" name="pharmacy_return_taka" value="0" type="text" />
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
                    <!--<button name="save" class="btn btn-primary btn-sm" onclick="doubleClickNotSubmit(this)" type="submit" value="<?php //echo lang('bf_action_save'); ?>">Submit</button>-->

                    <input type="reset"  class="btn btn-warning btn-sm" value="Reset"  />
                                   
                    </div>   
                </div>
                </fieldset>
            </div>

    <?php echo form_close(); ?>
    </div>
    
</div>