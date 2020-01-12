<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*----------------Symptom-----------------*/
class Symptom extends Admin_Controller{

	/**
	* Constructor
	*
	* @return void
	*/
	 
	public function __construct(){
		parent::__construct();
		$this->auth->restrict('Lib.Initial.Symptom.View');
		$this->load->model('symptom_model', NULL, TRUE);
		$this->lang->load('symptom');
		$this->lang->load('common');
		Template::set_block('sub_nav', 'initial/_sub_nav_symptom');
	}
	
	/*===================Show Records ===========================*/
	/**
	* Displays a list of form data.
	*
	* @return void
	**/
	 
	public function show_list(){		
		$this->auth->restrict('Lib.Initial.Symptom.View');
		Template::set('toolbar_title',lang("library_initial_symptom_view"));
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		//print_r($checked);exit();
		if (is_array($checked) && count($checked))
		{
		$this->auth->restrict('Lib.Initial.Symptom.Delete');
			$result = FALSE;
			$data=array();
			$data['is_deleted']=1;
			$data['deleted_by']=$this->current_user->id;
			$data['deleted_date']=date('Y-m-d H-i-s');

			foreach ($checked as $id){
				$result = $this->symptom_model->update($id,$data);
			}
			if ($result){
			
			// Log the activity
			log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_symptom');
							
			Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
				
			Template::set_message(lang('bf_msg_delete_failure') . $this->symptom_model->error, 'error');
			}
		
		}
		$records = $this->symptom_model->find_all_by('is_deleted',0);
		Template::set('records', $records);	
		
		Template::set_view('initial/symptom_list');
		Template::render();
   }

   
      
	/*===================Insert Records===========================*/ 
	/**
	 * Creates a exam object.
	 *
	 * @return void
	 **/
	 
	public function symptom_create(){
		
				
		if (isset($_POST['save']))
		{  
			if ($insert_id = $this->save_symptom_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_symptom');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/symptom/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_create_failure').$this->symptom_model->error, 'error');
			}
		}
		
		 		   
		Template::set('toolbar_title', lang("library_symptom_new"));		
		Template::set_view('initial/symptom_create');
		Template::render();
	}

	
	/*===================== Insert Function =========================*/
	
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
	
	private function save_symptom_details($type='insert', $id=0)
	{
		if ($type == 'update'){$_POST['id'] = $id;}
		
		// make sure we only pass in the fields we want	
		$data = array();
		$data['symptom_name']= $this->input->post('library_symptom_name');
		
		//echo $data['symptom_name']; die;

		if ($type =='insert'){	
			$this->auth->restrict('Lib.Initial.Symptom.Create');
			$id = $this->symptom_model->insert($data);
				if (is_numeric($id))
				{
					$return = $id;
				}
				else
				{
					$return = FALSE;
				}
		}elseif ($type == 'update'){
			$this->auth->restrict('Lib.Initial.Symptom.Edit');
			$return = $this->symptom_model->update($id, $data);
		}
		return $return;
	}

	
	/*==================== Edit Records =================================*/
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Exam data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function edit(){
		$id = $this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'initial/symptom_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Initial.Symptom.Edit');
			if ($this->save_symptom_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record').': '.$id.' : '.$this->input->ip_address(),'lib_symptom');
				
				Template::set_message(lang('bf_msg_edit_success'), 'success');
				
				redirect(SITE_AREA .'/symptom/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_edit_failure') . $this->symptom_model->error, 'error');
			}
		}
		
		Template::set('symptom_name', $this->symptom_model->find($id));
		Template::set('toolbar_title', lang('library_symptom_edit'));	

		Template::set_view('initial/symptom_create');
		Template::render();
	}
}

?>
