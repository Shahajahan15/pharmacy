<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Employee_transfer extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('employee_model', NULL, TRUE);
		$this->load->model('library/branch_info_model', NULL, TRUE);
		$this->load->model('employee_transfer_model', NULL, TRUE);		
		$this->lang->load('emp_transfer');		
		$this->lang->load('common');
		$this->load->config('config_employee_transfer');	
				
		Template::set_block('sub_nav', 'emp_transfer/_sub_nav_employee_transfer');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {		
	
		//$this->current_user->branch_id;
	
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('HRM.Employee.Transfer.Delete');
			$result = FALSE;

			$data = array();

			$data['IS_DELETED'] 		= 1; 
			$data['DELETED_BY']			= $this->current_user->id;	
			$data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $pid){
				
				$result = $this->employee_transfer_model->update($pid,$data);
				
			}
			
           
			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->employee_transfer_model->error, 'error');
			}
		}

		
		
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');		
						
		//====== Load Static List value from Config ==========			
		$transferReason			= $this->config->item('transfer_raeson');
		
		
		//====== Set search value ==========	TRANSFER_LETTER_NO		
		$TRANSFER_LETTER_NO 	= $this->input->post('TRANSFER_LETTER_NO'); 
		$EMP_ID 				= $this->input->post('empid'); 	
		$TRANSFER_DATE_START 	= $this->input->post('transfer_date_start'); 
		$TRANSFER_DATE_END		= $this->input->post('transfer_date_end'); 
		
		//====== Set search value ==========
		
		$getEmployeeListObj 		= new GetEmployeeTransferListService($this);		
		$records 					= $getEmployeeListObj	
									->setEmpId($EMP_ID)
									->setLetterNo($TRANSFER_LETTER_NO)
									->setStartDate($TRANSFER_DATE_START)
									->setEndtDate($TRANSFER_DATE_END)									
									->setLimit()
									->setOffset($offset)
									->execute();				
		
		$total = $getEmployeeListObj->getCount();
		
		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'records' 			=> $records,
			'transferReason' 	=> $transferReason		
			
			
		);
		
		
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("hrm_employee_transfer_list"));		
        Template::set_view('emp_transfer/employee_transfer_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function transfer_create()
    {
        //TODO you code here	
        if (isset($_POST['save']))
		{						
			
			$EMP_TRANSFER_ID = $this->saveTransfer_info();
			if($EMP_TRANSFER_ID > 0)
			{
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $EMP_TRANSFER_ID .' : '. $this->input->ip_address(), 'hrm_ls_transfer');
				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/employee_transfer/hrm/show_list');	
			}else
			{
				
				Template::set_message(lang('bf_msg_create_failure').$this->employee_transfer_model->error, 'error');
				
			}
				
			 				
		}
		
		$this->load->model('library/branch_info_model', NULL, true);		
		$this->load->model('library/designation_info_model', NULL, TRUE);	
		$this->load->model('library/department_model', NULL, TRUE);
		
		$transferReason			= $this->config->item('transfer_raeson');	
		
		$designation_list 	= $this->designation_info_model->find_all_by('IS_DELETED',0);	
		$work_station_list 	= $this->branch_info_model->find_all_by('IS_DELETED',0);		
		$department_list 	= $this->department_model->find_all_by('is_deleted',0);
	
		Template::set('department_list',$department_list);
		Template::set('transferReason',$transferReason);
		Template::set('work_station_list',$work_station_list);
		Template::set('designation_list',$designation_list);			
		Template::set('toolbar_title', lang("hrm_employee_transfer"));
        Template::set_view('emp_transfer/employee_transfer');
        Template::render();
    }
	
	
	
	public function getEmployeeDataAjax()
	{
		if( $employeeId = $this->input->post('employeeId'))
		{
			$this->db->select("	
								p.EMP_NAME AS employeeName,
								p.EMP_ID AS employeeId,
								adp.DESIGNATION_NAME AS designationName,
								adp.DESIGNATION_ID AS designationID,
								br.BRANCH_NAME AS branchName,	
								br.BRANCH_ID as branchId,
								dep.dept_id as departmentId,
								dep.department_name as departmentName
							");	
			
			
			$this->db->from('hrm_ls_employee as p');
			$this->db->where("p.EMP_ID","$employeeId");			
			$this->db->join('lib_designation_info as adp', 'adp.DESIGNATION_ID = p.EMP_DESIGNATION', 'left');			
			$this->db->join('lib_department as dep', 'dep.dept_id = p.EMP_DEPARTMENT', 'left');			
			$this->db->join('lib_hrm_branch_info as br', 'br.BRANCH_ID = p.PRESENT_BRANCH_ID', 'left');				
			$this->db->distinct("p.EMP_ID");

			$employeeData = $this->employee_model->find($employeeId);			
				
						
			
			$empName 	    		= $employeeData->employeeName;
			$empId 	    			= $employeeData->employeeId;
			$designition 	    	= $employeeData->designationName;			
			$branch 	    		= $employeeData->branchName;			
			$department_name 	    = $employeeData->departmentName;
			
			if( $empId > 0)
			{
				echo "									
					$('#employeeName').val('$empName');
					$('#EMP_ID').val('$empId');
					$('#DESIGNATION_NAME').val('$designition');					
					$('#PRESENT_BRANCH_NAME').val('$branch');					
					$('#PRESENT_DEPARTMENT_NAME').val('$department_name');	
						
				";	
			}
			
			
		}
	}
	
	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function transfer_edit()
	{
		$ID = $this->uri->segment(5);
		if (empty($ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/employee_transfer/hrm/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('HRM.Employee.Transfer.Edit');

			if ($this->saveTransfer_info('update', $ID))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $ID .' : '. $this->input->ip_address(), 'hrm_ls_transfer');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/employee_transfer/hrm/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->employee_transfer_model->error, 'error');
			}
		}
		
	
		$this->db->select("	
							em.EMP_ID,
							em.EMP_NAME,
							et.EMP_TRANSFER_ID,
							et.JOINNING_DATE_FROM,
							et.JOINNING_DATE_TO,
							et.TRANSFER_REASON,
							et.TRANSFER_LETTER_NO,							
							et.TRANSFER_BRANCH_ID,
							et.BEFORE_DEPARTMENT_ID,
							et.TRANSFER_DEPARTMENT_ID ,
							et.TRANSFER_DESIGNATION_ID,
							et.TRANSFER_REMARKS,							
							ed.DESIGNATION_NAME,
							et.BEFORE_BRANCH_ID,
							et.BEFORE_DESIGNATION_ID,
							br.BRANCH_NAME,
							dep.department_name
							
						");
						
		$this->db->from('hrm_ls_transfer as et');	
		$this->db->where("et.EMP_TRANSFER_ID", "$ID");
		$this->db->where("et.JOINING_DONE", 0);
		$this->db->join('hrm_ls_employee as em', 'em.EMP_ID = et.EMP_ID', 'left');		
		$this->db->join('lib_designation_info as ed', 'et.BEFORE_DESIGNATION_ID = ed.DESIGNATION_ID', 'left');
		$this->db->join('lib_hrm_branch_info as br', 'et.BEFORE_BRANCH_ID = br.BRANCH_ID', 'left');
		$this->db->join('lib_department as dep', 'et.BEFORE_DEPARTMENT_ID = dep.dept_id', 'left');
					
		$this->db->distinct("et.EMP_TRANSFER_ID");

		
		$emp_transfer_details = $this->employee_transfer_model->find($ID);			
		
		$this->load->model('library/branch_info_model', NULL, true);		
		$this->load->model('library/designation_info_model', NULL, TRUE);
		$this->load->model('library/department_model', NULL, TRUE);
		
		$transferReason		= $this->config->item('transfer_raeson');	
		
		$designation_list 	= $this->designation_info_model->find_all_by('IS_DELETED',0);	
		$work_station_list 	= $this->branch_info_model->find_all_by('IS_DELETED',0);
		$department_list 	= $this->department_model->find_all_by('is_deleted',0);
		
		Template::set('department_list',$department_list);
		Template::set('transferReason',$transferReason);
		Template::set('work_station_list',$work_station_list);
		Template::set('designation_list',$designation_list);
		Template::set('emp_transfer_details',$emp_transfer_details);
		Template::set('toolbar_title', lang('hrm_employee_transfer_update'));		
        Template::set_view('emp_transfer/employee_transfer');
		Template::render();
	}
	
	
	//  start TRANSFER LETTER NO  Check========	
	public function checkLetterNoAjax()
	{	
	
		$transferLetterNo	= $this->input->post('transferLetterNo');
		
			
		if(trim($transferLetterNo)!= '')
		{			
			$res =$this->db->query("SELECT TRANSFER_LETTER_NO FROM bf_hrm_ls_transfer WHERE  TRANSFER_LETTER_NO  LIKE '%$transferLetterNo%'");	
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Number Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}
	
	//  end TRANSFER LETTER NO =======
	
	
	
	
	//==============Save Transfer info =========//
	private function saveTransfer_info($type='insert', $id=0)	
	{		
		
		// make sure we only pass in the fields we want	
		
		if ($type == 'insert')
		{
            $this->auth->restrict('HRM.Employee.Transfer.Create');

			// 		  
			
			$TRANSFER_LETTER_NO 		= $this->input->post('TRANSFER_LETTER_NO');
			$EMP_ID						= $this->input->post('EMP_ID');				
			$BEFORE_BRANCH_ID			= $this->input->post('BEFORE_BRANCH_ID');
			$TRANSFER_BRANCH_ID			= $this->input->post('TRANSFER_BRANCH_ID');
			$BEFORE_DEPARTMENT_ID		= $this->input->post('BEFORE_DEPARTMENT_ID');
			$TRANSFER_DEPARTMENT_ID 	= $this->input->post('TRANSFER_DEPARTMENT_ID');
			$BEFORE_DESIGNATION_ID		= $this->input->post('BEFORE_DESIGNATION_ID');
			$TRANSFER_DESIGNATION_ID 	= $this->input->post('TRANSFER_DESIGNATION_ID');
			$TRANSFER_REASON			= $this->input->post('TRANSFER_REASON');
			$JOINNING_DATE_FROM	  		= $this->input->post('JOINNING_DATE_FROM');
			$JOINNING_DATE_TO	  	    = $this->input->post('JOINNING_DATE_TO');
			$TRANSFER_REMARKS 			= $this->input->post('TRANSFER_REMARKS');
			$CREATED_USER_BRANCH_ID		= $this->current_user->branch_id;	
			$CREATED_BY					= $this->current_user->id;	

			$get_length=count($TRANSFER_BRANCH_ID);
			$insertData = array();
			for($i=0; $i<$get_length; $i++)
			{
				$insertData[] = array(
					'TRANSFER_LETTER_NO' => $TRANSFER_LETTER_NO[$i],	
					'EMP_ID' => $EMP_ID,	
					'BEFORE_DESIGNATION_ID' => (int)$BEFORE_DESIGNATION_ID[$i],
					'TRANSFER_DESIGNATION_ID' => $TRANSFER_DESIGNATION_ID[$i],
					'BEFORE_DEPARTMENT_ID' => (int)$BEFORE_DEPARTMENT_ID[$i],
					'TRANSFER_DEPARTMENT_ID' => $TRANSFER_DEPARTMENT_ID[$i],
					'BEFORE_BRANCH_ID' => (int)$BEFORE_BRANCH_ID[$i],
					'TRANSFER_BRANCH_ID' => $TRANSFER_BRANCH_ID[$i],					
					'TRANSFER_REASON' => $TRANSFER_REASON[$i],
					'JOINNING_DATE_FROM' => $JOINNING_DATE_FROM[$i] ? date("Y-m-d", strtotime(str_replace('/', '-', $JOINNING_DATE_FROM[$i]))) : '0000-00-00',
					'JOINNING_DATE_TO' => $JOINNING_DATE_TO[$i] ? date("Y-m-d", strtotime(str_replace('/', '-', $JOINNING_DATE_TO[$i]))) : '0000-00-00',
					'TRANSFER_REMARKS' => $TRANSFER_REMARKS[$i],
					'CREATED_USER_BRANCH_ID' => (int)$CREATED_USER_BRANCH_ID,
					'CREATED_BY' => $CREATED_BY
				);
			}
		
			$return = $this->db->insert_batch('hrm_ls_transfer', $insertData);
			
			// update employee table ( EMP_DEPARTMENT, EMP_DESIGNATION, PRESENT_BRANCH_ID)  when transfer receive or complete.
			
							
		} elseif ($type == 'update')
		{
			$transfer_id = $id; // Get id from function argument	
			$this->auth->restrict('HRM.Employee.Transfer.Edit');
			/*
			$data = array();		
			$data['TRANSFER_LETTER_NO'] 		= $this->input->post('TRANSFER_LETTER_NO');
			$data['EMP_ID'] 					= $this->input->post('EMP_ID');
			$data['BEFORE_DESIGNATION_ID'] 		= $this->input->post('BEFORE_DESIGNATION_ID');
			$data['TRANSFER_DESIGNATION_ID'] 	= $this->input->post('TRANSFER_DESIGNATION_ID');			
			$data['BEFORE_DEPARTMENT_ID'] 		= $this->input->post('BEFORE_DEPARTMENT_ID');
			$data['TRANSFER_DEPARTMENT_ID'] 	= $this->input->post('TRANSFER_DEPARTMENT_ID');			
			$data['BEFORE_BRANCH_ID'] 			= $this->input->post('BEFORE_BRANCH_ID');
			$data['TRANSFER_BRANCH_ID'] 		= $this->input->post('TRANSFER_BRANCH_ID');
			$data['TRANSFER_REASON'] 			= $this->input->post('TRANSFER_REASON');
			$data['JOINNING_DATE_FROM'] 	  	= date("Y-m-d", strtotime($this->input->post('JOINNING_DATE_FROM')));
			$data['JOINNING_DATE_TO'] 	  	    = date("Y-m-d", strtotime($this->input->post('JOINNING_DATE_TO')));		
			$data['TRANSFER_REMARKS'] 			= $this->input->post('TRANSFER_REMARKS');			
			$data['CREATED_USER_BRANCH_ID'] 	= $this->current_user->branch_id;			
			$data['MODIFY_BY']					= $this->current_user->id;
			$data['MODIFY_DATE']				= date('Y-m-d H:i:s');
			$return  = $this->employee_transfer_model->update($transfer_id,$data);	
			*/
			
			//'EMP_TRANSFER_ID' => $transfer_id,
			
			$MODIFY_BY					= $this->current_user->id;
			$MODIFY_DATE				= date('Y-m-d H:i:s');		
			
			$get_length=count($TRANSFER_BRANCH_ID);		
			$insertData = array();			
			for($i=0; $i<$get_length; $i++)
			{				
				$insertData[] = array(	
					'TRANSFER_LETTER_NO' => $TRANSFER_LETTER_NO[$i],	
					'EMP_ID' => $EMP_ID,	
					'BEFORE_DESIGNATION_ID' => (int)$BEFORE_DESIGNATION_ID[$i],
					'TRANSFER_DESIGNATION_ID' => $TRANSFER_DESIGNATION_ID[$i],
					'BEFORE_DEPARTMENT_ID' => (int)$BEFORE_DEPARTMENT_ID[$i],
					'TRANSFER_DEPARTMENT_ID' => $TRANSFER_DEPARTMENT_ID[$i],
					'BEFORE_BRANCH_ID' => (int)$BEFORE_BRANCH_ID[$i],
					'TRANSFER_BRANCH_ID' => $TRANSFER_BRANCH_ID[$i],					
					'TRANSFER_REASON' => $TRANSFER_REASON[$i],
					'JOINNING_DATE_FROM' => $JOINNING_DATE_FROM[$i] ? date("Y-m-d", strtotime(str_replace('/', '-', $JOINNING_DATE_FROM[$i]))) : '0000-00-00',
					'JOINNING_DATE_TO' => $JOINNING_DATE_TO[$i] ? date("Y-m-d", strtotime(str_replace('/', '-', $JOINNING_DATE_TO[$i]))) : '0000-00-00',
					'TRANSFER_REMARKS' => $TRANSFER_REMARKS[$i],
					'CREATED_USER_BRANCH_ID' => (int)$CREATED_USER_BRANCH_ID,
					'MODIFY_BY' => $MODIFY_BY,
					'MODIFY_DATE' => $MODIFY_DATE
				);		
				
				
			}

				$this->db->set($insertData);
				$return = $this->db->update_batch('bf_hrm_ls_transfer', $insertData, 'EMP_TRANSFER_ID');
			
			//$return =  $this->employee_transfer_model->update_batch($insertData, $transfer_id);
		
			//$return = $this->db->update_batch('hrm_ls_transfer', $insertData);
			
			
			
			
		}		
		return $return;
	}	
	
	

}

