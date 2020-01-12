<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * library controller
 */
class Cash_collection extends Admin_Controller
{

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		//$this->auth->restrict('Library.Library.View');
        $this->load->model('collection_model', null, true);
        $this->lang->load('report');

		//Template::set_block('sub_nav', '_sub_nav_collection');
		
		//Assets::add_module_js('library', 'group_details.js');
	}

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{
		$records = $this->collection_model->find_all();
		Template::set('records', $records);
		Template::set('toolbar_title', 'Cash Collection Details');

        //Template::set_view('account/account/index');
		Template::render();
	}

	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	 */
	public function show_list( )
    {  
		//$this->load->config('config_admission_form');
		 
		//$id = $this->uri->segment(5); 
		
		$this->auth->restrict('Report.Collection.View');
		//======= Delete Multiple =======
		$checked = $this->input->post('checked');
		if (is_array($checked) && count($checked))
		{
			$this->auth->restrict('Report.Collection.View');
			$result = FALSE;
			foreach ($checked as $room_id){
				$result = $this->admission_bed_approve_model->delete($room_id);
			}
			if ($result){
			// Log the activity
			log_activity($this->current_user->id, lang('bf_act_delete_record') .': '. $room_id .' : '. $this->input->ip_address(), 'admission_room');

			Template::set_message(count($checked) .' '. lang('bf_msg_record_delete_success'), 'success');
			}else{
			Template::set_message(lang('bf_msg_record_delete_fail') . $this->admission_bed_approve_model->error, 'error');
			}
		}		
		
		
		$relation 					= $this->config->item('relation');  
		//===== select personal information=======
		$this->admission_form_model->select("a.id,a.attendant_name,a.attendant_relation,a.attendant_contact_no,a.attendant_address,p.patient_id,p.patient_name,p.father_name,p.mother_name,p.blood_group,p.sex,p.age,p.contact_no,a.patient_id,a.bed_id,a.status,a.admission_date,a.id");
		$this->admission_form_model->from('admission_patient AS a');
		$this->db->join('patient_master  as p', 'p.id = a.patient_id','left');
		$this->db->distinct("a.id");
		$records = $this->admission_form_model->find_by("a.id",$id);
		
	
		
		//print $this->db->last_query(); die;
		
		$sex 			= $this->config->item('sex');
		$marital_status = $this->config->item('marital_status');	
		$blood_group 	= $this->config->item('blood_group');
		$occupation 	= $this->config->item('occupation');		
		$room_type 		= $this->config->item('room_type');		
		
		
		$sendData = array(
			'records' 		=> $records,
			'permanent' 	=> $permanent,
			'mailing' 		=> $mailing,
			'admission' 	=> (object) $admission,
			'sex' 			=> $sex,
			'blood_group' 	=> $blood_group,
			'room_type' 	=> $room_type,
			'relation' 		=> $relation
		);
				
		Template::set('sendData', $sendData);
				
		//Template::set('toolbar_title', lang("admitted_patient_list"));		
		Template::set_view('admission/admitted_patient_show_details');		
		Template::render();
    }

      

}