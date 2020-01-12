<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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

<!-- ------------- TAB FOR Menu Permissions --------------- -->
<div role="tabpanel" class="tab-pane" id="menu_permissions">
    <?php echo form_open($this->uri->uri_string().'/module_permissions', 'role="form", class="form-horizontal"'); ?>
    <div class="box box-solid">
        <div class="box-body" style="overflow-x: scroll;">
            <div class="box-group">
                <div>
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

<script>
    $(document).ready(function(){
        $('#menu_permissions_tbl input[type="checkbox"]').on("change", function(){
            var menu_role_id = $(this).val();
            var ci_csrf_token = $("input[name='ci_csrf_token']").val();
            $.post( siteURL+'menu/permissions/menuPermissionsAjax', {menu_role_id:menu_role_id, ci_csrf_token:ci_csrf_token}).done( function( data ) {
                //TODO
            });
        });

        $('#menu #sub_menu').on("change", function(){
            var menu_id = $(this).val();
            if(parseInt(menu_id) == 0){ return false;}

            var ci_csrf_token = $("input[name='ci_csrf_token']").val();
            var targetUrl = "menu/permissions/getSubMenuByMenuId";
            var sendData = { menu_id: menu_id, ci_csrf_token:ci_csrf_token };
            var firstOption = "<option value=''>Select</option>";

            setInnerHTMLAjax(siteURL+targetUrl, sendData, "#sub_sub_menu", firstOption, 1, 1);
        });

    });
</script>