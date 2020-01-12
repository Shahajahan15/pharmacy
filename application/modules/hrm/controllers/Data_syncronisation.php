<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_Syncronisation extends Admin_Controller 
{
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() 
	{		
        
		parent::__construct();
		
        $this->lang->load('common');
        $this->lang->load('data_syncronisation');
		
		Template::set_block('sub_nav', 'data_syncronisation/_sub_nav_data_syncronisation');		
	} // end construct 
	
	
	
			
    public function show_list() {
		
		Template::set('toolbar_title', lang("data_sync_title"));
        Template::set_view('data_syncronisation/data_syncronisation_form');
        Template::render();
		
    } //  end show_list function 
	
	
    public function create() 
	{
        
		Template::set('toolbar_title', lang("data_sync_title"));
        Template::set_view('data_syncronisation/data_syncronisation_form');
        Template::render();
		
    } // end create function 
    
	
	
}// end controller


                     
                     
                     

