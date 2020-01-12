<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Designation extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('designation_info_model', NULL, true);
        $this->lang->load('designation');	
		$this->lang->load('common');
			
		Template::set_block('sub_nav', 'designation/_sub_nav_designation');
	}

    /**
     * store company 
     */
	 
    public function show_list()
    {
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Designation.Delete');
			$result = FALSE;
			
			$data = array();

			$data['IS_DELETED'] 		= 1; 
			$data['deleted_by']			= $this->current_user->id;	
			$data['deleted_date']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $pid){
				
				$result = $this->designation_info_model->update($pid,$data);
				
			}

			if ($result)
			{
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->designation_info_model->error, 'error');
			}
		}
		
		$this->load->model('library/grade_info_model', NULL, TRUE);			
		 
		$this->db->select("
							di.DESIGNATION_NAME,
							di.DESIGNATION_NAME_BANGLA,
							gradeName.GRADE_NAME,
							di.DESIGNATION_ID,
							di.STATUS
							
						 ");
								 
		$this->db->from('lib_designation_info as di');			
		$this->db->where("di.IS_DELETED = ",0);			
		$this->db->join('lib_grade_info as gradeName', 'gradeName.GRADE_ID = di.GRADE_ID', 'left');			
		$this->db->distinct("di.DESIGNATION_ID");	
		
		$records 	= $this->designation_info_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_designation_view"));		
        Template::set_view('designation/designation_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function designation_create()
    {
        //TODO you code here
			

			if (isset($_POST['save']))
			{
				if ($insert_id = $this->save_designation_details())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_designation_info');

					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/designation/library/show_list');
				}
				else
				{
					Template::set_message(lang('bf_msg_create_failure').$this->designation_info_model->error, 'error');
				}
			}
			$this->load->model('library/grade_info_model', NULL, TRUE);
			$gradeName = $this->grade_info_model->find_all();
			
			$records = $this->designation_info_model->find_all();
		
		Template::set('records', $records);	
		Template::set('gradeName', $gradeName);
		
		Template::set('toolbar_title', lang("Library_designation_create"));
        Template::set_view('designation/designation_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function designation_edit()
	{
		$DESIGNATION_ID = $this->uri->segment(5);
		if (empty($DESIGNATION_ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/designation/library/show_list');
		}
			
		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Designation.Edit');

			if ($this->save_designation_details('update', $DESIGNATION_ID))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $DESIGNATION_ID .' : '. $this->input->ip_address(), 'lib_designation_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/designation/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->designation_info_model->error, 'error');
			}
		}
		
		$this->load->model('library/grade_info_model', NULL, TRUE);
		$gradeName = $this->grade_info_model->find_all();
		
		$this->db->select("
							di.DESIGNATION_NAME,						
							di.DESIGNATION_ID,
							di.DESIGNATION_NAME_BANGLA,
							di.GRADE_ID,
							di.STATUS							
						 ");
								 
			$this->db->from('lib_designation_info as di');			
			$this->db->where("di.DESIGNATION_ID","$DESIGNATION_ID");			
			$this->db->join('lib_grade_info as gradeName', 'gradeName.GRADE_ID = di.GRADE_ID', 'left');			
			$this->db->distinct("di.DESIGNATION_ID");	
		
			$lib_designation = $this->designation_info_model->find($DESIGNATION_ID);		
		
			Template::set('lib_designation',$lib_designation);
			Template::set('gradeName', $gradeName);			
			Template::set('toolbar_title', lang('library_designation_update'));		
			Template::set_view('designation/designation_create');
			Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_designation_details($type='insert', $DESIGNATION_ID=0)
	{
		if ($type == 'update'){
			$_POST['DESIGNATION_ID'] = $DESIGNATION_ID;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['DESIGNATION_NAME']       	= $this->input->post('library_designation_name');
		$data['DESIGNATION_NAME_BANGLA']    = $this->input->post('library_designation_name_bangla');
		$data['GRADE_ID']       			= $this->input->post('library_grade_name');	

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Designation.Create');
			$data['CREATED_BY'] 		    	= $this->current_user->id; 
			$data['STATUS']      		    	= $this->input->post('library_designation_status');
		      
			$DESIGNATION_ID = $this->designation_info_model->insert($data);
			//print($this->db->last_query()); die; 
			
			
			
			if (is_numeric($DESIGNATION_ID)){
				$return = $DESIGNATION_ID;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Designation.Edit');
			
			$data['MODIFIED_BY'] 		    = $this->current_user->id;    
			$data['MODIFIED_DATE'] 		    = date('Y-m-d H:i:s'); 
			$data['STATUS']      		    	= $this->input->post('library_designation_status');
			$return = $this->designation_info_model->update($DESIGNATION_ID, $data);
		}

		return $return;
	}
	
	
	public function checkDesignationNameAjax()
	{	
	
		$designationName			= $this->input->post('designationName'); 
		
		if(trim($designationName)!= '')
		{			
			$res =$this->db->query("SELECT DESIGNATION_NAME FROM bf_lib_designation_info WHERE  DESIGNATION_NAME  LIKE '%$designationName%'");	
			
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Department Name Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}	
	
	
}

