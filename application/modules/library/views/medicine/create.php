
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
?>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">

        	<div class="col-sm-4 col-md-4 col-lg-2">
        		<div class="form-group <?php echo form_error('category_id') ? 'error' : ''; ?>">
        			<label class="control-label">Category Name<span class="required">*</span></label>
        			<select class="form-control" required="" name="category_id">
        				<option value="">Select one</option>
        				<?php foreach($cetegories as $category){?>
        					<option value="<?php echo $category->id; ?>"><?php echo $category->category_name;?></option>
        				<?php } ?>
        			</select>
        		</div>        		
        	</div>


        	<div class="col-sm-4 col-md-4 col-lg-2">
        		<div class="form-group <?php echo form_error('group_id') ? 'error' : ''; ?>">
        			<label class="control-label">Group<span class="required">*</span></label>
        			<select class="form-control" required="" name="group_id">
        				<option value="">Select one</option>
        				<?php foreach($gen_groups as $group){?>
        					<option value="<?php echo $group->GROUP_ID; ?>"><?php echo $group->GROUP_NAME;?></option>
        				<?php } ?>
        			</select>
        		</div>        		
        	</div>


        	<div class="col-sm-4 col-md-4 col-lg-2">
        		<div class="form-group <?php echo form_error('generic_name_id') ? 'error' : ''; ?>">
        			<label class="control-label">Generic Name<span class="required">*</span></label>
        			<select class="form-control" required="" name="generic_name_id">
        				<option value="">Select one</option>
        				<?php foreach($generic_names as $names){?>
        					<option value="<?php echo $names->GENERIC_ID; ?>"><?php echo $names->GENERIC_NAME;?></option>
        				<?php } ?>
        			</select>
        		</div>        		
        	</div>


        	<div class="col-sm-4 col-md-4 col-lg-2">
        		<div class="form-group <?php echo form_error('company_id') ? 'error' : ''; ?>">
        			<label class="control-label">Company<span class="required">*</span></label>
        			<select class="form-control" required="" name="company_id">
        				<option value="">Select one</option>
        				<?php foreach($companies as $company){?>
        					<option value="<?php echo $company->id; ?>"><?php echo $company->company_name;?></option>
        				<?php } ?>
        			</select>
        		</div>        		
        	</div>
        

            <div class="col-sm-4 col-md-4 col-lg-2">
				<div class="form-group <?php echo form_error('medicine_name') ? 'error' : ''; ?>">                      
					<label class="control-label" required="">Medicine Name</label>
					<input type='text' class="form-control" id='medicine_name' type='text' name='medicine_name' maxlength="100" value="<?php echo set_value('medicine_name', isset($record['medicine_category_name']) ? $record['medicine_name'] : ''); ?>"  required="" />
					<span class='help-inline'><?php echo form_error('medicine_name'); ?></span>
                </div>
            </div>


				
				
				<div class="col-sm-4 col-md-4 col-lg-2">
					<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
						<label class="control-label"><?php echo lang('bf_status').lang('bf_form_label_required');?></label>
						<select class="form-control" name="bf_status" id="bf_status" required="">
							<option value="1" <?php if(isset($record['status'])){if($record['status'] == 1){ echo "selected";}}?> >Active</option>
							<option value="0" <?php if(isset($record['status'])){if($record['status'] == 0){ echo "selected";}}?>>Inactive</option>											
						 </select>
						<span class='help-inline'><?php echo form_error('status'); ?></span>
					</div>
				</div>
				
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="footer pager">
                	<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save'); ?>"/>
                	<button type="reset" class="btn btn-warning">Reset</button>
					
                </div>
            </div>
        </fieldset>

    <?php echo form_close(); ?>

</div>








