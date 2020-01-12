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

if (isset($library_district))
{
	$library_district = (array) $library_district;
}
$id = isset($library_district['id']) ? $library_district['id'] : '';
?>


<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal district-form"'); ?>
    <fieldset>
        <legend>District Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">     
      
            <div class="form-group <?php echo form_error('division_ame') ? 'error' : ''; ?>">				
					<label><?php echo lang('library_zone_division_name').lang('bf_form_label_required');?></label>
					<select name="library_zone_division_name" id="library_zone_division_name" class="form-control" required="">
						<option value=""><?php echo lang('account_select_one'); ?></option>
						<?php
							foreach($division_list as $row)
							{				
								echo "<option value='".$row->division_id."'";
								if($library_district['division_no']==$row->division_id){echo "selected ";}
								echo ">".$row->division_name."</option>";
							}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('division_ame'); ?></span>					
                </div> 
					
   
            
            </div>      
        

  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
<div class="form-group <?php echo form_error('district_name') ? 'error' : ''; ?>">  
					<div id="checkName" style="color:#F00; font-size:14px;"></div>
					<label><?php echo lang('library_zone_district_name').lang('bf_form_label_required');?></label>                       
                    <input class="form-control" id='library_zone_district_name' type='text' name='library_zone_district_name' maxlength="285" value="<?php echo set_value('library_zone_district_name', isset($library_district['district_name']) ? $library_district['district_name'] : ''); ?>"  required=""/>
                    <span class='help-inline'><?php echo form_error('district_name'); ?></span>                   
                </div>
        
   </div>
                  
	  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
				<div class="form-group <?php echo form_error('district_status') ? 'error' : ''; ?>">					
					<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
					<select class="form-control" name="library_zone_district_status" id="library_zone_district_status" required="">
						<option value="1" <?php if(isset($library_district['district_status'])){if($library_district['district_status'] == 1){ echo "selected";}}?> >Active</option>
						<option value="0" <?php if(isset($library_district['district_status'])){if($library_district['district_status'] == 0){ echo "selected";}}?>>Inactive</option>						
					 </select>
					<span class='help-inline'><?php echo form_error('district_status'); ?></span>
				</div>
				</div>
               
            </fieldset>
            <fieldset>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="pager">
       
                    <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('save'); ?>"  />  
                    <button type="Reset" class="btn btn-warning btn-sm">Reset</button>
           
            </div>
            </div>
            </fieldset>

    <?php echo form_close(); ?>

</div>










