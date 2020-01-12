<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setup controller
 */
class Subject_create extends Admin_Controller
{	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	 
	public function __construct()
	{	
		parent::__construct();			
		$this->load->model('subject_create_model', NULL, TRUE);	
		$this->lang->load('common');
		$this->lang->load('subject_create');
		
		Template::set_block('sub_nav', 'subject_create/_sub_nav_subject');			
	}
	//----------------------------- end construct ------------------------
	
	
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function show_list()
	{
		$this->auth->restrict('Library.Subject.View');  				
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Subject.Delete');  
			$result = FALSE;
			foreach ($checked as $ID)
			{
				$data = [];
				$data['IS_DELETED'] = 1;
				$data['DELETED_BY'] = $this->current_user->id;
				$data['DELETED_DATE'] = date('Y-m-d H:i:s');
				$result = $this->subject_create_model->update($ID,$data);
			}
			if ($result)
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $ID .' : '. $this->input->ip_address(), 'library_subject');

				Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
				Template::set_message(lang('bf_msg_record_delete_fail') . $this->subject_create_model->error, 'error');
			}
		}
		
		$records = $this->subject_create_model->find_all_by('IS_DELETED',0);
				
		Template::set('records', $records);			
		Template::set('toolbar_title', lang("library_subject_view"));		
		Template::set_view('subject_create/subject_list');			
		Template::render();
    }           
	
	/**
	 * Create New subject if it is not exists.
	 * $insert_id = true then show success message.
	 * 
	 */
	public function create()
	{
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'library_subject');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/subject_create/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->subject_create_model->error, 'error');
			}
		}
					
		Template::set('toolbar_title', lang("library_subject_create"));						
		Template::set_view('subject_create/subject_create_form');		
		Template::render();
    }
	
	/**
	* Checking Subject name exits or not 
	* if ($subjectName = subject Name  is exists, IS_DELETED = 0) then query return 1
	* if $result = 1 then that data insert prevent and show a message. 
	*/
	public function checkSubjectNameAjax()
	{	
		$subjectName    = $this->input->post('subject');	
		
		if($subjectName!= '')
		{				
			$query=$this->db->select('SUBJECT')
            ->from('library_subject')
            ->where(array('IS_DELETED'=> 0,'SUBJECT'=> $subjectName))           
            ->get();
			$result = $query->num_rows();
			
			if($result > 0 )
			{
				echo 'The name "' . $subjectName . '" is already exist !!';					
			}
			else
			{
				echo '<p style="color:green !important;">The name "' . $subjectName . '" is available !!</p>';		
			}						
		}	
	}
	
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Building Details data.
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
			redirect(SITE_AREA .'/subject_create/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Subject.Edit');  

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_subject');
				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/subject_create/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->subject_create_model->error, 'error');
			}
		}
		
		Template::set('subject_details', $this->subject_create_model->find($id));
		Template::set('toolbar_title', lang('library_subject_update'));	
		Template::set_view('subject_create/subject_create_form');
		Template::render();
	}

	//--------------------------------------------------------------------

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
	 
	private function save($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['ID'] = $id;
		}

		// make sure we only pass in the fields we want		
		$data = array();	           
		$data['SUBJECT']          		= $this->input->post('subject'); 
		$data['SUBJECT_BENGALI']  		= $this->input->post('subject_bengali'); 		
		$data['CODE'] 	  	   			= $this->input->post('code'); 
		$data['REMARKS']        		= $this->input->post('remarks');
		$data['REMARKS_BENGALI']		= $this->input->post('remarks_bengali'); 
		$data['STATUS']             	= $this->input->post('status');
		$data['CREATED_BY']         	= $this->current_user->id;
					
		$subJect   						= $this->input->post('subject');
		// Start checking duplicate subject; 	
		$query = $this->db->select('SUBJECT')
				->from('library_subject')
				->where(array('IS_DELETED'=> 0,'SUBJECT'=> $subJect ))           
				->get();
		
				$result = $query->num_rows(); 
		// End checking duplicate subject; 

		if ($type == 'insert' && $result ==0)
		{
			$this->auth->restrict('Library.Subject.Create');  
			$result = FALSE;
			$id = $this->subject_create_model->insert($data);
			if (is_numeric($id))
			{
				$return = $id;
			}else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$data['MODIFIED_BY']         =  $this->current_user->id;
			$data['MODIFIED_DATE']       =  date('Y-m-d H:i:s');
			$this->auth->restrict('Library.Subject.Edit');  
			$return = $this->subject_create_model->update($id, $data);
		}
		return $return;
	}   
	
} //end class