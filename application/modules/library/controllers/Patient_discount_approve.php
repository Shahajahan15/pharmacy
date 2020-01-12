<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Patient_discount_approve extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();		
		$this->lang->load('common');
		Assets::add_module_js('library', 'patient_discount');
		Template::set_block('sub_nav', 'patient_discount/_sub_nav_patient_discount');
        $this->auth->restrict('Discount.Setup.Approve');
	}

    /**
     * store company 
     */
	 
    public function approve_patient_discount()
    {
		//======= Delete Multiple or single=======
		
		$conditon['is_deleted']=0;
		
		$data['records'] 	= $this->db->select("bf_lib_patient_discounts_mst.*,bf_patient_master.patient_name,bf_patient_master.patient_id as p_code")
									->join('bf_patient_master','bf_patient_master.id=bf_lib_patient_discounts_mst.patient_id','left')
									->where($conditon)
									->order_by('bf_lib_patient_discounts_mst.created_at','DESC')
									->where('approve_status',0)
									->get('bf_lib_patient_discounts_mst')
									->result();
		Template::set($data);
		Template::set('toolbar_title', 'Patient Discount Approve Pending List');
        Template::set_view('patient_discount/approve_list');
        Template::render();
    }

    
	 
    public function approved($id, $h_discount = 0){
    	$check = $this->approveCheck($id);
        if (!$check) {
            echo json_encode(['approve_status'=>false,'check'=> false,'message'=>'Already Approved']);
            exit;
        }
    	if($this->db
            ->where('id',$id)
            ->update('bf_lib_patient_discounts_mst',
                [
                'approve_status'=>1,
                ]
                )){
    		echo json_encode(['approve_status'=>true, 'check'=> true,'message'=>'Successfully Approved']);
    	}else{
    		echo json_encode(['approve_status'=>false, 'check'=> true,'message'=>'Approved Failure']);
    	}
        exit;
    }

    public function approveCheck($id)
    {
    	$result=$this->db->where('id',$id)->where('approve_status',1)->get('bf_lib_patient_discounts_mst')->row();
    	if($result)
    	{
    		return false;
    	}
    	else
    	{
    		return true ;
    	}

    }


    public function approved_cancel($id, $h_discount = 0){
    	$check = $this->approveCancelCheck($id);
        if (!$check) {
            echo json_encode(['approve_status'=>false,'check'=> false,'message'=>'Already Canceled']);
            exit;
        }
    	if($this->db
            ->where('id',$id)
            ->update('bf_lib_patient_discounts_mst',
                [
                'approve_status'=>2,
                ]
                )){
    		echo json_encode(['approve_status'=>true, 'check'=> true,'message'=>'Successfully Approve Cancel']);
    	}else{
    		echo json_encode(['approve_status'=>false, 'check'=> true,'message'=>'Approved Failure']);
    	}
        exit;
    }
    public function approveCancelCheck($id)
    {
    	$result=$this->db->where('id',$id)->where('approve_status',2)->get('bf_lib_patient_discounts_mst')->row();
    	if($result)
    	{
    		return false;
    	}
    	else
    	{
    		return true ;
    	}
    }

	
}


?>
