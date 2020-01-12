@php
    $this->load->library('session');
    $this->load->library('GetMenuService');
    $service = GetMenuService::getInstance($this);
    $menuItems = $service->getMenu($this->auth->role_id());

    echo $menuItems;
@endphp