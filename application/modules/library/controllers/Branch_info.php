<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Branch_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('branch_info_model', NULL, true);
		$this->load->model('company_info_model', NULL, true);
		$this->lang->load('branch');
		$this->lang->load('common');
		$this->load->config('config_branch_info');	
				
		Template::set_block('sub_nav', 'branch_info/_sub_nav_branchinfo');
	}

    /**
     * company branch
    */
	 
    public function show_list()
    {
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.HRM.Branch.Delete');
			
			$result = FALSE;			
			$data = array();
			$data['IS_DELETED'] 		= 1; 
			$data['DELETED_BY']			= $this->current_user->id;	
			$data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $pid)
			{				
				$result = $this->branch_info_model->update($pid,$data);				
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $pid .' : '. $this->input->ip_address(), 'lib_hrm_branch_info');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->branch_info_model->error, 'error');
			}
		}
		
			
			$this->db->select("
								b.BRANCH_ID,
								b.COMPANY_ID,
								b.BRANCH_NAME,
								b.BRANCH_CATEGORY,
								b.BRANCH_ADDRESS,
								b.STATUS,
								c.company_name						
								
							 ");
							 
							 
			$this->db->from('lib_hrm_branch_info as b');			
			$this->db->where("b.IS_DELETED",0);			
			$this->db->join('lib_company_info as c', 'c.id = b.COMPANY_ID', 'left');			
			$this->db->distinct("b.BRANCH_ID");	

			$records = $this->branch_info_model->find_all();
			$branch_category	= $this->config->item('branch_category');	
			
		Template::set('records', $records);	
		Template::set('branch_category', $branch_category);	
		Template::set('toolbar_title', lang("library_branch_view"));		
        Template::set_view('branch_info/branch_info_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function branch_create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_branch_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_hrm_branch_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/branch_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->branch_info_model->error, 'error');
			}
        }
		
		
		$branch_category	= $this->config->item('branch_category');		
		$company 			= $this->company_info_model->find_all_by('IS_DELETED',0);
		
		Template::set('company',$company);
		Template::set('branch_category',$branch_category);
		Template::set('toolbar_title', lang("Library_branch_create"));
        Template::set_view('branch_info/branch_info_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function branch_edit()
	{
		$ID = $this->uri->segment(5);
		if (empty($ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/branch_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.HRM.Branch.Edit');

			if ($this->save_branch_details('update', $ID))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $ID .' : '. $this->input->ip_address(), 'lib_hrm_branch_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/branch_info/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->branch_info_model->error, 'error');
			}
		}
		
		$branch_category	= $this->config->item('branch_category');	
		
		$company 			= $this->company_info_model->find_all_by('IS_DELETED',0);
		$branch_details 	= $this->branch_info_model->find($ID);
		
		Template::set('branch_category', $branch_category);	
		Template::set('company',$company);
		Template::set('branch_details',$branch_details);		
		Template::set('toolbar_title', lang('library_branch_update'));		
        Template::set_view('branch_info/branch_info_create');
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_branch_details($type='insert', $ID=0)
	{
		if ($type == 'update'){
			$_POST['ID'] = $ID;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['COMPANY_ID']        		= $this->input->post('library_branch_company');
		$data['BRANCH_NAME']      		= $this->input->post('library_branch_branch');
		$data['BRANCH_NAME_BANGLA']     = $this->input->post('library_branch_branch_bangla');
		$data['BRANCH_CATEGORY']      	= $this->input->post('library_branch_category');
		$data['BRANCH_ADDRESS']      	= $this->input->post('library_branch_address');
		$data['BRANCH_ADDRESS_BANGLA']  = $this->input->post('library_branch_address_bangla');
		$data['DESCRIPTION']      		= $this->input->post('library_branch_description');
		$data['STATUS']      			= $this->input->post('library_branch_status');	     

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.HRM.Branch.Create');
			$data['CREATED_BY'] 		    = $this->current_user->id;  
			$ID = $this->branch_info_model->insert($data);
			
			if (is_numeric($ID)){
				$return = $ID;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){						
			$this->auth->restrict('Lib.HRM.Branch.Edit');
			
			$data['MODIFIED_BY'] 		    = $this->current_user->id;   
			$data['MODIFY_DATE'] 		    = date('Y-m-d H:i:s');   
			$return = $this->branch_info_model->update($ID, $data);
			
		}

		return $return;
	}
	
	
	public function checkBranchNameAjax()
	{	
	
		$branchName			= $this->input->post('branchName'); 
		$branchCompany		= $this->input->post('branchCompany'); 
		if(trim($branchName)!= '')
		{			
			$res =$this->db->query("SELECT BRANCH_NAME FROM bf_lib_hrm_branch_info WHERE  BRANCH_NAME  LIKE '%$branchName%' AND COMPANY_ID='$branchCompany'");	
			
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Branch Name Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}	
	
	
}

