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

	if (isset($lib_quata))
	{
		$lib_quata = (array) $lib_quata;
	}
	$DESIGNATION_ID = isset($lib_quata['DESIGNATION_ID']) ? $lib_quata['DESIGNATION_ID'] : '';

	?>


<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">        
            <div class="col-sm-4 col-md-4 col-lg-4 col-md-offset-4"> 			
				<!---------------DISTRICT_ID--------------->						
				<div class="form-group <?php echo form_error('DISTRICT_ID') ? 'error' : ''; ?>">                   
					<label><?php echo lang('library_district_name').lang('bf_form_label_required');?></label>	          
					<select name="library_district_name" id="library_district_name" class="form-control" required=""  title="<?php echo lang('library_district_name')?>" >
						<option value=""><?php echo lang('bf_msg_selete_one')?></option>
						<?php
						foreach($districtName as $districtNames){
							echo "<option value='".$districtNames->district_id."'";
							
							if(isset($lib_quata['DISTRICT_ID']) )
							{							
							if($lib_quata['DISTRICT_ID']==$districtNames->district_id){ echo "selected ";}
							}
							echo ">".$districtNames->district_name."</option>";
						}
						?>
					</select>
					<span class='help-inline'><?php echo form_error('DISTRICT_ID'); ?></span>                   
                </div>
				
				<!---------------QUATA_NAME--------------->
				<div class="form-group <?php echo form_error('QUATA_NAME') ? 'error' : ''; ?>">	
					<div id="checkName" style="color:#F00; font-size:14px;"></div>
					<label><?php echo lang('library_quata_name').lang('bf_form_label_required');?></label>				
					<input type='text' class="form-control" name='library_quata_name' id='library_quata_name' maxlength="50" value="<?php echo set_value('library_quata_name', isset($lib_quata['QUATA_NAME']) ? $lib_quata['QUATA_NAME'] : ''); ?>" required=""  onblur="quotaNameCheck()"/>
					<span class='help-inline'><?php echo form_error('QUATA_NAME'); ?></span>				
                </div>
				
				
				<!---------------QUATA_NAME- Bangla-------------->
				<div class="form-group <?php echo form_error('QUATA_NAME_BANGLA') ? 'error' : ''; ?>">				
					<label><?php echo lang('library_quata_name_bangla');?></label>				
					<input type='text' class="form-control bn_language" name='library_quata_name_bangla' id='library_quata_name_bangla' maxlength="50" value="<?php echo set_value('library_quata_name_bangla', isset($lib_quata['QUATA_NAME_BANGLA']) ? $lib_quata['QUATA_NAME_BANGLA'] : ''); ?>" />
					<span class='help-inline'><?php echo form_error('QUATA_NAME_BANGLA'); ?></span>				
                </div>
				
				
				<!---------------NO_OF_QUATA--------------->
				<div class="form-group <?php echo form_error('NO_OF_QUATA') ? 'error' : ''; ?>">					
					<label><?php echo lang('library_no_of_quata').lang('bf_form_label_required');?></label>
					<input type='text' class="form-control" name='library_no_of_quata' id='library_no_of_quata' maxlength="50" value="<?php echo set_value('library_no_of_quata', isset($lib_quata['NO_OF_QUATA']) ? $lib_quata['NO_OF_QUATA'] : ''); ?>"required="" />
					<span class='help-inline'><?php echo form_error('NO_OF_QUATA'); ?></span>
					
                </div>
				
				
				<!-- status -->
					<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">					
						<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($lib_quata['STATUS'])){if($lib_quata['STATUS'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($lib_quata['STATUS'])){if($lib_quata['STATUS'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('STATUS'); ?></span>
					</div>
				
				<div class="box-footer pager">
					<?php echo anchor(SITE_AREA .'/quata_info/library/quata_create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>
					&nbsp;
					<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"  />
					
					
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
