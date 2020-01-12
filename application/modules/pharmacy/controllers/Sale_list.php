
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Diagnosis controller
 */
class Sale_list extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */
	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->load->model('pharmacy_sales_dtls_model', NULL, TRUE);
		$this->load->model('pharmacy_sales_mst_model', NULL, TRUE);
		$this->load->model('product_model', NULL, TRUE);
		$this->load->model('customer_model', NULL, TRUE);
		$this->lang->load('main_pharmacy');
		$this->load->library('pharmacyCommonService');
		Assets::add_module_js('pharmacy', 'main_pharmacy_sale.js');

	}

    public function main_pharmacy_sale_list(){
        $records=$this->db
        ->select('psm.*')
        ->from('pharmacy_sales_mst as psm')
        ->join('pharmacy_sales_dtls as psd','psd.master_id=psm.id')

        ->get()
        ->result();
        Template::set('records',$records);
        Template::set('toolbar_title','Main Pharmacy Sale List');
        Template::set_view('main_pharmacy_sale_list/list');
        Template::render();
    }

	
}






