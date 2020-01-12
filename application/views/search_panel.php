
<style>
    .mControl { max-width: 150px; min-width: 100px;}
    .fieldset{ margin: 0px; padding: 0px;}
    .panel-heading { padding: 5px 10px !important}
    .form-group{
		width:initial;
	}
</style>

<?php //echo form_open($this->uri->uri_string(), ' name="search_box"'); ?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="headingOne">
            <div class="panel-title">
                <span class="glyphicon glyphicon-plus"></span>
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Search Panel
                </a>
            </div>
        </div>
        <fieldset class="fieldset">
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body form-inline">
                	<?php if ($search_box['ticket_no_flag']): ?>
                        <div class="form-group">
                            Ticket Id <br/>
                            <input type="text" name="ticket_no" class="form-control" id="ticket_no_sp" value="<?php echo $search_box['ticket_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                    if ($search_box['patient_id_flag']): ?>
                        <div class="form-group">
                            Patient Id <br/>
                            <input type="text" name="patient_id" class="form-control" id="patient_id_sp" value="<?php echo $search_box['patient_id'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                     if ($search_box['serial_id_flag']): ?>
                        <div class="form-group">
                            Serial Id <br/>
                            <input type="text" name="serial_id" class="form-control" id="serial_id_sp" value="<?php echo $search_box['serial_id'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['contact_no_flag']): ?>
                        <div class="form-group">
                            Contact No<br/>
                            <input type="text" name="contact_no" class="form-control" id="contact_id_sp" value="<?php echo $search_box['contact_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                     if ($search_box['designation_name_flag']): ?>
                        <div class="form-group">
                           Designation Name<br/>
                            <input type="text" name="designation_name" class="form-control" id="designation_name_sp" value="<?php echo $search_box['contact_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    
                    if ($search_box['test_name_flag']): ?>
                        <div class="form-group">
                           Test Name<br/>
                            <input type="text" name="test_name" class="form-control" id="contact_id_sp" value="<?php echo $search_box['test_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                    if ($search_box['pharmacy_customer_name_flag']): ?>
                        <div class="form-group">
                           Customer Name<br/>
                            <input type="text" name="customer_name" class="form-control" id="customer_name_sp" value="<?php echo $search_box['customer_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                   if ($search_box['lab_name_flag']): ?>
                        <div class="form-group">
                           Lab Name<br/>
                            <input type="text" name="lab_name" class="form-control" id="contact_id_sp" value="<?php echo $search_box['lab_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                if ($search_box['patient_name_flag']): ?>
                        <div class="form-group">
                            Patient Name <br/>
                            <input type="text" name="patient_name" class="form-control" id="patient_name_id_sp" value="<?php echo $search_box['patient_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                      if ($search_box['floor_no_flag']): ?>
                        <div class="form-group">
                           Floor No<br/>
                            <input type="text" name="floor_no" class="form-control" id="floor_no_sp" value="<?php echo $search_box['floor_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['serial_no_flag']): ?>

                        <div class="form-group">
                            Serial No. <br/>
                            <input type="text" name="serial_no" class="form-control" id="serial_no_sp" value="<?php echo $search_box['serial_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
               if ($search_box['employee_name_flag']): ?>

                        <div class="form-group">
                            Employee Name <br/>
                            <input type="text" name="employee_name" class="form-control" id="employee_name_flag" value="<?php echo $search_box['employee_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                    if ($search_box['price_flag']): ?>

                        <div class="form-group">
                         Price <br/>
                            <input type="text" name="price" class="form-control" id="price_sp" value="<?php echo $search_box['price'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                    if ($search_box['commission_agent_type_flag']): ?>

                        <div class="form-group">
                         Agent Type <br/>
                            <select id="commission_agent_type_flag" name="commission_agent_type" class="form-control">
                                <option value="">Select</option>
                                <option value="1">External Doctor</option>
                                <option value="2">Reference</option>
                                <option value="3">Internal Doctor</option>
                            </select>
                        </div>
                    <?php
                    endif;
                    if ($search_box['commission_agent_type_flag']): ?>

                        <div class="form-group">
                         Agent <br/>
                            <select class="form-control" name="commission_agent_id" id="commission_agent" required=""><option value="">Select Agent</option></select>
                        </div>
                    <?php
                    endif;

                    if ($search_box['token_id_flag']): ?>
                        <div class="form-group">
                            Token No <br/>
                            <input type="text" name="token_id" class="form-control" id="token_id_sp" value="<?php echo $search_box['token_id'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['company_name_flag']) :
                        ?>
                        <div class="form-group">
                            Company Name<br/>
                            <input type="text" name="company_name" class="form-control" id="company_name_sp" value="<?php echo $search_box['company_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['sub_customer_name_flag']) :
                        ?>
                        <div class="form-group">
                            Sub Customer Name<br/>
                            <input type="text" name="sub_customer_name" class="form-control" id="sub_customer_name_sp" value="<?php echo $search_box['company_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                   if ($search_box['supplier_name_flag']): ?>
                        <div class="form-group">
                            Supplier Name <br/>
                            <input type="text" name="supplier_name" class="form-control" id="supplier_name_id_sp" value="<?php echo $search_box['supplier_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                if ($search_box['pharmacy_supplier_list_flag']): ?>
                        <div class="form-group">
                            Supplier Name <br/>
                            <input type="text" name="pharmacy_supplier_name" class="form-control" id="pharmacy_supplier_name_id_sp" value="<?php echo $search_box['supplier_name'] ?>" placeholder=""/>
                        </div>
                    <?php

                    endif;

                     if ($search_box['thana_name_flag']): ?>
                        <div class="form-group">
                            Thana Name <br/>
                            <input type="text" name="thana_name" class="form-control" id="thana_name_id_sp" value="<?php echo $search_box['thana_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['doctor_id_flag']) :
                        ?>
                        <div class="form-group">
                            Doctor Id<br/>
                            <input type="text" name="doctor_id" class="form-control" id="doctor_id_sp" value="<?php echo $search_box['doctor_id'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['mr_no_flag']) :
                        ?>
                        <div class="form-group">
                           MR No<br/>
                            <input type="text" name="mr_num" class="form-control" id="mr_num_sp" value="<?php echo $search_box['doctor_id'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['admission_id_flag']) :
                        ?>
                        <div class="form-group">
                            Admission Id<br/>
                            <input type="text" name="admission_id" class="form-control" id="doctor_id_sp" value="<?php echo $search_box['admission_id'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['doctor_code_flag']) :
                        ?>
                        <div class="form-group">
                            Doctor Code<br/>
                            <input type="text" name="doctor_code" class="form-control" id="doctor_code_sp" value="<?php echo $search_box['doctor_code'] ?>" placeholder=""/>
                        </div>
                    <?php endif; ?>

                    <?php if ($search_box['employee_code_flag']) :
                        ?>
                        <div class="form-group">
                            Employee Code<br/>
                            <input type="text" name="emp_code" class="form-control" id="emp_code_sp" value="<?php echo $search_box['emp_code'] ?>" placeholder=""/>
                        </div>
                    <?php endif; ?>

                  <?php  if ($search_box['bill_no_flag']) :
                        ?>
                        <div class="form-group">
                            Bill No<br/>
                            <input type="text" name="bill_no" class="form-control" id="bill_no_sp" value="<?php echo $search_box['bill_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                   
                   
                     if ($search_box['product_code_flag']) :
                        ?>
                        <div class="form-group">
                            Product Code<br/>
                            <input type="text" name="product_code" class="form-control" id="product_code_sp" value="<?php echo $search_box['product_code'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                     if ($search_box['product_name_flag']) :
                        ?>
                        <div class="form-group">
                            Product Name<br/>
                            <input type="text" name="product_name" class="form-control" id="product_name_sp" value="<?php echo $search_box['product_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                     if ($search_box['pharmacy_name_flag']) :
                        ?>
                        <div class="form-group">
                            Pharmacy Name1<br/>
                            <input type="text" name="pharmacy_nam" class="form-control" id="pharmacy_name_sp" value="<?php echo $search_box['Pharmacy_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['store_product_id_flag']) :
                        ?>
                        <div class="form-group">
                            Product<br/>
                            <select id="store_product_id_select" name="store_product_id" class="form-control">
                                <option></option>
                            </select>
                        </div>
                    <?php
                    endif;
                     if ($search_box['requisition_no_flag']) :
                        ?>
                        <div class="form-group">
                            Requisition No<br/>
                            <input type="text" name="requisition_no" class="form-control" id="department_name_sp" value="<?php echo $search_box['requisition_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                  if ($search_box['department_name_flag']) :
                        ?>
                        <div class="form-group">
                            Department Name<br/>
                            <input type="text" name="department_name" class="form-control" id="department_name_sp" value="<?php echo $search_box['requisition_no'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                    
                    
                      if ($search_box['location_flag']) :
                        ?>
                        <div class="form-group">
                            Location Name<br/>
                            <input type="text" name="location_name" class="form-control" id="company_name_sp" value="<?php echo $search_box['company_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                     if ($search_box['code_flag']) :
                        ?>
                        <div class="form-group">
                            Company Code<br/>
                            <input type="text" name="company_code" class="form-control" id="company_name_sp" value="<?php echo $search_box['company_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;

                     if ($search_box['category_name_flag']) :
                        ?>
                        <div class="form-group">
                            Category Name<br/>
                            <input type="text" name="category_name" class="form-control" id="category_name_sp" value="<?php echo $search_box['category_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    
                    if ($search_box['sex_list_flag']): ?>
                        <div class="form-group">
                            Sex<br/>
                            <select name="sex" class="form-control" id="sex_sp">
                            	<option value="">...Sex...</option>
                            	<?php foreach ($search_box['sex_list'] as $key => $row) : ?>
                            	<option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                            	<?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
               
                  if ($search_box['store_source_name_list_flag']): ?>
                        <div class="form-group">
                            Store Stock<br/>
                            <select name="store_source_name" class="form-control" id="sex_sp">
                                <option value="">...select...</option>
                                <?php foreach ($search_box['store_source_name_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;

                     if ($search_box['main_pharmacy_stock_list_flag']): ?>
                        <div class="form-group">
                            Main Pharmacy Stock<br/>
                            <select name="main_pharmacy_stock_list" class="form-control" id="main_pharmacy_stock_list_sp">
                                <option value="">...Select...</option>
                                <?php foreach ($search_box['main_pharmacy_stock_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <?php endif;
                        if ($search_box['sub_pharmacy_stock_list_flag']): ?>
                        <div class="form-group">
                            Sub Pharmacy Stock<br/>
                            <select name="sub_pharmacy_stock_list" class="form-control" id="main_pharmacy_stock_list_sp">
                                <option value="">...Select...</option>
                                <?php foreach ($search_box['sub_pharmacy_stock_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
                       if ($search_box['pharmacy_stock_type_flag']): ?>
                        <div class="form-group">
                             Stock Type<br/>
                            <select name="pharmacy_stock_type" class="form-control" id="pharmacy_stock_type_sp">
                    store_source_name_list_flag            <option value="">...Select...</option>
                                <?php foreach ($search_box['pharmacy_stock_type'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
                    if ($search_box['cash_users_flag']): ?>
                        <div class="form-group">
                            Cash User<br/>
                            <select name="user_id" class="form-control" id="cash_users_flag">
                                <option value="">Select User</option>
                                <?php foreach ($search_box['cash_users_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;

                     if ($search_box['report_type_flag']): ?>
                        <div class="form-group">
                            Report Type<br/>
                            <select name="report_type" class="form-control" id="sex_sp">
                                <option value="">...Report Type...</option>
                                <?php foreach ($search_box['report_type'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
                      if ($search_box['bed_type_list_flag']): ?>
                        <div class="form-group">
                            Bed Type<br/>
                            <select name="bed_type" class="form-control" id="bed_type_list_sp">
                                <option value="">bed type</option>
                                <?php foreach ($search_box['bed_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; 
                
                    if ($search_box['department_id_flag']): ?>

                        <div class="form-group">
                            &nbsp; Department <br/>
                            &nbsp;
                            <select class="form-control" name="department_id" id="department_id_select">
                                <option value=""></option>
                            </select>
                        </div>

                    <?php endif; ?>

                    <?php  if ($search_box['customer_type_list_flag']): ?>
                        <div class="form-group">
                            Customer Type<br/>
                            <select name="customer_type" class="form-control" id="customer_type_list_sp">
                                <option value="">customer type</option>
                                <?php foreach ($search_box['customer_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;

                 


                     if ($search_box['room_type_list_flag']): ?>
                        <div class="form-group">
                            Room Type<br/>
                            <select name="room_type" class="form-control" id="room_type_list_sp">
                                <option value="">Room type</option>
                                <?php foreach ($search_box['room_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
                    if ($search_box['admitted_bed_status_flag']): ?>
                        <div class="form-group">
                            Patient Bed Status<br/>
                            <select name="admitted_bed_status" class="form-control" id="room_type_list_sp">
                                <option value="">bed status</option>
                                <?php foreach ($search_box['admitted_bed_status'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
                       if ($search_box['bed_name_flag']): ?>
                        <div class="form-group">
                           Room/Bed Name<br/>
                            <input type="text" name="bed_name" class="form-control" id="bed_name_sp" value="<?php echo $search_box['lab_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                     if ($search_box['bed_short_name_flag']): ?>
                        <div class="form-group">
                           Bed Short Name/No<br/>
                            <input type="text" name="bed_short_name" class="form-control" id="bed_short_name_sp" value="<?php echo $search_box['bed_short_name'] ?>" placeholder=""/>
                        </div>
                    <?php
                    endif;
                    if ($search_box['appointment_type_flag']): ?>
                        <div class="form-group">
                            Appointment Type<br/>
                            <select name="appointment_type" class="form-control" id="appointment_type_sp">
                                <option value="">...Appointment Type...</option>
                                <?php foreach ($search_box['appointment_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;

                if ($search_box['pharmacy_customer_type_flag']): ?>
                        <div class="form-group">
                            Customer Type<br/>
                            <select name="pharmacy_customer_type" class="form-control" id="pharmacy_customer_type_sp">
                            	<option value="">...Customer Type...</option>
                            	<?php foreach ($search_box['pharmacy_customer_type'] as $key => $row) : ?>
                            	<option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                            	<?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;
                          
        if ($search_box['client_name_flag']) :
                        ?>
                        <div class="form-group">
                            Client Name<br/>
                <input type="text" name="clien_name" class="form-control" id="client_name_sp" value="<?php echo $search_box['client_name'] ?>" placeholder="" disabled/>
                        </div>
                    <?php
                    endif;
 if ($search_box['pharmacy_list_flag']): ?>
                        <div class="form-group">
                            Pharmacy Name<br/>
                            <?php
                            echo dropdown_helper('pharmacy_name', $search_box['pharmacy_name'], $search_box['pharmacy_name'], 'id="pharmacy_name_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                     if ($search_box['department_test_list_flag']): ?>
                        <div class="form-group">
                            Department<br/>
                            <?php
                            echo dropdown_helper('test_department_list', $search_box['department_test_list'], $search_box['department_test_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                   
                          if ($search_box['designation_list_flag']): ?>
                        <div class="form-group">
                            Designation List<br/>
                            <?php
                            echo dropdown_helper('designation_list', $search_box['designation_list'], $search_box['designation_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                          if ($search_box['department_name_list_flag']): ?>
                        <div class="form-group">
                            Department<br/>
                            <?php
                            echo dropdown_helper('department_name_list', $search_box['department_name_list'], $search_box['department_name_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                          if ($search_box['store_department_name_list_flag']): ?>
                        <div class="form-group">
                            Department<br/>
                            <?php
                            echo dropdown_helper('store_department_name_list', $search_box['store_department_name_list'], $search_box['store_department_name_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                        if ($search_box['empType_list_flag']): ?>
                        <div class="form-group">
                            Employee Type<br/>
                            <?php
                            echo dropdown_helper('empType_list', $search_box['empType_list'], $search_box['empType_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                          if ($search_box['diagnosis_test_list_flag']): ?>
                        <div class="form-group">
                            Test Name<br/>
                            <?php
                            echo dropdown_helper('diagnosis_test_list', $search_box['diagnosis_test_list'], $search_box['diagnosis_test_list'], 'id="diagnosis_test_list_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                    if ($search_box['sample_room_list_flag']): ?>
                        <div class="form-group">
                            Sample Room/Lab Room<br/>
                            <?php
                            echo dropdown_helper('sample_room_list', $search_box['sample_room_list'], $search_box['sample_room_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                           if ($search_box['test_group_list_flag']): ?>
                        <div class="form-group">
                           Test Group<br/>
                            <?php
                            echo dropdown_helper('test_group_list', $search_box['test_group_list'], $search_box['test_group_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                         <?php endif;
                    if ($search_box['store_company_list_flag']): ?>
                        <div class="form-group">
                            Company Name<br/>
                            <?php
                            echo dropdown_helper('store_company_id', $search_box['store_company_list'], $search_box['store_company_list'], 'id="store_company_id_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif;
                     
                    if ($search_box['store_product_list_flag']): ?>
                        <div class="form-group">
                            Category Name<br/>
                            <?php
                            echo dropdown_helper('store_category_id', $search_box['store_category_list'], $search_box['store_category_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                        <div class="form-group">
                           Sub Category Name<br/>
                            <?php
                            echo dropdown_helper('store_sub_category_id', $search_box['store_sub_category_list'], $search_box['store_sub_category_list'], 'id="store_sub_category_id_sp"
                                class="form-control"');
                            ?>
                        </div>
                        <div class="form-group">
                            Product Name<br/>
                            <?php
                            echo dropdown_helper('store_product_id', $search_box['store_product_list'], $search_box['store_product_list'], 'id="store_product_id_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif;

                  

                    if ($search_box['pharmacy_product_list_flag']): ?>
                        <div class="form-group">
                            Category Name<br/>
                            <?php
                            echo dropdown_helper('pharmacy_category_name', $search_box['pharmacy_category_list'], $search_box['pharmacy_category_list'], 'id="pharmacy_category_id_sp" class="form-control"');
                            ?>
                        </div>
                        <!-- <div class="form-group">
                           Sub Category Name<br/>
                            <?php
                           // echo dropdown_helper('pharmacy_category_id', $search_box['pharmacy_sub_category_list'], $search_box['pharmacy_sub_category_list'], 'id="pharmacy_sub_category_id_sp"class="form-control"');
                            ?>
                        </div> -->
                         <div class="form-group">
                            Product Name<br/>
                            <?php
                            echo dropdown_helper('pharmacy_product_id', $search_box['pharmacy_product_list'], $search_box['pharmacy_product_list'], 'id="pharmacy_product_id_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($search_box['pharmacy_product_id_select_flag']): ?>
                        <div class="form-group">
                            Product Name<br/>
                            <select name="pharmacy_product_id_select" id="pharmacy_product_id_select" class="form-control" style="width:150px;">
                                <option></option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($search_box['sub_pharmacy_id_select_flag']): ?>
                        <div class="form-group">
                            Sub Pharmacy<br/>
                            <select name="sub_pharmacy_id_select" id="sub_pharmacy_id_select" class="form-control" style="width:150px;">
                                <option></option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($search_box['store_supplier_list_flag']): ?>
                        <div class="form-group">
                            Supplier Name<br/>
                            <?php
                            echo dropdown_helper('store_supplier_id', $search_box['store_supplier_list'], $search_box['store_supplier_list'], 'id="store_supplier_id_sp" class="form-control"');
                            ?>
                        </div>
					<?php endif;
                    if ($search_box['store_name_list_flag']): ?>
                        <div class="form-group">
                            Store Name<br/>
                            <?php
                            echo dropdown_helper('store_name_id', $search_box['store_name_list'], $search_box['store_name_list'], 'id="store_name_id_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif;
                            if ($search_box['discount_service_list_flag']): ?>
                        <div class="form-group">
                            Service Name<br/>
                            <?php
                            echo dropdown_helper('discount_service_id', $search_box['discount_service_list'], $search_box['discount_service_list'], 'id="discount_service_id_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif;
                    if ($search_box['store_employee_list_flag']): ?>
                        <div class="form-group">
                            Employee Name<br/>
                            <?php
                            echo dropdown_helper('store_employee_id', $search_box['store_employee_list'], $search_box['store_employee_list'], 'id="store_employee_id_sp" class="form-control"');
                            ?>
                        </div>

                    <?php endif; ?>


                   
           
                     <?php if ($search_box['patient_type_list_flag']): ?>
                        <div class="form-group">
                            Patient Type<br/>
                            <?php
                            echo dropdown_helper('patient_type_id', $search_box['patient_type_list'], $search_box['patient_type_list'], 'id="patient_type_id_sp" class="form-control"');
                            ?>
                        </div>
                         <div class="form-group">
                            Patient SubType<br/>
                            <?php
                            echo dropdown_helper('patient_subtype_id', $search_box['patient_subtype_list'], $search_box['patient_subtype_list'], 'id="patient_subtype_id_sp" class="form-control"');
                            ?>
                        </div>


                    <?php endif;
                    if ($search_box['pharmacy_company_list_flag']): ?>

                        <div class="form-group">
                            Company Name<br/>
                            <?php
                            echo dropdown_helper('pharmacy_company_id', $search_box['pharmacy_company_list'], $search_box['pharmacy_company_list'], 'id="pharmacy_company_id_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif;

                     if ($search_box['sstore_product_list_flag']): ?>
                        <div class="form-group">
                            Category Name<br/>
                            <?php
                            echo dropdown_helper('store_category_id', $search_box['store_category_list'], $search_box['store_category_list'], 'id="store_category_id_sp" class="form-control"');
                            ?>
                        </div>
                        <?php endif;
                  
                   
                    
                    
                    if ($search_box['pharmacy_shelf_id_select_flag']): ?>

                        <div class="form-group">
                            Shelf<br/>
                            <?php
                            echo dropdown_helper('pharmacy_shelf_id_select', $search_box['pharmacy_shelf_id_select_list'], $search_box['pharmacy_shelf_id_select'], 'id="pharmacy_shelf_id_select_sp" class="form-control"');
                            ?>
                        </div>
                    <?php endif;

                   if ($search_box['common_text_search_flag']): ?>
                        <div class="form-group">
                            &nbsp; <?php echo limit_string($search_box['common_text_search_label'], 10); ?> <br/>
                            &nbsp; <input type="text" name="common_text_search" class="form-control" id="common_text_search_sp" value="<?php echo $search_box['common_text_search'] ?>" placeholder=""/>
                        </div>
                    <?php endif; ?>

                    <?php  if ($search_box['amount_type_flag']): ?>
                        <div class="form-group">
                            Amount Type<br/>
                            <select name="amount_type" class="form-control" id="amount_type_sp">
                                <option value="">---Amount Type---</option>
                                <?php foreach ($search_box['amount_type'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;  ?>

                    <?php if ($search_box['admission_status_list_flag']): ?>
                        <div class="form-group">
                            Admission Status<br/>
                            <select name="admission_status_list" class="form-control" id="admission_status_list_sp">
                                <option value="">...Admission Status...</option>
                                <?php foreach ($search_box['admission_status_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($search_box['admission_discharge_reason_list_flag']): ?>
                        <div class="form-group">
                            Discharge Reason<br/>
                            <select name="admission_discharge_reason_list" class="form-control" id="admission_discharge_reason_list_sp">
                                <option value="">...Discharge Reason...</option>
                                <?php foreach ($search_box['admission_discharge_reason_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <?php if ($search_box['referred_doctor_list_flag']): ?>
                    <div class="form-group">
                            Referred Doctor Name<br/>
                        <select name="admission_referred_doctor" id="admission_referred_doctor" class="form-control chosenCommon">
                            <option value="">...Refered Doctor Name...</option>
                            <?php foreach ($search_box['referred_doctor_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <?php if ($search_box['doctor_type_list_flag']): ?>
                    <div class="form-group">
                            Doctor Type<br/>
                        <select name="doctor_type_list" id="doctor_type_list" class="form-control">
                            <option value="">...Doctor Type...</option>
                            <?php foreach ($search_box['doctor_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                <?php if ($search_box['payment_type_list_flag']): ?>
                    <div class="form-group">
                           Payment Type<br/>
                        <select name="payment_type_list" id="payment_type_list" class="form-control">
                            <option value="">...Payment Type...</option>
                            <?php foreach ($search_box['payment_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <?php if ($search_box['doctor_full_name_flag']) :?>
                      
                        <div class="form-group">
                            Doctor Full Name<br/>
                            <input type="text" name="doctor_full_name" class="form-control" id="doctor_full_name_sp" value="<?php echo $search_box['doctor_full_name']; ?>" placeholder=""/>
                        </div>
                   
                  <?php endif; ?>
                    <?php if ($search_box['doctor_list_flag']): ?>
                    <div class="form-group">
                            Doctor Name<br/>
                        <select name="doctor_list" id="doctor_list" class="form-control chosenCommon">
                            <option value="">...Doctor Name...</option>
                            <?php foreach ($search_box['doctor_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                   <?php if ($search_box['from_date_flag']): ?>
                        <div class="form-group">
                            From Date<br/>
                            <input type="text" name="from_date" class="form-control datepickerCommon" id="from_date_sp" value="<?php echo $search_box['from_date'] ?>" placeholder="From date">
                        </div>
                        <?php
                    endif;
                    if ($search_box['to_date_flag']):
                        ?>
                        <div class="form-group">
                            &nbsp; To date <br/>
                            &nbsp; <input type="text" name="to_date" class="form-control datepickerCommon" id="to_date_sp" value="<?php echo $search_box['to_date'] ?>" placeholder="To Date">
                        </div>
                        <?php
                    endif;
                    if ($search_box['by_date_flag']):
                        ?>
                        <div class="form-group">
                            &nbsp; Date <br/>
                            &nbsp; <input type="text" name="by_date" class="form-control datepickerCommon" id="by_date_sp" value="<?php echo $search_box['by_date'] ?>" placeholder="Date">
                        </div>
                        <?php
                     endif;?>



                

                    <?php if ($search_box['store_stock_main_sources_flag']): ?>

                        <div class="form-group">
                            &nbsp; Main Store Source <br/>
                            &nbsp;
                            <select class="form-control" name="store_stock_main_source">
                                <option value="">--Select--</option>
                                <option value="1" <?php echo $search_box['store_stock_main_source'] == '1' ? 'selected' : '' ?>>Return Products</option>
                                <option value="2" <?php echo $search_box['store_stock_main_source'] == '2' ? 'selected' : '' ?>>Return Product Replace</option>
                                <option value="3" <?php echo $search_box['store_stock_main_source'] == '3' ? 'selected' : '' ?>>Issue to Department</option>
                                <option value="4" <?php echo $search_box['store_stock_main_source'] == '4' ? 'selected' : '' ?>>Purchase Received</option>
                                <option value="5" <?php echo $search_box['store_stock_main_source'] == '5' ? 'selected' : '' ?>>Opening Balance</option>
                            </select>
                        </div>

                    <?php endif; ?>

                    <?php if ($search_box['store_stock_dept_sources_flag']): ?>

                        <div class="form-group">
                            &nbsp; Department Store Source <br/>
                            &nbsp;
                            <select class="form-control" name="store_stock_dept_source">
                                <option value="">--Select--</option>
                                <option value="1" <?php echo $search_box['store_stock_dept_source'] == '1' ? 'selected' : '' ?>>Employee Issue</option>
                                <option value="2" <?php echo $search_box['store_stock_dept_source'] == '2' ? 'selected' : '' ?>>Requisition Received</option>
                                <option value="3" <?php echo $search_box['store_stock_dept_source'] == '3' ? 'selected' : '' ?>>Opening Balance</option>
                            </select>
                        </div>

                    <?php endif; ?>

                    <?php if ($search_box['store_issue_purchase_select_flag']): ?>

                        <div class="form-group">
                            &nbsp; Issue/Receive <br/>
                            &nbsp;
                            <select class="form-control" name="store_issue_purchase_select">
                                <option value="">Both</option>
                                <option value="1" <?php echo $search_box['store_issue_purchase_select'] == '1' ? 'selected' : '' ?>>Issue Only</option>
                                <option value="2" <?php echo $search_box['store_issue_purchase_select'] == '2' ? 'selected' : '' ?>>Receive Only</option>
                            </select>
                        </div>

                    <?php endif; 

                    if ($search_box['due_paid_flag']): ?>
                        <div class="form-group">
                            Is Due?<br/>
                            <select name="due_paid" class="form-control" id="due_paid_sp">
                                <option value="">...Select One...</option>
                                <?php foreach ($search_box['due_paid'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($search_box['pharmacy_stock_sources_flag']): ?>

                        <div class="form-group">
                            &nbsp; Pharmacy Main Source <br/>
                            &nbsp;
                            <select class="form-control" name="pharmacy_stock_source">
                                <option value="">All</option>
                                <option value="1" <?php echo $search_box['pharmacy_stock_source'] == '1' ? 'selected' : '' ?>>Sale</option>
                                <option value="2" <?php echo $search_box['pharmacy_stock_source'] == '2' ? 'selected' : '' ?>>Sub Pharmacy Issue</option>
                                <option value="3" <?php echo $search_box['pharmacy_stock_source'] == '3' ? 'selected' : '' ?>>Return</option>
                            </select>
                        </div>

                    <?php endif; ?>

                    <?php if ($search_box['sub_pharmacy_stock_sources_flag']): ?>

                        <div class="form-group">
                            &nbsp; Sub Pharmacy Source <br/>
                            &nbsp;
                            <select class="form-control" name="sub_pharmacy_stock_source">
                                <option value="">All</option>
                                <option value="1" <?php echo $search_box['sub_pharmacy_stock_source'] == '1' ? 'selected' : '' ?>>Issue</option>
                                <option value="2" <?php echo $search_box['sub_pharmacy_stock_source'] == '2' ? 'selected' : '' ?>>Normal Sale</option>
                                <option value="3" <?php echo $search_box['sub_pharmacy_stock_source'] == '3' ? 'selected' : '' ?>>Sale Return</option>
                            </select>
                        </div>

                    <?php endif; ?>

                    <!--                hrm                   -->

                  <?php  if ($search_box['hrm_policy_type_with_list_flag']): ?>
                        <div class="form-group">
                            With Policy<br/>
                            <select name="policy_type_with" class="form-control" id="policy_type_with_flag">
                                <option value="">Select With Policy</option>
                                <?php foreach ($search_box['hrm_policy_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                   <?php if ($search_box['hrm_policy_type_without_list_flag']): ?>
                        <div class="form-group">
                            Without Policy<br/>
                            <select name="policy_type_without" class="form-control" id="policy_type_without_flag">
                                <option value="">Select Without Policy</option>
                                <?php foreach ($search_box['hrm_policy_type_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                   <?php if ($search_box['shift_list_flag']): ?>
                        <div class="form-group">
                            Shift Name<br/>
                            <select name="shift_id" class="form-control" id="shift_id_flag">
                                <option value="">Select Shift</option>
                                <?php foreach ($search_box['shift_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>


                    <?php if ($search_box['pharmacy_supplier_list_flag']): ?>
                        <div class="form-group">
                            Supplier Name<br/>
                            <select name="pharmacy_supplier_id" class="form-control" id="shift_id_flag">
                                <option value="">Select Supplier</option>
                                <?php foreach ($search_box['pharmacy_supplier_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>


                  <?php  if ($search_box['roster_list_flag']): ?>
                        <div class="form-group">
                            Roster Name<br/>
                            <select name="roster_id" class="form-control" id="roster_id_flag">
                                <option value="">Select Roster</option>
                                <?php foreach ($search_box['roster_list'] as $key => $row) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif;  ?>

                    <?php if ($search_box['per_page_flag']): ?>
                    <div class="form-group">
                        Rows/Page<br/>
                        <select name="per_page" id="per_page_sp" class="form-control" >
                            <?php 
                                if ($this->input->post('per_page')) {
                                    $per_page = $this->input->post('per_page');
                                } else {
                                    $per_page = isset($search_box['per_page']) ? $search_box['per_page'] : 25;
                                }
                            ?>
                            <option <?php echo $per_page == '25' ? 'selected':'' ?> value="25">25</option>
                            <option <?php echo $per_page == '50' ? 'selected':'' ?> value="50">50</option>
                            <option <?php echo $per_page == '100' ? 'selected':'' ?> value="100">100</option>
                            <option <?php echo $per_page == '250' ? 'selected':'' ?> value="250">250</option>
                            <option <?php echo $per_page == '500' ? 'selected':'' ?> value="500">500</option>
                        </select>
                    </div>
                <?php endif; ?>

                    <div class="form-group">
                        <br/>
                        <a href=""  id="search" class="btn btn-primary pull-right btn-xs">Search</a>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<?php //echo form_close() ?>
<script>

  $(function () {
        $("#store_category_id_sp").on("change", function () {
            getStoreSubCategoryList();
        });
        $("#store_category_id_sp").on("change", function () {
            getStoreProductListbyCategoryId();
        });
        $("#store_sub_category_id_sp").on("change", function () {
            getStoreProductList();
        });
    });
</script>
<script>
     $(function () {
        $("#pharmacy_category_id_sp").on("change", function () {
            getPharmacySubCategoryList();
        });

        $("#pharmacy_sub_category_id_sp").on("change", function () {
            getPharmacyProductList();
        });
    });

    $(function () {
        $("#patient_type_id_sp").on("change", function () {
            getPatientSubtypeList();
        });

        if ($('#department_id_select').length > 0) {
            $('#department_id_select').select2({
                ajax: {
                    url: siteURL + "department/library/search_department",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Department Name',
                minimumInputLength: 1,
                allowClear: true
            });
        }

        if ($('#store_product_id_select').length > 0) {
            $('#store_product_id_select').select2({
                ajax: {
                    url: siteURL + "product/store/search_product",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Product Name/Code',
                minimumInputLength: 1,
                allowClear: true
            });
        }

        if ($('#pharmacy_product_id_select').length > 0) {
            $('#pharmacy_product_id_select').select2({
                ajax: {
                    url: siteURL + "product/pharmacy/search_product",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.products
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Product Name/Code',
                minimumInputLength: 1,
                allowClear: true
            });
        }

        if ($('#sub_pharmacy_id_select').length > 0) {
            $('#sub_pharmacy_id_select').select2({
                ajax: {
                    url: siteURL + "pharmacy_setup/pharmacy/search_sub_pharmacy",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.pharmacys
                        };
                    },
                    cache: true
                },
                placeholder: 'Enter Pharmacy Name',
                minimumInputLength: 1,
                allowClear: true
            });
        }
    });




$(document).on('change','#commission_agent_type_flag',function(){
    var agent_type = $(this).val();
    if(agent_type==""){        
        $('#commission_agent').html('<option value="">Not Available</option>');
        return  false;
    }
    var target = siteURL+'commission_setup/doctor/getAgentByType/'+agent_type;
    $('#commission_agent').html('<option value="">Searching....</option>');
    $.get(target,function(data){
        $('#commission_agent').html(data);
    })
})


$(document).on('change','#pharmacy_customer_type_sp',function(){
    is_client=$(this).val(); 
    //consol.log(is_client); 
    if(is_client){
        $('#client_name_sp').attr('disabled',false);
    }else{
        $('#client_name_sp').attr('disabled',true);
    }
})

</script>