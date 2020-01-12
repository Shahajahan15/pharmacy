<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Branch_wise_post extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('branch_wise_post_model', NULL, true);		
		$this->lang->load('branch_wise_post');
		$this->lang->load('common');
		
		Template::set_block('sub_nav', 'branch_wise_post/_sub_nav_branch_wise_post');
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
			$this->auth->restrict('HRM.Branch_wise_post.Delete');
			
			$result = FALSE;
			
			$data = array();

			$data['IS_DELETED'] 		= 1; 
			$data['DELETED_BY']			= $this->current_user->id;	
			$data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $pid)
			{				
				$result = $this->branch_wise_post_model->update($pid,$data);				
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $pid .' : '. $this->input->ip_address(), 'hrm_branch_wise_post');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->branch_wise_post_model->error, 'error');
			}
		}
		
			
			$query = $this->db->select("																
							b.BRANCH_NAME,
							dep.department_name,
							deg.DESIGNATION_NAME,
							bwp.SEX,
							bwp.NUMBER_OF_POST,
							bwp.BRANCH_WISE_POST_ID									
								
							 ");
							 
							 
			$this->db->from('hrm_branch_wise_post AS bwp');			
			$this->db->where("bwp.IS_DELETED",0);			
			$this->db->join('lib_hrm_branch_info AS b', 'b.BRANCH_ID = bwp.BRANCH_ID', 'left');	
			$this->db->join('lib_department AS dep', 'dep.dept_id= bwp.DEPARTMENT_ID', 'left');
			$this->db->join('lib_designation_info AS deg', 'deg.DESIGNATION_ID = bwp.DESIGNATION_ID', 'left');
			$this->db->distinct("bwp.BRANCH_WISE_POST_ID");			
			$records = $this->branch_wise_post_model->find_all();
			
			
		Template::set('records', $records);			
		Template::set('toolbar_title', lang("Lib_branch_wise_post_view"));		
        Template::set_view('branch_wise_post/branch_wise_post_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create_post()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'hrm_branch_wise_post');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/branch_wise_post/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->branch_wise_post_model->error, 'error');
			}
        }		
			

		$this->load->model('department_model', NULL, true);	
		$this->load->model('designation_info_model', NULL, true);	
		$this->load->model('branch_info_model', NULL, true);	
		
		$department 		= $this->department_model->find_all_by('is_deleted', 0);
		$branch			 	= $this->branch_info_model->find_all_by('IS_DELETED', 0);
		$designation		= $this->designation_info_model->find_all_by('IS_DELETED', 0);
		
		$sendData = array
		(
			'department' 		=> $department,
			'branch' 			=> $branch,
			'designation' 		=> $designation
		
		); 
		
		
		Template::set('sendData',$sendData);
		Template::set('toolbar_title', lang("Lib_branch_wise_post_create"));
        Template::set_view('branch_wise_post/branch_wise_post');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/branch_wise_post/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('HRM.Branch_wise_post.Edit');

			if ($this->save_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'hrm_branch_wise_post');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/branch_wise_post/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->branch_wise_post_model->error, 'error');
			}
		}
				
		
		$this->load->model('department_model', NULL, true);	
		$this->load->model('designation_info_model', NULL, true);	
		$this->load->model('branch_info_model', NULL, true);	
		
		$department 		= $this->department_model->find_all_by('is_deleted', 0);
		$branch			 	= $this->branch_info_model->find_all_by('IS_DELETED', 0);
		$designation		= $this->designation_info_model->find_all_by('IS_DELETED', 0);
		
		
		$postDetails = $this->branch_wise_post_model->find($id);
		
		$sendData = array
		(
			'department' 		=> $department,
			'branch' 			=> $branch,
			'designation' 		=> $designation,
			'postDetails' 		=> $postDetails
		
		); 
		
		
		Template::set('sendData',$sendData);
		Template::set('toolbar_title', lang('Lib_branch_wise_post_update'));		
        Template::set_view('branch_wise_post/branch_wise_post');
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_details($type='insert', $ID=0)
	{
		// make sure we only pass in the fields we want		
		$data = array();
		$data['BRANCH_ID']        		= (int)$this->input->post('library_branch_branch');
		$data['DEPARTMENT_ID']      	= (int)$this->input->post('library_branch_department');
		$data['DESIGNATION_ID']     	= (int)$this->input->post('library_branch_designation');
		$data['SEX']     				= (int)$this->input->post('library_sex');
		$data['NUMBER_OF_POST']      	= (int)$this->input->post('library_number_of_post');		
	

		if ($type == 'insert')
		{	
			$this->auth->restrict('HRM.Branch_wise_post.Create');
			
			
			$res =$this->db->query("
								SELECT
									*
								FROM 
									bf_hrm_branch_wise_post
								WHERE 
									BRANCH_ID ='".(int)$this->input->post('library_branch_branch')."'
								AND 
									DEPARTMENT_ID ='".(int)$this->input->post('library_branch_department')."'
								AND 
									DESIGNATION_ID ='".(int)$this->input->post('library_branch_designation')."'
								AND 
									SEX ='".(int)$this->input->post('library_sex')."'							
							
								");		
							
			$result = $res->num_rows();
			if($result > 0 )
			{
				Template::set_message(lang('bf_msg_edit_failure'), 'error');
				
			}else
			{			
				$data['CREATED_BY'] 		    = $this->current_user->id;  
				$return = $this->branch_wise_post_model->insert($data);	
				
			}
			
		}
		elseif ($type == 'update')
		{
			$this->auth->restrict('HRM.Branch_wise_post.Edit');
			$data['MODIFIED_BY'] 		    = $this->current_user->id;
			$data['MODIFIED_DATE'] 		    = date('Y-m-d H:i:s');
			$return = $this->branch_wise_post_model->update($ID, $data);
		}

		return $return;
	}
	
	
	
	
	public function checkNumberOfpostAjax()	
	{		
		 
		 
		$sex					= (int)$this->input->post('sex');
		$designatioId			= (int)$this->input->post('designatioId'); 
		$departmentId			= (int)$this->input->post('departmentId'); 
		$branchId				= (int)$this->input->post('branchId'); 
		
		if(trim($sex)!= '')
		{			
			
			$res =$this->db->query("
								SELECT
									*
								FROM 
									bf_hrm_branch_wise_post
								WHERE 
									BRANCH_ID ='$branchId' 
								AND 
									DEPARTMENT_ID ='$departmentId' 
								AND 
									DESIGNATION_ID ='$designatioId' 
								AND 
									SEX ='$sex' 							
							
								");		
							
			$result = $res->num_rows();
		
			if($result > 0 )
			{
				echo $result;
				
			}else
			{
				echo false;
			}			
			
		}	
	}	
	
}

