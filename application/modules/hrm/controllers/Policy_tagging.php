<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Policy_tagging extends Admin_Controller 
{
	
	/**
	 * Constructor
	 *
	 * @return void
	*/	 
    public function __construct() 
	{		
        parent::__construct();       	
        $this->load->model('absent_leave_mst_model', NULL, true);
        $this->load->model('maternity_leave_model', NULL, true);
        $this->load->model('medical_policy_master_model',NULL,true);
        $this->load->model('policy_bonus_master_model',NULL,true);
        $this->load->model('policy_leave_mst_model',NULL,true);
        $this->load->model('policy_shift_model',NULL,true);
        $this->load->model('policy_shift_model',NULL,true);
        $this->load->model('library/department_model',NULL,true);            
        $this->load->model('employee_model',NULL,true);      
        $this->load->model('policy_tagging_model',NULL,true);
        $this->load->model('policy_roster_model', NULL, true);
		
		$this->lang->load('common');
        $this->lang->load('policy_tagging');
		
        $this->load->library('hrm/GetEmployeePolicyTagging',NULL,true);  
        $this->load->config('config_policy_tagging');
        
        Assets::add_module_js('hrm', 'policy_tagging.js');
		Assets::add_module_css('hrm', 'policy_tagging.css');
      
	} // end construct 
	

		
	/**
	 * Form data insert and update by calling save function.		
	*/
    public function create()
	{
        $this->auth->restrict('HRM.Policy_Tracker.Create'); 

        $con = [];
        $data = [];
        $this->load->library('pagination');

        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page'])?$this->input->post('per_page') : 25;

        $search_box = $this->searchpanel->getSearchBox($this->current_user); 

        $search_box['from_date_flag'] = 0;
        $search_box['to_date_flag'] = 0;
        $search_box['employee_name_flag'] = 1;
        $search_box['designation_list_flag'] = 1;
        $search_box['department_test_list_flag'] = 1;
        $search_box['empType_list_flag'] = 1;
        $search_box['hrm_policy_type_with_list_flag'] = 1;
        $search_box['hrm_policy_type_without_list_flag'] = 1;
        $search_box['empType_list_flag'] = 1;
        $search_box['employee_code_flag'] = 1;

        //echo "<pre>";print_r($search_box);exit;     
       
		$data = $this->policy_tagging_model->getPolicy();

		/*        query        */
		$data['records'] = $this->policy_tagging_model->getEmployeePolicyTaggingList($limit, $offset, $con);

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
       
        $this->pager['base_url'] = site_url() . '/admin/policy_tagging/hrm/create' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);

        $list_view ='policy_tagging/policy_tagging_form';
        $data ['toolbar_title'] = lang("hrm_policy_tagging");
        $data['search_box'] = $search_box;

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }

		Template::set($data);
		Template::set('list_view', $list_view);       
		Template::set_view('report_template');
		Template::render();
		
    } // end create function 
    
	public function getEmployeeSearchAjax()
	{      
        
         //load library	// Pagination
        $this->load->library('doctrine');	
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');		
						
        //====== Load Static List value from Config ==========			
        $sex 				= $this->config->item('gender_list');	
        $empType 			= $this->config->item('emp_type');	
		
		
        //====== Set search value ==========	
        $src_emp['EMP_ID'] 					= $this->input->post('empId'); 
        $src_emp['EMP_NAME'] 				= $this->input->post('empName');	
        $src_emp['department_name'] 		= $this->input->post('empDept'); 
        $src_emp['WITH_POLICY_TYPE'] 		= $this->input->post('empWithPolicy');
        $src_emp['WITHOUT_POLICY_TYPE'] 	= $this->input->post('empWithoutPolicy');		
		
		
        //====== Set search value ==========
        $GetEmployeePolicyTagging 		= new GetEmployeePolicyTagging($this);		
        $records 						= $GetEmployeePolicyTagging	
                                                                ->setEmpId($src_emp['EMP_ID'])
                                                                ->setEmpName($src_emp['EMP_NAME'])
                                                                ->setEmpDepartment($src_emp['department_name'])
                                                                ->setWithPolicyType((int)$src_emp['WITH_POLICY_TYPE'])
                                                                 ->setWithoutPolicyType((int)$src_emp['WITHOUT_POLICY_TYPE'])
                                                                ->setLimit($limit)
                                                                ->setOffset($offset)
                                                                ->execute();				

        $total = $GetEmployeePolicyTagging->getCount();
		
        $this->pager['base_url'] 			= current_url() .'?';
        $this->pager['total_rows'] 			= $total;
        $this->pager['per_page'] 			= $limit;
        $this->pager['page_query_string']	= TRUE;

        $this->pagination->initialize($this->pager);
        
        foreach($records as $record)
        {
            
			?>
		
			<tr>
				<td class="column-check"><input type="checkbox" name="checked[]" value="<?php echo  $record['EMP_ID'];?>" class="empCheckid" /></td>
				<td><?php echo $record['EMP_NAME'].'=>'.$record['EMP_ID']; ?></td>				
				<td><?php echo $record['department_name'];  ?></td>
				<td><?php echo $record['LEAVE_POLICY_NAME']; ?></td>
				<td><?php echo $record['ABSENT_POLICY_NAME']; ?></td>
				<td><?php echo $record['MATERNITY_POLICY_NAME']; ?></td>
				<td><?php echo $record['MEDICAL_POLICY_NAME']; ?></td>
				<td><?php echo $record['SHIFT_NAME']; ?></td>
				<td><?php echo $record['NAME']; ?></td>
			</tr>	  
		
			<?php  
		}         

    }

	public function savePolicyDetailsAjax()
	{
		//echo '<pre>';print_r($_POST);exit;
		$this->auth->restrict('HRM.Policy_Tracker.Create');

        $this->load->model('policy_tagging_model',NULL,true);           
            
		$EMP_ID = $this->input->post('empId'); 		
		$EMP_IDs = explode(',',$EMP_ID);		
		$POLICY_ID = $this->input->post('policyId');		
		$POLICY_IDs = explode(',',$POLICY_ID);		
		$POLICY_TYPE = $this->input->post('policyType');
		$POLICY_TYPEs = explode(',',$POLICY_TYPE);
		$shift_roster = $this->input->post('shift_roster');
		$CREATED_BY = $this->current_user->id;
		$CREATED_DATE = date('Y-m-d h:i:s');
		$STATUS = '1';
		$length = count($POLICY_IDs);
		$this->db->trans_begin();
          
		foreach($EMP_IDs as $EMP_IDD)
		{
			$insertData = array();
			for($i = 0; $i < $length ; $i++ )
			{
				$policy_type = $POLICY_TYPEs[$i];
				$policy_id = $POLICY_IDs[$i];

				$where = ['EMP_ID' =>$EMP_IDD, 'POLICY_TYPE' => $policy_type];
				$this->policy_tagging_model->delete_where($where);	
				
				$insertData [] = array(  	
					'EMP_ID' =>$EMP_IDD,
					'POLICY_ID' => $policy_id,
					'POLICY_TYPE' => $policy_type,
					'CREATED_BY' => $CREATED_BY,
					'CREATED_DATE' => $CREATED_DATE,
					'STATUS' => $STATUS
				);
			}
			$return = $this->db->insert_batch('hrm_policy_tagging', $insertData);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
				echo json_encode(array('success' => false,'message' => "Error"));
			} else {
				$this->db->trans_commit();
				echo json_encode(array('success' => true,'message' => 'Successfully done'));
			}
			exit;		
		
	}  

	public function getShiftByShiftType()
	{
		$policyType = $this->input->post("shift_type", true);
		if ($policyType == 1)  {
			$where =['STATUS'=>1, 'IS_DELETED' => 0, 'SHIFT_TYPE' => 1];
							$this->db->select('SHIFT_POLICY_ID as id, SHIFT_NAME as name');
			$shift_list 	= $this->policy_shift_model->find_all_by($where);
		} else {
			$where =['STATUS'=>1, 'IS_DELETED' => 0];
			$this->db->select('ROSTER_POLICY_ID as id, CONCAT(ROSTER_POLICY_NAME, "(", AFTER_CHANGE_DAY,")") AS name');
			$shift_list = $this->policy_roster_model->find_all_by($where);
		}
		$row = '';
		$row .= '<select class="form-control a" name="shifting_policy" id="shifting_policy">';
		$row .= 'option value="">Select One</option>';
		if ($shift_list) {
			foreach ($shift_list as $key => $val) {
				$row .= '<option value="'.$val->id.'">'.$val->name.'</option>';
			}
		}
		$row .= '</select>';
		echo $row;
	} 

}// end controller


                     
                     
                     

