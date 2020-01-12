<?php
    Assets::add_js( 'bootstrap.min.js' );
    Assets::add_css( array('bootstrap.min.css', 'bootstrap-responsive.min.css'));

    $inline  = '$(".dropdown-toggle").dropdown();';
    $inline .= '$(".tooltips").tooltip();';

    Assets::add_js( $inline, 'inline' );
?>
<!doctype html>
<head>
    <meta charset="utf-8">

    <title><?php echo isset($page_title) ? $page_title .' : ' : ''; ?> <?php if (class_exists('Settings_lib')) e(settings_item('site.title')); else echo 'Jute'; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php echo Assets::css(); ?>

    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
    <style>
        .body-bg {
            background-image: url("<?php echo assets_path().'images/login-bg.jpg'; ?>");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        .hospital-title {
            width: 100%;
            height: 120px;
            text-align: center;
            margin-top: 40px;
        }

    </style>
</head>
<body class="body-bg">
    <div class="hospital-title">
        <!-- <img class="img-responsive center-block" id="logo-img" src="<?php echo base_url('logo-side.png') ?>" /> -->
    </div>
