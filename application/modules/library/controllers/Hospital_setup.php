<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Hospital_setup extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('hospital_model', NULL, true);
		$this->lang->load('hospital');
		$this->lang->load('common');
		//Assets::add_module_js('library','hospital.js');
		
		Template::set_block('sub_nav', 'hospital_info/_sub_nav_hospitalinfo');
		Assets::add_module_js('js','blog.js');

	}

    /**
     * store company 
     */
	 
    public function show_list()
    {	$this->auth->restrict('Lib.Hospital.View'); 
		
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Lib.Hospital.Delete');
			$result = FALSE;
			// $data = array();
			// $data['IS_DELETE
            //
            //D'] 		= 1;
			// $data['DELETED_BY']			= $this->current_user->id;	
			// $data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
            foreach ($checked as $pid)
			{				
				$result = $this->hospital_model->delete($pid);				
			}
			
			if ($result){
			    Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			    Template::set_message(lang('bf_msg_record_delete_fail') . $this->hospital_model->error, 'error');
			}
		}

        $records = $this->hospital_model->find_all();
		
		Template::set('records', $records);	
		Template::set('toolbar_title', 'Hospital List');		
        Template::set_view('hospital_info/hospital_info_list');
        Template::render();
    }

    /**
     * company create
     */
	 
    public function create()
    {
        //TODO you code here	

        if (isset($_POST['save']))
        {
            //echo '<pre>'; print_r($_FILES['logo'] ); exit();
			if ($insert_id = $this->save_hospital_details())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_hospital');

				Template::set_message(lang('bf_msg_create_success'), 'success');
				redirect(SITE_AREA .'/hospital_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_create_failure').$this->hospital_model->error, 'error');
			}
        }
		
		
		Template::set('toolbar_title', lang("Library_hospital_create"));
        Template::set_view('hospital_info/hospital_create');
        Template::render();
    }
	

	/**
	 * Allows editing of company data.
	 *
	 * @return void
	 */
	// public function edit()
	// {
	// 	$id = $this->uri->segment(5);
	// 	if (empty($id))
	// 	{
	// 		Template::set_message(lang('bf_act_invalid_record_id'), 'error');
	// 		redirect(SITE_AREA .'/hospital_setup/library/show_list');
	// 	}

	// 	if (isset($_POST['save']))
	// 	{
	// 		$this->auth->restrict('Lib.Hospital.Edit');

	// 		if ($this->save_hospital_details('update', $id))
	// 		{
	// 			// Log the activity
	// 			log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_hospital');

	// 			Template::set_message(lang('bf_msg_edit_success'), 'success');
	// 			redirect(SITE_AREA .'/hospital_setup/library/show_list');
	// 		}
	// 		else
	// 		{
	// 			Template::set_message(lang('bf_msg_edit_failure') . $this->hospital_model->error, 'error');
	// 		}
	// 	}
		
		
	// 	$records = $this->hospital_model->find($id);		
		
	// 	Template::set('records',$records);		
	// 	Template::set('toolbar_title', 'hospital updated');		
 //        Template::set_view('hospital_info/hospital_create');
	// 	Template::render();
	// }
	
	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('bf_act_invalid_record_id'), 'error');
			redirect(SITE_AREA .'/hospital_setup/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Lib.Hospital.Edit');

			if ($this->save_hospital_details('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('bf_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'lib_hospital');

				Template::set_message(lang('bf_msg_edit_success'), 'success');
				redirect(SITE_AREA .'/hospital_setup/library/show_list');
			}
			else
			{
				Template::set_message(lang('bf_msg_edit_failure') . $this->hospital_model->error, 'error');
			}
		}
		
		
		$records = $this->hospital_model->find($id);		
		
		Template::set('records',$records);		
		Template::set('toolbar_title', 'Hospital Info updated');		
        Template::set_view('hospital_info/hospital_create');
		Template::render();
	}
	
	
	
	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts	
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_hospital_details($type='insert', $id=0)
	{
		if ($type == 'update'){
			$_POST['id'] = $id;
		}
	
		// make sure we only pass in the fields we want		
		$data = array();
		$data['name']        		= $this->input->post('name');
		$data['mobile']      = $this->input->post('mobile');
		$data['phone']        	= $this->input->post('phone');
		$data['email']      		    = $this->input->post('email');
		$data['address']      		    = $this->input->post('address');
		$data['status']      		    = $this->input->post('status');

		//echo '<pre>'; print_r($_FILES['logo']);die();


		//$data['CREATED_BY'] 		    = $this->current_user->id;       

		if ($type == 'insert')
		{
			$this->auth->restrict('Lib.Hospital.Create');
			$id = $this->hospital_model->insert($data);
			if (is_numeric($id)){
			    print_r($id);
			    exit();
				$return = $id;
				if($_FILES['logo']['name'] && $return){
				$this->do_upload($return);
			}
			return $return;
			}else{
				$return = FALSE;
			}
		}elseif ($type == 'update'){

		$this->auth->restrict('Lib.Hospital.Edit');		 	 
			$this->hospital_model->update($id, $data);
            $return = $id;

			if($_FILES['logo']['name'] && $id){

			$this->do_upload($return);

		 }
        }

        return $return;

    }



	public function do_upload($id)

	{

		$config['upload_path'] = "../public/assets/images/hospital/";
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		//$config['max_size'] = '1000';
		//$config['max_width'] = '1024';
		//$config['max_height'] = '768';
		
		$file_name = $_FILES['logo']['name'];
		$file_ex = explode(".", $file_name);
		$ext = end($file_ex);
		$config['file_name'] = time().'.'.$ext;				
		$this->load->library('upload', $config);
		
		if(!$this->upload->do_upload('logo'))
		{
			//echo 'hi';exit;
			$error = array('error'=>$this->upload->display_errors());			
		}				
		else
		{
			$hospital_photo = $this->db->where('id',$id)->get('bf_lib_hospital')->row()->logo;
			if ($hospital_photo) {				
				$path = FCPATH.'assets/images/hospital/'.$hospital_photo;
				unlink($path);
			}
			

			$data = array('upload_data'=>$this->upload->data());					
			$imageName = $data['upload_data']['file_name'];	
			//print_r($imageName);
			
			$pictureName = array(				
				'logo' => $imageName
			);
		  // print_r($pictureName);exit;		
			$this->db->update('bf_lib_hospital', $pictureName, array('id' => $id));
		}
	}

	public function checkHospitalNameAjax()
	{	
	
		$hospitalName			= $this->input->post('hospitalName'); 
		
		if(trim($hospitalName)!= '')
		{			
			$res =$this->db->query("SELECT name FROM bf_lib_hospital WHERE  name  LIKE '%$hospitalName%'");	
			
			$result = $res->num_rows();
			
					
			if($result > 0 )
			{
				echo "hospital name already exit";

			}
		}	
	}	
	
	
}



