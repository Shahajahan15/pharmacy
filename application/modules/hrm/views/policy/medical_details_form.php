<?php
if (isset($result))
{
	$details = (array) $result;	
}

?>

<!-- ----------- Bonus Policy / Confirmation Status ---------------- -->
<span class="row">

	<!-- ----------- Bonus Policy / Base Head ---------------- --> 
	<div class="col-sm-3 col-md-3 col-lg-3  padding-left-div">

		<div class="form-group <?php echo form_error('BASE_HEAD') ? 'error' : ''; ?>">
			<?php echo form_label(lang('BASE_HEAD'). lang('bf_form_label_required'), 'BASE_HEAD', array('class' => 'control-label') ); ?>
			<select class="form-control" name="BASE_HEAD[]" id="BASE_HEAD" required="" tabindex="6">
				<option value=""><?php echo lang('bf_msg_selete_one');?></option>
				
				<?php 						
				foreach($base_heade_list as $base_heade_list)
						{									
							echo "<option value='".$base_heade_list->BASE_HEAD_ID."'";	
							
							if(isset($details['BASE_HEAD'])){
									if(trim($details['BASE_HEAD'])==$base_heade_list->BASE_HEAD_ID){echo "selected";}
							}	
							
							echo ">".$base_heade_list->BASE_SYSTEM_HEAD."</option>";
						}
				?>	
				
			 </select>
			 <span class='help-inline'><?php echo form_error('BASE_HEAD'); ?></span>
		</div>

	</div>

	<!-- ----------- Bonus Policy / Amount Type ---------------- --> 
	<div class="col-sm-3 col-md-3 col-lg-3  padding-left-div">
		
		<div class="form-group <?php echo form_error('AMOUNT_TYPE') ? 'error' : ''; ?>">
			<?php echo form_label(lang('AMOUNT_TYPE'). lang('bf_form_label_required'), 'AMOUNT_TYPE', array('class' => 'control-label') ); ?>
			<select class="form-control" name="AMOUNT_TYPE[]" id="AMOUNT_TYPE" required="" tabindex="7">
				<option value=""><?php echo lang('bf_msg_selete_one');?></option>
				
				<?php 
					foreach($amount_type as $key => $val)
					{
						echo "<option value='".$key."'";
						
						if(isset($details['AMOUNT_TYPE']))
						{
							if(trim($details['AMOUNT_TYPE'])==$key){echo "selected";}
						}
						
						echo ">".$val."</option>";
					}
				?>
				
			 </select>
			 <span class='help-inline'><?php echo form_error('AMOUNT_TYPE'); ?></span>
		</div>

	</div>

	<!-- ------- Bonus Policy / Amount ----- --> 
	<div class="col-sm-3 col-md-3 col-lg-3  padding-left-div">

		<div class="form-group <?php echo form_error('AMOUNT') ? 'error' : ''; ?>">
			<?php echo form_label(lang('AMOUNT'), 'AMOUNT', array('class' => 'control-label') ); ?>			
			<input type='text' name='AMOUNT[]' value="<?php echo set_value('AMOUNT', isset($details['AMOUNT']) ? $details['AMOUNT'] : ''); ?>" placeholder="<?php echo lang('AMOUNT')?>" id='AMOUNT' class="form-control" maxlength="100" required onblur="checkNumericAmount()" tabindex="8"/>							
			<span class='help-inline'><?php echo form_error('AMOUNT'); ?></span>
		</div>

	</div>

	<div class="padding-left-div">
		<div class="form-group">
			<div class='control'>
			&nbsp;
			<?php if(isset($removeRow) && $removeRow){?> 				
				<a name="clicktoadd" class="btn btn-danger glyphicon glyphicon-minus" onclick="removeMedicalDetailsRow(this)" href="javascript:void(0)"> </a>
			<?php } else { ?>	
				<a name="clicktoadd" class="btn btn-primary glyphicon glyphicon-plus" onclick="addMedicalDetailsRow(this)" href="javascript:void(0)"> </a> 										
			<?php } ?>
			</div>
		</div>
	</div>	
	
</span>
