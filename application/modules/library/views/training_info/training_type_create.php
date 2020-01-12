
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

if (isset($lib_training))
{
	$lib_training = (array) $lib_training;
}
$id = isset($lib_training['id']) ? $lib_training['id'] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control{
        width: 50%;
    }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", id="training_type_setup_form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        
            <div class="col-sm-10 col-md-10 col-lg-10">     
                                        
				<!------------TRAINING_Type_NAME----------->
				<div class="form-group <?php echo form_error('TRAINING_TYPE_NAME') ? 'error' : ''; ?>">
					<span id="checkTypeName" style="color:#F00; font-size:14px;"></span>
						<?php echo form_label(lang('library_training_type'). ' ' . lang('english'). lang('bf_form_label_required'), 'library_training_type', array('class' => 'control-label col-sm-4') ); ?>
						<div class='control'>
						<input type='text' class="form-control" name='library_training_type' id='library_training_type'  maxlength="50" value="" required="" tabindex="1" onkeyup='trainingTypeCheck()'/>
						<span class='help-inline'><?php echo form_error('TRAINING_TYPE_NAME'); ?></span>
					</div>
                </div>
				
				<!------------TRAINING_Type_NAME Bengali ----------->
				<div class="form-group <?php echo form_error('training_type_name_bengali') ? 'error' : ''; ?>">
					<div id="checkTypeName" style="color:#F00; font-size:14px;"></div>
					<?php echo form_label(lang('library_training_type'). ' '. lang('bengali'), 'training_type_name_bengali', array('class' => 'control-label col-sm-4') ); ?>
					<div class='control'>
						<input type='text' class="form-control bn_language" name='training_type_name_bengali' id='training_type_name_bengali'  maxlength="50" value="" required="" tabindex="2"/>
						<span class='help-inline'><?php echo form_error('training_type_name_bengali'); ?></span>
					</div>
                </div>
				
				<!------------DESCRIPTION----------->
				<div class="form-group <?php echo form_error('TYPE_DESCRIPTION') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_training_type_description').' '. lang('english'), 'library_training_type_description', array('class' => 'control-label col-sm-4') ); ?>
					<div class='control'>
						<input type='text' class="form-control" name='library_training_type_description' id='library_training_type_description'  maxlength="150" value=""  tabindex="3"/>
						<span class='help-inline'><?php echo form_error('TYPE_DESCRIPTION'); ?></span>
					</div>
                </div>
				
				<!------------ DESCRIPTION IN BENGALI ----------->
				<div class="form-group <?php echo form_error('type_description_bengali') ? 'error' : ''; ?>">
					<?php echo form_label(lang('library_training_type_description').' '. lang('bengali'), 'type_description_bengali', array('class' => 'control-label col-sm-4') ); ?>
					<div class='control'>
						<input type='text' class="form-control bn_language" name='type_description_bengali' id='type_description_bengali'  maxlength="150" value=""  tabindex="4"/>
						<span class='help-inline'><?php echo form_error('type_description_bengali'); ?></span>
					</div>
                </div>
				
				<!---	Training Type----------->
				<div class="form-group <?php echo form_error('TYPE_STATUS') ? 'error' : ''; ?>">					
					<?php echo form_label(lang('library_training_type_type'), 'library_training_type_type', array('class' => 'control-label col-sm-4') ); ?>
					 <div class='control'>
					<select name="library_training_type_type" id="library_training_type_type" class="form-control" required="" tabindex="5">						
						<option value="1">Local Internal</option>
						<option value="2">Local External</option>
						<option value="3">Foreign</option>							
					 </select>
					</div>
					 <span class='help-inline'><?php echo form_error('TYPE_STATUS'); ?></span>
				</div>	
				
				
				
				<!------------Status----------->
				<div class="form-group <?php echo form_error('TYPE_STATUS') ? 'error' : ''; ?>">					
					<?php echo form_label(lang('library_training_status'), 'library_training_status', array('class' => 'control-label col-sm-4') ); ?>
					 <div class='control'>
					<select name="library_training_status" id="library_training_status" class="form-control" required="" tabindex="6">
						
						<option value="1">Active</option>
						<option value="0">Inactive</option>							
					 </select>
					</div>
					 <span class='help-inline'><?php echo form_error('TYPE_STATUS'); ?></span>
				</div>			
                    
                <div class="box-footer pager">
					<input type='hidden' name='TRAINING_TYPE_ID' value=""  id='TRAINING_TYPE_ID' />
                    <input type="button" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>" onclick="addTrainingType()"  />
                    &nbsp;
                    <?php echo anchor(SITE_AREA .'/training_type/library/training_type_create', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
					<input type="button" name="" class="btn btn-primary" value="<?php echo lang('bf_action_view'); ?>" onclick="showTrainingType()"/>
                	
                </div>               

            </div>
        </fieldset>

    <?php echo form_close(); ?>
	
	
	<div class="col-sm-10 col-md-10 col-lg-10">
		<div>
			<table class="table table-striped">
				<tbody id="employeeTrainingInnerHTML">
					<!--Ajax data goes here if responds is successful -->
				</tbody>							
			</table>
			
		</div>
    </div> 

</div>








