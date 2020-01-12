<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_Synchronisation extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() {		
        
		parent::__construct();
		
        $this->lang->load('common');
        $this->lang->load('data_synchronisation');
		  $this->load->model('punch_card_reader_configure_model', NULL, TRUE);
		Template::set_block('sub_nav', 'data_synchronisation/_sub_nav_data_synchronisation');		
	} // end construct 
	
	
	
			
    public function show_list() {
		
		Template::set('toolbar_title', lang(""));
        Template::set_view('/');
        Template::render();
		
    } //  end show_list function 
	
	
    public function create() {
        $data[''] 		= $this->input->post('data_sync_reader_model'); 
        $data[''] 		= $this->input->post('dload_form');	
        $data[''] 		= $this->input->post('dload_to'); 
		
		
		$SendData = array(
		'' => '',
		'' => '',
		'' => ''
		);
		
		$query = $this->db->select('rd.READER_MODEL_ID,rd.READER_MODEL_NAME',false)
								->from('hrm_reader_model_table as rd')
								->get();
		
		$reader_model_details = $query->result();
		Template::set('reader_model_details',$reader_model_details);
		Template::set('toolbar_title', lang("data_sync_title"));
        Template::set_view('data_synchronisation/data_synchronisation_form');
		Template::render();
		
    } // end create function 
    
	public function getVal(){
        
        $this->load->library('doctrine');
        $this->load->library('GetEmployeeListService');
		
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit 	= $this->settings_lib->item('site.list_limit');		
						
        //====== Load Static List value from Config ==========			
        $sex 				= $this->config->item('gender_list');	
        $empType 			= $this->config->item('emp_type');	
		
		
        //====== Set search value ==========	
        $src_emp[''] 		= $this->input->post(''); 
        $src_emp[''] 		= $this->input->post('');	
        $src_emp[''] 		= $this->input->post(''); 
        $src_emp[''] 		= $this->input->post('');
        $src_emp[''] 		= $this->input->post('');
		
		
		
        //====== Set search value ==========

        $GetEmployeePolicyTracker 		= new GetEmployeePolicyTracker($this);		
        $records 						= $GetEmployeePolicyTracker	
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


                     
                     
                     

