<?php

Assets::add_css(array(
    'bootstrap.css',
    'font-awesome.min.css',
    //'ionicons.min.css',
    //'datepicker/datepicker3.css',
    'timepicker/bootstrap-timepicker.min.css',
    //'daterangepicker/daterangepicker-bs3.css',
    //'bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
    'select2.min.css',
    'datatables/dataTables.bootstrap.css',
    'AdminLTE.css',
    'jquery-ui.min.css',
    'rahat-hospital.css'
));

if (isset($shortcut_data) && is_array($shortcut_data['shortcut_keys'])) {
    //Assets::add_js($this->load->view('ui/shortcut_keys', $shortcut_data, true), 'inline');
}

?><!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo isset($toolbar_title) ? $toolbar_title . ' : ' : ''; ?> <?php e($this->settings_lib->item('site.title')) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex" />
        <?php echo Assets::css(null, true); ?>
        <script>window.jQuery || document.write('<script src="<?php echo js_path(); ?>jquery.min.js"><\/script>')</script>
		<script type="text/javascript">
				var base_url = '<?php echo base_url(); ?>';
		</script>
        <style type="text/css">
            .remove:hover{
                color:red;
                border: 2px solid black;
                cursor: pointer;
            }
            .bootstrap-timepicker-widget table td input {
                width:50px;
            }
        </style>
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header hidden-print">
            <input type="hidden" name="site-url-hidden-field" id="site-url-hidden-field" value="<?php echo site_url() . '/admin/' ?>">
            <input type="hidden" name="env_var" id="env_var" value="<?php echo ENVIRONMENT ?>">
            <a href="<?php echo site_url() ?>/admin/content" class="logo-link">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
               <!--  <img id="logo-img" class="logo img-responsive" src="<?php echo base_url('logo-side.png') ?>" /> -->
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

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo current_user()->username ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <?php
                                    if ($empImg = $this->session->userdata('image_profile')) {
                                    ?>
                                        <img src="<?php echo img_path().'employee_img/'.$empImg?>" class="img-circle" alt="User Image" />
                                    <?php } else { ?>
                                        <img src="<?php echo img_path().'profile/empty-people.png'?>" class="img-circle" alt="User Image" />
                                    <?php } ?>
                                    <p>
                                        <div><?php echo $this->session->userdata('display_name')?></div>
                                        <small><?php echo $this->session->userdata('email')?></small>
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
                                        <a href="<?php echo site_url(SITE_AREA . '/settings/users/edit') ?>" class="btn btn-default btn-flat">Profile</a>
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
                            <?php
                            if ($empImg = $this->session->userdata('image_profile')) {
                                ?>
                                <img src="<?php echo img_path().'employee_img/'.$empImg?>" class="img-circle" alt="User Image" />
                            <?php } else { ?>
                                <img src="<?php echo img_path().'profile/empty-people.png'?>" class="img-circle" alt="User Image" />
                            <?php } ?>
                        </div>                        
                        <div class="pull-left info">
                            <p>Hi, <?php echo current_user()->username ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>

                    <?php
                        if ($this->session->userdata('role_id') == 10) {
                        ?>
                           <div class="user-panel">
                                <p class="info">Collected: <?php echo $this->session->userdata('collection')?> Tk</p>
                           </div>
                    <?php } ?>

                    <ul class="sidebar-menu">
                        <li class="active">
                            <a href="<?php echo site_url() ?>/admin/content">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>

                        <?php echo theme_view('partials/_menu');  ?>
					</ul>
                </section>
                <!-- /.sidebar -->
            </aside>