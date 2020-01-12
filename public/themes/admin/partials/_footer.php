        </div><!-- ./wrapper -->

        <?php

        $start_year = '2017';
        $end_year   = date('Y');

        $copyright_years = $start_year;
        if ($start_year != $end_year) {
            $copyright_years .= '-'.$end_year;
        }

        ?>

        <footer class="container-fluid footer">
            <p class="pull-right">
                <br/>
                <div class="pull-right" id="footer">
					<i class="glyphicon glyphicon-copyright-mark"></i> <?php echo $copyright_years ?> <span>Design &amp; Developed By <a href="http://worldtechbd.org/" target="_blank">World Tech Org.</a></span>
                </div>
            </p>
        </footer>

        <div id="debug"><!-- Stores the Profiler Results --></div>

        <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="<?php echo js_path(); ?>jquery.min.js"><\/script>')</script>

        <?php
        //<!-- jQuery UI 1.10.3 -->
        Assets::add_js(Template::theme_url('js/jquery-ui.min.js'));
		Assets::add_js(Template::theme_url('ckeditor-basic/ckeditor.js'));
		Assets::add_js(Template::theme_url('ckeditor-basic/config.js'));
        //<!-- Bootstrap -->
        //Assets::add_js(Template::theme_url('js/bootstrap.min.js'));
        //<!-- daterangepicker -->
        //Assets::add_js(Template::theme_url('js/plugins/daterangepicker/daterangepicker.js'));
        //<!-- datepicker -->
        //Assets::add_js(Template::theme_url('js/plugins/datepicker/bootstrap-datepicker.js'));
        Assets::add_js(Template::theme_url('js/plugins/timepicker/bootstrap-timepicker.min.js'));
        // <!-- Bootstrap WYSIHTML5 -->
        //Assets::add_js(Template::theme_url('js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'));
        //<!--jQuery drop dwon select2-->
        Assets::add_js(Template::theme_url('js/plugins/select2/select2.min.js'));
        //<!-- input-mask -->
        Assets::add_js(Template::theme_url('js/plugins/input-mask/jquery.inputmask.js'));
        Assets::add_js(Template::theme_url('js/plugins/input-mask/jquery.inputmask.date.extensions.js'));
        Assets::add_js(Template::theme_url('js/plugins/input-mask/jquery.inputmask.numeric.extensions.js'));
        Assets::add_js(Template::theme_url('js/plugins/input-mask/jquery.inputmask.extensions.js'));
        //<!-- Datatables  -->
        Assets::add_js(Template::theme_url('js/plugins/datatables/jquery.dataTables.js'));
        Assets::add_js(Template::theme_url('js/plugins/datatables/dataTables.bootstrap.js'));
        //<!-- iCheck -->
        //Assets::add_js(Template::theme_url('js/plugins/iCheck/icheck.min.js'));
        //-- validator plugin for Bootstrap 3 -----------//
        Assets::add_js(Template::theme_url('js/plugins/validator/validator.min.js'));
        Assets::add_js(Template::theme_url('js/plugins/jquery.autocomplete.min.js'));
        //<!-- AdminLTE App -->
        Assets::add_js(Template::theme_url('js/AdminLTE/app.js'));
        //<!-- AdminLTE for demo purposes -->
        //Assets::add_js(Template::theme_url('js/AdminLTE/demo.js'));
        //Assets::add_js(Template::theme_url('js/jwerty.js'));
       // Assets::add_js(Template::theme_url('js/global.js'));
        ?>
        <?php echo Assets::add_js(Template::theme_url('js/sweet_alert.js')); ?>


        <?php echo Assets::js(); ?>


    </body>
</html>

