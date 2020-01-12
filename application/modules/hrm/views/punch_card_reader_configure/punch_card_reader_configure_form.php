<?php
if(isset($SendData)) extract($SendData);

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

if (isset($reader_model_details)){
    $reader_model_detail = (array) $reader_model_details;
}

?>
	<div class="row box box-primary">
		<?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
			<fieldset class="box-body">
					<div class="row  ">
						<div class="container ">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="col-md-12 form-group">
                                        <label for="reader_model"><?php  echo lang('reader_model_group'); ?></label>
                                        <input type='text' name='reader_model' class="form-control" id='reader_model' value="<?php echo set_value('reader_model', isset($reader_model_detail['READER_MODEL_NAME'])? $reader_model_detail['READER_MODEL_NAME'] : ''); ?>"   placeholder="<?php echo lang('reader_model_group')?>" required="" tabindex="1"/>
                                        <span class='help-inline'><?php echo form_error('reader_model'); ?></span>
                                    </div>
                                    <div class="col-sm-6  col-md-6 col-lg-6">
                                        <div class="form-group ">
											<label for="database_type" class=""><?php  echo lang('database_type'); ?></label>
											<select class="form-control" name="database_type" id="database_type" tabindex="2" >
												<option value=""> <?php echo lang('bf_msg_selete_one');?></option>
												<?php
													 foreach($database_type as $key => $val)
													{
														echo "<option value='".$key."'";

														if(isset($reader_model_detail['DATABASE_TYPE']))
														{
															if($reader_model_detail['DATABASE_TYPE']== $key){ echo "selected ";}
														}

														echo ">".$val."</option>";
													}
												?>

											</select>
											<span class='help-inline'><?php echo form_error('database_type'); ?></span>

										</div>
										<div class="form-group ">
											<label for="database_name" class=" "><?php  echo lang('database_name'); ?></label>

											<input type='text' name='database_name' class="form-control  " id='database_name' value="<?php echo set_value('reader_model', isset($reader_model_detail['DATABASE_NAME'])? $reader_model_detail['DATABASE_NAME'] : ''); ?>"   placeholder="<?php echo lang('database_name')?>" required="" tabindex="3"/>
											<span class='help-inline'><?php echo form_error('database_name'); ?></span>
										</div>
										<div class="form-group ">
											<label for="server_name" class=""><?php  echo lang('server_name'); ?></label>

											<input type='text' name='server_name' class="form-control  " id='server_name' value="<?php echo set_value('reader_model', isset($reader_model_detail['SERVER_NAME'])?  $reader_model_detail['SERVER_NAME'] : ''); ?>"    placeholder="<?php echo lang('server_name')?>" required="" tabindex="13"/>
											<span class='help-inline'><?php echo form_error('server_name'); ?></span>
										</div>

									</div>
									<div class="col-sm-6  col-md-6 col-lg-6  ">
										<div class="form-group ">
											<label for="table_name" class=""><?php  echo lang('table_name'); ?></label>

											<input type='text' name='table_name' class="form-control  " id='table_name' value="<?php echo set_value('reader_model', isset($reader_model_detail['TABLE_NAME'])?  $reader_model_detail['TABLE_NAME'] : ''); ?>"    placeholder="<?php echo lang('table_name')?>" required="" tabindex="7"/>
											<span class='help-inline'><?php echo form_error('table_name'); ?></span>
										</div>
										<div class="form-group ">
											<label for="user_name" class=""><?php  echo lang('user_name'); ?></label>

											<input type='text' name='user_name' class="form-control  " id='user_name' value="<?php echo set_value('reader_model', isset($reader_model_detail['USER_NAME'])?  $reader_model_detail['USER_NAME'] : ''); ?>"    placeholder="<?php echo lang('user_name')?>" required="" tabindex="6"/>
											<span class='help-inline'><?php echo form_error('user_name'); ?></span>
										</div>
										<div class="form-group ">
											<label for="password" class=""><?php  echo lang('password'); ?></label>

											<input type='password' name='password' class="form-control  " id='password' value="<?php echo set_value('reader_model', isset($reader_model_detail['PASSWORD'])?  $reader_model_detail['PASSWORD'] : ''); ?>"    placeholder="<?php echo lang('password')?>" required="" tabindex="10"/>
											<span class='help-inline'><?php echo form_error('password'); ?></span>
										</div>
									</div>
								</div>

								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="col-sm-6  col-md-6 col-lg-6 ">
										<div class="form-group">
											<label for="rf_code_field" class=""><?php  echo lang('rf_code_field'); ?></label>

											<input type='text' name='rf_code_field' class="form-control " id='rf_code_field' value="<?php echo set_value('reader_model', isset($reader_model_detail['RF_CODE_FIELD'])?  $reader_model_detail['RF_CODE_FIELD'] : ''); ?>"    placeholder="<?php echo lang('rf_code_field')?>" required="" tabindex="11"/>
											<span class='help-inline'><?php echo form_error('rf_code_field'); ?></span>
										</div>
										<div class="form-group">
											<label for="date_field" class=""><?php  echo lang('date_field'); ?></label>

											<input type='text' name='date_field' class="form-control  datepickerCommon" id='date_field' value="<?php echo set_value('reader_model', isset($reader_model_detail['DATE_FIELD'])?  $reader_model_detail['DATE_FIELD'] : ''); ?>"      placeholder="<?php echo lang('date_field')?>" required="" tabindex="14"/>
											<span class='help-inline'><?php echo form_error('date_field'); ?></span>
										</div>
										<div class="form-group ">
											<label for="time_field" class=""><?php  echo lang('time_field'); ?></label>

											<input type='text' name='time_field' class="form-control " id='time_field'  value="<?php echo set_value('reader_model', isset($reader_model_detail['TIME_FIELD'])?  $reader_model_detail['TIME_FIELD'] : ''); ?>"   placeholder="<?php echo lang('time_field')?>" required="" tabindex="4"/>
											<span class='help-inline'><?php echo form_error('time_field'); ?></span>
										</div>
										<div class="form-group ">
											<label for="reader_no_field" class=""><?php  echo lang('reader_no_field'); ?></label>

											<input type='text' name='reader_no_field' class="form-control " id='reader_no_field'  value="<?php echo set_value('reader_model', isset($reader_model_detail['READER_NO_FIELD'])?  $reader_model_detail['READER_NO_FIELD'] : ''); ?>"  placeholder="<?php echo lang('reader_no_field')?>" required="" tabindex="8"/>
											<span class='help-inline'><?php echo form_error('reader_no_field'); ?></span>
										</div>


									</div>
									<div class="col-sm-6  col-md-6 col-lg-6 ">

										<div class="form-group">
											<label for="network_no_field" class=""><?php  echo lang('network_no_field'); ?></label>

											<input type='text' name='network_no_field' class="form-control " id='network_no_field' value="<?php echo set_value('reader_model', isset($reader_model_detail['NETWORK_NO_FIELD'])?  $reader_model_detail['NETWORK_NO_FIELD'] : ''); ?>"   placeholder="<?php echo lang('network_no_field')?>" required="" tabindex="12"/>
											<span class='help-inline'><?php echo form_error('network_no_field'); ?></span>
										</div>
										<div class="form-group">
											<label for="status_field" class=""><?php  echo lang('status_field'); ?></label>

											<input type='text' name='status_field' class="form-control " id='status_field' value="<?php echo set_value('reader_model', isset($reader_model_detail['STATUS_FIELD'])?  $reader_model_detail['STATUS_FIELD'] : ''); ?>"     placeholder="<?php echo lang('status_field')?>" required="" tabindex="15"/>
											<span class='help-inline'><?php echo form_error('status_field'); ?></span>
										</div>
										<div class="form-group">
											<label for="id_field_name" class=""><?php  echo lang('id_field_name'); ?></label>

											<input type='text' name='id_field_name' class="form-control " id='id_field_name'  value="<?php echo set_value('reader_model', isset($reader_model_detail['ID_FIELD_NAME'])?  $reader_model_detail['ID_FIELD_NAME'] : ''); ?>"   placeholder="<?php echo lang('id_field_name')?>" required="" tabindex="5"/>
											<span class='help-inline'><?php echo form_error('id_field_name'); ?></span>
										</div>
										<div class="form-group">
											<label for="date_format" class=""><?php  echo lang('date_format'); ?></label>

											<select class="form-control" name="date_format" id="date_format" required=""  tabindex="9">
												<option value=""><?php echo lang('bf_msg_selete_one');?></option>
												<?php
													 foreach($date_format as $key => $val)
													{
														echo "<option value='".$key."'";

														if(isset($reader_model_detail['DATE_FORMAT']))
														{
															if($reader_model_detail['DATE_FORMAT']== $key){ echo "selected ";}
														}

														echo ">".$val."</option>";
													}
												?>


											</select>
											<span class='help-inline'><?php echo form_error('date_format'); ?></span>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

				<div class="col-md-12">
					<div class="col-md-12">
						<div class="col-md-12 box-footer pager">
							<?php echo anchor(SITE_AREA .'/punch_card_reader_configure/hrm/create', lang("bf_action_cancel"), 'class="btn btn-warning"'); ?>
							&nbsp;
							<input type="submit" name="save" class="btn btn-primary btn-md" value="<?php echo lang('bf_action_save'); ?>"/>

						</div>
					</div>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
    