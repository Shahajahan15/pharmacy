<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance_Processing extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() {		
        
		parent::__construct();
	
        $this->lang->load('common');
        $this->lang->load('attendance_processing');
       
	Template::set_block('sub_nav', 'attendance_processing/_sub_nav_attendance_processing');		
	} // end construct 
	
    
    public function show_list() {
		
       
        Template::render();
		
    } //  end show_list function 
	
	/**
	 * Form data insert and update by calling save function.		
	*/
    public function create() {
        $data[''] 		= $this->input->post('date_from'); 
        $data[''] 		= $this->input->post('date_to');
		
		$SendData = array(
		'' => '',
		'' => ''
		);
        
		
        Template::set('toolbar_title', lang("atndnce_proces_title"));
        Template::set_view('attendance_processing/attendance_processing_form');
        Template::render();
		
    } // end create function 
    
	public function getVal(){
        
         
        
        $this->load->library('doctrine');
        $this->load->library('GetEmployeeListService');
		
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');		
						
        //====== Load Static List value from Config ==========			
        $sex 				= $this->config->item('gender_list');	
        $empType 			= $this->config->item('');	
		
		
        //====== Set search value ==========	
        $src_emp[''] 		= $this->input->post(''); 
        $src_emp[''] 		= $this->input->post('');	
        $src_emp[''] 	= $this->input->post(''); 
         $src_emp[''] 	= $this->input->post('');
         $src_emp[''] = $this->input->post('');
		
		
		
        //====== Set search value ==========

        $GetEmployeePolicyTracker 		= new GetEmployeePolicyTracker($this);		
        $records 					= $GetEmployeePolicyTracker	
                                                                ->setEmpId($src_emp[''])
                                                                ->setEmpName($src_emp[''])
                                                                ->setEmpDepartment($src_emp[''])
                                                                ->setWithPolicyType($src_emp[''])
                                                                 ->setWithoutPolicyType($src_emp[''])
                                                                ->setLimit($limit)
                                                                ->setOffset($offset)
                                                                ->execute();				

        $total = $GetEmployeePolicyTracker->getCount();
		
        $this->pager['base_url'] 			= current_url() .'?';
        $this->pager['total_rows'] 			= $total;
        $this->pager['per_page'] 			= $limit;
        $this->pager['page_query_string']	= TRUE;

        $this->pagination->initialize($this->pager);	

    
    }

	public function savePolicyDetails(){
			$this->load->model('',NULL,true);          
				
				$insertData = array();
			  
				  
				   for($i = 0; $i < $length ; $i++ ){
							$insertData [] = array(  
												
							   
						);
						   
			
					}
			
		   $return = $this->db->insert_batch('', $insertData);
		   
	}   
            
}// end controller


                     
                     
                     

