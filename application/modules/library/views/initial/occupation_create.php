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

if (isset($library_occupation))
{
	$library_occupation = (array) $library_occupation;
}
$id = isset($library_occupation['id']) ? $library_occupation['id'] : '';

?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend>Occupation Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
              <div class="form-group <?php echo form_error('occupation_name') ? 'error' : ''; ?>">
                  	<label><?php echo lang('library_initial_occupation_name').lang('bf_form_label_required');?></label>                     
                    <input class="form-control" id='lib_occupation_occupation_name' type='text' name='lib_occupation_occupation_name' maxlength="285" value="<?php echo set_value('lib_occupation_occupation_name', isset($library_occupation['occupation_name']) ? $library_occupation['occupation_name'] : '');?>"required="" />
                    <span class='help-inline'><?php echo form_error('occupation_name'); ?></span>                       
                </div>  
                
            </div>      
           <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">    
            <div class="form-group <?php echo form_error('occupation_name_bangla') ? 'error' : ''; ?>">
                  	<label><?php echo lang('library_initial_occupation_name_bangla');?></label>                     
                    <input class="form-control" id='occupation_name_bangla' type='text' name='occupation_name_bangla' maxlength="285" value="<?php echo set_value('lib_occupation_occupation_name', isset($library_occupation['occupation_name_bangla']) ? $library_occupation['occupation_name_bangla'] : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('occupation_name_bangla'); ?></span>                       
                </div>  
                </div>  
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
          <div class="form-group <?php echo form_error('occupation_status') ? 'error' : ''; ?>">					
						<label><?php echo lang('lib_occupation_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="lib_occupation_status" id="lib_occupation_status" required="">
							<option value="1" <?php if(isset($library_occupation['occupation_status'])){if($library_occupation['occupation_status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($library_occupation['occupation_status'])){if($library_occupation['occupation_status'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('occupation_status'); ?></span>
					</div>
				
                </div>
       
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
            <div class=" pager">
                  				
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  /> 
                      <button type="Reset" class="btn btn-warning btn-sm">Reset</button>    
                </div>   
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>








