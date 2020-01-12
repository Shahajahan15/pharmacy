<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*----------------Exam-----------------*/
class Exam extends Admin_Controller{

	/**
	* Constructor
	*
	* @return void
	*/
	 
	public function __construct()
	{
		parent::__construct();	
		$this->load->model('exam_model', NULL, TRUE);
		$this->lang->load('exam');
		$this->lang->load('common');
		
		Template::set_block('sub_nav', 'exam/_sub_nav_exam');
	}
	
	/*===================Show Records ===========================*/
	/**
	* Displays a list of form data.
	*
	* @return void
	**/
	 
	public function show_list()
	{		
		$this->auth->restrict('Lib.Exam.View');
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Exam.Delete');
			$result = FALSE;
			foreach ($checked as $id)
			{
				$result = $this->exam_model->delete($id);
			}
			
			if ($result)
			{	// Log the activity
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'library_exam');						
				Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
				Template::set_message(lang('bf_msg_delete_failure') . $this->exam_model->error, 'error');
			}
		}
		
		$records = $this->exam_model->find_all();
		Template::set('records', $records);	
		
		Template::set('toolbar_title',lang("library_exam_exam_view"));
		Template::set_view('exam/exam_list');
		Template::render();
   }

   
      
	/*===================Insert Records===========================*/ 
	/**
	 * Creates a exam object.
	 *
	 * @return void
	 **/
	 
	public function exam_create()
	{			
		if (isset($_POST['save']))
		{  
			if ($insert_id = $this->save_exam_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_exam');
				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/exam/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_create_failure').$this->exam_model->error, 'error');
			}
		}
		
		$library_exam['exam_name'] 	= $this->input->post('library_exam_exam_name');  		   
		Template::set('toolbar_title', lang("library_exam_exam_new"));
		Template::set('library_exam', $library_exam);	
		
		Template::set_view('exam/exam_create');
		Template::render();
	}


	
	/*==================== Edit Records =================================*/
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Exam data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/exam/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Exam.Edit');
			if ($this->save_exam_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record').': '.$id.' : '.$this->input->ip_address(),'library_exam');
				Template::set_message(lang('exam_update_success'), 'success');
				redirect(SITE_AREA .'/exam/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_edit_failure') . $this->exam_model->error, 'error');
			}
		}
		
		Template::set('exam_equivalent', $this->exam_model->find($id));
		Template::set('toolbar_title', lang('exam_details_edit'));	

		Template::set_view('exam/exam_create');
		Template::render();
	}
	
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	
	private function save_exam_details($type='insert', $id=0)
	{
		if ($type == 'update'){$_POST['id'] = $id;}
		
		// make sure we only pass in the fields we want	
		$data = array();
		$data['exam_equivalent']	= $this->input->post('library_exam_exam_equivalent');
		$data['exam_name']   		= $this->input->post('library_exam_exam_name');
		$data['exam_status']   		= $this->input->post('exam_exam_status');
		$data['created_by'] 		= $this->current_user->id;

		if ($type == 'insert')
		{	
			$this->auth->restrict('Lib.Exam.Create');
			$id = $this->exam_model->insert($data);
				if (is_numeric($id))
				{
					$return = $id;
				}
				else
				{
					$return = FALSE;
				}
		}elseif ($type == 'update')
		{
			$this->auth->restrict('Lib.Exam.Edit');
			$return = $this->exam_model->update($id, $data);
		}
		return $return;
	}

	
	
	
}

?>
