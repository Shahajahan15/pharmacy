<?php
$site_open = $this->settings_lib->item('auth.allow_register');
?>
<style>.alert-error{color:#a94442;background-color:#f2dede;border-color:#ebccd1}</style>
<div class="container">
    <div id="loginbox" style="margin-top:10px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Log in to Software</div>
            </div>

            <div style="padding-top:30px" class="panel-body" >
                <?php echo Template::message(); ?>

                <?php echo form_open(LOGIN_URL, array('autocomplete' => 'off', 'class' => 'form-horizontal', 'id' => 'loginform', 'role'=> 'form')); ?>

                <div style="margin-bottom: 15px" class="input-group <?php echo iif( form_error('login') , 'error') ;?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input class="form-control" type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo lang('bf_username')?>" />
                </div>

                <div style="margin-bottom: 15px" class="input-group <?php echo iif( form_error('password') , 'error') ;?>"">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input class="form-control" type="password" name="password" id="password" value="" tabindex="2" placeholder="<?php echo lang('bf_password'); ?>" />
            </div>

            <div class="input-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember_me" id="login-remember" value="1" tabindex="3" />  Remember me
                    </label>
                </div>
            </div>

            <div style="margin-top:10px" class="form-group">
                <!-- Button -->
                <div class="col-sm-12 controls">
                    <input class="btn btn-primary" type="submit" name="log-me-in" id="submit" value="<?php e(lang('us_let_me_in')); ?>" tabindex="5" />
                    Or <?php echo anchor('/forgot_password', lang('us_forgot_your_password')); ?>
                </div>
            </div>

            <?php echo form_close(); ?>

            <?php /* if ( $site_open ) : ?>
                <div class="form-group">
                    <div class="col-md-12 control">
                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                            Don't have an account!
                            <?php echo anchor(REGISTER_URL, lang('us_sign_up')); ?>
                        </div>
                    </div>
                </div>
            <?php endif; */?>

            <?php // show for Email Activation (1) only
            if ($this->settings_lib->item('auth.user_activation_method') == 1) : ?>
                <!-- Activation Block -->
                <p style="text-align: left" class="well">
                    <?php echo lang('bf_login_activate_title'); ?><br />
                    <?php
                    $activate_str = str_replace('[ACCOUNT_ACTIVATE_URL]',anchor('/activate', lang('bf_activate')),lang('bf_login_activate_email'));
                    $activate_str = str_replace('[ACTIVATE_RESEND_URL]',anchor('/resend_activation', lang('bf_activate_resend')),$activate_str);
                    echo $activate_str; ?>
                </p>
            <?php endif; ?>

            <div class="container-fluid footer" style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                <p class="pull-right">
                    <br/>
        <?php

        $start_year = '2017';
        $end_year   = date('Y');

        $copyright_years = $start_year;
        if ($start_year != $end_year) {
            $copyright_years .= '-'.$end_year;
        }

        ?>
                    <div id="footer">
                        <span class="pull-left"><img id="erp-img" src="<?php e(img_path().'erp.png')?>"></span>
                        <span class="pull-right" style="padding-top:10px">
                            <i class="glyphicon glyphicon-copyright-mark"></i> <?php echo $copyright_years ?> <span>Design &amp; Developed By <a href="http://worldtechbd.org" target="_blank">World Tech Org.</a></span>
                        </span>
                    </div>
                </p>
            </div>
        </div>
    </div>
</div>
