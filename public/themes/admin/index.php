<?php
    Assets::add_js( array( 'bootstrap.min.js', 'jwerty.js'), 'external', true);
?>
<?php //echo theme_view('partials/_header_old'); ?>
<?php echo theme_view('partials/_header'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <section class="content-header ">
                <?php if (isset($toolbar_title)) : ?>
                    <h1><?php echo $toolbar_title ?></h1>
                <?php endif; ?>
				
				<?php if(strpos($this->uri->uri_string(), 'settings') !== false) { ?>
					<div class="breadcrumb">
					<?php Template::block('sub_nav', ''); ?>
					</div>
				<?php } else { ?>				
					<?php Template::block('sub_nav', ''); ?>
				<?php } ?>				
                  <?php echo theme_view('partials/_confirm_alert'); ?>
            </section>



            <section class="content container-min-height">
            	<div id="messages" style="display: none;">
                    <div class='alert alert-block fade in notification'><a data-dismiss='alert' class='close' href='#'>×</a><div id="text"></div></div>
                </div>
                <?php echo Template::message(); ?>

                <?php echo isset($content) ? $content : Template::content(); ?>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->

<?php echo theme_view('partials/_footer'); ?>