<?php

    Assets::add_css( array(
        'bootstrap.css',
        'font-awesome.min.css',
        'ionicons.min.css',
        'datepicker/datepicker3.css',
        'daterangepicker/daterangepicker-bs3.css',
        'bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'chosen.min.css',
        'datatables/dataTables.bootstrap.css',
        'AdminLTE.css',
    ));

	if (isset($shortcut_data) && is_array($shortcut_data['shortcut_keys'])) {
		Assets::add_js($this->load->view('ui/shortcut_keys', $shortcut_data, true), 'inline');
	}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo isset($toolbar_title) ? $toolbar_title .' : ' : ''; ?> <?php e($this->settings_lib->item('site.title')) ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

	<?php echo Assets::css(null, true); ?>

    <script>window.jQuery || document.write('<script src="<?php echo js_path(); ?>jquery.min.js"><\/script>')</script>

</head>
<body class="skin-blue">
<!-- header logo: style can be found in header.less -->

<header class="header hidden-print">

<input type="hidden" name="site-url-hidden-field" id="site-url-hidden-field" value="<?php echo site_url().'/admin/' ?>">

<a href="<?php echo site_url()?>/admin/content" class="logo">
    <!-- Add the class icon to your logo image or logo icon to add the margining -->
    Hospital Software
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-right">
    <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        <?php /*
    <li class="dropdown messages-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope"></i>
            <span class="label label-success">4</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">You have 4 messages</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li><!-- start message -->
                        <a href="#">
                            <div class="pull-left">
                                <img src="img/avatar3.png" class="img-circle" alt="User Image"/>
                            </div>
                            <h4>
                                Support Team
                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                            </h4>
                            <p>Why not buy a new awesome theme?</p>
                        </a>
                    </li><!-- end message -->
                    <li>
                        <a href="#">
                            <div class="pull-left">
                                <img src="img/avatar2.png" class="img-circle" alt="user image"/>
                            </div>
                            <h4>
                                AdminLTE Design Team
                                <small><i class="fa fa-clock-o"></i> 2 hours</small>
                            </h4>
                            <p>Why not buy a new awesome theme?</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="pull-left">
                                <img src="img/avatar.png" class="img-circle" alt="user image"/>
                            </div>
                            <h4>
                                Developers
                                <small><i class="fa fa-clock-o"></i> Today</small>
                            </h4>
                            <p>Why not buy a new awesome theme?</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="pull-left">
                                <img src="img/avatar2.png" class="img-circle" alt="user image"/>
                            </div>
                            <h4>
                                Sales Department
                                <small><i class="fa fa-clock-o"></i> Yesterday</small>
                            </h4>
                            <p>Why not buy a new awesome theme?</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="pull-left">
                                <img src="img/avatar.png" class="img-circle" alt="user image"/>
                            </div>
                            <h4>
                                Reviewers
                                <small><i class="fa fa-clock-o"></i> 2 days</small>
                            </h4>
                            <p>Why not buy a new awesome theme?</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="footer"><a href="#">See All Messages</a></li>
        </ul>
    </li>
    <!-- Notifications: style can be found in dropdown.less -->
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-warning"></i>
            <span class="label label-warning">10</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">You have 10 notifications</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li>
                        <a href="#">
                            <i class="ion ion-ios7-people info"></i> 5 new members joined today
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-warning danger"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-users warning"></i> 5 new members joined
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="ion ion-ios7-cart success"></i> 25 sales made
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="ion ion-ios7-person danger"></i> You changed your username
                        </a>
                    </li>
                </ul>
            </li>
            <li class="footer"><a href="#">View all</a></li>
        </ul>
    </li>
    <!-- Tasks: style can be found in dropdown.less -->
    <li class="dropdown tasks-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-tasks"></i>
            <span class="label label-danger">9</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">You have 9 tasks</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <li><!-- Task item -->
                        <a href="#">
                            <h3>
                                Design some buttons
                                <small class="pull-right">20%</small>
                            </h3>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">20% Complete</span>
                                </div>
                            </div>
                        </a>
                    </li><!-- end task item -->
                    <li><!-- Task item -->
                        <a href="#">
                            <h3>
                                Create a nice theme
                                <small class="pull-right">40%</small>
                            </h3>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">40% Complete</span>
                                </div>
                            </div>
                        </a>
                    </li><!-- end task item -->
                    <li><!-- Task item -->
                        <a href="#">
                            <h3>
                                Some task I need to do
                                <small class="pull-right">60%</small>
                            </h3>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </a>
                    </li><!-- end task item -->
                    <li><!-- Task item -->
                        <a href="#">
                            <h3>
                                Make beautiful transitions
                                <small class="pull-right">80%</small>
                            </h3>
                            <div class="progress xs">
                                <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">80% Complete</span>
                                </div>
                            </div>
                        </a>
                    </li><!-- end task item -->
                </ul>
            </li>
            <li class="footer">
                <a href="#">View all tasks</a>
            </li>
        </ul>
    </li>
    */ ?>

        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-user"></i>
            <span>Jane Doe <i class="caret"></i></span>
        </a>
        <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header bg-light-blue">
                <img src="<?php echo Template::theme_url()?>img/avatar3.png" class="img-circle" alt="User Image" />
                <p>
                    Jane Doe - Web Developer
                    <small>Member since Nov. 2012</small>
                </p>
            </li>
            <!-- Menu Body -->
            <!--li class="user-body">
                <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </li-->
            <!-- Menu Footer-->
            <li class="user-footer">
                <div class="pull-left">
                    <a href="<?php echo site_url(SITE_AREA .'/settings/users/edit') ?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                    <a href="<?php echo site_url('logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
            </li>
        </ul>
    </li>
    </ul>
</div>
</nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left hidden-print">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar hidden-print">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?php echo Template::theme_url()?>img/avatar3.png" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>Hello, Jane</p>

                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <!--form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
                                <span class="input-group-btn">
                                    <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                                </span>
                </div>
            </form-->
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->

            <ul class="sidebar-menu">
                <li class="active">
                    <a href="<?php echo site_url()?>/admin/content">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <?php //echo theme_view('partials/_menu'); ?>

                <!-- Start Library Module -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Library</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                      <li class="treeview">
					  
                        	<a href="#"><i class="fa fa-angle-double-right"></i>Initial</a>
                    		<ul class="treeview-menu">
							
                              <li> <a href="<?php echo site_url(); ?>/admin/company_info/library/show_companylist"><i class="fa fa-angle-double-right"></i>Company </a>  </li>						   
							   <li><a href="<?php echo site_url(); ?>/admin/building/library/show_list"><i class="fa fa-angle-double-right"></i>Building </a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/counter/library/show_list"><i class="fa fa-angle-double-right"></i>Counter</a></li>

                               <li><a href="<?php echo site_url(); ?>/admin/department/library/show_list"><i class="fa fa-angle-double-right"></i>Department</a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/designation/library/show_list"><i class="fa fa-angle-double-right"></i>Designation</a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/specialization/library/show_list"><i class="fa fa-angle-double-right"></i>Specialization</a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/occupation/library/show_list"><i class="fa fa-angle-double-right"></i>Occupation</a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/shift/library/show_list"><i class="fa fa-angle-double-right"></i>Shift Create</a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/time_range/library/show_list"><i class="fa fa-angle-double-right"></i>Time Range</a></li>
                               <li><a href="<?php echo site_url(); ?>/admin/symptom/library/show_list"><i class="fa fa-angle-double-right"></i>Symptom Create</a></li>
                            </ul>
                      </li>


                      <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i>Secondary</a>
                    		<ul class="treeview-menu">
                               <li><a href="<?php echo site_url(); ?>/admin/measurement_unit/library/show_list"><i class="fa fa-angle-double-right"></i>Measurement Unit</a></li>
							   <li><a href="<?php echo site_url(); ?>/admin/sales_reference/library/show_list"><i class="fa fa-angle-double-right"></i>Reference</a></li>
							   <li><a href="<?php echo site_url(); ?>/admin/ticket_price/library/show_list"><i class="fa fa-angle-double-right"></i>Ticket Fee</a></li>
							    <li><a href="<?php echo site_url(); ?>/admin/otherservice/library/show_list"><i class="fa fa-angle-double-right"></i>Other Service</a></li>
                            </ul>
                      </li>


                      <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i>Zone</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/division/library/show_list"><i class="fa fa-angle-double-right"></i> Division </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/district/library/show_list"><i class="fa fa-angle-double-right"></i> District </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/area/library/show_list"><i class="fa fa-angle-double-right"></i>Area</a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/trtarea/library/show_list"><i class="fa fa-angle-double-right"></i>TRT Area</a></li>
                            </ul>
                      </li>


                      <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i>Exam</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/exam/library/show_list"><i class="fa fa-angle-double-right"></i> Exam Name </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/exam_board/library/show_list"><i class="fa fa-angle-double-right"></i> Exam Board </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
				<!-- End Library Module -->

				<!-- Start Account Module -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Account</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Setup</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/setup/account/group_list"><i class="fa fa-angle-double-right"></i> Group </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/setup/account/category_list"><i class="fa fa-angle-double-right"></i> Category </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/setup/account/subcategory_list"><i class="fa fa-angle-double-right"></i> Sub Category</a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/setup/account/subchild_list"><i class="fa fa-angle-double-right"></i> Sub Child</a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/setup/account/head_list"><i class="fa fa-angle-double-right"></i>Account Head</a></li>

                            </ul>
                        </li>
                        <li><a href="<?php echo site_url(); ?>/admin/voucher/account/create"><i class="fa fa-angle-double-right"></i> Vaucher Entry</a></li>
                        <li><a href="<?php echo site_url(); ?>/admin/voucher/account/voucher_list"><i class="fa fa-angle-double-right"></i> Vaucher List</a></li>

                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/voucher/account/cashbook"><i class="fa fa-angle-double-right"></i> Cash Book</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/voucher/account/bankbook"><i class="fa fa-angle-double-right"></i> Bank Book</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/voucher/account/daybook"><i class="fa fa-angle-double-right"></i> Day Book</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/voucher/account/ledgerbook"><i class="fa fa-angle-double-right"></i> Ledger Book</a></li>

								<li><a href="<?php echo site_url(); ?>/admin/report/account/income_statement"><i class="fa fa-angle-double-right"></i> Income Statement</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/report/account/balance_sheet"><i class="fa fa-angle-double-right"></i> Balance Sheet</a></li>

                            </ul>
                        </li>

                    </ul>
                </li>
				<!-- End Account Module -->
				
	<!-- Start HRM Module -->
							<li class="treeview">
								<a href="#">
									<i class="fa fa-bar-chart-o"></i>
									<span>HRM</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
								
									<li>
										<a href="<?php echo site_url(); ?>/admin/employee/hrm/show_list">
											<i class="fa fa-angle-double-right">
											</i>
											Employee Create
										</a>
									</li>
									
									<li class="treeview">
										<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
										<ul class="treeview-menu">
											<li><a href="<?php echo site_url(); ?>/admin/report/hrm/employee_list"><i class="fa fa-angle-double-right"></i>Employee List</a></li>
										</ul>
									</li>

								</ul>
							</li>
							<!-- End HRM Module -->




				<!-- Start Store Module -->
				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Store</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
						<li><a href="#"><i class="fa fa-angle-double-right"></i> Setup</a>
							<ul class="treeview-menu">
								<li><a href="<?php echo site_url();?>/admin/mainstore_setup/store/show_mainstorelist"><i class="fa fa-angle-double-right"></i>Main Store</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/substore_setup/store/show_substorelist"><i class="fa fa-angle-double-right"></i>SubStore</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/subsubstore_setup/store/show_list"><i class="fa fa-angle-double-right"></i>SubSubStore</a></li>
									<li><a href="<?php echo site_url(); ?>/admin/company_setup/store/show_companylist"><i class="fa fa-angle-double-right"></i> Company</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/supplier/store/show_list"><i class="fa fa-angle-double-right"></i> Supplier</a></li>													
								<li><a href="<?php echo site_url(); ?>/admin/Category_setup/store/show_list"><i class="fa fa-angle-double-right"></i>Category</a></li>								
								<li><a href="<?php echo site_url(); ?>/admin/subcategory/store/show_list"><i class="fa fa-angle-double-right"></i>SubCategory</a></li>
								
								<li><a href="<?php echo site_url(); ?>/admin/childcategory_setup/store/show_list"><i class="fa fa-angle-double-right"></i> ChildCategory</a></li>



							</ul>
						</li>
                        <li><a href="<?php echo site_url(); ?>/admin/purchase/store/show_list"><i class="fa fa-angle-double-right"></i> Purchase </a></li>

                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/report/store/stock_status"><i class="fa fa-angle-double-right"></i> Stock Status</a></li>

                            </ul>
                        </li>

                    </ul>
                </li>
				<!-- End Store Module -->

				<!-- Start Pharmacy Module -->
				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Pharmacy</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li><a href="<?php echo site_url(); ?>/admin/sales/pharmacy/show_list"><i class="fa fa-angle-double-right"></i> Sales </a></li>

                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/report/pharmacy/sales_status"><i class="fa fa-angle-double-right"></i> Sales Status</a></li>

                            </ul>
                        </li>

                    </ul>
                </li>
				<!-- End Pharmacy Module -->

				<!-- Start Doctor Module -->

				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Doctor</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Setup</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/doctor_department/doctor/show_list"><i class="fa fa-angle-double-right"></i>Doctor Division</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/external_doctor/doctor/show_list"><i class="fa fa-angle-double-right"></i>External Doctor </a></li>

                            </ul>
                        </li>
                        <li><a href="<?php echo site_url(); ?>/admin/time_schedule/doctor/show_list"><i class="fa fa-angle-double-right"></i>Time Schedule </a></li>

                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/report/doctor/show_list"><i class="fa fa-angle-double-right"></i>Doctor List</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>

				<!-- End Doctor Module -->

				<!-- Start Outdoor Module -->
				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Outdoor</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
						<li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Ticket</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/outdoor_ticket/outdoor/newp_ticket"><i class="fa fa-angle-double-right"></i> New Patient </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/outdoor_ticket/outdoor/oldp_ticket"><i class="fa fa-angle-double-right"></i> Old Patient </a></li>

                            </ul>
                        </li>


                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/outdoor_ticket/outdoor/ticket_list"><i class="fa fa-angle-double-right"></i>Ticket List</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>
				<!-- End Outdoor Module -->

				<!-- Start Emergency Module -->
				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Emergency</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
						<li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Ticket</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/emergency_ticket/emergency/newp_ticket"><i class="fa fa-angle-double-right"></i> New Patient </a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/emergency_ticket/emergency/oldp_ticket"><i class="fa fa-angle-double-right"></i> Old Patient </a></li>

                            </ul>
                        </li>


                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/emergency_ticket/emergency/ticket_list"><i class="fa fa-angle-double-right"></i>Ticket List</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>
				<!-- End Emergency Module -->


				<!-- Start Diagnosis Module -->
				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Diagnosis</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
						<li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Setup</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/test_machine/pathology/show_list"><i class="fa fa-angle-double-right"></i>Machine Name</a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/test_group/pathology/show_list"><i class="fa fa-angle-double-right"></i>Test Group</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/test_name/pathology/show_list"><i class="fa fa-angle-double-right"></i>Test Name</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/test_attribute/pathology/show_list"><i class="fa fa-angle-double-right"></i>Test Attribute</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/test_wise_machine_add/pathology/show_list"><i class="fa fa-angle-double-right"></i>Test Wise Machine Add</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/test_package/pathology/show_list"><i class="fa fa-angle-double-right"></i>Test Package</a></li>
                                <li><a href="<?php echo site_url(); ?>/admin/lab/pathology/show_list"><i class="fa fa-angle-double-right"></i>Lab Room</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/package_name/pathology/show_list"><i class="fa fa-angle-double-right"></i>Package Name</a></li>
								<li><a href="<?php echo site_url(); ?>/admin/package/pathology/show_list"><i class="fa fa-angle-double-right"></i>Package</a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo site_url(); ?>/admin/diagnosis/pathology/show_list"><i class="fa fa-angle-double-right"></i> Diagnosis Test </a></li>
						<li><a href="<?php echo site_url(); ?>/admin/diagnosis/pathology/pending_sample"><i class="fa fa-angle-double-right"></i> Pending Sample List </a></li>
						
						<li><a href="<?php echo site_url(); ?>/admin/diagnosis/pathology/refundApprovedList"><i class="fa fa-angle-double-right"></i> Refund Approved List </a></li>

                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/diagnosis/pathology/collectionList"><i class="fa fa-angle-double-right"></i> Collection List</a></li>

                            </ul>
                        </li>

                    </ul>
                </li>
				<!-- End Diagnosis Module -->

				<!-- Start Patient Module -->
				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Patient</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">  
                    
					<!-- Start Setup  -->                    
                     <li class="treeview">
                       <a href="#"><i class="fa fa-angle-double-right"></i> Setup</a>
                    	 <ul class="treeview-menu">
                           <li><a href="<?php echo site_url(); ?>/admin/admission_room_create/patient/show_list"><i class="fa fa-angle-double-right"></i>Room Create</a></li>
                           <li><a href="<?php echo site_url(); ?>/admin/admission_bed_create/patient/show_list"><i class="fa fa-angle-double-right"></i>Bed Create</a></li>
						    <li><a href="<?php echo site_url(); ?>/admin/admission_fee/patient/show_list"><i class="fa fa-angle-double-right"></i>Admission Fee</a></li>

                         </ul>
                      </li>               
					<!-- End Setup  --> 				                    
					
					<li><a href="<?php echo site_url(); ?>/admin/admission_bed_approve/patient/create_new"><i class="fa fa-angle-double-right"></i> Admission Bed Approve</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/admission_money_receive/patient/create_new"><i class="fa fa-angle-double-right"></i> Admission Money Receive</a></li>		   
					<li><a href="<?php echo site_url(); ?>/admin/admission_form/patient/admission_tab"><i class="fa fa-angle-double-right"></i> Patient Admission</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/admission_bed_migrate/patient/create_new"><i class="fa fa-angle-double-right"></i> Patient Admission Migrate</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/others_service_bill/patient/bill_create"><i class="fa fa-angle-double-right"></i> Add Other Service Bill</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/admission_discharge/patient/admittedPatientForDischarge"><i class="fa fa-angle-double-right"></i>Patient Discharge Approve</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/admission_form/patient/billing_list"><i class="fa fa-angle-double-right"></i>Admission Billing</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/admission_discharge/patient/dischargeApproveList"><i class="fa fa-angle-double-right"></i>Discharge Approve List</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/registration_form/patient/registration_tab"><i class="fa fa-angle-double-right"></i> Patient Registration </a></li>
					<li><a href="<?php echo site_url(); ?>/admin/free_patient_discount/patient/show_list"><i class="fa fa-angle-double-right"></i> Free Patient Discount</a></li>
					<li><a href="<?php echo site_url(); ?>/admin/special_discount/patient/show_list"><i class="fa fa-angle-double-right"></i> Special Discount </a></li>
					<li><a href="<?php echo site_url(); ?>/admin/additional_discount/patient/create"><i class="fa fa-angle-double-right"></i> Additional Discount </a></li>
					
					<li class="treeview">
					<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
					<ul class="treeview-menu">
						<li><a href="<?php echo site_url(); ?>/admin/report/patient/outDoorPatient_list"><i class="fa fa-angle-double-right"></i> OutdoorPatient List</a></li>
						<li><a href="<?php echo site_url(); ?>/admin/report/patient/registerdPatient_list"><i class="fa fa-angle-double-right"></i> Registered Patient List</a></li>
						<li><a href="<?php echo site_url(); ?>/admin/report/patient/admittedPatient_list"><i class="fa fa-angle-double-right"></i>Admitted Patient List</a></li>
						<li><a href="<?php echo site_url(); ?>/admin/report/patient/pendingDischargePatient_list"><i class="fa fa-angle-double-right"></i>Discharge Pending List</a></li>
						<li><a href="<?php echo site_url(); ?>/admin/report/patient/dischargePatient_list"><i class="fa fa-angle-double-right"></i>Discharge Patient List</a></li>


					</ul>
					</li>

                    </ul>
                </li>
				<!-- End Patient Module -->

				<!-- Start ambulance Module -->

				<li class="treeview">
                    <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Ambulance</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Setup</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/ambulance_create/ambulance/show_list"><i class="fa fa-angle-double-right"></i>Ambulance Create</a></li>
                            </ul>
                        </li>
						<li><a href="<?php echo site_url(); ?>/admin/ambulance_book/ambulance/show_list"><i class="fa fa-angle-double-right"></i> Ambulance Booking </a></li>

                        <li class="treeview">
                        	<a href="#"><i class="fa fa-angle-double-right"></i> Report</a>
                    		<ul class="treeview-menu">
                                <li><a href="<?php echo site_url(); ?>/admin/ambulance_create/ambulance/show_list"><i class="fa fa-angle-double-right"></i>Ambulance List</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>

				<!-- End ambulance Module -->
				
				<!--Start Report-->
				  <li class="treeview">
                      <a href="#"><i class="fa fa-angle-double-right"></i>Report</a>
                    	<ul class="treeview-menu">
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/collectionList"><i class="fa fa-angle-double-right"></i>Transaction List</a></li>
							
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_collection"><i class="fa fa-angle-double-right"></i>Collection List</a></li>
							
							 <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_refund"><i class="fa fa-angle-double-right"></i>Refund List</a></li>
							 
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_commission"><i class="fa fa-angle-double-right"></i>Commission List</a></li>
							
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_discount"><i class="fa fa-angle-double-right"></i>Discount List</a></li>
							
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_payment"><i class="fa fa-angle-double-right"></i>Payment List</a></li>
							
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_payment"><i class="fa fa-angle-double-right"></i>User Wise Collection</a></li>
							
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_payment"><i class="fa fa-angle-double-right"></i>Department Wise Collection</a></li>
							
                            <li><a href="<?php echo site_url(); ?>/admin/transaction_report/reportView/show_doctor_wise"><i class="fa fa-angle-double-right"></i>Doctor Wise Collection</a></li>
							
							
							
							<li><a href="<?php echo site_url(); ?>/admin/transaction_moneyreceipt_report/reportView/show_list"><i class="fa fa-angle-double-right"></i>Money Receipt Wise Collection</a></li>
                        </ul>
                  </li>
				
				<!--End Report-->


				<!-- Start Settings Module -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-laptop"></i>
                        <span>Settings</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo site_url(); ?>/admin/settings/users"><i class="fa fa-angle-double-right"></i> Users </a></li>
                        <li><a href="<?php echo site_url(); ?>/admin/settings/permissions"><i class="fa fa-angle-double-right"></i> Permissions</a></li>
                        <li><a href="<?php echo site_url(); ?>/admin/settings/roles"><i class="fa fa-angle-double-right"></i> Roles</a></li>
                        <li><a href="<?php echo site_url(); ?>/admin/menu/permissions"><i class="fa fa-angle-double-right"></i> Menu Permissions</a></li>
                        <li><a href="<?php echo site_url(); ?>/admin/errorlog/report"><i class="fa fa-angle-double-right"></i> Error Logs</a></li>
                    </ul>
                </li>
				<!-- End Settings Module -->

            </ul>


        </section>

        <!-- /.sidebar -->
    </aside>