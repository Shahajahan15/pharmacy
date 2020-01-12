<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
extract($sendData, EXTR_SKIP);
?>

<div id="menu_ordered">
    <div class="row">
        <div class="col-md-8">
            <div class="well">
                <p>
                    <a href="javascript:void(0)" class="pull-right add_menu_btn" data-href="menu/permissions/load_menu"><span class="glyphicon glyphicon-plus-sign"></span> Add Menu</a> <strong>Menu:(Supported 2 levels)</strong>
                </p>
                <div class="dd" id="nestable">
                    <?php echo json_decode($menuHtml); ?>
                </div>

                <p id="success-indicator" style="display:none; margin-right: 10px;">
                    <span class="glyphicon glyphicon-ok"></span> Menu order has been saved
                </p>
            </div>
        </div>
    </div>
</div>


<?php Assets::add_js(Template::theme_url('js/menu/jquery.nestable.js'));?>
<?php Assets::add_js(Template::theme_url('js/menu/selectize.min.js'));?>
<?php Assets::add_js(Template::theme_url('js/menu/rwd-table.js'));?>