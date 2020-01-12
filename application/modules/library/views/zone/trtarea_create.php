<?php
//extract($sendData);
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

if (isset($area_details))
{
	$area_details = (array) $area_details;
}
$id = isset($area_details['id']) ? $area_details['id'] : '';

?>

<div class="box box-primary row">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="nform-horizontal"'); ?>
    <fieldset>
        <legend> Add</legend>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">

            <div class="form-group <?php echo form_error('division_ame') ? 'error' : ''; ?>">
					<label><?php echo lang('library_zone_division_name').lang('bf_form_label_required');?></label>
					<select name="library_zone_division_name" id="library_zone_division_name" class="form-control" required="">
						<option value=""><?php echo lang('account_select_one'); ?></option>
						<?php
							foreach($division_list as $row)
							{
								echo "<option value='".$row->division_id."'";
								if(isset($area_details, $area_details['division_no']) && $area_details['division_no']==$row->division_id){echo "selected ";}
								echo ">".$row->division_name."</option>";
							}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('division_ame'); ?></span>
                </div>



            </div>


  <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
<div class="form-group <?php echo form_error('district_ame') ? 'error' : ''; ?>">
					<label><?php echo lang('library_zone_district_name').lang('bf_form_label_required');?></label>
					<select name="library_zone_district_name" id="library_zone_district_name" class="form-control" required="">
						<option value=""><?php echo lang('account_select_one'); ?></option>
							<?php
								foreach($district_list as $row)
								{
									echo "<option value='".$row->district_id."'";
									if(isset($area_details['district_no'])==$row->district_id){echo "selected ";}
									echo ">".$row->district_name."</option>";
								}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('district_ame'); ?></span>
				</div>

   </div>
     <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
	<div class="form-group <?php echo form_error('area_name') ? 'error' : ''; ?>">
					<label><?php echo lang('library_zone_area_name').lang('bf_form_label_required');?></label>
					<select name="library_zone_area_name" id="library_zone_area_name" class="form-control" required="">
						<option value=""><?php echo lang('account_select_one'); ?></option>
							<?php
								foreach($area_list as $row)
								{
									echo "<option value='".$row->area_id."'";
									if(isset($area_details['area_no'])==$row->area_id){echo "selected ";}
									echo ">".$row->area_name."</option>";
								}
							?>
					</select>
					<span class='help-inline'><?php echo form_error('area_name'); ?></span>
				</div>
				</div>


        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">

			<div class="form-group <?php echo form_error('trt_name') ? 'error' : ''; ?>">
					<label><?php echo lang('library_zone_trt_name').lang('bf_form_label_required');?></label>
					<input class="form-control" id='library_zone_trt_name' type='text' name='library_zone_trt_name' maxlength="285" value="<?php echo set_value('library_zone_trt_name', isset($area_details['trt_name']) ? $area_details['trt_name'] : ''); ?>" onblur="postOfficeCheck()" required=""/>
					<span class='help-inline'><?php echo form_error('trt_name'); ?></span>
				</div>
        </div>
        <div class="col-xs-8 col-sm-4 col-md-4 col-lg-4">
	 <div class="form-group <?php echo form_error('trt_status') ? 'error' : ''; ?>">
					<label><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
					<select class="form-control" name="library_zone_trt_status" id="library_zone_trt_status" required="">
						<option value="1" <?php if(isset($area_details['trt_status'])){if($area_details['trt_status'] == 1){ echo "selected";}}?> >Active</option>
						<option value="0" <?php if(isset($area_details['trt_status'])){if($area_details['trt_status'] == 0){ echo "selected";}}?>>Inactive</option>
					 </select>
					<span class='help-inline'><?php echo form_error('trt_status'); ?></span>
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



