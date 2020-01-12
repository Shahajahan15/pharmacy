
<div class="box-primary">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 main_body">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal form_reset sale_submit", data-toggle="validator"'); ?>
            <div class="row">
                <fieldset>
                	<legend>Admission Patient Info</legend>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        	<table class="table table-bordered" style="margin-bottom:0px; ">
                        		<thead>
									<tr class="active">
										<th>Patient Name</th>
										<th>Patient Id</th>
										<th>Contact No</th>
										<th>Age</th>
										<th>Ticket No</th>
										<th>Doctor Name</th>
									</tr>
								</thead>
								<?php if ($patient_info) : ?>
								<tbody>
									<tr class="info">
										<td><?php echo $patient_info->patient_name; ?>
											<input type="hidden" value="<?php echo $patient_info->patient_id; ?>" id="patient_id" name="patient_id" />
											
										</td>
										<td><?php echo $patient_info->patient_code; ?>
											<input type="hidden" name="master_id" value="<?php echo $patient_info->id; ?>"/>
										</td>
										<td><?php echo $patient_info->contact_no; ?></td>
										<td><?php echo dob_convert_age($patient_info->birthday); ?></td>
										<td><?php echo $patient_info->receipt_no; ?></td>
										<td><?php echo $patient_info->doctor_name; ?></td>
									</tr>
								</tbody>
								<?php endif; ?>
                        	</table>
                        </div>
                </fieldset>
                </div>
			  

               
                <div class="row">
                	<fieldset>
                	<legend>Madecine Add</legend>
                		<div class="col-sm-12 col-md-12 col-lg-12">
				            <div class="col-sm-4 col-md-4 col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
				                <div class="form-group">
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
			                     <th>P.Qnty.</th>
			                     <th>Qnty</th>
			                     <th>Sub Total</th>
			                     <th>-</th>
			                 </tr>
			             </thead>
			             <tbody id="sale_data"> 
			             	<?php 
			             	$total = 0;

			             	$qnty = 1;
			             	foreach ($medicine_info as $key => $row) : 
			             		$p_qnty = getMedicineCount($row->roule_id, $row->duration);
			             		$qnty = ($row->stock < $p_qnty)? (int) $row->stock : (int) $p_qnty;

			             		$nd_percent = 0;
			             		$nd_amount = 0;
			             		$nd_discount_id = 0;
			             		$nd_discount_type = 0;

			             		$sd_percent = 0;
			             		$sd_amount = 0;
			             		$sd_discount_id = 0;
			             		$sd_discount_type = 0;


			             		$over_all_discount= normal_discount();

                               if($over_all_discount)
                               	{
                                  $nd_percent = $over_all_discount->discount_parcent;
                                  $nd_amount = percent_convert_amount($over_all_discount->discount_parcent,$row->sale_price);
                                  $nd_discount_id = $over_all_discount->id;
                                  $nd_discount_type  = 1;
                               	}else
                               	{
                               		$service_discount =  patient_discount($row->patient_id,2,$row->medicine_id);
                               		 $sd_percent = $service_discount->discount;
                               		$sd_amount = percent_convert_amount($sd_percent, $row->sale_price);
                               		$sd_discount_id = $service_discount->id;
                               		$sd_discount_type = 3;
                               	}





			             		// $sd_percent = patient_discount($row->patient_id,2,$row->medicine_id)->discount;
			             		// $sd_amount= percent_convert_amount($sd_percent, $row->sale_price);


			             		// $nd_percent = medicine_discount($row->medicine_id);
			             		// $nd_amount= percent_convert_amount($nd_percent, $row->sale_price);




			             		$tot_discount = ($sd_amount * $qnty) + ($nd_amount * $qnty);
			             		$sub_total = ($row->sale_price * $qnty) - $tot_discount;
			             		$total += $sub_total;
			             	?>
				             <tr class="success">
				             	<td><?php echo $row->category_name.' -> '.$row->product_name; ?><input type="hidden" name="product_id[]" class="product_id" value="<?php echo $row->medicine_id; ?>"> </td>
				             	<td><?php echo $row->sale_price; ?><input type="hidden" class="tot_price" name="sale_price[]" value="<?php echo $row->sale_price; ?>"> </td>
				             	<td><?php echo isset($row->stock) ? $row->stock : 0; ?><input type="hidden" name="stock[]" class="stock" value="<?php echo isset($row->stock) ? $row->stock : 0; ?>"> </td>
				             	<td><?php echo $nd_percent; ?><input type="hidden" name="nd_percent[]" value="<?php echo $nd_percent; ?>"></td>
				             	<td><?php echo $nd_amount; ?><input type="hidden" class="nd_amount" name="nd_amount[]" value="<?php echo $nd_amount; ?>"> <input type="hidden" class="nd_discount_id" name="nd_discount_id[]" value="<?php echo $nd_discount_id; ?>"><input type="hidden" class="nd_discount_type" name="nd_discount_type[]" value="<?php echo $nd_discount_type; ?>"></td>
				             	<td><?php echo $sd_percent; ?><input type="hidden" name="sd_percent[]" value="<?php echo $sd_percent; ?>"> <input type="hidden" name="sd_discount_id[]" value="<?php echo $sd_discount_id; ?>"><input type="hidden" name="sd_discount_type[]" value="<?php echo $sd_discount_type; ?>"></td>
				             	<td><?php echo $sd_amount; ?><input type="hidden" class="sd_amount" name="sd_amount[]" value="<?php echo $sd_amount; ?>"> </td>
				             	<td><span class="tot_discount_text"><?php echo $tot_discount; ?></span><input type="hidden" class="tot_discount" name="td_amount[]" value="<?php echo $tot_discount; ?>"> </td>
				             	<td><?php echo $p_qnty; ?><input type="hidden" name="roule_id[]" value="<?php echo isset($row->roule_id)? $row->roule_id : 0; ?>"><input type="hidden" value="<?php echo isset($row->duration) ? $row->duration : 0; ?>" name="duration[]" ></td>
				             	<td><input type="text" name="qnty[]" class="form-control s_qnty decimalmask" autocomplete="off" value="<?php echo $qnty; ?>" style="width:50px;" required=""></td>
				             	<td><span class="sub_total_text"><?php echo $sub_total; ?></span><input type="hidden" name="sub_total[]" class="sub_total" value="<?php echo $sub_total; ?>"> </td>
				             	<td><button type="button" class="remove s_remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
				             </tr>
				             <?php endforeach; ?>
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
							   <input class="form-control" id="pharmacy_total_price" name="pharmacy_total_price" value="<?php echo $total; ?>" type="text" readonly="" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_paid') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_paid'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_paid" name="pharmacy_total_paid" autocomplete="off" type="text" value="0" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_less_discount') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_less_discount'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_less_discount" name="pharmacy_total_less_discount" value="0" autocomplete="off" type="text" />
							</div>
						</div>		
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
						<div class="<?php echo form_error('pharmacy_total_due') ? 'error' : ''; ?>">
							<div class='form-group'>
							   <label><?php e(lang('pharmacy_total_due'));?><span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
							   <input class="form-control" id="pharmacy_total_due" name="pharmacy_total_due" type="text" value="<?php echo $total; ?>" readonly="" />
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
                    <input type="reset" class="btn btn-warning btn-sm" value="Reset"/>                 
                    </div>   
                </div>
                </fieldset>
            </div>

    <?php echo form_close(); ?>
    </div>
    
</div>