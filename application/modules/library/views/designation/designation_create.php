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

	if (isset($lib_designation))
	{
		$lib_designation = (array) $lib_designation;
	}
	$DESIGNATION_ID = isset($lib_designation['DESIGNATION_ID']) ? $lib_designation['DESIGNATION_ID'] : '';

	?>

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

if (isset($library_department))
{
	$library_department = (array) $library_department;
}
$id = isset($library_department['id']) ? $library_department['id'] : '';

?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Department Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
             <div class="form-group <?php echo form_error('DESIGNATION_NAME') ? 'error' : ''; ?>">	
					<div id="checkName" style="color:#F00; font-size:14px;"></div>
					<label><?php echo lang('library_designation_name').lang('bf_form_label_required');?></label>						
					<input type='text' class="form-control" name='library_designation_name' id='library_designation_name' maxlength="50" value="<?php echo set_value('library_designation_name', isset($lib_designation['DESIGNATION_NAME']) ? $lib_designation['DESIGNATION_NAME'] : ''); ?>" required="" 
					onblur="designationCheck()" />
					<span class='help-inline'><?php echo form_error('DESIGNATION_NAME'); ?></span>				
                </div>
				
				
                
            </div>      
        

                 <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
				<div class="form-group <?php echo form_error('GRADE_ID') ? 'error' : ''; ?>">
                    <label><?php echo lang('library_grade_name').lang('bf_form_label_required');?></label>                                             
					<select name="library_grade_name" id="library_grade_name" class="form-control" required=""  title="<?php echo lang('library_grade_name')?>" >
						<option value=""><?php echo lang('bf_msg_selete_one')?></option>
						<?php
						foreach($gradeName as $gradeNames){
							echo "<option value='".$gradeNames->GRADE_ID."'";
							
							if(isset($lib_designation['GRADE_ID']) )
							{							
							if($lib_designation['GRADE_ID']==$gradeNames->GRADE_ID){ echo "selected ";}
							}
							echo ">".$gradeNames->GRADE_NAME."</option>";
						}
						?>
					</select>
					<span class='help-inline'><?php echo form_error('GRADE_ID'); ?></span>                  
                </div>
				
                </div>
               <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
	<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">					
						<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="library_designation_status" id="library_designation_status" required="">
							<option value="1" <?php if(isset($lib_designation['STATUS'])){if($lib_designation['STATUS'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($lib_designation['STATUS'])){if($lib_designation['STATUS'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('STATUS'); ?></span>
					</div>
                 
                </div>
               
       
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
                 <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
					<button type="Reset" class="btn btn-warning btn-sm">Reset</button>
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>

