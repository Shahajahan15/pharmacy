<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * library controller
 */
class Transaction_report extends Admin_Controller
{

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->auth->restrict('Collection.Details.View');
        $this->load->model('collection_model', null, true);
        $this->lang->load('common');
		$this->load->library('GetAllCollectionListService');
		
		
	}

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{
		Template::set('toolbar_title', 'Cash Collection Details');

        //Template::set_view('account/account/index');
		Template::render();
	}

	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	 */
	 public function collectionList()
    {
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 	= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 		= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 		= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['trans_source_name'] = $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   = $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   = $this->input->post('collection_date_to');
		$source_name 						= $this->config->item('source_name');
		
		//=========== Get Collection List ==========
		// 1=Collection, 2=Refund, 3=Commission, 4=Discount, 5=Payment
		
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);

		// collection records
		$collection_records 				= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo			($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(1)  // 1=Collection
												->setCollectionSource	($src_diagnosis['trans_source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])														
												->setLimit($limit)
												->setOffset($offset)
												->execute();

		// refund records
		$refund_records 					= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(2)  // 2=Refund
												->setCollectionSource	($src_diagnosis['trans_source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		//commission records
		$commission_records 				= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(3)  // 4=Commission
												->setCollectionSource	($src_diagnosis['trans_source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		// Discount records
		$discount_records 					= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(4)  // 5=Discount
												->setCollectionSource	($src_diagnosis['trans_source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		
		$total 				= $getDiagnosisCLObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'collection_records' 		=> $collection_records,
			'refund_records' 			=> $refund_records,
			'commission_records' 		=> $commission_records,
			'discount_records' 			=> $discount_records,
			'src_diagnosis' 			=> $src_diagnosis,
			'source_name'				=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("transaction_View"));		
		Template::set_view('transaction/all_transaction_list');
		
		Template::render();
    }
	
	
	/*  collection list start */
	
	 public function show_collection()
    {
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 		= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 			= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 			= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['source_name'] 			= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   	= $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']    = $this->input->post('collection_date_to');
		$source_name 							= $this->config->item('source_name');
		
		//=========== Get Collection List ==========
		
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);

		// collection records
		$collection_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(1)  // 1=Collection
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])														
												->setLimit($limit)
												->setOffset($offset)
												->execute();

		
		
		
		$total 				= $getDiagnosisCLObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'collection_records' 		=> $collection_records,
			'src_diagnosis' 			=> $src_diagnosis,
			'source_name'				=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("collection_View"));		
		Template::set_view('transaction/collection_list');
		
		Template::render();
    }
	
	/*  collection list end */
	
	
	/*  Refund list start */
	
	 public function show_refund()
    {
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 	= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 		= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 		= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['source_name'] 		= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   = $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   = $this->input->post('collection_date_to');
		$source_name 						= $this->config->item('source_name');
		
		//=========== Get Collection List ==========
		
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);

		// refund records
		$refund_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(2)  // 1=Collection
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();

		
		
		
		$total 				= $getDiagnosisCLObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'refund_records' 			=> $refund_records,
			'src_diagnosis' 			=> $src_diagnosis,
			'source_name'				=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("refund_View"));		
		Template::set_view('transaction/refund_list');
		
		Template::render();
    }
	
	/*  Refund list end */
	
	/*  Commission list start */
	
	 public function show_commission()
    {
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 	= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 		= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 		= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['source_name'] 		= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   = $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   = $this->input->post('collection_date_to');
		$source_name 						= $this->config->item('source_name');
		
		//=========== Get Collection List ==========
		
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);

		// refund records
		$commission_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(3)  // 1=Collection
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();

		
		
		
		$total 				= $getDiagnosisCLObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'commission_records' 		=> $commission_records,
			'src_diagnosis' 			=> $src_diagnosis,
			'source_name'				=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("commission_View"));		
		Template::set_view('transaction/commission_list');
		
		Template::render();
    }
	
	/*  Commission list end */
	
	
	/*  discount list start */
	public function show_discount()
    {
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 	= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 		= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 		= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['source_name'] 		= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   = $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   = $this->input->post('collection_date_to');
		$source_name 						= $this->config->item('source_name');
		
		//=========== Get Collection List ==========
		
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);	
		// Discount records
		$discount_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(4)  // 5=Discount
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		
		$total 				= $getDiagnosisCLObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'discount_records' 			=> $discount_records,
			'src_diagnosis' 			=> $src_diagnosis,
			'source_name'				=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("transaction_View"));		
		Template::set_view('transaction/show_discount');
		
		Template::render();
    }
	
	/*  discount list end */
	
	
	/*  Payment List Start */
	public function show_payment()
    {
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 	= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 		= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 		= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['source_name'] 		= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   = $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   = $this->input->post('collection_date_to');
		$source_name 						= $this->config->item('source_name');
		
		//=========== Get Collection List ==========
		
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);	
		// Discount records
		$discount_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo		($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(4)  // 5=Discount
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		
		$total 				= $getDiagnosisCLObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'discount_records' 			=> $discount_records,
			'src_diagnosis' 			=> $src_diagnosis,
			'source_name'				=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("payment_information"));		
		Template::set_view('transaction/show_payment');
		
		Template::render();
    }
	
	/* Payment List End */
	
	public function GetUserTotalCollectionAmount($UserID){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$total_collection=0;
		$date = date("Y-m-d");
				
		if($UserID)
		{				
		$this->db->select(" SUM(amount) as total_collection",TRUE);	
		
		$where = array(
			'collection_by' 		=> $UserID,
			'Date(collection_date)' => $date,
			'transaction_type'		=> 1		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->total_collection >=0){
			$total_collection = $records->total_collection;
		}
		
		}
		return $total_collection;	
			
	}
	
	
	
	public function GetUserTotalRefundAmount($UserID){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$total_refund=0;
		$date = date("Y-m-d");
				
		if($UserID)
		{				
		$this->db->select(" SUM(amount) as total_refund",TRUE);	
		
		$where = array(
			'collection_by' 		=> $UserID,
			'Date(collection_date)' => $date,
			'transaction_type'		=> 2		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->total_refund >=0){
			$total_refund = $records->total_refund;
		}
		
		}
		return $total_refund;	
			
	}
	
	
	
	public function GetUserTotalCommissionAmount($UserID){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$total_commission=0;
		$date = date("Y-m-d");
				
		if($UserID)
		{				
		$this->db->select(" SUM(amount) as total_commission",TRUE);	
		
		$where = array(
			'collection_by' 		=> $UserID,
			'Date(collection_date)' => $date,
			'transaction_type'		=> 3		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->total_commission >=0){
			$total_commission = $records->total_commission;
		}
		
		}
		return $total_commission;	
			
	}
	
	
	
	public function GetUserTotalDiscountAmount($UserID){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$total_discount=0;
		$date = date("Y-m-d");
				
		if($UserID)
		{				
		$this->db->select(" SUM(amount) as total_discount",TRUE);	
		
		$where = array(
			'collection_by' 		=> $UserID,
			'Date(collection_date)' => $date,
			'transaction_type'		=> 4		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->total_discount >=0){
			$total_discount = $records->total_discount;
		}
		
		}
		return $total_discount;	
			
	}
	
	/*  Userwise list Start */
	public function show_userwise(){
		
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->library('doctrine');
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
		
		$this->db->select("td.collection_by,e.first_name,e.last_name");
		$this->db->from('transaction_details AS td');
		$this->db->join('users  as u', 'u.id = td.collection_by','left');
		$this->db->join('hrm_employees  as e', 'e.id = u.employee_id','left');
		$this->db->distinct("u.id");
		$userList = $this->collection_model->find_all();
		
		$userwise_collection_records;
		$userwise_refund_records;
		$userwise_commission_records;
		$userwise_discount_records;
		
		
		//var_dump($username);exit();
		//print $this->db->last_query();	die;	
		$src_diagnosis['collection_id'] 		= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 			= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 			= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['source_name'] 			= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_by'] 		= $this->input->post('transaction_userwise'); 
		$flag							 		= $this->input->post('transaction_userwise'); 
		$src_diagnosis['collection_date']  		= $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   	= $this->input->post('collection_date_to');
		$source_name 							= $this->config->item('source_name');
		
		
		//=========== Get Collection List ==========
		
		
		
		if($src_diagnosis['collection_by'] !="")
		{
			$total_collection ="" ;
			$total_refund     ="";
			$total_commission ="";
			$total_discount   ="";
			
			
			$getDiagnosisCLObj 	= new GetAllCollectionListService($this);	
			// Discount records
			$userwise_collection_records 		= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo			($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												->setCollectionBy		($src_diagnosis['collection_by'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(1)  // 1=Collection
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo	($src_diagnosis['collection_date_to'])														
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
			$userwise_refund_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo			($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												->setCollectionBy		($src_diagnosis['collection_by'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(2)  // 2=Refund
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])
												->setCollectionDateTo	($src_diagnosis['collection_date_to'])
												->setLimit($limit)
												->setOffset($offset)
												->execute();
												
			$userwise_commission_records 		= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo			($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												->setCollectionBy		($src_diagnosis['collection_by'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(3)  // 3=Commission
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo	($src_diagnosis['collection_date_to'])														
												->setLimit($limit)
												->setOffset($offset)
												->execute();

			$userwise_discount_records 			= $getDiagnosisCLObj	
												->setCollectionId		($src_diagnosis['collection_id'])
												->setPatientNo			($src_diagnosis['patient_id'])
												->setPatientName		($src_diagnosis['patient_name'])
												->setCollectionBy		($src_diagnosis['collection_by'])
												//->setContactNo		($src_diagnosis['contact_no'])
												->setTransactionType(4)  // 4=Discount
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo	($src_diagnosis['collection_date_to'])														
												->setLimit($limit)
												->setOffset($offset)
												->execute();
												
			$total 				= $getDiagnosisCLObj->getCount();
		
		}
		else
		{
			
			
			
			$userwise_collection_records	="";
			$userwise_refund_records		="";
			$userwise_commission_records	="";
			$userwise_discount_records		="";
			$total =0;
			
			$total_collection 		= array();
			$total_refund	 		= array();
			$total_commission 		= array();
			$total_discount		 	= array();
			
			foreach($userList as $row) :
			$row = (object) $row;
			$collection_by = $row->collection_by;
			
			$total_collection[$collection_by] 	= $this->GetUserTotalCollectionAmount($collection_by);
			$total_refund[$collection_by] 		= $this->GetUserTotalRefundAmount($collection_by);
			$total_commission[$collection_by] 	= $this->GetUserTotalCommissionAmount($collection_by);
			$total_discount[$collection_by] 	= $this->GetUserTotalDiscountAmount($collection_by);
			endforeach;
		}
		
		//print $this->db->last_query();die;
		
		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'userwise_collection_records' 		=> $userwise_collection_records,
			'userwise_refund_records' 			=> $userwise_refund_records,
			'userwise_commission_records' 		=> $userwise_commission_records,
			'userwise_discount_records' 		=> $userwise_discount_records,
			'src_diagnosis' 					=> $src_diagnosis,
			'source_name'						=> $source_name,
			'total_collection'					=> $total_collection,
			'total_refund'						=> $total_refund,
			'total_commission'					=> $total_commission,
			'total_discount'					=> $total_discount,
			'userList'							=> $userList,
			'flag'								=> $flag
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("userwise_transaction_View"));		
		Template::set_view('transaction/user_wise_transaction_list');
		
		Template::render();
		
		
		
	}
	
	/*  Userwise list end */
	
	
	/*  Dept Wise list Start */
	public function show_dept_wise(){
		
		
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
		$this->load->model('dept_wise_model', null, true);
		//$this->load->model('collection_model', null, true);
        $this->load->library('doctrine');
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
		
		
		//select sub department		
		$this->db->select("sd.subdept_name");
		$this->db->from('pathology_diagnosis_details AS pdd');
		$this->db->join('pathology_test_name  as ptn', 'ptn.id = pdd.test_id','left');
		$this->db->join('lib_department  as dep', 'dep.dept_id = ptn.test_sub_department','left');
		$this->db->join('pathology_sub_department  as sd', 'sd.dept_id = dep.dept_id','left');
		//$this->db->group_by('sd.id','left');
		$where = array(
			'dep.department_name' => 'Pathology'
		);
		$this->db->where($where);
		$this->db->distinct("sd.id");
		$subdept_name = $this->dept_wise_model->find_all();
		
		
		
		// select sub department resources
		$this->db->select("ptn.test_name,pdd.test_price,pdd.discount,pdd.total_price");
		$this->db->from('pathology_diagnosis_details AS pdd');
		$this->db->join('pathology_test_name  as ptn', 'ptn.id = pdd.test_id','left');
		$this->db->join('lib_department  as dep', 'dep.dept_id = ptn.test_sub_department','left');
		$this->db->join('pathology_sub_department  as sd', 'sd.dept_id = dep.dept_id','left');
		$this->db->distinct("dep.dept_id");
		$testdata = $this->dept_wise_model->find_all();
		//print $this->db->last_query();die;
		
		
		
		$this->db->select('amount');
		$this->db->from('transaction_details');
		$this->db->where('source_name',2);
		
		$total_amount = $this->dept_wise_model->find_all();
		
		
		$sendData = array(
			'subdept_name' 				=> $subdept_name,
			'total_amount' 				=> $total_amount,
			'testdata' 					=> $testdata
			
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("transaction_View"));		
		Template::set_view('transaction/dept_wise_transaction_list');
		
		Template::render();
		
	}
	/*  Dept Wise list end */
	
	
	
	
	
	/*  Doctor Wise list Start */
	public function show_doctor_wise()
	{
		
		$this->auth->restrict('Collection.Details.View');
		//load library		
       
        $this->load->model('doctor/doctor_model', null, true);
		$this->load->model('outdoor/outdoor_ticket_model', null, true);       
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		/*
		$this->load->library('doctrine');
		$this->load->library('GetAllDoctorCollectionListService');	
		
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
		
		$src_name['collection_date_start']   	= $this->input->post('collection_date_start');
		$src_name['collection_date_end']   		= $this->input->post('collection_date_end');
		$src_name['source_name']   				= $this->input->post('transaction_source_name');
		$source_name 							= $this->config->item('source_name');
		
	
		$this->db->select("
		
						ddi.doctor_type,
						ddi.doctor_id
						
						");
		$this->db->from('doctor_doctor_info AS ddi');
		$this->db->where('ddi.doctor_type', "1");
		$this->db->join('outdoor_patient_ticket  as odp', 'odp.doctor_id = ddi.doctor_id','left');
		$this->db->group_by('ddi.doctor_id');
		$this->db->distinct("ddi.id");
		
		
		$doctor = $this->doctor_model->find_all();
		$doctorId 		= array();
		$doctorIdType 	= array();
		
		foreach($doctor as $doctors)		
		{
			$doctors			= (object) $doctors;			
			$doctorId 	[]		= $doctors->doctor_id; 
			$doctorIdType[] 	= $doctors->doctor_type; 
			
		}
		
		//print_r($doctorId);
		
		//print $this->db->last_query();
		//=========== Get Collection List ==========
		
		$getDoctorCollectionObj 	= new GetAllDoctorCollectionListService($this);	
		// Discount records
		$doctorwise_records 			= $getDoctorCollectionObj	
												->setdoctorIdArray		($doctorId)	
												->setCollectionSource	($src_name['source_name'])
												->setCollectionDateStart($src_name['collection_date_start'])
												->setCollectionDateEnd  ($src_name['collection_date_end'] )
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		
		$total 				= $getDoctorCollectionObj->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;
		
		
		//print_r($doctorwise_records); die;

		 $this->pagination->initialize($this->pager);
			$sendData = array(
			'doctorwise_records' 		=> $doctorwise_records,
			'src_name' 					=> $src_name,
			'source_name'				=> $source_name
		); */
		
	
		
		
		
	// this is for another  query for show	
	$this->db->select("		
					emp.first_name,
					odp.ticket_fee,					
					odp.appointment_type,					
					SUM(if(odp.appointment_type=1,odp.ticket_fee,null)) as ticketFee,					
					SUM(if(odp.appointment_type=2, odp.ticket_fee, null)) as consultantFee, 
					COUNT(if(odp.appointment_type=1 and pm.sex=1, pm.sex, null)) as maleTicket, 
					COUNT(if(odp.appointment_type=2 and pm.sex=1, pm.sex, null)) as maleConsult,
					pm.sex	
						",FALSE);					
					
		$this->db->from("outdoor_patient_ticket  as odp");		
		$this->db->join("doctor_doctor_info AS ddi", "odp.doctor_id = ddi.id","left");
		$this->db->where("ddi.doctor_type", "1");	
		$this->db->join("hrm_employees as emp", "ddi.doctor_id = emp.id","left");
		$this->db->join("patient_master as pm", "pm.id=odp.patient_id","left");			
		$this->db->group_by("odp.id");
		$this->db->distinct("odp.id");
		
		
		$doctor = $this->outdoor_ticket_model->find_all();
		
		//print_r($doctor); die;
		//print($this->db->last_query()); die;
		
		
		$sendData = array(
			'doctor' 		=> $doctor
			
		);  
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("doctor_collection_View"));		
		Template::set_view('transaction/doctor_wise_collection_list');
		
		Template::render();
		
	}
	/*  Doctor Wise list end */	
	
	
	
	
	public function GetCollectionAmount($id){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$collection=0;
		$date = date("Y-m-d");
				
		if($id)
		{				
		$this->db->select(" amount as collection",TRUE);	
		
		$where = array(
			'Date(collection_date)' => $date,
			'transaction_type'		=> 1		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->collection >=0){
			$collection = $records->collection;
		}
		
		}
		return $collection;	
			
	}
	
	public function GetRefundAmount($id){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$refund=0;
		$date = date("Y-m-d");
				
		if($id)
		{				
		$this->db->select(" amount as refund",TRUE);	
		
		$where = array(
			'Date(collection_date)' => $date,
			'transaction_type'		=> 2		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->refund >=0){
			$refund = $records->refund;
		}
		
		}
		return $refund;	
			
	}
	
	public function GetCommissionAmount($id){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$commission=0;
		$date = date("Y-m-d");
				
		if($id)
		{				
		$this->db->select(" amount as commission",TRUE);	
		
		$where = array(
			'Date(collection_date)' => $date,
			'transaction_type'		=> 3		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->commission >=0){
			$commission = $records->commission;
		}
		
		}
		return $commission;	
			
	}
	
	public function GetDiscountAmount($id){	
	
		$this->load->model('collection_model', NULL, TRUE);
		$discount=0;
		$date = date("Y-m-d");
				
		if($id)
		{				
		$this->db->select(" amount as discount",TRUE);	
		
		$where = array(
			'Date(collection_date)' => $date,
			'transaction_type'		=> 4		
		);
		
		$records = $this->collection_model->find_by($where);
		
		//print $this->db->last_query(); die;
		if($records->discount >=0){
			$discount = $records->discount;
		}
		
		}
		return $discount;	
			
	}
	
	
	/*  Money Receive Wise list Start */
	public function show_money_receive_wise(){
		
		$this->auth->restrict('Collection.Details.View');
		//load library		
        $this->load->library('pagination');
        $this->load->model('doctor/doctor_model', null, true);
        $this->load->library('doctrine');
		$this->lang->load('transaction_list');
		$this->load->config('config_transaction');
		
		// select today's money receive no.
		$date = date("Y-m-d");
		$where = array(
			'Date(collection_date)' => $date	
		);	
		$this->collection_model->select('*',TRUE);	
		$this->db->where($where);			
		$mr_no = $this->collection_model->find_all();
		
		
		//var_dump($mr_no);exit();
		
		// Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');
		
				
		$src_diagnosis['collection_id'] 		= $this->input->post('collection_id'); 
		$src_diagnosis['patient_id'] 			= $this->input->post('diagnosis_patient_id'); 
		$src_diagnosis['patient_name'] 			= $this->input->post('diagnosis_patient_name');	
		$src_diagnosis['external_doc_name'] 	= $this->input->post('transaction_doctor_name'); 
		$src_diagnosis['source_name'] 			= $this->input->post('transaction_source_name'); 
		$src_diagnosis['collection_date']   	= $this->input->post('collection_date');
		$flag								   	= $this->input->post('collection_date');
		$src_diagnosis['collection_date_to']   	= $this->input->post('collection_date_to');
		$source_name 							= $this->config->item('source_name');
		
		
		
		//=========== Get Collection List ==========
		if($src_diagnosis['collection_date']==""){
			
			$total_collection ="" ;
			$total_refund     ="";
			$total_commission ="";
			$total_discount   ="";	
			
			
		$getDiagnosisCLObj 	= new GetAllCollectionListService($this);	
		// Discount records
		$money_receive_wise_records 			=$getDiagnosisCLObj	
												->setTransactionType(4)  // 5=Discount
												->setCollectionSource	($src_diagnosis['source_name'])
												->setCollectionDate		($src_diagnosis['collection_date'])														
												->setCollectionDateTo		($src_diagnosis['collection_date_to'])														
												->setLimit($limit)
												->setOffset($offset)
												->execute();
		
		
		$total 					= $getDiagnosisCLObj->getCount();
		}
		else
		{
			
			$total_collection 		= array();
			$total_refund	 		= array();
			$total_commission 		= array();
			$total_discount		 	= array();
			
			foreach($mr_no as $row) :
			$row = (object) $row;
			$id = $row->id;
			
			$total_collection[$id] 	= $this->GetCollectionAmount($id);
			$total_refund[$id] 		= $this->GetRefundAmount($id);
			$total_commission[$id] 	= $this->GetCommissionAmount($id);
			$total_discount[$id] 	= $this->GetDiscountAmount($id);
			endforeach;
			
		}
		
		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);
		
		$sendData = array(
			'money_receive_wise_records' 		=> $money_receive_wise_records,
			'src_diagnosis' 					=> $src_diagnosis,
			'flag' 								=> $flag,
			'mr_no' 							=> $mr_no,
			'total_collection' 					=> $total_collection,
			'total_refund' 						=> $total_refund,
			'total_commission' 					=> $total_commission,
			'total_discount' 					=> $total_discount,
			'source_name'						=> $source_name
		);
				
		Template::set('sendData', $sendData);		
		Template::set('toolbar_title', lang("transaction_View"));		
		Template::set_view('transaction/money_receive_wise_transaction');
		
		Template::render();
		
	}
	/*  Money Receive Wise list end */	
	
}