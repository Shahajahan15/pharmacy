<!doctype html>
<html lang="en">
<head>
    <?php echo $__env->make('layout/_header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldContent('header'); ?>
</head>
<body class="skin-blue">
<header class="header hidden-print">
    <input type="hidden" name="site-url-hidden-field" id="site-url-hidden-field" value="<?php echo e(site_url()); ?>'/admin/'">
    <input type="hidden" name="env_var" id="env_var" value="<?php echo e(ENVIRONMENT); ?>">
    <a href="<?php echo e(site_url()); ?>/admin/content" class="logo-link">
        <img id="logo-img" class="logo img-responsive" src="<?php echo base_url('logo-side.png'); ?>" />
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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo e(current_user()->username); ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php if($empImg = current_session()->userdata('image_profile')): ?>
                                <img src="<?php echo e(img_path()); ?>employee_img/<?php echo e($empImg); ?>" class="img-circle" alt="User Image" />
                            <?php else: ?>
                                <img src="<?php echo e(img_path()); ?>profile/empty-people.png" class="img-circle" alt="User Image" />
                            <?php endif; ?>
                            <p>
                            <div><?php echo e(current_user()->display_name); ?></div>
                            <small><?php echo e(current_user()->email); ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo site_url(SITE_AREA . '/settings/users/edit'); ?>" class="btn btn-default btn-flat">Profile</a>
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
        <?php echo $__env->make('layout/_left_nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <?php echo $__env->make('layout/_content_header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <section class="content container-min-height">
                <div id="messages" style="display: none;">
                    <div class='alert alert-block fade in notification'><a data-dismiss='alert' class='close' href='#'>×</a><div id="text"></div></div>
                </div>
                <?php echo Template::message(); ?>


                <?php echo $__env->yieldContent('content'); ?>

                <?php if(isset($content)): ?>
                    <?php echo $content; ?>

                <?php else: ?>
                    <?php echo Template::content(); ?>

                <?php endif; ?>
            </section>
        </aside>
    </div><!-- ./wrapper -->

    <?php echo $__env->make('layout/_footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</body>
</html>