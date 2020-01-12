                              
                             

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

	      
