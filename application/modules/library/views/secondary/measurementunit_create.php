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

$id = isset($library_building, $library_building->id) ? $library_building->id : ''
?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Measurement Unit Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">

            <div class="form-group <?php echo form_error('unit_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('lib_measurement_unit_name'). lang('bf_form_label_required'), 'lib_measurement_unit_name', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_measurement_unit_name' type='text' name='lib_measurement_unit_name' maxlength="285" value="<?php echo set_value('lib_measurement_unit_name', isset($library_building->unit_name) ? $library_building->unit_name: ''); ?>"required="" />
                            <span class='help-inline'><?php echo form_error('unit_name'); ?></span>
                        </div>
                </div>





            </div>


                 <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
			<div class="form-group <?php echo form_error('unit_details') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('lib_measurement_unit_details'). lang('bf_form_label_required'), 'lib_measurement_unit_details', array('class' => 'control-label col-sm-4') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_measurement_unit_details' type='text' name='lib_measurement_unit_details' maxlength="285" value="<?php echo set_value('lib_measurement_unit_details', isset($library_building->unit_details) ? $library_building->unit_details : ''); ?>" required=""/>
                            <span class='help-inline'><?php echo form_error('unit_details'); ?></span>
                        </div>
                </div>

                </div>
                   <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
                   <div class="form-group <?php echo form_error('parent_unit_id') ? 'error' : ''; ?>">
					<?php echo form_label(lang('lib_measurement_unit'). lang('bf_form_label_required'), 'parent_unit_id', array('class' => 'control-label col-sm-4') ); ?>
					<select class="form-control" name="parent_unit_id" id="parent_unit_id" required="" tabindex="3" required="">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						<?php
							if($measurement_unit){
								foreach($measurement_unit as $key => $value){
									echo "<option value='".$key."'";

									if(isset($library_building->PARENT_UNIT_ID))
									{
										if(trim($library_building->PARENT_UNIT_ID)== $key){echo "selected";}
									}
									echo ">".$value."</option>";
								}
							}
						?>
					 </select>
					 <span class='help-inline'><?php echo form_error('parent_unit_id'); ?></span>
				</div>
				</div>


<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<div class="form-group <?php echo form_error('lib_measurement_unit_status') ? 'error' : ''; ?>">
				  <?php echo form_label(lang('lib_measurement_unit_status'),'lib_measurement_unit_status',array('class'=> 'control-label')); ?>
                  <div class='control'>
                  	<select name="lib_measurement_unit_status" id="lib_measurement_unit_status" class="form-control">
						<option value="1" <?php if(isset($library_building, $library_building->status)){echo (($library_building->status == 1)? "selected" : "");} ?>>Active</option>
						<option value="0" <?php if(isset($library_building, $library_building->status)){echo (($library_building->status == 0)? "selected" : "");} ?>>Inactive</option>
					</select>
                    <span class='help-inline'><?php echo form_error('library_building'); ?></span>
                  </div>
                  </div>
                    </div>



            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class=" pager">
                <input type="submit" name="save" class="btn btn-primary btn-sm
                " value="<?php echo lang('save'); ?>"  />
                     <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
                </div>
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>

