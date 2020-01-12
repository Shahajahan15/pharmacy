<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Salary Head, controller
 */
 
class Salary_rules extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */	 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('base_head_model', NULL, TRUE);	
		$this->load->model('salary_rule_mst_model', NULL, TRUE);	
		$this->load->model('salary_rule_dtls_model',NULL, TRUE);
        $this->lang->load('salary_rules');	
		$this->lang->load('common');
		
		//Assets::add_module_css('library', 'salary_head.css');
		//Assets::add_module_js('library', 'salary_rules.js');
	}

  
    /**
     * salary head create
    */	 
    public function create()
    {    
		
		$salary_rule_list = $this->load->view('salary_rules/salary_rule_list', null, TRUE);	
		
		$salary_heads = $this->base_head_model->select('BASE_HEAD_ID,BASE_SYSTEM_HEAD')
		->find_all_by(array('IS_SALARY_HEAD'=>1,'IS_DELETED'=>0,'STATUS'=>1));	
		
		Template::set('salary_rule_list', $salary_rule_list);
		Template::set('salary_heads', $salary_heads);		
		Template::set('toolbar_title', lang("salary_rule_create"));
        Template::set_view('salary_rules/salary_rule_form');			
		Template::render();	
    }
	
	//====== Ajax Function of Salary head ====//	
	public function salaryRuleAjax()
	{		
		
		// make sure we only pass in the fields we want		       
		$mstData = array();
		$mstData['RULE_NAME']   		= $this->input->post('salaryRuleName');
		$mstData['RULE_DESCRIPTION']    = $this->input->post('ruleDescription');
		$mstData['STATUS']      		= $this->input->post('status');
		
		
		$detailsData 			= [];
		
		$mstId 					= $this->input->post('mstId');
		$detailsId 				= $this->input->post('detailsId');	
		$salaryHeadId 			= $this->input->post('salaryHeadId');					
		$percentageValue		= $this->input->post('percentageValue');		
		$fixedValue				= $this->input->post('fixedValue');
		
		if($mstId == '')
		{				
			$this->auth->restrict('library.salary_rule.Create');
			// Db insert transaction start
			$this->db->trans_begin();
			$mstData['CREATED_BY']			= $this->current_user->id;
			$returnMstId = $this->salary_rule_mst_model->insert($mstData);
			
			$salaryHeadIdLength    			= count($this->input->post('salaryHeadId'));	 
			
			for($i=0; $i<$salaryHeadIdLength; $i++)
			{
				$detailsData[]	= array(
					'MST_ID'			=> $returnMstId, 							
					'SALARY_HEAD_ID' 	=> $salaryHeadId[$i],					
					'PERCENTAGE'  		=> $percentageValue[$i]?$percentageValue[$i]:0,
					'FIXED'		  		=> $fixedValue[$i]?$fixedValue[$i] : 0
				);

			}

			$this->db->insert_batch('library_salary_rule_dtls', $detailsData);		
			
			if($this->db->trans_status()=== FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
		} 
		else if($mstId) 				
		{
			$mstData['MODIFY_BY'] = $this->current_user->id;
            $mstData['MODIFY_DATE'] = date('Y-m-d H:i:s');
			
            $this->salary_rule_mst_model->update($mstId,$mstData); // master part update 	

			$salaryHeadIdLength    			= count($this->input->post('salaryHeadId'));	 
			$detailsId						= $this->input->post('detailsId');	
			$detailsTableMstId				= $this->input->post('detailsTableMstId');
			
			for($i=0; $i<$salaryHeadIdLength; $i++)
			{
				$detailsData[]	= array(
					'DTLS_ID'			=> $detailsId[$i],
					'MST_ID'			=> $mstId, 							
					'SALARY_HEAD_ID' 	=> $salaryHeadId[$i],					
					'PERCENTAGE'  		=> $percentageValue[$i]?$percentageValue[$i]:0,
					'FIXED'		  		=> $fixedValue[$i]?$fixedValue[$i] : 0
				);				
			}			
			
			$this->db->update_batch('library_salary_rule_dtls',$detailsData,'DTLS_ID');
		}
																				
		$this->showRuleList(); // call showType function 														
	}
		
	// show records 
	public function showRuleList()
	{				
		$records = $this->salary_rule_mst_model->select('*')->find_all_by('IS_DELETED',0);
			
		echo $this->load->view('salary_rules/salary_rule_list', array('records' => $records));	
	}

	/* 
	/*Delete single or multiple salary Rule records 
	*/
	public function deleteTargetSalaryRule()
	{						
		$salaryRuleMstId		= $this->input->post('salaryRuleMstId');	
		$targetMultipleId		= $this->input->post('targetMultipleId');	
			
        if (is_array($targetMultipleId) && count($targetMultipleId)) 
		{
            $this->auth->restrict('library.salary_rule.Delete');
            $result = FALSE;
            $data = array();
            $data['IS_DELETED'] = 1;
            $data['DELETED_BY'] = $this->current_user->id;
            $data['DELETED_DATE'] = date('Y-m-d H:i:s');
			
            foreach ($targetMultipleId as $targetMultipleId) 
			{
                $result = $this->salary_rule_mst_model->update($targetMultipleId, $data);	
            }
			
            if ($result) 
			{
                Template::set_message(count($targetMultipleId) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
			{
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->salary_rule_mst_model->error, 'error');
            }		
			$this->showRuleList();
        }						
	}
	
	
	// ===== Show for editing salary Rule
	public function getSalaryRuleInfo()
	{	
		$this->auth->restrict('library.salary_rule.Edit');
		$salaryRuleMstId      = (int)$this->input->post('masterID');
		if($salaryRuleMstId)			
		{			
			$salaryRuleMstRecord 	= $this->salary_rule_mst_model->find($salaryRuleMstId);	
			$salaryRuleName 		= $salaryRuleMstRecord->RULE_NAME;
			$ruleDescription 		= $salaryRuleMstRecord->RULE_DESCRIPTION;
			$status 	    		= $salaryRuleMstRecord->STATUS;
			$mstId 	    			= $salaryRuleMstRecord->MST_ID;
			
			$returnData = array();
			
			$returnData['evalData'] = "			
				$('#rule_name').val('$salaryRuleName');
				$('#rule_description').val('$ruleDescription');
				$('#status').val('$status');
				$('#master_id').val('$mstId');			
			";
			
			$query = $this->db->select('salary_rule_dtls.*,lib_base_head_info.BASE_SYSTEM_HEAD')
			->from('library_salary_rule_dtls as salary_rule_dtls')
			->where('MST_ID',$salaryRuleMstId)
			->join('lib_base_head_info','lib_base_head_info.BASE_HEAD_ID=salary_rule_dtls.SALARY_HEAD_ID','left')
			->get();
			$result = $query->result_array();
			
			
			$salary_heads = $this->base_head_model->select('BASE_HEAD_ID,BASE_SYSTEM_HEAD')
			->find_all_by(array('IS_SALARY_HEAD'=>1,'IS_DELETED'=>0,'STATUS'=>1));	
	
			$detailsHtml = "";
			foreach($result as $row)
			{
				$data = array();					
				$data['result'] 					= $row;
				$data['salary_heads'] 				= $salary_heads;
				$data['detailsTableData'] 			= $detailsHtml == "" ? false : true;			
				$detailsHtml .= $this->load->view('salary_rules/salary_rule_details_form', $data, true);
			}
			
			$returnData['detailsHtml'] = $detailsHtml;			
			echo json_encode($returnData);
			exit;	   				
		}				
	}
	
	
} // End controller 

