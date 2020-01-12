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

if (isset($library_division))
{
	$library_division = (array) $library_division;
}
$id = isset($library_division['id']) ? $library_division['id'] : '';

?>



<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>

        <fieldset class="box-body">   
   <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
  
        <div class="form-group <?php echo form_error('division_name') ? 'error' : ''; ?>"> 
                    <div id="checkName" style="color:#F00; font-size:14px;"></div>
                    <label><?php echo lang('library_zone_division_name').lang('bf_form_label_required');?></label>                      
                    <input class="form-control" id='library_zone_division_name' type='text' name='zone_division_division_name' maxlength="285" value="<?php echo set_value('zone_division_division_name', isset($library_division['division_name']) ? $library_division['division_name'] : ''); ?>" 
                    onblur="divisionCheck()" />
                    <span class='help-inline'><?php echo form_error('division_name'); ?></span>                      
                </div>
    </div>   
    <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
       <div class="form-group <?php echo form_error('division_name_bangla') ? 'error' : ''; ?>">  
		<div id="checkName" style="color:#F00; font-size:14px;"></div>
		<label><?php echo lang('library_zone_division_name_bangla').lang('bf_form_label_required');?></label>                       
            <input class="form-control" id='division_name_bangla' type='text' name='division_name_bangla' maxlength="285" value="<?php echo set_value('division_name_bangla', isset($library_division['division_name_bangla']) ? $library_division['division_name_bangla'] : ''); ?>" onblur="districtCheck()"/>
            <span class='help-inline'><?php echo form_error('division_name_bangla'); ?></span>                   
                </div>
        
    </div> 	
				
               
	<div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">			
<div class="form-group <?php echo form_error('division_status') ? 'error' : ''; ?>">					
		<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
					<select class="form-control" name="zone_division_division_status" id="zone_division_division_status" required="">
						<option value="1" <?php if(isset($library_division['division_status'])){if($library_division['division_status'] == 1){ echo "selected";}}?> >Active</option>
						<option value="0" <?php if(isset($library_division['division_status'])){if($library_division['division_status'] == 0){ echo "selected";}}?>>Inactive</option>
						
					 </select>
					<span class='help-inline'><?php echo form_error('division_status'); ?></span>
				</div>
				</div>
				</fieldset>
				<fieldset>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="pager">
					
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  />
                   <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                   
                	
                </div>  
                </div>             

            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>
