<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Menu extends Admin_Controller
{

	/**
	 * Controller constructor sets the login restriction
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		//$this->auth->restrict('Permissions.Administrator.Manage');
		$this->auth->restrict();

        $this->load->model('menu_model');        
        Template::set_block('sub_nav', 'menu/_sub_nav');
	}//end __construct()

	//--------------------------------------------------------------------


	/**
	 * Redirects the user to the Content context
	 *
	 * @return void
	 */
    public function index()
    {
        Assets::add_module_js('permissions', 'menu_filter.js');
        $this->load->model('roles/role_model');
        $id = (int)$this->uri->segment(5);
        
        $rollArray = $this->role_model->select('role_id, role_name')->where('deleted', 0)->find_all();

        $smt = $this->doctrine->db->prepare("select * from bf_menu order by rang");
        $smt->execute();
        $menuArrays = $smt->fetchAll(PDO::FETCH_ASSOC);
        $menuArray = $menuArrays;
        $menu = array();
        if($menuArrays){
            foreach($menuArrays as $res){
                $menu[$res['id']] = $res['name'];
            }
        }

        $smt = $this->doctrine->db->prepare("select * from bf_menu_permissions");
        $smt->execute();
        $result = $smt->fetchAll();
        $permissions = array();
        foreach($result as $row){
            $permissions[$row['menu_id']][$row['role_id']] = $row['id'];
        }

        $filter = [];
        $filter['modules']   = $this->menu_model->filter_modules_list();
        $filter['items']     = $this->menu_model->filter_items_list();
        $filter['sub_items'] = $this->menu_model->filter_sub_items_list();

        $sendData = array(
            'menu' => $menu,
            'menu_array' => $menuArray,
            'roll_array' => $rollArray,
            'permissions' => $permissions,
            'filter' => $filter,
        );

        Template::set('sendData', $sendData);
        Template::set("toolbar_title", "Menu Permissions");
        Template::set_view('menu/list');
		Template::render();

    }//end index()

    public function create($id = 0) 
    {
        Assets::add_module_css('permissions', 'nestable.css');
        Assets::add_module_css('permissions', 'rwd-table.min.css');
        Assets::add_module_js('permissions', 'menu.js');


        if(isset($_POST['save'])){
            $data = array();
            $data['parent_id']              = (int)$this->input->post('sub_menu');
            $data['parent_parent_id']       = 0; //(int)$this->input->post('sub_sub_menu');
            $data['name']                   = $this->input->post('menu_name');
            $data['menu_link']              = $this->input->post('menu_link');
            $data['is_active']              = (int)$this->input->post('menu_status');

            Template::set_message('Menu has been saved successfully', 'success');
            if ($id == 0)
            {
                if(!$this->menu_model->insert($data)){
                    Template::set_message('Problem occured in menu save', 'warning');
                }
            } else {
                if(!$this->menu_model->update($id, $data)){
                    Template::set_message('Problem occured in menu update', 'warning');
                }
            }

            redirect(SITE_AREA .'/menu/permissions/create');
        }

        $sendData = array(
            'menuHtml' => json_encode($this->getHTML()),
        );

        Template::set('sendData', $sendData);
        Template::set("toolbar_title", "Menu");
        Template::set_view('menu/create');
        Template::render();
    }

    public function menu_delete($id = 0)
    {
        $this->doctrine->db->exec(sprintf("DELETE FROM bf_menu WHERE id = %d", $id)); 
                 
        redirect(SITE_AREA .'/menu/permissions/create');    
    }

    public function load_menu($id = 0)
    {
        $this->load->model('roles/role_model');
        $rollArray = $this->role_model->select('role_id, role_name')->where('deleted', 0)->find_all();

        $smt = $this->doctrine->db->prepare("select * from bf_menu order by rang");
        $smt->execute();
        $menuArray = $smt->fetchAll(PDO::FETCH_ASSOC);
        
        //for edit menu---------------------------
        $record = array();
        if($id){
            foreach($menuArray as $row){
                if($row['id'] == $id){
                    $record = $row;
                    break;
                }
            }
        }

        $sendData = array(
            'menu_array' => $menuArray,
            'record' => $record,
        );

        echo $this->load->view("/menu/modal", $sendData, true);
    }


    public function buildMenuPermission($menu, $parentId = 0, $subItem = 0)
    {
        $result = array();
        $data = null;
        foreach ($menu as $item) {
            if ($item['parent_id'] == $parentId) {
                $data[] = $item;
                if ($item['parent_id'] == 0) $result = array_merge($result, array($item));
                $results = $this->buildMenuPermission($menu, $item['id'], 1);

                $result = array_merge($result, $results);
            }
        }

        if ($subItem) {
            return $data;
        }

        return $result ? $result : array();
    }

	//--------------------------------------------------------------------
    public function extraPermission($id, $root = 0) {
        $smt = $this->doctrine->db->prepare(sprintf("select * from bf_menu where id = %s", $id));
        $smt->execute();
        $menuArrays = $smt->fetchAll(PDO::FETCH_ASSOC);
        //$extraPerm = array();
        foreach ($menuArrays as $row) {
            if ($row['id'] == $id && ($row['parent_id'] || $root)) {
                $menuId = $row['parent_id'] ? $row['parent_id'] : $row['id'];
                $extraPerm[$menuId] = $menuId;
                if ($row['parent_id'] > 0) {
                    $menuId = $this->extraPermission($menuId, 1);
                    $extraPerm[$menuId] = $menuId;
                }
            }
        }

        if ($root) {
            return $menuId;
        }

        return $extraPerm;
    }

    public function menuPermissionsAjax()
    {
        $this->load->model('menu_permissions_model');
        $per = explode("_", $_POST['menu_role_id']);
        $data = array(
            'menu_id' => $per[0],
            'role_id' => $per[1]
        );

        $permObj = $this->menu_permissions_model->select('id')->find_by($data, null, 'and');
        if($permObj){
            $this->menu_permissions_model->delete($permObj->id);
        } else {
            $this->menu_permissions_model->insert($data);
            $extraPerm = $this->extraPermission($per[0]);
            if ($extraPerm) {
                $this->menu_permissions_model->replace_menu_permission($extraPerm, $per[1]);
            }
        }

        echo true;
    }

    public function getSubMenuByMenuId()
    {
        $groupDropDown = "";
        $menuId = (int)$_POST['menu_id'];
        //$this->load->model('account/list_model', NULL, true);

        $smt = $this->doctrine->db->prepare(sprintf("select id, name from bf_menu where parent_id = %s", $menuId));
        $smt->execute();
        $menuArray = $smt->fetchAll(PDO::FETCH_ASSOC);
        $sub_menu = array();
        if($menuArray){
            foreach($menuArray as $res){
                $sub_menu[$res['id']] = $res['name'];
            }

            $groupDropDown = $this->list_model->getDropdownOption($sub_menu, "sub_sub_menu");
        }

        echo json_encode($groupDropDown);
    }

    public function buildMenu($menu, $parentId = 0)
    {
        $result = null;
        foreach ($menu as $item)
            if ($item->parent_id == $parentId) {
                $result .= "<li class='dd-item nested-list-item' data-order='{$item->rang}' data-id='{$item->id}'>
	      <div class='dd-handle nested-list-handle'>
	        <span class='glyphicon glyphicon-move'></span>
	      </div>
	      <div class='nested-list-content'>{$item->name}
	        <div class='pull-right'>
              <a href='javascript:void(0)' class='add_menu_btn' data-href='menu/permissions/load_menu/".$item->id."'>Edit</a>  
	           |
              ".anchor(SITE_AREA."/menu/permissions/menu_delete/{$item->id}", "Delete", "onClick='return confirm(\"Are you sure?\")'")."
	        </div>
	      </div>".$this->buildMenu($menu, $item->id) . "</li>";
        }
        return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTML()
    {
        $menu = $this->menu_model->order_by('rang')->where('is_active', 1)->find_all();

        return $this->buildMenu($menu);
    }

    public function menuOrderSaved()
    {
        $source       = $_POST['source'];
        $destination  = $_POST['destination'];

        $this->menu_model->update($source, array('parent_id' => (int)$destination));

        $ordering       = json_decode($_POST['order']);
        $rootOrdering   = json_decode($_POST['rootOrder']);

        if($ordering){
            foreach($ordering as $order=>$item_id){
                $this->menu_model->update($item_id, array('rang' => $order));
            }
        } else {
            foreach($rootOrdering as $order=>$item_id){
                $this->menu_model->update($item_id, array('rang' => $order));
            }
        }

        echo 'ok';
    }

}//end class