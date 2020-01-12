<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*----------------Exam-----------------*/
class Exam_board extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('exam_board_model', null, true);
		$this->lang->load('exam_board');
		$this->lang->load('common');
		
		Template::set_block('sub_nav', 'exam/_sub_nav_exam_board');
	}
		
		

	/*===================Show Table ===========================*/
	public function show_list()
	{	
		$this->auth->restrict('Lib.Exam_board.View');		
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Exam.Delete');			
			$result = FALSE;
			foreach ($checked as $id)
			{
				$result = $this->exam_board_model->delete($id);
			}
			
			if ($result)
			{
				Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
				Template::set_message(lang('bf_msg_delete_failure') . $this->exam_board_model->error, 'error');
			}
		}	
		
		$records = $this->exam_board_model->find_all();
		
		Template::set('records', $records);			
		Template::set('toolbar_title',lang("library_exam_exam_board_view"));
		Template::set_view('exam/exam_board_list');
		Template::render(); 
   } 
   
   
   
	/*====================Create =========================*/      
	public function exam_board_create()
	{
		if (isset($_POST['save']))
		{  
			if ($insert_id = $this->save_exam_board_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_exam_board');
				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/exam_board/library/show_list');
			}else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->exam_board_model->error, 'error');
			}
		}

		
		$library_exam['exam_exam_board'] 	= $this->input->post('library_exam_exam_board');
		
		Template::set('toolbar_title', lang("library_exam_exam_board_new"));
		Template::set('library_exam', $library_exam);			
		Template::set_view('exam/exam_board_create');
		Template::render();
	}	
	
/*=========================Edit ===============================================*/
	  
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('group_details_invalid_id'), 'error');
			redirect(SITE_AREA .'/exam_board/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Exam_board.Edit');
			
			if ($this->save_exam_board_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record').': '.$id.' : '.$this->input->ip_address(),'library_exam_board');
				Template::set_message(lang('exam_update_success'), 'success');
				redirect(SITE_AREA .'/exam_board/library/show_list');
			}else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->exam_board_model->error, 'error');
			}
		}
		
		
		Template::set('exam_board_equivalent', $this->exam_board_model->find($id));
		
		Template::set('toolbar_title', lang('exam_board_details_edit'));	
        Template::set_view('exam/exam_board_create');
		Template::render();
	}
	
	
	
	/*=====================Insert Fuction =========================*/
	private function save_exam_board_details($type='insert', $id=0)
	{
		if ($type == 'update'){$_POST['id'] = $id;}
		// make sure we only pass in the fields we want	
		$data = array();
		$data['exam_board_equivalent']     = $this->input->post('library_exam_exam_board_equivalent');
		$data['exam_board']   	           = $this->input->post('library_exam_exam_board');
	
		$data['exam_board_status']   	   = $this->input->post('exam_exam_board_status');
		$data['created_by'] 			   = $this->current_user->id;

		
		
		if ($type == 'insert')
		{	
			$this->auth->restrict('Lib.Exam_board.Create');
			$id = $this->exam_board_model->insert($data);
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
			$this->auth->restrict('Lib.Exam_board.Edit');
			$return = $this->exam_board_model->update($id, $data);
		}
		return $return;
	}

	
	
}

?>
