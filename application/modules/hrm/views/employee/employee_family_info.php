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
if (isset($emp_family_details)){
	 $emp_family_details = (array) $emp_family_details;
	// print_r($emp_family_details);
}
$EMP_FAMILY_ID = isset($emp_family_details['EMP_FAMILY_ID']) ? $emp_family_details['EMP_FAMILY_ID'] : '';
?>
<style>
.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'id="familyInFoFrm", role="form", class="form-horizontal", onsubmit=""'); ?>

    <fieldset class="box-body">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="panel panel-primary">
					<div class="panel-heading" align="center">
                        <h3 class="panel-title">Employee Family Information</h3>
                    </div>
					
		<div class="panel-body">
			
			<!-- left side start -->
			<div class="col-sm-6  col-md-6 col-lg-6 padding-left-div">
				<!-- ----------- Name (English)------------------- --> 
                <div class="form-group <?php echo form_error('NAME') ? 'error' : ''; ?>">
                     <?php echo form_label(lang('NAME'). ' '. lang('ENGLISH') . lang('bf_form_label_required'), 'NAME', array('class' => 'control-label') ); ?>
                    <input type='text' name='NAME' value="<?php echo set_value('NAME', isset($emp_family_details['NAME']) ? $emp_family_details['NAME'] : ''); ?>" id='NAME' class="form-control" maxlength="100" required tabindex="1"/>
                    <span class='help-inline'><?php echo form_error('NAME'); ?></span>
                </div>
				
				<!-- -----------Name (Bengali)------------------- --> 
                <div class="form-group <?php echo form_error('NAME_BENGALI') ? 'error' : ''; ?>">
                     <?php echo form_label(lang('NAME_BENGALI') .' '  .lang('BENGALI'), 'NAME', array('class' => 'control-label') ); ?>
                    <input type='text' name='NAME_BENGALI' value="<?php echo set_value('NAME_BENGALI', isset($emp_family_details['NAME_BENGALI']) ? $emp_family_details['NAME_BENGALI'] : ''); ?>" id='NAME_BENGALI' class="form-control" maxlength="100" required tabindex="2"/>
                    <span class='help-inline'><?php echo form_error('NAME_BENGALI'); ?></span>
                </div>
				
											
				<!-- ----------- Date of Birth --------------- -->
				<div class="form-group <?php echo form_error('BIRTH_DATE') ? 'error' : ''; ?>">	
					<?php echo form_label(lang('BIRTH_DATE'). lang('bf_form_label_required'), 'BIRTH_DATE', array('class' => 'control-label') ); ?>					
					<input type="text" name="BIRTH_DATE" value="<?php echo set_value('BIRTH_DATE', isset($employee_details['BIRTH_DATE']) ? $employee_details['BIRTH_DATE'] : '');?>" id="BIRTH_DATE" class="form-control datepickerCommon"  title="<?php e(lang('BIRTH_DATE'));?>" required="" onblur="ageCalculation()" tabindex="3"/>
					<span class='help-inline'><?php echo form_error('BIRTH_DATE'); ?></span>
				</div>	
				
				
				<!-- ----------- Occupation ------------- --> 
                <div class="form-group <?php echo form_error('OCCPATION') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('OCCPATION'), 'OCCPATION', array('class' => 'control-label') ); ?>	
                    <input type='text' name='OCCPATION' value="<?php echo set_value('OCCPATION', isset($emp_family_details['OCCPATION']) ? $emp_family_details['OCCPATION'] : ''); ?>" id='OCCPATION' class="form-control" tabindex="4"/>
                    <span class='help-inline'><?php echo form_error('OCCPATION'); ?></span>
                </div>

					
				
			</div> 
			<!-- left side end -->
			
			<!-- Right side stat -->
			<div class="col-sm-5 col-md-5 col-lg-5 padding-left-div">				
				
				<!-- ----------- Occupation Bengali ------------- --> 
                <div class="form-group <?php echo form_error('OCCPATION_BENGALI') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('OCCPATION_BENGALI'), 'OCCPATION_BENGALI', array('class' => 'control-label') ); ?>	
                    <input type='text' name='OCCPATION_BENGALI' value="<?php echo set_value('OCCPATION_BENGALI', isset($emp_family_details['OCCPATION_BENGALI']) ? $emp_family_details['OCCPATION_BENGALI'] : ''); ?>" id='OCCPATION_BENGALI' class="form-control" tabindex="5"/>
                    <span class='help-inline'><?php echo form_error('OCCPATION_BENGALI'); ?></span>
                </div>	
				
				<!-- ----------- Relation ---------------- --> 
				<div class="form-group <?php echo form_error('RELATION') ? 'error' : ''; ?>">
					<?php echo form_label(lang('RELATION'). lang('bf_form_label_required'), 'RELATION', array('class' => 'control-label') ); ?>
					<select class="form-control" name="RELATION" id="RELATION" required="" tabindex="6">
						<option value=""><?php echo lang('bf_msg_selete_one');?></option>
						
						<?php 
							foreach($relation as $key => $val)
							{
								echo "<option value='".$key."'";
								
								if(isset($emp_family_details['RELATION']))
								{
									if($emp_family_details['RELATION']==$key){ echo "selected ";}
								}
								
								echo ">".$val."</option>";
							}
						?>
						
					 </select>
					 <span class='help-inline'><?php echo form_error('RELATION'); ?></span>
				</div>
				
				<!-- ----------- Employee Number of_children ------------ -->               
				<div class="form-group <?php echo form_error('AGE') ? 'error' : ''; ?>">
                    <?php echo form_label(lang('AGE'). lang('bf_form_label_required'), 'AGE', array('class' => 'control-label') ); ?>
                    <input type='text' name='AGE' value="<?php echo set_value('AGE', isset($emp_family_details['AGE']) ? $emp_family_details['AGE'] : ''); ?>" placeholder="<?php echo lang('AGE')?>" id='AGE' class="form-control" maxlength="30"  required readonly />
                    <span class='help-inline'><?php echo form_error('AGE'); ?></span>
                </div>
				
								
				<!-- ----------- Address ---------------- -->               
				<div class="form-group <?php echo form_error('CONTACT_NO') ? 'error' : ''; ?>">
                    <label><?php echo lang('CONTACT_NO');?></label>
                    <input type='text' name='CONTACT_NO' value="<?php echo set_value('CONTACT_NO', isset($emp_family_details['CONTACT_NO']) ? $emp_family_details['CONTACT_NO'] : ''); ?>" placeholder="<?php echo lang('CONTACT_NO')?>" id='CONTACT_NO' class="form-control" maxlength="15" tabindex="7"/>
                    <span class='help-inline'><?php echo form_error('CONTACT_NO'); ?></span>
                </div>
				
				<!-- hidden value pass -->
				<input type='hidden' name='EMP_FAMILY_ID_TARGET' value=""  id='EMP_FAMILY_ID_TARGET' />							
				<a name="add_employee_family_info" href="javascript:void(0)" onclick="addFamilyInfo(<?php echo  $employeeId = (int)$this->uri->segment(5);?>)" class="btn btn-primary btn-sm mlm">ADD</a>
			</div>
			<!-- Right side end -->
						
		</div>  <!-- panel body end -->
		</div>	<!-- for panel end -->
		
        <div class="col-md-12"> 
				<div class="col-md-12"> 
					<div class="col-md-10 box-footer pager">
						<?php echo anchor(SITE_AREA .'', lang('bf_action_cancel'), 'class="btn btn-default"'); ?>
						
						<?php echo lang('bf_or'); ?>
						
						<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang('bf_action_save').' '.lang('bf_action_next'); ?>"/>
					</div>
				</div>
        </div>
      </fieldset>

    <?php echo form_close(); ?>
	

</div>