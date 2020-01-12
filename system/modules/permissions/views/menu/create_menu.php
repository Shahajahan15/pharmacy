<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
$status = array(1 => 'Active', 0 => 'In Active');

extract($sendData, EXTR_SKIP);

function rootMenuName($menu, $parentId) {
    if ($parentId == 0) {
        return '---';
    }
    $return = '';
    foreach ($menu as $row) {
        if ($row['id'] == $parentId) {
            $return = $row['parent_id'] > 0 ? rootMenuName($menu, $row['parent_id']) : $row;
            break;
        }
    }
    return $return;
}
?>

<style>
    .form-group .form-control, .control-group .form-control{ width: 80%;}
    .control { width: 100%;}
    .menu-container{
        padding-left: 20%;
    }
</style>

<div role="tabpanel">

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#menu" aria-controls="menu" role="tab" data-toggle="tab">Menu</a></li>
    <!--li role="presentation"><a href="#menu_list" aria-controls="menu_list" role="tab" data-toggle="tab">Menu List</a></li-->
    <li role="presentation"><a href="#menu_ordered" aria-controls="menu_ordered" role="tab" data-toggle="tab">Menu Order</a></li>
    <li role="presentation"><a href="#menu_permissions" aria-controls="menu_permissions" role="tab" data-toggle="tab">Menu Permisions</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
<!----------- TAB FOR MENU ----------------->
<div role="tabpanel" class="tab-pane active" id="menu">
    <?php echo form_open($this->uri->uri_string(), 'role="form", class="form-horizontal"'); ?>
    <fieldset class="box-body">
        <div class="menu-container">
            <div class="col-md-8">
                <div class="form-group ">
                    <label for="menu_name" class="control-label">Menu Name<span class="required">*</span></label>
                    <div class="control">
                        <input class="form-control" id="menu_name" name="menu_name" maxlength="50" value="<?php echo set_value('name', isset($record['name']) ? $record['name'] : ''); ?>" type="text" required="">
                        <span class="help-inline"><?php echo form_error('menu'); ?></span>
                    </div>
                </div>

                <div class="form-group <?php echo form_error('sub_menu') ? 'error' : ''; ?>">
                    <label for="menu_name" class="control-label">Level</label>
                    <div class='control'>
                        <select name="sub_menu" id="sub_menu" class="form-control chosenCommon chosen-container chosen-container-single">
                            <option value="">Select.....</option>
                            <?php foreach($menu_array as $val){
                                //if($val['parent_id'] > 0) continue;
                                echo "<option value='".$val['id']."'";
                                $mod = isset($record['parent_id'])? $record['parent_id'] : '';
                                if($mod==$val['id']){ echo "selected";}
                                echo ">".$val['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <?php /*<div class="form-group <?php echo form_error('sub_sub_menu') ? 'error' : ''; ?>">
                                <label for="menu_name" class="control-label">2nd Level</label>
                                <div class='control'>
                                    <select name="sub_sub_menu" id="sub_sub_menu" class="form-control chosenCommon chosen-container chosen-container-single" >
                                        <option value="">Select......</option>
                                        <?php foreach($menu_array as $val){
                                            if($val['parent_id'] == 0) continue;
                                            echo "<option value='".$val['id']."'";
                                            $subMenu = isset($record['parent_parent_id'])? $record['parent_parent_id'] : '';
                                            if($subMenu==$val['parent_id']){ echo "selected";}
                                            echo ">".$val['name']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> */?>

                <div class="form-group ">
                    <label for="menu_name" class="control-label">Menu Link</label>
                    <div class="control">
                        <input class="form-control" id="menu_link" name="menu_link" maxlength="100" value="<?php echo set_value('menu_link', isset($record['menu_link']) ? $record['menu_link'] : ''); ?>" type="text" >
                        <span class="help-inline"><?php echo form_error('menu_link'); ?></span>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="control-label">Status</label>
                    <div class="controls">
                        <select name="menu_status" id="menu_status" class="form-control">
                            <?php foreach($status as $key => $val){
                                echo "<option value='".$key."'";
                                $menuStatus = isset($record['is_active'])? $record['is_active'] : 1;
                                if($menuStatus==$key){ echo "selected";}
                                echo ">".$val."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="box-footer pager">
                    <input name="save" class="btn btn-primary" value="Save" type="submit">
                    &nbsp;
                    <a href="#" class="btn btn-warning">Cancel</a>
                </div>
            </div>
        </div>
    </fieldset>
    </form>
</div>
<!----------------- TAB FOR MENU ----------------->

<!--------------- TAB FOR MENU LIST----------------->
<?php /*
        <div role="tabpanel" class="tab-pane" id="menu_list">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th></th>
                    <th>Menu</th>
                    <th>Menu Link</th>
                    <th>Parent</th>
                    <th>Parent Parent</th>
                    <th>Status</th>
                </tr>
                </thead>
                <?php if ($menu_array) : ?>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <?php echo lang('bf_with_selected'); ?>
                                <input type="submit" name="delete" id="delete-me" class="btn btn-danger" value="Delete" onclick="return confirm('<?php e(js_escape('Are you want to delete?')); ?>')" />
                            </td>
                        </tr>
                    </tfoot>
                <?php endif; ?>
                <tbody>
                <?php
                if ($menu_array) :

                    foreach ($menu_array as $record) :
                        $record = (array) $record;
                        ?>
                        <tr>
                            <td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record['id']; ?>" /></td>
                            <td><?php echo anchor(SITE_AREA . '/menu/permissions/index/' . $record['id'], '<span class="glyphicon glyphicon-pencil"></span>' . $record["name"]); ?></td>
                            <td><?php e($record["menu_link"]) ?></td>
                            <td><?php e($record["parent_id"] ? $menu[$record["parent_id"]] : '-------') ?></td>
                            <td><?php e($record["parent_parent_id"] ? $sub_menu[$record["parent_parent_id"]] : '------') ?></td>
                            <td><?php e($status[$record["is_active"]]) ?></td>
                        </tr>
                    <?php
                    endforeach;

                else:
                    ?>
                    <tr>
                        <td colspan="6">
                            No record Found
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        */?>
<!--------------- TAB FOR MENU LIST ----------------->

<!-- MENU ORDERED -->
<div role="tabpanel" class="tab-pane" id="menu_ordered">
    <div class="row">
        <div class="col-md-8">
            <div class="well">
                <p class="lead"><a href="javascript:void(0)" class="pull-right" onclick="$('[href=\'#menu\']').trigger('click')"><span class="glyphicon glyphicon-plus-sign"></span> new menu item</a> Menu:(Supported 2 levels)</p>
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
<!-- MENU ORDERED -->

<!--------------- TAB FOR Menu Permissions ----------------->
<div role="tabpanel" class="tab-pane" id="menu_permissions">
    <?php echo form_open($this->uri->uri_string().'/module_permissions', 'role="form", class="form-horizontal"'); ?>
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title">Modules are displayed. Please checked for give permissions.</h3>
        </div><!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
            <div id="accordion" class="box-group">

                <div id="moduleDiv">
                    <table id="menu_permissions_tbl" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Item</th>
                                <th>Sub Item</th>
                                <th>Menu Link</th>
                                <?php foreach($roll_array as $val){
                                    echo "<th>".$val->role_name."</th>";
                                }
                                ?>
                            </tr>
                            <tr>
                                <th>
                                    <select class="filter_menu_actions" id="filter_menu_module" name="filter_menu_module">
                                        <option value="">--</option>
                                        <?php foreach ($filter['modules'] as $module) : ?>
                                        <option value="<?php echo $module['id'] ?>"><?php echo $module['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </th>
                                <th>
                                    <select class="filter_menu_actions" id="filter_menu_module_item" name="filter_menu_module_item">
                                        <option value="">--</option>
                                        <?php foreach ($filter['items'] as $item) : ?>
                                        <option value="<?php echo $item['id'] ?>" data-parent-id="<?php echo $item['parent_id'] ?>"><?php echo $item['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </th>
                                <th>
                                    <select class="filter_menu_actions" id="filter_menu_module_item_sub" name="filter_menu_module_item_sub">
                                        <option value="">--</option>
                                        <?php foreach ($filter['sub_items'] as $sub_item) : ?>
                                        <option value="<?php echo $sub_item['id'] ?>" data-parent-id="<?php echo $sub_item['parent_id'] ?>" data-parent-parent-id="<?php echo $sub_item['parent_parent_id'] ?>"><?php echo $sub_item['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </th>
                                <th></th>
                                <?php foreach ($roll_array as $roll) : ?>
                                <th></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($menu_array as $res){
                            if (!$res['menu_link']) continue;
                            $root    = rootMenuName($menu_array, $res['parent_id']);
                            $has_sub = $res['parent_id'] && $root['name'] != $menu[$res['parent_id']];
                            $is_root = empty($root) || $root == '' || $root == '---';
                            $item_id = $has_sub ? $res['parent_id'] : $res['id'];
                            $sub_id  = $has_sub ? $res['id'] : '';
                            ?>
                            <tr data-module-id="<?php echo isset($root['id']) ? $root['id'] : $res['id'] ?>" data-item-id="<?php echo $item_id ?>" data-item-sub-id="<?php echo $sub_id ?>">
                                <td><?php echo isset($root['name']) ? $root['name'] : $res['name']; ?></td>
                                <td><?php echo $is_root ? '---' : ($has_sub ? $menu[$res['parent_id']] : $menu[$res['id']]); ?></td>
                                <td><?php echo $is_root ? '---' : ($has_sub ? $menu[$res['id']] : '---'); ?></td>
                                <td><?php echo $res['menu_link'] ?></td>
                                <?php foreach($roll_array as $val){?>
                                    <td>
                                        <input name="checkbox[]" type="checkbox" <?php
                                            echo array_key_exists($res['id'], $permissions)? (array_key_exists($val->role_id, $permissions[$res['id']])? "checked" : "") : "";
                                            ?> value="<?php echo $res['id'].'_'.$val->role_id?>" />
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div><!-- /.box-body -->
        </div>
        </form>
    </div>
    <!--------------- TAB FOR Menu Permissions ----------------->


</div>

</div>

<?php Assets::add_js(Template::theme_url('js/menu/jquery.nestable.js'));?>
<?php Assets::add_js(Template::theme_url('js/menu/selectize.min.js'));?>
<?php Assets::add_js(Template::theme_url('js/menu/rwd-table.js'));?>