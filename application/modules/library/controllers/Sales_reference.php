<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*----------------Sales Reference-----------------*/
class Sales_reference extends Admin_Controller{

	/**
	* Constructor
	*
	* @return void
	*/
	 
	public function __construct(){
		parent::__construct();		
		$this->load->model('sales_reference_model', NULL, TRUE);
		$this->lang->load('sales_reference');
		$this->lang->load('common');
		Template::set_block('sub_nav', 'secondary/_sub_nav_sales_reference');
	}
	
	/*===================Show Records ===========================*/
	/**
	* Displays a list of form data.
	*
	* @return void
	**/
	 
	public function show_list()
	{	 
	  $this->auth->restrict('Lib.Reference.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Reference.Delete');
			$result = FALSE;
			foreach ($checked as $id){
					$result = $this->sales_reference_model->delete($id);
			}
			if ($result){		
			// Log the activity
			log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'sales_reference');
							
			Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			Template::set_message(lang('bf_msg_delete_failure') . $this->sales_reference_model->error, 'error');
			}
		}	
		$records = $this->sales_reference_model->find_all();
		Template::set('toolbar_title',lang("doctor_ref_doctor_view"));
		Template::set('records', $records);		
		Template::set_view('secondary/sales_reference_list');
		Template::render();
		
   }

   
      
	/*===================Insert Records===========================*/ 
	/**
	 * Creates a sales reference object.
	 *
	 * @return void
	 **/
	 
	public function ref_create(){
		
	    $this->auth->restrict('Lib.Reference.Create');	
		if (isset($_POST['save']))
		{  
			if ($insert_id = $this->save_ref_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'reference_doctor');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/sales_reference/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_create_failure').$this->sales_reference_model->error, 'error');
			}
		}
		
		$doctor_ref_doc['doctor_ref_doc'] = $this->input->post('doctor_ref_doc_or_org_name');  		   
		Template::set('toolbar_title', lang("doctor_ref_doctor_Create"));
		Template::set('doctor_ref_doc', $doctor_ref_doc);	
				
		Template::set_view('secondary/sales_reference_create');
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
	
	public function save_ref_details($type='insert', $id=0)
	{
		if ($type == 'update'){$_POST['id'] = $id;}
		
		// make sure we only pass in the fields we want	
		$data = array();
		$data['ref_name']    			= $this->input->post('doctor_ref_doc_or_org_name');
		$data['ref_mobile']   	        = $this->input->post('doctor_ref_doc_or_org_mobile');
		$data['ref_phone']   	        = $this->input->post('doctor_ref_doc_or_org_phone');
		$data['ref_quali']              = $this->input->post('doctor_ref_doc_quali');
		$data['ref_address']   	        = $this->input->post('doctor_ref_doc_or_org_address');
		$data['ref_type']   	        = $this->input->post('doctor_ref_type');
		$data['ref_status']   	        = $this->input->post('doctor_ref_doc_or_org_status');
		$add_type = $this->input->post('add_type', true);
		
		
		if ($type == 'insert'){	
			$this->auth->restrict('Lib.Reference.Create');
			$data['created_by'] 	    = $this->current_user->id;
			$id = $this->sales_reference_model->insert($data);
			
			if (is_numeric($id))
			{
				if ($add_type) {
					$ref_name = $data['ref_name'];
					if ($data['ref_type'] == 1) {
						$ref_type = "Doctor";
					} elseif ($data['ref_type'] == 2) {
						$ref_type = "Organization";
					} else {
						$ref_type = "Others";
					}
					$html = '
						<option value="'.$id.'">'.$ref_name.'->'.$ref_type.'</option>
					';
				echo json_encode(array('success' => true,'chtml' => $html,'message' => 'Successfully done'));
				exit;
				} else {
					$return = $id;
				}
			}
			else
			{
				if ($add_type) {
				echo json_encode(array('success' => false,'message' => "Error"));
				exit;
				} else {
					$return = FALSE;
				}
			}
		}elseif ($type == 'update'){		
			$data['update_time']   	        =  date('Y-m-d H:i:s');;
		    $data['updated_by'] 	        = $this->current_user->id;
			$this->auth->restrict('Lib.Reference.Edit');
			$return = $this->sales_reference_model->update($id, $data);
		}
		return $return;
	}

	
	/*==================== Edit Records =================================*/
	//--------------------------------------------------------------------
	/**
	 * Allows editing of sales reference data.
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------
	
	public function edit(){
		$id = $this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/sales_reference/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Reference.Edit');
			if ($this->save_ref_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record').': '.$id.' : '.$this->input->ip_address(),'sales_reference');
				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/sales_reference/library/show_list');
			}else{
				Template::set_message(lang('bf_msg_edit_failure') . $this->sales_reference_model->error, 'error');
			}
		}
		
		Template::set('doctor_ref_doc', $this->sales_reference_model->find($id));
		Template::set('toolbar_title', lang('doctor_ref_doctor_edit'));	

		Template::set_view('secondary/sales_reference_create');
		Template::render();
	}

	
	/** Ajax Form Submission for Modal **/

	public function ref_new_doctor_form()
	{
		$this->lang->load('library/sales_reference');
		
		$data = array();
				
		$this->load->view('library/secondary/sales_reference_create', $data);
	}
	
	public function ref_new_doctor_save()
	{
		if ( ! $this->auth->has_permission('Lib.Reference.Create')) {
			$json = array(
				'error' => 'You do not have permission to access here',	
			);
			
			return $this->output->set_status_header(401)
							->set_content_type('application/json')
							->set_output(json_encode($json));	
		}
		
		$json = array();
		$status_code = 200;
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules(array(
			'ref_name' => array(
				'field' => 'ref_name',
				'label' => 'Reference Doctor Name',
				'rules' => 'trim|required',
			),
			'ref_mobile' => array(
				'field' => 'ref_mobile',
				'label' => 'Mobile No.',
				'rules' => 'trim|required|numeric',
			),
			'ref_phone' => array(
				'field' => 'ref_phone',
				'label' => 'Phone No.',
				'rules' => 'trim|numeric',
			),
			'ref_quali' => array(
				'field' => 'ref_quali',
				'label' => 'Qualification',
				'rules' => 'trim',
			),
			'ref_address' => array(
				'field' => 'ref_address',
				'label' => 'Address',
				'rules' => 'trim',
			),
			'ref_commission' => array(
				'field' => 'ref_commission',
				'label' => 'Commision',
				'rules' => 'trim|numeric',
			),
			'ref_type' => array(
				'field' => 'ref_type',
				'label' => 'type',
				'rules' => 'trim',
			),
			'ref_status' => array(
				'field' => 'ref_status',
				'label' => 'Contact No.',
				'rules' => 'trim',
			),
		));
		
		$data = array();
		$data['ref_name']    			= trim($this->input->post('doctor_ref_doc_or_org_name'));
		$data['ref_mobile']   	        = trim($this->input->post('doctor_ref_doc_or_org_mobile'));
		$data['ref_phone']   	        = trim($this->input->post('doctor_ref_doc_or_org_phone'));
		$data['ref_quali']              = trim($this->input->post('doctor_ref_doc_quali'));
		$data['ref_address']   	        = trim($this->input->post('doctor_ref_doc_or_org_address'));
		$data['ref_commission']   	    = trim($this->input->post('doctor_ref_doc_or_org_comission') ?: 0);
		$data['ref_type']   	        = trim($this->input->post('doctor_ref_type'));
		$data['ref_status']   	        = trim($this->input->post('doctor_ref_doc_or_org_status'));
		$data['created_by']             = $this->current_user->id;
		
		$this->form_validation->set_data($data);
		
		if ($this->form_validation->run() === false) {
			
			$status_code = 422;
			$json['errors'] = $this->form_validation->error_array();
			$json['success'] = false;
			
			return $this->output->set_status_header($status_code)
							->set_content_type('application/json')
							->set_output(json_encode($json));

		}
		
		$this->load->model('library/sales_reference_model', NULL, TRUE);
		
		$this->db->trans_start();
		$data['id'] = $this->sales_reference_model->skip_validation()->insert($data);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === false) {
			$json['error'] = $this->db->error();
			
			return $this->output->set_status_header(500)
							->set_content_type('application/json')
							->set_output(json_encode($json));
		}
		
		$json['data'] = $data;
		
		
		return $this->output->set_status_header($status_code)
							->set_content_type('application/json')
							->set_output(json_encode($json));
	}
	
}

