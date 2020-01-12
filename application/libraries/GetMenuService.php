<?php

class GetMenuService {

    private static $instance = null;
    private $context = null;

    public static function getInstance($context)
    {
        if (!self::$instance)
        {
            return self::$instance = new self($context);
        }

        return self::$instance;
    }

    private function __clone(){}

    private $connection = null;
    private $menuArray = array();

    public function __construct($context='')
    {
        //$this->connection = $context->doctrine->db;
        //$this->session = $context->session;
        $this->context = $context;
    }

    public function getMenu($roleId = 0)
    {
        if (!$roleId) {
            return redirect('/logout');
        }

        $adapter = $this->context->cache->get_adapter();

        $key = $this->context->input->ip_address() .'_menu_'. (int)$roleId;
        $data = "";
        if(ENVIRONMENT != 'development' && $this->context->cache->{$adapter}->is_supported()){
            $data = $this->context->cache->{$adapter}->get($key);
        }

        if ($data) {
            return $data;
        }

        $menus = $this->context->db->query("
            SELECT
              m.id,
              m.name,
              m.parent_id,
              m.parent_parent_id,
              IF(
                m.menu_link = '',
                '#',
                m.menu_link
              ) AS menu_link
            FROM
              bf_menu m,
              bf_menu_permissions mp
            WHERE m.id = mp.menu_id
              AND mp.role_id = $roleId
              AND m.is_active = 1
            GROUP BY m.id
            ORDER BY m.rang,m.id
        ");

        $this->menuArray = $menus->result_array();
        $this->removedEmptyMenu();
        $menu = $this->buildMenu(json_decode(json_encode($this->menuArray), false));

        // Save into the cache for 30 minutes
        if(ENVIRONMENT != 'development' && $this->context->cache->memcached->is_supported()){
            $this->context->cache->memcached->save($key, $menu, 7200);
        }

        return $menu;
    }

    public function buildMenu($menu, $parentId = 0, $subItem = 0) {
        $result = null;
        foreach ($menu as $item) {
            if ($item->parent_id == $parentId) {
                $link = $item->menu_link == '#' ? '#' : site_url().'/'.$item->menu_link;
                $dropdown = $item->menu_link == '#' ? 'treeview' : '';
                $result .= "<li class='" . $dropdown . "'>";
                if ($subItem == 1) {
                    $result .= $item->menu_link == '#' ? '<a href="#"><i class="fa fa-angle-double-right"></i><span>' . $item->name . '</span></a>' : '<a href="' . $link . '"><i class="fa fa-angle-double-right"></i>' . $item->name . '</a>';
                } else {
                    $result .= $item->menu_link == '#' ? '<a href="#"><i class="fa fa-bars"></i><span>' . $item->name . '</span><i class="fa fa-angle-left pull-right"></i></a>' : '<a href="' . $link . '">' . $item->name . '</a>';
                }

                $result .= $this->buildMenu($menu, $item->id, 1) . "</li>";
            }
        }

        if ($subItem) {
            return $result ? "<ul class='treeview-menu'>$result</ul>" : null;
        }

        return $result ? $result : null;
    }

    public function removedEmptyMenu () {        
        foreach ($this->menuArray as $key => $value) {
            if ($value['parent_id']) continue;
            $ct = 0;
            foreach ($this->menuArray as $val) {
                if ($value['id'] == $val['parent_id']) $ct++;
            }
            if ($ct < 1) {
                unset($this->menuArray[$key]);                
            }
        }
    }
}
