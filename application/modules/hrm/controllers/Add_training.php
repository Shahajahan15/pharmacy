<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_Training extends Admin_Controller {
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() {		
        
		parent::__construct();
		  
        $this->lang->load('training');
        $this->lang->load('common');
        $this->lang->load('employee');
		
		
		
		
		
		Template::set_block('sub_nav', 'add_training/_sub_nav_add_training');		
	} // end construct 
	
    
  
	
	/**
	 * Form data insert and update by calling save function.		
	*/
    public function create() {
		
		
		$this->load->model('library/designation_info_model', NULL, true);
		$this->load->model('library/department_model', NULL, true);
		$this->load->model('library/training_type_model', NULL, true);
		
		
		if(isset($_POST['save'])){ 
			
			if($insert_id = $this->save_details())
			{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'employee_training_info');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/add_training/hrm/create');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->add_training_info_model->error, 'error');
				redirect(SITE_AREA .'/add_training/hrm/create');
			}
		}   
		
		
        
		$designation_details	= $this->designation_info_model->find_all();
		$department_details 	= $this->department_model->find_all();
		$employees_trainings	= $this->training_type_model->find_all();
		
		$SendData = array
		(
			
			'designation_details' 	=> $designation_details,
			'department_details' 	=> $department_details,
			'employees_trainings' 	=> $employees_trainings
		
		); 
		
		
       
        Template::set('toolbar_title', lang("training_page_title"));
		Template::set('SendData', $SendData );
        Template::set_view('add_training/add_training_form');
        Template::render();
		
    } // end create function 
	public function show_list() 
	{
		  
		$this->load->model('add_training_info_model', NULL, true);  
	  
		$checked = $this->input->post('checked');
		
		 
		
		 
		if (is_array($checked) && count($checked)) 
		{
            $this->auth->restrict('HRM.Add_Training.Delete');
            $result = FALSE;
            $data = array();
            $data['IS_DELETED'] = 1;
            $data['DELETED_BY'] = $this->current_user->id;
            $data['DELETED_DATE'] = date('Y-m-d h:i:s');
			
			
			foreach ($checked as $empId) 
			{
				$deleteResult = $this->add_training_info_model->update($empId,$data);
			}
			
				
           if ($deleteResult) {
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else {
                Template::set_message(lang('delete_message') . $this->add_training_info_model->error, 'error');
            } 
        } 
				
		
		$query = $this->db->select('
									etr.EMPLOYEE_TRAINING_INFO_ID, 
									emp.EMP_NAME,
									tt.TRAINING_TYPE_NAME,
									eb.BRANCH_NAME
									')
									
									->from('employee_training_info as etr')
									->join('hrm_ls_employee as emp','etr.EMP_ID = emp.EMP_ID','left')
									->join('lib_training_type as tt','etr.TRAINING_ID = tt.TRAINING_TYPE_ID','left')
									->join('lib_hrm_branch_info as eb','etr.BRANCH_ID = eb.BRANCH_ID','left')								 
									->where('etr.IS_DELETED',0)							
									->get();
								
		$emp_training_details = $query->result();
		
		Template::set('toolbar_title', lang("training_list_page_title"));
		Template::set('emp_training_details', $emp_training_details);
        Template::set_view('add_training/add_training_list');
        Template::render();
		
    } //  end show_list function 
	
	
	
	
	
	public function getEmployeeSearchAjax(){
        
         
        
       
		
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');		
						
        //====== Load Static List value from Config ==========			
        $sex 				= $this->config->item('gender_list');	
        $empType 			= $this->config->item('');	
		
		
        //====== Set search value ==========
		
        $src_emp['empId']						= $this->input->post('empId');
		$src_emp['empName'] 					= $this->input->post('empName'); 
       	$src_emp['empDesignation'] 				= $this->input->post('empDesignation');
		$src_emp['empDept'] 					= $this->input->post('empDept');
        
		
		
		
        //====== Set search value ==========

        $GetEmployeeAddTraining 		= new GetEmployeeAddTraining($this);		
        $records 							= $GetEmployeeAddTraining	
                                                                ->setEmpId($src_emp['empId'])
																->setEmpName($src_emp['empName'])
																->setEmpDesignation($src_emp['empDesignation'])
                                                                ->setEmpDepartment($src_emp['empDept'])
                                                                ->setLimit($limit)
                                                                ->setOffset($offset)
                                                                ->execute();				

        $total = $GetEmployeeAddTraining->getCount();
		
        $this->pager['base_url'] 			= current_url() .'?';
        $this->pager['total_rows'] 			= $total;
        $this->pager['per_page'] 			= $limit;
        $this->pager['page_query_string']	= TRUE;
		

        $this->pagination->initialize($this->pager);
		
		
		foreach($records as $record)
            {
            
            ?>
		
				<tr>
					<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo $record['EMP_ID'] ?>" class="" /></td>
					<td> <?php echo $record['EMP_NAME'] ?></td>				
					<td><?php echo $record['DESIGNATION_NAME'] ?></td>
					<td><?php echo $record['division_name'] ?></td>
					<td><?php echo $record['department_name'] ?></td>
				</tr>
			
			<?php   }
		 
		
    
    }//end of getEmployeeSearchAjax function 

	
	
	
	private function save_details($type='insert', $id=0)
	{		
	
           
		$current_user	 = $this->current_user->id;
		$mills_id		 = $this->current_user->branch_id;		
		
		
		$employee_id 				= $this->input->post('checked');
		$trainings_of_employees 	= $this->input->post('trainings_of_employees');
		
		$insertData = array();
		
			if($type == 'insert')
			{
				$this->auth->restrict('HRM.Add_Training.Create');
				if (is_array($employee_id) && count($employee_id))
				{
					
					foreach ($employee_id as $pid) 
					{
						foreach ($trainings_of_employees as $trainemp)
						{
							$insertData[] = array
							( 
								'EMP_ID' 					=> $pid,
								'TRAINING_ID'				=> $trainemp,
								'BRANCH_ID' 				=> $mills_id,
								'CREATED_BY'				=> $current_user,
							);						
						}
						
						
					}
				}
				
			$mstId = $this->db->insert_batch('bf_employee_training_info',$insertData);
			return $mstId; 
				
            }
			
			
			
			
	} // end function save_details
            
}// end controller


                     
                     
                     

