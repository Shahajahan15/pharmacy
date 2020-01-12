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

if (isset($library_shift))
{
	$library_shift = (array) $library_shift;
}
$id = isset($library_shift['id']) ? $library_shift['id'] : '';

?>

<style type="text/css">
	element {
    margin-left: 10px;
}
</style>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Shift Add</legend>
       <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">  
      
            	<div class="form-group <?php echo form_error('shift_name') ? 'error' : ''; ?>">
                        <?php echo form_label(lang('library_initial_shift_name'). lang('bf_form_label_required'), 'lib_shift_shift_name', array('class' => 'control-label ') ); ?>
                        <div class='control'>
                            <input class="form-control" id='lib_shift_shift_name' type='text' name='lib_shift_shift_name'value="<?php echo set_value('lib_shift_shift_name', isset($library_shift['shift_name']) ? $library_shift['shift_name'] : '');?>"required=""  />
                            <span class='help-inline'><?php echo form_error('shift_name'); ?></span>
                        </div>
                </div>
				
                
            </div>      
        

                 <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
                 <div class="form-group">
				<?php 					
					echo form_dropdown('lib_shift_shift_from', $time_options, set_value('lib_shift_shift_from', isset($library_shift['shift_from']) ? $library_shift['shift_from'] : '1'), lang('library_initial_shift_from'), "class='form-control'", '', "class='control-label'");
				?>
				</div>
                </div>
               <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
	<?php 					
					echo form_dropdown('lib_shift_shift_to', $time_options, set_value('lib_shift_shift_to', isset($library_shift['shift_to']) ? $library_shift['shift_to'] : '1'), lang('library_initial_shift_to'), "class='form-control'", '', "class='control-label'");
				?>
				
                 
                </div>

             

<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<div class="form-group <?php echo form_error('library_initial_shift_status') ? 'error' : ''; ?>">
				  <?php echo form_label(lang('library_initial_shift_status'),'library_initial_shift_status',array('class'=> 'control-label'));?>
                  <div class='control'>
                  	<select name="lib_shift_shift_status" id="lib_shift_shift_status" class="form-control"required="" >
						<option value="1" <?php if(isset($library_shift)){echo (($library_shift['shift_status'] == 1)? "selected" : "");} ?>>Active</option>
						<option value="0" <?php if(isset($library_shift)){echo (($library_shift['shift_status'] == 0)? "selected" : "");} ?>>Inactive</option>
					</select>
                    <span class='help-inline'><?php echo form_error('lab_status'); ?></span>
                  </div>
                  </div> 
                    </div>


       
       
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
                  <input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('save'); ?>"  />
                  <button type="reset" class="btn btn-warning btn-sm">Reset</button>

                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>

















