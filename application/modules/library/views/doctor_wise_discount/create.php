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
	</style>

	<div class="box box-primary">
		<?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal doctor_discount_form"'); ?>
		<fieldset>
			<legend>Patient Search</legend>
			<!-- Patient code-->
			<div class="col-md-12 col-lg-12 col-sm-12">
				<div class="col-sm-12 col-md-6 col-lg-4 col-lg-offset-4">
					<div class="form-group p_code <?php echo form_error('patient_code') ? 'error' : ''; ?>">

						<select class="form-control patient-auto-complete"></select>

						<span class='help-inline'><?php echo form_error('patient_code'); ?></span>
					</div>
				</div>
			</div>	
		</fieldset>
		<fieldset>
			<legend>Patient Add</legend>
			<div class="">
        	<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        		<div class="form-group p_name<?php echo form_error('patient_name') ? 'error' : ''; ?>">
        				<label>Patient Name<span class="required"><?php e(lang('bf_star_mark'));?></span> </label>
        				<input class="form-control patient_name" id='patient_name'  name='patient_name' required />
        				<input type="hidden" class="form-control patient_id" id = "patient_id" name="patient_id" />
        				<span class='help-inline'><?php echo form_error('patient_name'); ?></span>
        		</div>
        	</div>
	        <div class="col-xs-4 col-sm-2 col-md-1 col-lg-1">
    			<div class="form-group p_sex <?php echo form_error('sex') ? 'error' : ''; ?>">
    					<label>Sex<span class="required"><?php e(lang('bf_star_mark'));?></span></label>
    					<select name="sex" id="sex" class="form-control" required="required">
    						<option value="" selected="">Select</option>
    							<?php foreach($sex as $key => $val) : ?>
									<option value="<?php echo $key; ?>"><?php echo $val;?></option>
								<?php endforeach; ?>
								</select>
						<span class='help-inline'><?php echo form_error('sex'); ?></span>
				</div>
			</div>

			<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1" style="padding-right:0px;">	
				<div class="control"> 
					<label>Year<span class="required">*</span> </label>             
					<input autocomplete="off" class="form-control cform-control" id="cyear" type="text" value="0"> 
				</div>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1" style="padding-right:0px;padding-left: 0px;">	
				<div class="control"> 
					<label>Month<span class="required">*</span> </label>             
					<input autocomplete="off" class="form-control cform-control" id="cmonth" type="text" value="0"> 
				</div>	
			</div>
			<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1" style="padding-left: 0px;">	
				<div class="control"> 
					<label>Day<span class="required">*</span> </label>             
					<input autocomplete="off" class="form-control cform-control" id="cday" type="text" value="0"> 
				</div>	
			</div>
            <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
            	<div class="p_dob <?php echo form_error('birthday') ? 'error' : ''; ?>">
            		<div class='dbirthday form-group'>
            			<label>Date of Birth<span class="required">*</span></label>
            			<div class="input-group">
            				<div class="input-group-addon">
            					<i class="fa fa-calendar"></i>
            				</div>
            				<input type="text" id='dob' name='dob' class="form-control nc-birth datepickerCommon" required="" />
            			</div>
            			<span class='help-inline'><?php echo form_error('birthday'); ?></span>
            		</div>
            	</div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
            	<div class="form-group p_conNo <?php echo form_error('contact_no') ? 'error' : ''; ?>">
            			<label>Contact No<span class="required"><?php e(lang('bf_star_mark'));?></span></label>
            			<div class="input-group">
            				<div class="input-group-addon">
            					<i class="fa fa-phone"> +880</i>
            				</div>
            				<input type="text" id='contact_no' name='contact_no' class="form-control numeric-zero" required=""  />
            			</div>
            			<span class='help-inline'><?php echo form_error('contact_no'); ?></span>
            	</div>
            </div>
	    </fieldset>
	    <fieldset>
	    	<legend>Add Patient Discount</legend>
            	<!-- Patient name-->
            	<!--<div class="col-sm-12 col-md-6 col-lg-3">
            		<div class="form-group p_id <?php echo form_error('patient_id') ? 'error' : ''; ?>">
            			<label class="control-label">Patient Name<span class="required">*</span></label>				

            			<input type="text" id='patient_name' disabled="" value="<?php if(isset($record)){ echo $record->patient_name; }?>" class="form-control" required="">

            			<span class='help-inline'><?php echo form_error('patient_id'); ?></span>
            		</div>
            	</div> -->

            	<div class="col-sm-12 col-md-3 col-lg-2">
            		<div class="form-group a_type">
            			<label class="control-label">Agent Type<span class="required">*</span></label>
            			<select class="form-control" name="agent_type" id="agent_type" required="">
            				<option value="">Select Agent</option>
            				<option value="1">External Doctor</option>
            				<option value="2">Reference</option>
            				<option value="3">Internal Doctor</option>
            			</select>
            		</div>
            	</div>

            	<div class="col-sm-12 col-md-4 col-lg-3">
            		<div class="form-group a_id">
            			<label class="control-label">Agent<span class="required">*</span></label>
            			<select class="form-control" name="agent" id="agent" required="">
            				<option value="">Not Available</option>				
            			</select>
            		</div>
            	</div>
			

			<!-- discount_start_date-->
			<div class="col-sm-12 col-md-3 col-lg-2">
				<div class="form-group <?php echo form_error('discount_type') ? 'error' : ''; ?>">
					<label class="control-label">Discount Type<span class="required">*</span></label>				
					<select class="form-control discount_type" name="discount_type" required="">
						<option value="1">Overall</option>
						<option value="2">Single</option>
					</select>
				</div>
			</div>

			<!-- Service ID-->
			<div class="col-sm-12 col-md-3 col-lg-2">
				<div class="form-group s_id <?php echo form_error('service_id') ? 'error' : ''; ?>">	
					<label class="label-control">Discount Service<span class="required">*</span></label>
					<select class="form-control service_id" name="service_id" required="">
						<option value="">Select Service</option>
						<?php if($service_lists): foreach($service_lists as $list){?>
							<option value="<?php echo $list->id; ?>"><?php echo $list->service_name; ?></option>

							<?php } endif;?>
						</select>
						<span class='help-inline'><?php echo form_error('service_id'); ?></span>
					</div>
				</div>

				<!-- Discount percent-->
				<div class="col-sm-12 col-md-3 col-lg-1">
					<div class="form-group d_p <?php echo form_error('discount_percent') ? 'error' : ''; ?>">	
						<label class="label-control">Discount<span class="required">*</span></label>
						<input type="text" name="suggest_discount" required="" class="form-control suggest_discount discount_percent-if-overall">

						<span class='help-inline'><?php echo form_error('discount_percent'); ?></span>
					</div>
				</div>

				<!-- Sub Service ID-->
				<div class="col-sm-12 col-md-2 col-lg-2">
					<div class="form-group ss_id <?php echo form_error('sub_service_id') ? 'error' : ''; ?>">
						<label class="label-control">Sub Service<span class="required sub_required" style="display: none;">*</span></label>
						<select class="form-control sub_service_id" disabled="">
							<span id="sub_service_list">

							</span>
						</select>
						<span class='help-inline'><?php echo form_error('sub_service_id'); ?></span>
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2 col-lg-offset-5">
					<input class="btn btn-info btn-xs show" type="button" value="Show" style="margin-top: 18px; margin-right:3px; float: left;">
					<button type="button" class="btn btn-warning btn-xs" onClick="resetDoctorDiscountForm()" style="margin-top: 18px; float:left;">Reset</button>
				</div>

			</fieldset>



	<!--<fieldset>
		<legend>Multiple Sub Item Discount</legend>

		<table class="table">
			<thead>
			<tr class="active">
				<th>Service Name</th>
				<th>Sub Service Name</th>
				<th>Discount (percent)</th>
				<th><span class="btn btn-xs btn-danger"><i class="fa fa-times" aria-hidden="true"></i></span></th>
			</tr>
			</thead>

			<tbody class="multi-input-fields">
			<!--<?php if(isset($discounts)){?>
				<?php foreach($discounts as $discount){?>
				<tr class="success">
					<td>
						<?php echo $discount['service_name']; ?><input type="hidden" name="service_id[]" class="form-control service_id" value="<?php echo $discount['service_id']; ?>"> 
					</td>
					<td>
						<?php echo $discount['sub_service_name']; ?><input type="hidden" name="sub_service_id[]" class="form-control sub_service_id" value="<?php echo $discount['sub_service_id']; ?>"> 
					</td>
					<td>
						<select name="discount_type[]" class="form-control" required="">
							<option value="1" <?php if($discount['discount_type']==1){echo 'selected'; }?>>Percentage</option>
							<option value="0" <?php if($discount['discount_type']==0){echo 'selected'; }?>>Amount</option>
						</select> 
					</td>
					<td>
						<input type="text" name="discount[]" class="form-control" value="<?php echo $discount['discount']; ?>" required=""> 
					</td>
					<td>
						<select name="discount_unit[]" class="form-control discount_unit" required="">
							<option value="1" <?php if($discount['discount_unit']==1){echo 'selected'; }?>>Day</option>
							<option value="2" <?php if($discount['discount_unit']==2){echo 'selected'; }?>>Hour</option>
						</select> 
					</td>
					<td>
						<button type="button" class="remove"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<?php }?>
				<?php }?> -->


			<!--</tbody>
		</table>
	</fieldset> -->

	<fieldset id="discount_show" style="display: none">
		<legend>Discount Add</legend>
		<table class="table">
			<thead>
				<tr>
					<th>Service Name</th>
					<th>Sub Service Name</th>
					<th>Doctor/Reference Discount</th>
					<th>Hospital Discount</th>
					<th>-</th>
				</tr>
			</thead>
			<tbody id="discount_body_show">
			</tbody>
		</table>
	</fieldset>

	<fieldset class="submit-btn" style="display: none;">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="pager">
				<input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>" />
			</div> 
		</div>
	</fieldset>

	<?php echo form_close(); ?>

	</div>
