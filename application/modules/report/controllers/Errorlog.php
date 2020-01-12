<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Report controller
 */
class Errorlog extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

        $this->load->model('errorlogs_model');
        $this->lang->load('errorlog');

        Assets::add_module_js('report', 'report.js');
        Assets::add_module_css('report', 'report.css');
	}
	
	public function index()
	{

        //load library
        $this->load->library('pagination');
        $this->load->library('doctrine');
        $this->load->library('GetErrorLogDetailsService');

        // Pagination
        $this->load->library('pagination');
        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');

        $getErrorLogDetails = new GetErrorLogDetailsService($this);
        $result = $getErrorLogDetails
                                ->setLimit($limit)
                                ->setOffset($offset)
                                ->execute();

        $total = $getErrorLogDetails->getCount();

		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;

		$this->pagination->initialize($this->pager);

		Template::set('results', $result);

		Template::set("toolbar_title", lang("errorlog_report"));
        Template::set_view('report/errorlog/errorlog');
		Template::render();
	}
			
}