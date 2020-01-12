
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

if (isset($employee_holiday_details))
{
	$employee_holiday_details = (array) $employee_holiday_details;
}
$id = isset($employee_holiday_details['HOLIDAY_ID']) ? $employee_holiday_details['HOLIDAY_ID'] : '';

?>

<style>
    .form-group .form-control, .control-group .form-control, .control-label{
        
        margin-left: 5px;
    }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">
        <legend>Holyday Define</legend>
		<div class="col-sm-3 col-md-3 col-lg-3"> 
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2"> 
					
			<div class="form-group <?php echo form_error('HOLIDAY_DATE') ? 'error' : ''; ?>">
				<label class="control-label">	<?php echo form_label(lang('HOLIDAY_DATE'). lang('bf_form_label_required'), 'HOLIDAY_DATE', array('class' => 'control-label') ); ?>	</label>
									                   
					<input type="text" name="HOLIDAY_DATE" value="<?php echo set_value('HOLIDAY_DATE', isset($employee_holiday_details['HOLIDAY_DATE']) ? $employee_holiday_details['HOLIDAY_DATE'] : ''); ?>" placeholder="<?php e(lang('HOLIDAY_DATE'));?>" id="HOLIDAY_DATE" class="form-control datepickerCommon" title="<?php e(lang('HOLIDAY_DATE'));?>" required="" tabindex="1"/>
                    
			</div>
                <span class='help-inline'><?php echo form_error('HOLIDAY_DATE'); ?></span>
			</div>
			
            <div class="col-sm-2 col-md-2 col-lg-2">     
                 
				<div class="form-group <?php echo form_error('HOLIDAY_NAME') ? 'error' : ''; ?>">
				<label class="control-label">
					<?php echo form_label(lang('HOLIDAY_NAME'). lang('bf_form_label_required'), 'HOLIDAY_NAME', array('class' => 'control-label') ); ?></label>
										             
					<input type='text' name='HOLIDAY_NAME' value="<?php echo set_value('HOLIDAY_NAME', isset($employee_holiday_details['HOLIDAY_NAME']) ? $employee_holiday_details['HOLIDAY_NAME'] : ''); ?>"  placeholder="<?php echo lang('HOLIDAY_NAME')?>" id='HOLIDAY_NAME' class="form-control" tabindex="2"/>					
                    
				</div>
             <span class='help-inline'><?php echo form_error('HOLIDAY_NAME'); ?></span>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2"> 
            		<div class="form-group <?php echo form_error('TYPE') ? 'error' : ''; ?>">
            		<label class="control-label">
					<?php echo form_label(lang('TYPE'). lang('bf_form_label_required'), 'TYPE', array('class' => 'control-label') ); ?></label>
					
					<select class="form-control" name="TYPE" id="TYPE" required="" tabindex="3">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>						
						<?php 
							foreach($holiday_type as $key => $val)
							{
								echo "<option value='".$key."'";
								if(isset($employee_holiday_details['HOLIDAY_TYPE']))
								{
									if($employee_holiday_details['HOLIDAY_TYPE']==$key){ echo "selected ";}
								}
									echo ">".$val."</option>";
							}
						?>
					</select>
					
				</div>
				<span class='help-inline'><?php echo form_error('TYPE'); ?></span>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3"> 
			</div>
			
        </fieldset>
        <fieldset>
             <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="footer pager">
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"/>
                    <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                    
                </div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








