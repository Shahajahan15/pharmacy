<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar hidden-print">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php if($empImg = current_session()->userdata('image_profile')): ?>
                    <img src="<?php echo e(img_path()); ?>employee_img/<?php echo e($empImg); ?>" class="img-circle" alt="User Image" />
                <?php else: ?>
                    <img src="<?php echo e(img_path()); ?>profile/empty-people.png" class="img-circle" alt="User Image" />
                <?php endif; ?>
            </div>
            <div class="pull-left info">
                <p>Hello, <?php echo e(current_user()->username); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?php if(current_session()->userdata('role_id') == 10): ?>
           <div class="user-panel">
                <p class="info">Collected: <?php echo e(current_session()->userdata('collection')); ?> Tk</p>
           </div>
        <?php endif; ?>
        
        <ul class="sidebar-menu">
            <li class="active">
                <a href="<?php echo e(site_url()); ?>/admin/admin_content">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            
            
            <?php echo theme_view('partials/_menu'); ?>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>