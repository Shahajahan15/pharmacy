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

<style>    
	.padding-left-div{margin-left:10px;}
</style>

<div class="row box box-primary">
    <?php echo form_open($this->uri->uri_string(), 'id="salary_rule_form", role="form", class="form-horizontal"'); ?>

        <fieldset class="box-body">      
			<div class="col-lg-12,col-md-12">
				<div class="row">
					<!------------Salary Rule Name----------->
					<div class="col-sm-4 col-md-4 col-lg-4 padding-left-div">    	
						<input type="hidden" name="master_id" id="master_id" value=""/>                                         						
						<div class="form-group  <?php echo form_error('rule_name') ? 'error' : ''; ?>">
							<div id="checkDuplicateSalaryHeadName" style="color:#F00; font-size:14px;"></div>
							<?php echo form_label(lang('hrm_salary_rule_name'). lang('bf_form_label_required'), 'rule_name'); ?>
							<div class='control'>
								<input type='text' class="form-control" name='rule_name' id='rule_name'  maxlength="45" value="" placeholder="<?php echo lang('hrm_salary_rule_name')?>" required="" tabindex="1" onblur=''/>
								<span class='help-inline'><?php echo form_error('rule_name'); ?></span>
							</div>
						</div>			
					</div>
					
					<!------------ Rule DESCRIPTION ----------->
					<div class="col-sm-4 col-md-4 col-lg-4 padding-left-div"> 				
						<div class="form-group <?php echo form_error('rule_description') ? 'error' : ''; ?>">
							<?php echo form_label(lang('hrm_salary_rule_description'), 'rule_description'); ?>
							<div class='control'>
								<input type='text' class="form-control" name='rule_description' id='rule_description'  maxlength="100" value="" placeholder="<?php echo lang('hrm_salary_rule_description')?>" tabindex="2"/>
								<span class='help-inline'><?php echo form_error('rule_description'); ?></span>
							</div>
						</div>
					</div>	
					
					<!------------Status----------->
					<div class="col-sm-2 col-md-2 col-lg-2 padding-left-div"> 				
						<div class="form-group <?php echo form_error('status') ? 'error' : ''; ?>">					
							<?php echo form_label(lang('bf_status'), 'status'); ?>
							 <div class='control'>
								<select name="status" id="status" class="form-control" required="" tabindex="3">
								
									<option value="1">Active</option>
									<option value="0">Inactive</option>							
								</select>
							</div>
							 <span class='help-inline'><?php echo form_error('status'); ?></span>
						</div>	
					</div>	
				
				</div>
			</div>	
			
			<div class="col-lg-12 col-md-12 col-sm-12">
				<hr/>
			</div>
			
			<div class="col-lg-12 col-md-12 col-sm-12" id="detailsContainer">
				<!--details part start -->
				<?php  echo $this->load->view('salary_rules/salary_rule_details_form', $_REQUEST, true); ?>
				<!-- details part end -->
			</div>		
			
			<div class="col-lg-12,col-md-12">
				<div class="row">
					<div class="col-md-10 box-footer pager">
						<?php echo anchor(SITE_AREA .'/test_type/library/create', lang("bf_action_cancel"), 'class="btn btn-default"'); ?>
						<input type='hidden' name='salary_head_id' value=""  id='salary_head_id' />  									
						&nbsp;
						<a name="save" href="javascript:void(0)" onclick="saveSalaryRule()" class="btn btn-primary mlm"><?php echo lang('bf_action_save'); ?></a>	
						
					</div>	
				</div>				
			</div>	
			
		</fieldset>
	<?php echo form_close(); ?>
</div>
        	
<?php echo $salary_rule_list; ?>
	











