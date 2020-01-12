<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

 
class Salary_info extends Admin_Controller{
    public function __construct(){
		parent::__construct();    
	
		$this->load->model('salary_info_mst_model', NULL, TRUE);
		$this->load->model('salary_info_dtls_model', NULL, TRUE);
		$this->load->model('employee_model', NULL, TRUE);
		$this->load->model('library/base_head_model', NULL, TRUE);
		$this->load->model('library/salary_rule_mst_model', NULL, TRUE);
		$this->lang->load('common');
		$this->lang->load('salary_info');
		$this->load->library('session');
		
		//Assets::add_module_js('hrm', 'salary.js'); 
		
		Template::set_block('sub_nav', 'salary_info/_sub_nav_salary_info');
    }
    
	/*
	*
	*
	*/
    public function create()
	{
        $this->auth->restrict('hrm.salary_details.Create');
		
        $employee_details 	= $this->employee_model->find_all();
        $salary_heads 		= $this->base_head_model->select('BASE_HEAD_ID,BASE_SYSTEM_HEAD')->find_all_by($where=array('STATUS'=>1,'IS_DELETED'=>0));        
		$salaryRuleList 	= $this->salary_rule_mst_model->select('MST_ID,RULE_NAME')->find_all_by($where=array('STATUS'=>1,'IS_DELETED'=>0));
		       
		Template::set('toolbar_title', lang("employee_salary_info_create_heading"));
        Template::set('employee_details', $employee_details);
        Template::set('employee_details_manual', $employee_details);
        Template::set('salary_heads', $salary_heads);
		Template::set('salaryRuleList',$salaryRuleList);
        Template::set_view('salary_info/salary_info_form');
        Template::render();        
    }
    
	/*
	*
	*
	*/
    public function show_list()
	{
        $this->auth->restrict('hrm.salary_details.View');
        $checked = $this->input->post('checked');
		//  multiple delete start
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Subcategory.Store.Delete');
			$result = FALSE;
			foreach ($checked as $pid){
					$result = $this->salary_info_mst_model->delete($pid);
			}

			if ($result)
			{
				Template::set_message(count($checked) .' '. lang('account_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('account_delete_failure') . $this->salary_info_mst_model->error, 'error');
			}
		}
		//  multiple delete end 
		
        $query = $this->db->select('hrm_ls_employee.EMP_NAME,hrm_employee_salary_info_mst.*')
                  ->from('hrm_ls_employee')
                  ->join('hrm_employee_salary_info_mst','hrm_employee_salary_info_mst.EMP_ID = hrm_ls_employee.EMP_ID','inner')
                  ->get();
        $result = $query->result();
	
        Template::set('salary_info_mst_all', $result);
		Template::set('toolbar_title', lang("employee_salary_info_showlist_heading"));		
        Template::set_view('salary_info/employee_salary_info_list');
        Template::render();
    }
	
	/*
	* Details list
	*/
	public function detailsList()
	{				
		$mstId = $this->uri->segment(5);
		$detailsRecords = $this->db->select('salary_info_details.*,salary_head.SALARY_HEAD_NAME')
						->from('hrm_employee_salary_info_details as salary_info_details')
						->where('MST_ID',$mstId)
						->join('hrm_salary_head AS salary_head', 'salary_head.SALARY_HEAD_ID = salary_info_details.SALARY_HEAD','left')
						->distinct('salary_info_details.DETAILS_ID')
						->get()
						->result();
	
		Template::set('detailsRecords', $detailsRecords);
		Template::set('toolbar_title', lang("employee_salary_info_details_list"));
		Template::set_view('salary_info/salary_info_details_list');
		Template::render();
	}
	
	public function processingSalaryRule()
	{
		$salaryRuleMstId = $this->input->post('salaryRuleId');
		$salary_amount	 = $this->input->post('salary_amount');
		
		/*if($salaryRuleId !='' && !$mstMrrSrData)
		{	
			return false;
		}
		*/
		
		$query = $this->db->select('*')
		->from('library_salary_rule_dtls')
		->where('MST_ID',$salaryRuleMstId)
		->get();
				
		$result = $query->result_array();
		
		$salary_heads 		= $this->base_head_model->select('BASE_HEAD_ID,BASE_SYSTEM_HEAD')->find_all_by($where=array('STATUS'=>1,'IS_DELETED'=>0));
				
		$returnData = array();

		$detailsHtml = "";
		foreach($result as $row){
			$data = array();					
			$data['result'] 					= $row;
			$data['salary_amount']				= $salary_amount;
			$data['salary_heads']				= $salary_heads;
			$data['detailsTableData'] 			= $detailsHtml == "" ? false : true;			
			$detailsHtml .= $this->load->view('salary_info/salary_info_form_automatic', $data, true);
		}
		 
		$returnData['detailsHtml'] = $detailsHtml;
		
		echo json_encode($returnData);
		exit;	
	}
    
	/*
	*
	*
	*/
	public function saveSalaryDataAjax()
	{	
		$this->auth->restrict('hrm.salary_details.Create');
		
		// master table data start
		$data = array();
		$data['EMP_ID'] 		= $this->input->post('employeeId');
		$data['SALARY_AMOUNT']	= $this->input->post('salaryAmount');
		$data['SALARY_RULE']	= $this->input->post('salary_rule');
		$data['CREATED_BY']		= $this->current_user->id;
		// master table data end
		
		$targetMstId 			= $this->input->post('mstId'); // for updating record 
		$targetDetailsId 		= $this->input->post('detailsId'); // for updating record 
		
		$insertData = array();
		$employeeId			= $this->input->post('employeeId');
		$salaryHhead 		= $this->input->post('salaryHhead');
		$amountType 		= $this->input->post('amountType');
		$fixedValue 		= $this->input->post('fixedValue');	
		$percentageValue	= $this->input->post('percentageValue');	
		$calCulativeValue 	= $this->input->post('calCulativeValue');
		$CREATED_BY			= $this->current_user->id;	
		
		$salaryHheadLength             = count($salaryHhead );
		
		if (trim($targetMstId) == '') 
		{	
			
			// insert transaction start
			$this->db->trans_begin(); 	
			
			$returnMstId = $this->salary_info_mst_model->insert($data);	
			
			for($i=0; $i<$salaryHheadLength ; $i++) 
			{			
				$insertData[] = array(  
										'MST_ID'			=> $returnMstId,									
										'SALARY_HEAD' 		=> $salaryHhead[$i],
										'AMOUNT_TYPE' 		=> $amountType[$i],
										'FIXED_VALUE' 		=> ($fixedValue[$i])?$fixedValue[$i]:0,
										'PERCENTAGE_VALUE'  => ($percentageValue[$i])?$percentageValue[$i]:0,
										'CALCULATIVE_VALUE' => $calCulativeValue[$i],
										'CREATED_BY'	 	=> $CREATED_BY
				);
			}
			
			$this->db->insert_batch('hrm_employee_salary_info_details', $insertData);	
			
			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			} 
			else
			{
				$this->db->trans_commit();				
			}
			// insert db transaction end
			
		} else if ($targetMstId !== '' && $targetDetailsId) 
		{
			
			$data['MODIFY_DATE'] = date('Y-m-d H:i:s');
			$data['MODIFY_BY'] 	 = $this->current_user->id;
			
			$this->salary_info_mst_model->update($targetMstId,$data);
			
			$this->salary_info_dtls_model->delete('MST_ID');
			
			$this->db->delete('hrm_employee_salary_info_details', array('MST_ID' => $targetMstId )); // Delete existing records instead of update and newly insert record 
			
			for($i=0; $i<$salaryHheadLength ; $i++) 
			{			
				$insertData[] = array(  
										'MST_ID'			=> $targetMstId,									
										'SALARY_HEAD' 		=> $salaryHhead[$i],
										'AMOUNT_TYPE' 		=> $amountType[$i],
										'FIXED_VALUE' 		=> ($fixedValue[$i])?$fixedValue[$i]:0,
										'PERCENTAGE_VALUE'  => ($percentageValue[$i])?$percentageValue[$i]:0,
										'CALCULATIVE_VALUE' => $calCulativeValue[$i],
										'CREATED_BY'	 	=> $CREATED_BY
				);
			}
			
			$this->db->insert_batch('hrm_employee_salary_info_details', $insertData);	
			
			// detail have to update
				
		} 	
		// master table part end	
				
	}
    

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$ID = $this->uri->segment(5);
		if (empty($ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/salary_info/hrm/show_list');
		}

		$targetMasterRecords  = $this->salary_info_mst_model->find($ID); // record target to update from master table	
		$targetDetailsRecords = $this->salary_info_dtls_model->find_all_by('MST_ID',$ID); // record target to update from master table	
		
		
		$salaryRuleList 	= $this->salary_rule_mst_model->select('MST_ID,RULE_NAME')->find_all_by($where=array('STATUS'=>1,'IS_DELETED'=>0));
		
		$employee_details= $this->employee_model->find_all();
        $salary_heads= $this->base_head_model->find_all();
                       
		Template::set('toolbar_title', lang("employee_salary_info_Update_heading"));
		Template::set('targetMasterRecords', $targetMasterRecords);
		Template::set('targetDetailsRecords', $targetDetailsRecords);
        Template::set('employee_details', $employee_details);       
        Template::set('salary_heads', $salary_heads);
		Template::set('salaryRuleList', $salaryRuleList);
        Template::set_view('salary_info/salary_info_form');
        Template::render();   		
	}
   
    
} // end controller