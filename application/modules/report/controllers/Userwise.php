<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userwise extends Admin_Controller {

    public function __construct()
	{
        parent::__construct();
        
        $this->lang->load('userwise');
        // $this->load->model('emergency_ticket_model');
	}

    
    public function index()
    {
        $items = array();
        Template::set('items', $items);
        Template::set("toolbar_title", lang("userwise_report_title"));
        Template::set_view('userwise/report');
		Template::render();
    }
    

}