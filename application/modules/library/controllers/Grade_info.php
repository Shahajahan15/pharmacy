<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Grade_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('grade_info_model', NULL, true);
		$this->lang->load('grade');
		$this->lang->load('common');
				
		Template::set_block('sub_nav', 'grade_info/_sub_nav_gradeinfo');
	}

    /**
     * store company 
     */
	 
    public function show_gradelist()
    {	$this->auth->restrict('Library.Grade.View'); 
		
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Grade.Delete');
			$result = FALSE;
			$data = array();
			$data['IS_DELETED'] 		= 1; 
			
            foreach ($checked as $pid)
			{				
				$result = $this->grade_info_model->update($pid,$data);				
			}
			
			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->grade_info_model->error, 'error');
			}
		}

        $records = $this->grade_info_model->find_all_by(['IS_DELETED'=>0]);
		
		Template::set('records', $records);	
		Template::set('toolbar_title', lang("library_grade_view"));		
        Template::set_view('grade_info/grade_info_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function grade_create()
    {
        //TODO you code here
        $this->auth->restrict('Library.Grade.Create'); 	

        if (isset($_POST['save']))
        {
			if ($insert_id = $this->save_grade_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_grade_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/grade_info/library/show_gradelist');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->grade_info_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_grade_create"));
        Template::set_view('grade_info/grade_info_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function grade_edit()
	{
		$this->auth->restrict('Library.Grade.Edit'); 
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/grade_info/library/show_gradelist');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Grade.Edit');

			if ($this->save_grade_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_grade_info');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/grade_info/library/show_gradelist');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->grade_info_model->error, 'error');
			}
		}
		
		
		$grade_details = $this->grade_info_model->find($id);		
		
		Template::set('grade_details',$grade_details);		
		Template::set('toolbar_title', lang('library_grade_update'));		
        Template::set_view('grade_info/grade_info_create');
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_grade_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['GRADE_NAME']        		= $this->input->post('library_grade_name');		
		$data['DESCRIPTION']        	= $this->input->post('library_grade_description');
		$data['STATUS']      		    = $this->input->post('bf_status');
		$data['CREATED_BY'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Grade.Create');
			$id = $this->grade_info_model->insert($data);
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Library.Grade.Edit');
			$data['MODIFIED_BY'] 		    = $this->current_user->id;    
			$data['MODIFIED_DATE'] 		    = date('Y-m-d H:i:s');    
			$return = $this->grade_info_model->update($id, $data);
		}

		return $return;
	}
	
	
	public function checkGradeNameAjax()
	{	
	
		$gradeName			= $this->input->post('gradeName'); 
		
		if(trim(gradeName)!= '')
		{			
			$res =$this->db->query("SELECT GRADE_NAME FROM bf_lib_grade_info WHERE  GRADE_NAME  LIKE '%$gradeName%'");	
			
			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Grade Name Already Exist !!';	
				
			}else
			{
			
			}			
			
		}	
	}	
	
	
	
	
}

