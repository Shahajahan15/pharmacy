<section class="content-header ">
    <?php if(!$monolith && isset($toolbar_title)): ?>
        <h1><?php echo e($toolbar_title); ?></h1>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content-header'); ?>

    <?php if(strpos(uri()->uri_string(), 'settings') !== false): ?>
        <div class="breadcrumb">
            <?php echo Template::block('sub_nav', ''); ?>

        </div>
    <?php else: ?>
        <?php echo Template::block('sub_nav', ''); ?>

    <?php endif; ?>

    <?php echo $__env->make('layout/_confirm_alert', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</section>