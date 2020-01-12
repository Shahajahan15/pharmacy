
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Bank_info extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Library.Bank.View'); 
		$this->load->model('bank_info_model', NULL, true);
		$this->lang->load('bank');
		$this->lang->load('common');
		
		//Assets::add_module_js('store', 'brand.js');		
		//Template::set_block('sub_nav', 'initial/_sub_nav_bankinfo');
	}

    /**
     * store company 
     */
	 public function form_list(){
	 	$list_data=$this->show_list();
	 	$form_data=$this->create();
	 	$data=array();
	 	if(is_array($list_data))
        $data=array_merge($data,$list_data);
        if(is_array($form_data))
        $data=array_merge($data,$form_data);
        $data['form_action_url']=site_url('admin/bank_info/library/create');
        $data['list_action_url']=site_url('admin/bank_info/library/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("Library_bank_list"));
        Template::render();

	 } 
    public function show_list()
    {
    	   if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Library.Bank.Delete');
			$result = FALSE;
            $data=array();
            $data['is_deleted'] = 1;
            $data['deleted_by'] = $this->current_user->id;
            $data['deleted_date'] = date('Y-m-d H:i:s');
            foreach ($checked as $pid){
				$result = $this->bank_info_model->update($pid,$data);
			}

			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->bank_info_model->error, 'error');
			}
		}

        $records = $this->bank_info_model->find_all_by('is_deleted',0);
		$data['records']=$records;
        $form_view = 'initial/bank_info_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set($data);	
		Template::set('toolbar_title', lang("library_bank_view"));		
        Template::set_view($form_view);
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
    	   if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'create') {
            show_404();
        }
        //TODO you code here	
        $data=array();
        if (isset($_POST['save']))
        {
        	if($this->duplicateCheck(trim($_POST['library_bank_name']))){
        		if ($this->input->is_ajax_request()) {
	                $json = array(
	                    'status' => false,
	                    'message' => 'Data already exist',
	                );

	                return $this->output->set_status_header(200)
	                                ->set_content_type('application/json')
	                                ->set_output(json_encode($json));
	                }

        	}else{
	        	if ($insert_id = $this->save_bank_details())
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'company_info');
	                if ($this->input->is_ajax_request()) {
	                    $json = array(
	                        'status' => true,
	                        'message' => lang('bf_msg_create_success'),
	                        'inserted_id' => $insert_id,
	                    );

	                    return $this->output->set_status_header(200)
	                                        ->set_content_type('application/json')
	                                        ->set_output(json_encode($json));
	                }
					Template::set_message(lang('bf_msg_create_success'), 'success');
					redirect(SITE_AREA .'/bank_info/library/show_list');
				}
				else
				{
					  if ($this->input->is_ajax_request()) {
	                    $json = array(
	                        'status' => true,
	                        'message' => lang('bf_msg_create_success'),
	                        'inserted_id' => $insert_id,
	                    );

	                    return $this->output->set_status_header(200)
	                                        ->set_content_type('application/json')
	                                        ->set_output(json_encode($json));
	                }
					Template::set_message(lang('bf_msg_create_failure').$this->bank_info_model->error, 'error');
				}

        	}
			
        }
		$form_view='initial/bank_info_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set('toolbar_title', lang("Library_Bank_create"));
        Template::set_view($form_view);
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$ID = $this->uri->segment(5);
		if (empty($ID))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/bank_info/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.bank.Edit');

			if($this->duplicateCheck($_POST['library_bank_name'],$ID)){
				if ($this->input->is_ajax_request()) {
	                $json = array(
	                    'status' => false,
	                    'message' => 'Data already exist',
	                );

	                return $this->output->set_status_header(200)
	                                ->set_content_type('application/json')
	                                ->set_output(json_encode($json));
	                }
			}else{
				if ($this->save_bank_details('update', $ID))
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $ID .' : '. $this->input->ip_address(), 'bank_info');
	                 if ($this->input->is_ajax_request()) {
	                    $json = array(
	                        'status' => true,
	                        'message' => lang('bf_msg_edit_success'),
	                        'inserted_id' => $ID,
	                    );

	                    return $this->output->set_status_header(200)
	                                        ->set_content_type('application/json')
	                                        ->set_output(json_encode($json));
	                }
					Template::set_message(lang('bf_msg_edit_success'), 'success');
					redirect(SITE_AREA .'/bank_info/library/show_list');
				}
				else
				{
					if ($this->input->is_ajax_request()) {
	                    $json = array(
	                        'status' => true,
	                        'message' => lang('bf_msg_edit_success'),
	                        'inserted_id' => $ID,
	                    );

	                    return $this->output->set_status_header(200)
	                                        ->set_content_type('application/json')
	                                        ->set_output(json_encode($json));
	                }
					Template::set_message(lang('bf_msg_edit_failure') . $this->bank_info_model->error, 'error');
				}
			}
		}
		
		
		$data['bank_details'] = $this->bank_info_model->find($ID);
		$form_view='initial/bank_info_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set($data);		
		Template::set('toolbar_title', lang('library_bank_update'));		
        Template::set_view($form_view);
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_bank_details($type='insert', $ID=0)
	{
		//echo '<pre>';print_r($_POST);die();

		if ($type == 'update'){
			$_POST['ID'] = $ID;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['bank_name']        		= $this->input->post('library_bank_name');
		
		$data['status']      		    = $this->input->post('bf_status');
		$data['created_by'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Library.bank.Create');
			$ID = $this->bank_info_model->insert($data);
			if (is_numeric($ID)){
				$return = $ID;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Library.bank.Edit');
			$return = $this->bank_info_model->update($ID, $data);
		}

		return $return;
	}

	//id on update
	public function duplicateCheck($name,$id=0){
		$name=trim($name);
		if($id){
			$record=$this->db->like('bank_name',$name)->where('id !=',$id)->get('bf_lib_bank_info')->row();
		}else{
			$record=$this->db->like('bank_name',$name)->get('bf_lib_bank_info')->row();
		}

		if($record){
			return true;
		}
		else{
			return false;
		}

	}
	
}

