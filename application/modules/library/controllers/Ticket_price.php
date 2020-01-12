<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*----------------Ticket Price-----------------*/
class Ticket_price extends Admin_Controller{

	/**
	* Constructor
	*
	* @return void
	*/
	 
	public function __construct(){
		parent::__construct();		
		$this->load->model('ticket_price_model', NULL, TRUE);
		$this->lang->load('ticket_price');
		$this->lang->load('common');
		Template::set_block('sub_nav', 'secondary/_sub_nav_ticket_price');

		$this->config->load('outdoor/outdoor');
		
	}
	
	/*===================Show Records ===========================*/
	/**
	* Displays a list of form data.
	*
	* @return void
	**/
	 
	public function show_list(){		
		$this->auth->restrict('Lib.Ticket.Price.View');
		Template::set('toolbar_title',lang("library_ticket_price_view"));
		
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Ticket.Price.Delete');
			$result = FALSE;
			foreach ($checked as $id){
					$result = $this->ticket_price_model->delete($id);
			}
			if ($result){
			
			// Log the activity
			log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'ticket_price');
							
			Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			Template::set_message(lang('bf_msg_delete_failure') . $this->ticket_price_model->error, 'error');
			}
		}
		$conItem=$this->config->item('appointment_type');
		unset($conItem[2]);
		
		$records = $this->ticket_price_model->find_all();
		Template::set('records', $records);
		Template::set('types',$conItem);	
		
		Template::set_view('secondary/ticket_price_list');
		Template::render();
   }
  
      
	/*===================Insert Records===========================*/ 
	/**
	 * Creates a exam object.
	 *
	 * @return void
	 **/
	 
	public function price_create(){
				
		if (isset($_POST['save']))
		{  
			if ($insert_id = $this->saveTicketPrice())
			{
				// Log the activity
				log_activity($this->current_user->id,lang('bf_act_create_record').': '.$insert_id .' : '.$this->input->ip_address(),'ticket_price');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/ticket_price/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_create_failure').$this->ticket_price_model->error, 'error');
			}
		}
		$conItem=$this->config->item('appointment_type');
		unset($conItem[2]);
		
		

	 		   
		Template::set('toolbar_title', lang("library_ticket_price_new"));
		Template::set('types',$conItem);
		Template::set_view('secondary/ticket_price_create');
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
	
	private function saveTicketPrice($type='insert', $id=0)
	{
		if ($type == 'update'){	$_POST['id'] = $id;	}
		
		// make sure we only pass in the fields we want	
		$data = array();
		$data['ticket_price']    = $this->input->post('library_ticket_price');
		$data['app_type']    = $this->input->post('app_type');
		if ($id == 0){		
		$data['created_by'] 	 = $this->current_user->id;
		}
		
		if ($type == 'insert'){	
			$this->auth->restrict('Lib.Ticket.Price.Create');
			$id = $this->ticket_price_model->insert($data);
				if (is_numeric($id))
				{
					$return = $id;
				}
				else
				{
					$return = FALSE;
				}
		}elseif ($type == 'update'){
			$this->auth->restrict('Lib.Ticket.Price.Edit');
			$return = $this->ticket_price_model->update($id, $data);
		}
		return $return;
	}

	
	/*==================== Edit Records =================================*/
	//--------------------------------------------------------------------
	/**
	 * Allows editing of Test Attribute data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function edit(){
		$id = $this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/ticket_price/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Ticket.Price.Edit');
			if ($this->saveTicketPrice('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record').': '.$id.' : '.$this->input->ip_address(),'ticket_price');
				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/ticket_price/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_edit_failure') . $this->ticket_price_model->error, 'error');
			
			}
		}
		$conItem=$this->config->item('appointment_type');
		unset($conItem[2]);
		
				
		Template::set('ticket_price', $this->ticket_price_model->find($id));
		Template::set('toolbar_title', lang('library_ticket_price_edit'));	
		Template::set('types',$conItem);
		Template::set_view('secondary/ticket_price_create');
		Template::render();
	}
}

?>
