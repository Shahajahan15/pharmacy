<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
$status = array(1 => 'Active', 0 => 'In Active');
$updateId = empty($record['id']) ? 0 : $record['id'];
$actionUrl = SITE_AREA . '/menu/permissions/create/' . $updateId;

function rootMenuName($menu, $parentId) {
    $return = null;

    foreach ($menu as $row) {
        if ($row['id'] == $parentId) {
            if ($row['parent_id'] > 0) {
                $return .= rootMenuName($menu, $row['parent_id']);    
            }
            $return .= ' >> ' . $row['name'];
        }
    }
    return $return;
}
?>


<div>
<?php echo form_open($actionUrl, 'role="form", class="nform-horizontal"'); ?>
    <div class="modal-body">        
        <div class="form-group">
            <label for="menu_name" class="control-label">Menu Name<span class="required">*</span></label>
            <div class="control">
                <input class="form-control" id="menu_name" name="menu_name" maxlength="50" value="<?php echo set_value('name', isset($record['name']) ? $record['name'] : ''); ?>" type="text" required>
            </div>
        </div>

        <div class="form-group">
            <label for="menu_name" class="control-label">Level<span class="required">*</span></label>
            <div class='control'>
                <select name="sub_menu" id="sub_menu" class="form-control chosenCommon chosen-container chosen-container-single" required>
                    <option value="">Select.....</option>
                    <?php foreach($menu_array as $val){
                        $level = rootMenuName($menu_array, $val['id']);
                        if(count(explode(">>", $level)) > 3) continue;

                        echo "<option value='".$val['id']."'";
                        $mod = isset($record['parent_id'])? $record['parent_id'] : '';
                        if($mod==$val['id']){ echo "selected";}
                        echo ">".$level."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group ">
            <label for="menu_name" class="control-label">Menu Link<span class="required">*</span></label>
            <div class="control">
                <input class="form-control" id="menu_link" name="menu_link" maxlength="100" value="<?php echo set_value('menu_link', isset($record['menu_link']) ? $record['menu_link'] : ''); ?>" type="text" required>
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
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" name="save" class="btn btn-primary">Save changes</button>
    </div>
</form>
</div>


<script type="text/javascript">
    $(".nform-horizontal").validator();
    $(".chosenCommon").select2();
</script>