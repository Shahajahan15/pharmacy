<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setup controller
 */
class Bank_setup extends Admin_Controller
{	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	 
	public function __construct()
	{	
		parent::__construct();			
		$this->load->model('bank_setup_model', NULL, TRUE);	
		$this->lang->load('common');
		$this->lang->load('bank');
		
		Template::set_block('sub_nav', 'bank_setup/_sub_nav_bank');			
	}

	//--------------------------------------------------------------------
	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	 
	
	/*========================================================================================
										start Bank Setup 
	  ========================================================================================*/
	
	public function show_list()
	{
		$this->auth->restrict('Library.Bank.View');  				
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Bank.Delete');  
			$result = FALSE;
			foreach ($checked as $ID)
			{
				$data = [];
				$data['IS_DELETED'] = 1;
				$data['DELETED_BY'] = $this->current_user->id;
				$data['DELETED_DATE'] = date('Y-m-d H:i:s');
				$result = $this->bank_setup_model->update($ID,$data);
			}
			if ($result)
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $ID .' : '. $this->input->ip_address(), 'lib_bank_info');

				Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else
			{
				Template::set_message(lang('bf_msg_record_delete_fail') . $this->bank_setup_model->error, 'error');
			}
		}
		
		/*
			$query = $this->db->select('a.ID,a.BRANCH_NAME,a.ADDRESS,a.STATUS, b.BANK_NAME as PARENT_BANK_NAME,'
                       )
                ->from('lib_bank_info as a')
				
                ->join('lib_bank_info as b', 'a.BANK_ID = b.ID', 'left')
				
                ->where('a.IS_DELETED !=', 1)
				->distinct('a.ID')
                ->order_by("a.ID", "asc")
                ->get();
				
			$records = $query->result();
		
		*/
		
		
		
	$query = $this->db->select('
							
							  a.ID,
							  a.BANK_ID,
							  b.BANK_NAME,
							  a.BANK_NAME AS ONLY_BANK_NAME,
							  a.BRANCH_NAME,
							  a.ADDRESS,
							  a.STATUS																
							')
                ->from('lib_bank_info a')	
				->join('lib_bank_info b', 'a.BANK_ID = b.ID', 'left')
                ->where('a.IS_DELETED !=', 1)				
				->distinct('a.ID')
                ->order_by("a.ID", "asc")
                ->get();				
		$records = $query->result();
		
		
		
		
		//$records = $this->bank_setup_model->find_all('IS_DELETED',0);
		
		/*
		SELECT 
			  a.ID,
			  a.BANK_ID,
			  b.BANK_NAME,
			  a.BANK_NAME AS ONLY_BANK_NAME,
			  a.BRANCH_NAME,
			  a.ADDRESS,
			  a.STATUS 
			FROM
			  bf_lib_bank_info a 
			  LEFT JOIN bf_lib_bank_info b 
				ON a.`BANK_ID` = b.`ID`


		
		*/
		
		
		
		
		
		Template::set('records', $records);			
		Template::set('toolbar_title', lang("library_bank_view"));		
		Template::set_view('bank_setup/bank_list');			
		Template::render();
    }           

	public function create()
	{
		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_bank_info');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/bank_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->bank_setup_model->error, 'error');
			}
		}
		
		$where = array('BANK_NAME !=' => '', 'IS_DELETED' => 0, 'STATUS' => 1);
		$banklist = $this->bank_setup_model->select('*')->find_all_by($where);
					
		Template::set('toolbar_title', lang("library_bank_create"));				
		Template::set('banklist', $banklist);		
		Template::set_view('bank_setup/bank_create_form');		
		Template::render();
    }
	
	/**
	* Checking Bank name exits or not 
	* if ($bankName = bankName is exists, IS_DELETED = 0) then query return 1
	* if $result = 1 then that data insert prevent and show a message. 
	*/
	public function checkBankNameAjax()
	{	
		$bankName    = $this->input->post('bankName');	
		
		if($bankName!= '')
		{				
			$query=$this->db->select('BANK_NAME')
            ->from('lib_bank_info')
            ->where(array('IS_DELETED'=> 0,'BANK_NAME'=> $bankName))           
            ->get();
			$result = $query->num_rows();
			
			if($result > 0 )
			{
				echo 'The name "' . $bankName . '" is already exist !!';					
			}
			else
			{
				echo '<p style="color:green !important;">The name "' . $bankName . '" is available !!</p>';		
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
			redirect(SITE_AREA .'/bank_setup/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Bank.Edit');  

			if ($this->save('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_bank_info');
				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/bank_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->bank_setup_model->error, 'error');
			}
		}
		
		$where = array('BANK_NAME !=' => '', 'IS_DELETED' => 0, 'STATUS' => 1);
		$banklist = $this->bank_setup_model->select('*')->find_all_by($where);
		
		Template::set('bank_details', $this->bank_setup_model->find($id));
		Template::set('banklist', $banklist);
		Template::set('toolbar_title', lang('library_bank_update'));	
		Template::set_view('bank_setup/bank_create_form');
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
		$data['BANK_NAME']          	= $this->input->post('bank_name'); 
		$data['BANK_NAME_BENGALI']  	= $this->input->post('bank_name_bengali'); 		
		$data['BANK_ID'] 	  	   		= $this->input->post('bank_id')? $this->input->post('bank_id'): null; 
		$data['BRANCH_NAME']        	= $this->input->post('branch_name');
		$data['BRANCH_NAME_BENGALI']	= $this->input->post('branch_name_bengali'); 
		$data['ADDRESS']            	= $this->input->post('address');
		$data['ADDRESS_BENGALI']      	= $this->input->post('address_bengali');
		$data['STATUS']             	= $this->input->post('status');
		$data['CREATED_BY']         	= $this->current_user->id;
		
		$bankName   					= $this->input->post('bank_name');
		// Start checking duplicate bank name; 	
		$query = $this->db->select('BANK_NAME')
				->from('lib_bank_info')
				->where(array('IS_DELETED'=> 0,'BANK_NAME'=> $bankName ))           
				->get();
		
				$result = $query->num_rows(); 
		// End checking duplicate bank name; 

		if ($type == 'insert' && $result ==0)
		{
			$this->auth->restrict('Library.Bank.Create');  
			$result = FALSE;
			$id = $this->bank_setup_model->insert($data);
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
			$this->auth->restrict('Library.Bank.Edit');  
			$return = $this->bank_setup_model->update($id, $data);
		}
		return $return;
	}   
	/*======================================= Bank End ====================================*/
}