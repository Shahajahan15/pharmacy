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


<div class="box box-primary">
<?php echo form_open($this->uri->uri_string(), 'role="form" class="nform-horizontal"'); ?>
 
    	<fieldset>
                	<legend>Employee Information</legend>
                			<div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4">
                                    <!--   age-->
                                    <div class="<?php echo form_error('emp_id') ? 'error' : ''; ?>">
                                        <div class='form-group'>               
                                                <select class="form-control employee-auto-complete" name="emp_id" id="emp_id" required="">
                                               
                                                </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                    <div class="<?php echo form_error('emp_name') ? 'error' : ''; ?>">
                                        <div class='form-group'>
                                            <label>Employee Name</label>
                                                <input class="form-control" id='emp_name'  name='emp_name' type='text' value="<?php echo isset($record)?$record->emp_name:''; ?>" readonly="" />
                                        </div>
                                    </div>
                                </div>
                            <!--   mobile / contact -->
                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <div class="<?php echo form_error('emp_mobile') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label>Employee Mobile</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"> +880</i>
                                        </div>
                                        <input type="text" id='emp_mobile'  class="form-control" value="<?php echo isset($record)?$record->customer_mobile:''; ?>" readonly="" />
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!--   Designation -->
                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <div class="<?php echo form_error('designation') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label>Designation</label>
                                    <div class="form-group">
                                        <input type="text" id='designation'  class="form-control" value="<?php echo isset($record)?$record->designation:''; ?>" readonly="" />
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!--   Department -->
                            <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                            <div class="<?php echo form_error('emp_department') ? 'error' : ''; ?>">
                                <div class='form-group'>
                                    <label>Department</label>
                                    <div class="form-group">
                                        <input type="text" id='emp_department'  class="form-control" value="<?php echo isset($record)?$record->emp_department:''; ?>" readonly="" />
                                    </div>
                                </div>
                            </div>
                            </div>
                </fieldset>
                <fieldset>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="control-label">Date</label>
                            <input type="text" name="date" class="form-control datepickerCommon" required="">
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Loan Amount</label>
                            <input type="text" name="loan_amount" required="" class=" advance_amount form-control decimal" required="">
                        </div>                        
                    </div>

                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="control-label">Repaid Amount(per month)</label>
                            <input type="text" name="per_month_repaid_amount" required="" class="per_month_repaid_amount form-control real-number">
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="control-label">Month</label>
                            <input type="text" name="payment_month" class="form-control real-number payment_month" required="">
                        </div>                        
                    </div>

                    <div class="col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label class="control-label">Repaid Start Month</label>
                            <input type="text" name="lp_starting_date" required="" class="form-control datepickerCommon">
                        </div>                        
                    </div>
                </fieldset>

        <fieldset>
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary btn-sm" value="<?php echo lang('bf_action_save'); ?>"  />
                &nbsp;
                <input type="reset" class="btn btn-warning btn-sm" value="Reset"/>
            </div> 
        </fieldset>

<?php echo form_close(); ?>

</div>  