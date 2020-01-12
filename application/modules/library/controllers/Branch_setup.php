
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Branch_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('branch_info_model', NULL, true);
		$this->load->model('bank_info_model', NULL, true);

        $this->lang->load('branch');	
		$this->lang->load('common');
		Assets::add_module_js('library','branch_info.js');

		//Template::set_block('sub_nav', 'initial/_sub_nav_branch_info');
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
        $data['form_action_url']=site_url('admin/branch_setup/library/create');
        $data['list_action_url']=site_url('admin/branch_setup/library/show_list');
        Template::set($data);
        Template::set_view('form_list_template');
        Template::set('toolbar_title', lang("Library_branch_list"));
        Template::render();

	 } 
    public function show_list()
    {	
    	   if (!$this->input->is_ajax_request() && $this->uri->segment(4) == 'show_list') {
            show_404();
        }
		$this->auth->restrict('Lib.Branch.View'); 
		//======= Delete Multiple or single=======
		$checked = $this->input->post('checked');
	
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Branch.Delete');
			$result = FALSE;
            $data = [];
            $data['is_deleted'] = 1;
            $data['deleted_by'] = $this->current_user->id;
            $data['deleted_date'] = date('Y-m-d H:i:s');
            foreach ($checked as $id) 
			{
                
                $result = $this->branch_info_model->update($id,$data);
            }
            if ($result) 
			{
                Template::set_message(count($checked) . ' ' . lang('bf_msg_record_delete_success'), 'success');
            } else 
			{
                Template::set_message(lang('bf_msg_record_delete_fail') . $this->branch_info_model->error, 'error');
            }
		}
		$records=$this->db->select('lib_branch_info.*,lib_bank_info.bank_name')
	             ->join('lib_bank_info','lib_bank_info.id=lib_branch_info.bank_id')
	             ->where('lib_branch_info.is_deleted',0)
	             ->where('lib_bank_info.status',1)
	             ->get('lib_branch_info')
	             ->result_array();
		$data['records']=$records;
        $form_view = 'initial/branch_info_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set($data);	
		Template::set('toolbar_title', lang("Library_branch_list"));		
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

         if (isset($_POST['save'])) 
        {
            if ($insert_id = $this->save()) 
            {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'lib_branch_info');
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
                redirect(SITE_AREA . '/branch_setup/library/show_list');
            } else 
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
                Template::set_message(lang('bf_msg_create_failure') . $this->branch_info_model->error, 'error');
            }
        }
		$data['bank_details']=$this->db->select('*')
		                        ->where('lib_bank_info.is_deleted',0)
		                        ->where('lib_bank_info.status',1)
		                        ->get('lib_bank_info')
		                        ->result();
		$form_view='initial/branch_info_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
		Template::set($data);	
		Template::set('toolbar_title', lang("Library_branch_create"));
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
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/branch_setup/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Branch.Edit');

			if($this->duplicateCheck($_POST,$id)){
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
				$data['modify_by'] = $this->current_user->id;
	            $data['modify_date'] = date('Y-m-d H:i:s');
				if ($this->save('update', $id))
				{
					// Log the activity
					log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_branch_info');
	                 if ($this->input->is_ajax_request()) {
	                    $json = array(
	                        'status' => true,
	                        'message' => lang('bf_msg_create_success'),
	                        'inserted_id' => $id,
	                    );

	                    return $this->output->set_status_header(200)
	                                        ->set_content_type('application/json')
	                                        ->set_output(json_encode($json));
	                }
					Template::set_message(lang('bf_msg_edit_success'), 'success');
					redirect(SITE_AREA .'/branch_setup/library/show_list');
				}
				else
				{
					if ($this->input->is_ajax_request()) {
	                    $json = array(
	                        'status' => true,
	                        'message' => lang('bf_msg_create_success'),
	                        'inserted_id' => $id,
	                    );

	                    return $this->output->set_status_header(200)
	                                        ->set_content_type('application/json')
	                                        ->set_output(json_encode($json));
	                }
					Template::set_message(lang('bf_msg_edit_failure') . $this->branch_info_model->error, 'error');
				}

			}
			
            
		}
		
		
		$records = $this->branch_info_model->find($id);		
		$data['bank_details']= $this->bank_info_model->find_all();
		$data['records']=$records;
		$form_view='initial/branch_info_create';
        if ($this->input->is_ajax_request()) {
            echo $this->load->view($form_view, $data, true);
            exit;
        }
        Template::set($data);
		Template::set('toolbar_title', lang('library_branch_update'));		
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
	private function save($type='insert', $id=0)
	{
		//echo '<pre>';print_r($_POST);die();
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['bank_id']        	= $this->input->post('bank_id');	
		$data['branch_name']        	= $this->input->post('library_branch_name');	
		$data['account_no']        	= $this->input->post('library_branch_account_no');	
		$data['status']      		    = $this->input->post('bf_status');
		$data['created_by'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Branch.Create');
			$id = $this->branch_info_model->insert($data);
			if (is_numeric($id)){
				$return = $id;
			}else{
				$return = FALSE;
			}
		}
		elseif ($type == 'update'){
			$this->auth->restrict('Lib.Branch.Edit');
			
			$data['modify_by'] 		    = $this->current_user->id;   
			$data['modify_date'] 		    = date('Y-m-d H:i:s');   
			$return = $this->branch_info_model->update($id, $data);
		}

		return $return;
	}

	public function getPackageInfo($id)
	{
		$data = array();
		$data['package_info'] = $this->getTestInfoByPackage($id);
		$data['type'] = 2;
		$page=$this->load->view('library/initial/branch_info_create',$data,true);
		echo json_encode($page);
		exit;
	}

	public function getTestInfoByPackage($id)
	{
		$result = $this->db
				->select('dtls.bank_name, br.branch_name,br.status,br.account_no')
				->from('lib_branch_info as br')
				->where('br.id', $id)
				->join('lib_bank_info as dtls', 'dtls.id = br.id')
				//->join('pathology_test_name as tname', 'dtls.test_id = tname.id')
				->get()
				->result();
		return $result;
	}


	//id on update
	public function duplicateCheck($post,$id=0){
		
		if($id){
			$record=$this->db->like('branch_name',trim($post['library_branch_name']))->where('bank_id',trim($post['bank_id']))->where('account_no',trim($post['library_branch_account_no']))->where('id !=',$id)->get('bf_lib_branch_info')->row();
		}else{
			$record=$this->db->like('branch_name',trim($post['library_branch_name']))->where('bank_id',trim($post['bank_id']))->where('account_no',trim($post['library_branch_account_no']))->get('bf_lib_branch_info')->row();
		}

		if($record){
			return true;
		}
		else{
			return false;
		}

	}
	
	// public function checkBranchNameAjax()
	// {

	// 	$branchName	= $this->input->post('branchName');

	// 	if(trim($branchName)!= '')
	// 	{
	// 		$res =$this->db->query("SELECT branch_name FROM bf_lib_branch_info WHERE  branch_name  LIKE '%$branchName%'");

	// 		$result = $res->num_rows();
	// 		if($result > 0 )
	// 		{
	// 			echo json_encode(['status'=>1,'message'=>'Branch Name Already Exist !!']);

	// 		}

	// 	}
	// }
	public function checkBranchNameAjax(){
		$branchName=$this->input->post('branchName');
		$bankId=$this->input->post('bankId');
		if(trim($bankId)!=''){
			$res=$this->db->query("SELECT branch_name FROM bf_lib_branch_info WHERE bank_id='$bankId' AND branch_name='$branchName'");
			$result=$res->num_rows();
			if($result > 0){
				echo json_encode(['status'=>1,'message'=>'Branch name already exit with same Bank name']);
			}
		}
	}
     public function checkAccountNameAjax()
     {
     	$bankName=$this->input->post('bankName');
     	$accountNo=$this->input->post('accountNo');
     	if(trim($bankName)!=''){
     		$res =$this->db->query("SELECT account_no FROM bf_lib_branch_info WHERE bank_id='$bankName' AND account_no='$accountNo'");
     		$result=$res->num_rows();
     		if($result > 0){
     			echo json_encode(['status'=>1,'message'=>'account no already exit with same bank name ']);

     		}

     	}
     }
	
}

