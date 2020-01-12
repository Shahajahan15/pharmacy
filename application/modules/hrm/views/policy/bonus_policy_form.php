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
.padding-left-div{margin-left:23px; }
</style>

<div class="row box box-primary">

	<?php echo form_open($this->uri->uri_string(), 'id="bonuspolicyFrm", role="form", class="form-horizontal", onsubmit=""'); ?>
	
    <fieldset class="box-body">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="panel panel-primary">
					<div class="panel-heading" align="center">
                        <h3 class="panel-title">Bonus Policy</h3>
                    </div>
					
		<div class="panel-body">


			<!-- master part Start-->
			

				<div class="col-sm-12 col-md-12 col-lg-12">

						<!-- ------- Bonus Policy / Name ----- -->
						<div class="col-sm-3 col-md-3 col-lg-3  padding-left-div">
							
							<div class="form-group <?php echo form_error('BONUS_NAME') ? 'error' : ''; ?>">
								<div id="existsBonusPolicyName" style="color:#F00; font-size:14px;"></div>
			                    <?php echo form_label(lang('BONUS_NAME'). lang('bf_form_label_required'), 'BONUS_NAME', array('class' => 'control-label') ); ?>
			                    <input type='text' name='BONUS_NAME' value="<?php //echo set_value('BONUS_NAME', isset($bonus_details['BONUS_NAME']) ? $bonus_details['BONUS_NAME'] : ''); ?>" placeholder="<?php echo lang('BONUS_NAME')?>" id='BONUS_NAME' class="form-control" maxlength="100" required onblur='bonusPolicyNameCheck()' tabindex="1"/>
			                  
			                </div>

						</div>

						<!-- ------- Bonus Policy / Description ----- -->
						<div class="col-sm-4 col-md-4 col-lg-4  padding-left-div">
							 
			                <div class="form-group <?php echo form_error('DESCRIPTION') ? 'error' : ''; ?>">
			                    <?php echo form_label(lang('DESCRIPTION'), 'DESCRIPTION', array('class' => 'control-label') ); ?>			
								<input type='text' name='DESCRIPTION' value="<?php echo set_value('DESCRIPTION', isset($bonus_details['DESCRIPTION']) ? $bonus_details['DESCRIPTION'] : ''); ?>" placeholder="<?php echo lang('DESCRIPTION')?>" id='DESCRIPTIONS' class="form-control" maxlength="100"  tabindex="2"/>							
							
			                </div>

						</div>

						<!-- ----------- Bonus Policy / Status ---------------- --> 
						<div class="col-sm-3 col-md-3 col-lg-3  padding-left-div">
							
							<div class="form-group <?php echo form_error('STATUS') ? 'error' : ''; ?>">
								<?php echo form_label(lang('STATUS'). lang('bf_form_label_required'), 'BPSTATUS', array('class' => 'control-label') ); ?>
								<select class="form-control" name="STATUS" id="BPSTATUS" required="" tabindex="3">								
									
									<?php 
										foreach($status as $key => $val)
										{
											echo "<option value='".$key."'";
											
											echo ">".$val."</option>";
										}
									?>
									
								 </select>
								
							</div>

						</div>



				</div>

		
			<!-- master part end-->

			<div class="col-sm-12 col-md-12 col-lg-12"><hr/></div>

			<!--details part start -->
			
			<div class="col-sm-12 col-md-12 col-lg-12 detailsContainer">
				<?php echo $this->load->view('policy/bonus_policy_details_form', $_REQUEST, TRUE); ?>
			</div> <!-- column end -->
			
			<!-- details part end -->

			
			<div class="col-md-12"> 
				<div class="col-md-12 box-footer pager">							
					<input type='hidden' name='BONUS_POLICY_MST_ID' value=""  id='BONUS_POLICY_MST_ID' />	
					<a name="reset" class="btn btn-default" onclick="resetBonus()">Reset</a>								
					<a name="" href="javascript:void(0)" onclick="addBonusPolicy()" class="btn btn-primary mlm">Save</a>					
				</div>
			</div>	
		

		</div>  <!-- panel body end -->
		</div>	<!-- for panel end -->
	</div>
      </fieldset>
    <?php echo form_close(); ?>
	
</div>