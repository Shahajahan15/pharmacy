<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*----------------Exam-----------------*/
class Surgeon_doctor extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Surgeon_model', null, true);
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
				$result = $this->Surgeon_model->delete($id);
			}
			
			if ($result)
			{
				Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
				Template::set_message(lang('bf_msg_delete_failure') . $this->Surgeon_model->error, 'error');
			}
		}	
		
		$records = $this->Surgeon_model->find_all();
		
		Template::set('records', $records);			
		Template::set('toolbar_title',lang("library_exam_exam_board_view"));
		Template::set_view('surgeon_doctor/list');
		Template::render(); 
   } 
   
   
   
	/*====================Create =========================*/      
	public function create()
	{
		if (isset($_POST['save']))
		{  
			
			if ($insert_id = $this->save_surgeon())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'bf_lib_surgeon');
				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/surgeon_doctor/library/show_list');
				
			}else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->exam_board_model->error, 'error');
			}
		}

		
		$library_exam['exam_exam_board'] 	= $this->input->post('library_exam_exam_board');
		
		Template::set('toolbar_title', lang("library_exam_exam_board_new"));
		Template::set('library_exam', $library_exam);			
		Template::set_view('surgeon_doctor/create');
		Template::render();
	}	
	
/*=========================Edit ===============================================*/
	  

	
	
	
	/*=====================Insert Fuction =========================*/
	public function save_surgeon($type='insert', $id=0)
	{
		if ($type == 'update'){$_POST['id'] = $id;}
		// make sure we only pass in the fields we want	
		$data = array();
		$data['surgeon_name']  = $this->input->post('surgeon_name');
		$data['mobile']   	   = $this->input->post('mobile');
	
		$data['address']   	   = $this->input->post('address');
		$data['status']   	   = $this->input->post('status');


		
		
		if ($type == 'insert')
		{	
			$this->auth->restrict('Lib.Exam_board.Create');
			$id = $this->Surgeon_model->insert($data);
				if (is_numeric($id))
				{
					$html = '
                        <option value="'.$id.'">'.$data['surgeon_name'].'</option>
                    ';
                	echo json_encode(array('success' => true,'chtml' => $html,'message' => 'Successfully done'));
                	exit;
				//$return = $id;
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
