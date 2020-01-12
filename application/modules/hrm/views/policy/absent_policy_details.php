<?php
if (isset($result))
{
	$details = (array) $result;	
}

?>

<style type="text/css">
		.new-row{margin-left: 5px;margin-right: 5px;}
	</style>
<span>
<div class="row new-row">
	<!-- Absent Parameter Type --> 
	<div class="col-sm-2 col-md-2 col-lg-2">
		<div class="form-group <?php echo form_error('ABSENT_PARAMETER_TYPE') ? 'error' : ''; ?>">
			<div class="control">
				<label><?php echo lang('absent_parameter_type').lang('bf_form_label_required');?></label>
				<select class="form-control absentMeter" name="absent_parameter_type[]" tabindex="4"  onchange='paraMeterCheck(this)'>
					<option value=""><?php echo lang('bf_msg_selete_one');?></option>
					<?php 
							foreach($parameter_type as $key=>$val)
							{		
								echo "<option value='".$key."'";
								
								if(isset($details['ABSENT_PARAMETER_TYPE']))
								{
									if(trim($details['ABSENT_PARAMETER_TYPE'])==$key){echo "selected";}
								}	
								
								echo ">".$val."</option>";
							}
					?>							
				 </select>
				 <span class='help-inline'><?php echo form_error('ABSENT_PARAMETER_TYPE'); ?></span>
			</div>	
		</div>
	</div>
	
	 
	<!-- absent_persentage Start -->
	<div class="col-sm-2 col-md-3 col-lg-3">
		<div class="form-group <?php echo form_error('ABSENT_PERSENT_FORMULA') ? 'error' : ''; ?>">									
			<label><?php echo lang('absent_persentage').lang('bf_form_label_required');?></label>
			<div class='control'>
				<input type="text" class="form-control absentPersent" name="absent_persentage[]" value="<?php echo set_value('ABSENT_PERSENT_FORMULA', isset($details['ABSENT_PERSENT_FORMULA']) ? $details['ABSENT_PERSENT_FORMULA'] : ''); ?>" placeholder="<?php e(lang('absent_persentage'));?>" title="<?php e(lang('absent_persentage'));?>" required="" tabindex="5"/>
				<span class='help-inline'><?php echo form_error('ABSENT_PERSENT_FORMULA'); ?></span>
			</div>
		</div>
	</div>
	
	
	<!-- absent_base_head Start -->
	<div class="col-sm-2 col-md-3 col-lg-3">							
		<div class="form-group <?php echo form_error('ABSENT_BASE_HEAD') ? 'error' : ''; ?>">
			<label><?php echo lang('absent_base_head').lang('bf_form_label_required');?></label>
			<select class="form-control" required="" name="absent_base_head[]" tabindex="6">
				<option value=""><?php echo lang('bf_msg_selete_one');?></option>
				<?php 
					
						foreach($baseHead as $baseHeads)
						{									
							echo "<option value='".$baseHeads->BASE_HEAD_ID."'";	
							if(isset($details['ABSENT_BASE_HEAD'])){
									if(trim($details['ABSENT_BASE_HEAD'])==$baseHeads->BASE_HEAD_ID){echo "selected";}
								}						
							echo ">".$baseHeads->BASE_SYSTEM_HEAD."</option>";
						}
					
				?>							
			 </select>
			 <span class='help-inline'><?php echo form_error('ABSENT_BASE_HEAD'); ?></span>
		</div>						
	</div>
	
	<!-- absent_amunt Start -->
	<div class="col-sm-2 col-md-2 col-lg-2">
		<div class="form-group <?php echo form_error('ABSENT_AMOUNT') ? 'error' : ''; ?>">
			<label><?php echo lang('absent_amount').lang('bf_form_label_required');?></label>
			<div class='control'>
				<input type="text" class="form-control absentAmountDis" name="absent_amount[]" value="<?php echo set_value('ABSENT_AMOUNT', isset($details['ABSENT_AMOUNT']) ? $details['ABSENT_AMOUNT'] : ''); ?>" placeholder="<?php e(lang('absent_amount'));?>" title="<?php e(lang('absent_amount'));?>" required="" tabindex="7" onkeyup="checkNumericAbsentAmount()"/>
				<span class='help-inline'><?php echo form_error('ABSENT_AMOUNT'); ?></span>
			</div>
		</div>
	</div>
	
	
	<!--<div class="col-sm-1 col-md-1 col-lg-1">
		<div class="form-group">
			<label>&nbsp; </label>
			<div class='control'>		
			<?php if(isset($removeRow) && $removeRow){?> 
				<a name="clicktoadd" class="btn btn-danger btn-xs glyphicon glyphicon-minus" onclick="removeAbsentTr(this)" href="javascript:void(0)"> </a>
			<?php } else { ?>	
				<a name="clicktoadd" class="btn btn-primary btn-xs glyphicon glyphicon-plus" onclick="addAbsentRow(this)" href="javascript:void(0)"> </a>
			<?php } ?>	
			</div>
		</div>
	</div>-->
	</div>
</span>