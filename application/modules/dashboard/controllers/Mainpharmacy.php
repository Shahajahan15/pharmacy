<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard controller
 */
class Mainpharmacy extends Admin_Controller
{

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

        Assets::add_js(Template::theme_url('js/plugins/charts/Chart.min.js'));
        Assets::add_module_css('dashboard', 'dashboard.css');
        Assets::add_module_js('dashboard', 'pharmacy_dashboard.js');
	}

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{
        $sendData = array(
            'mcashIn' => (float)$this->getTodayCashIn(),
            'mcashRn' => (float)$this->getTodayReturn(),
            'scashIn' => (int)$this->getTodaySubCashIn(),
            'scashRn' => (int)$this->getTodaySubReturn(),
            'cashMainDailyChartData' => $this->getCashMainDailyChartData(),
            'cashSubDailyChartData' => $this->getSubCashDailyChartData(),
            'cashMainMonthlyChartData' => $this->getMainCashMonthlyChartData(),
            'cashSubMonthlyChartData' => $this->getSubCashMonthlyChartData(),
        );

        Template::set('toolbar_title', 'Pharmacy Dashboard');
        Template::set($sendData);
        

        $this->view('dashboard/mainpharmacy', Template::getData());

        
	}

    public function getTodayCashIn()
    {
        $where['DATE(create_time) >='] = date('Y-m-d');
        $data = @$this->db
                 ->select('
                        SUM(IF(ppt.type = 3, 0, ppt.amount)) as amount
                    ')
                 ->from('bf_pharmacy_payment_transaction ppt')
                 ->where($where)
                 ->get()->row()->amount;


        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

    public function getTodayReturn()
    {
        $where['DATE(create_time) >='] = date('Y-m-d');
        $data = @$this->db
                 ->select('
                        SUM(IF(ppt.type = 3,ppt.amount,0)) as amount
                    ')
                 ->from('bf_pharmacy_payment_transaction ppt')
                 ->where($where)
                 ->get()->row()->amount;


        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

    public function getTodaySubCashIn()
    {
        $where['DATE(create_time) >='] = date('Y-m-d');
        $data = @$this->db
                 ->select('
                        SUM(IF(pspt.type = 3, 0, pspt.amount)) as amount
                    ')
                 ->from('bf_pharmacy_sub_payment_transaction pspt')
                 ->where($where)
                 ->get()->row()->amount;


        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

        public function getTodaySubReturn()
    {
        $where['DATE(create_time) >='] = date('Y-m-d');
        $data = @$this->db
                 ->select('
                        SUM(IF(pspt.type = 3,pspt.amount,0)) as amount
                    ')
                 ->from('bf_pharmacy_sub_payment_transaction pspt')
                 ->where($where)
                 ->get()->row()->amount;


        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }


    public function getCashMainDailyChartData($length = 12) 
    {
        $days = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $days[date("d, M", strtotime(date('Y-m-d') . " -$i days"))] = 0;
        }

        $lastDays = date("Y-m-d", strtotime(date('Y-m-d') . "-" . ($length - 1) . " days"));
        $where['DATE(tp.create_time) >='] = $lastDays;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(tp.create_time, \'%d,%b\') AS label,
                        IF(tp.type = 3, 0, SUM(tp.amount)) as value
                    ')
                 ->from('bf_pharmacy_payment_transaction tp')
                 ->where($where)
                 ->group_by('DATE(tp.create_time)')
                 ->get()->result_array();

        foreach (@$records as $val) {
            if (isset($days[$val['label']])) {
                $days[$val['label']] = $val['value'];
            }
        }

        $data = json_encode(array('label' => array_keys($days), 'value' => array_values($days)));
        if ($this->input->is_ajax_request()) {
            echo $data;
        }

        return $data;
    }

    public function getSubCashDailyChartData($length = 12) 
    {
        $days = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $days[date("d, M", strtotime(date('Y-m-d') . " -$i days"))] = 0;
        }

        $lastDays = date("Y-m-d", strtotime(date('Y-m-d') . "-" . ($length - 1) . " days"));
        $where['DATE(tp.create_time) >='] = $lastDays;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(tp.create_time, \'%d,%b\') AS label,
                        IF(tp.type = 3, 0, SUM(tp.amount)) as value
                    ')
                 ->from('bf_pharmacy_sub_payment_transaction tp')
                 ->where($where)
                 ->group_by('DATE(tp.create_time)')
                 ->get()->result_array();

        foreach (@$records as $val) {
            if (isset($days[$val['label']])) {
                $days[$val['label']] = $val['value'];
            }
        }

        $data = json_encode(array('label' => array_keys($days), 'value' => array_values($days)));
        if ($this->input->is_ajax_request()) {
            echo $data;
        }

        return $data;
    }

    public function getMainCashMonthlyChartData($length = 12)
    {
        $months = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $months[date("M, y", strtotime(date('Y-m-d') . " -$i months"))] = 0;
        }

        $lastMonths = date("Y-m", strtotime(date('Y-m-01') . "-" . ($length - 1) . " months"));
        $where['DATE(tp.create_time) >='] = $lastMonths;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(tp.create_time, \'%b,%y\') AS label,
                        IF(tp.type = 3, 0, SUM(tp.amount)) as value
                    ')
                 ->from('bf_pharmacy_payment_transaction tp')
                 ->where($where)
                 ->group_by('DATE_FORMAT(tp.create_time, \'%Y%m\')')
                 ->get()->result_array();

        foreach (@$records as $val) {
            if (isset($months[$val['label']])) {
                $months[$val['label']] = $val['value'];
            }
        }

        $data = json_encode(array('label' => array_keys($months), 'value' => array_values($months)));
        if ($this->input->is_ajax_request()) {
            echo $data;
        }

        return $data;
    }

        public function getSubCashMonthlyChartData($length = 12)
    {
        $months = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $months[date("M, y", strtotime(date('Y-m-d') . " -$i months"))] = 0;
        }

        $lastMonths = date("Y-m", strtotime(date('Y-m-01') . "-" . ($length - 1) . " months"));
        $where['DATE(tp.create_time) >='] = $lastMonths;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(tp.create_time, \'%b,%y\') AS label,
                        IF(tp.type = 3, 0, SUM(tp.amount)) as value
                    ')
                 ->from('bf_pharmacy_sub_payment_transaction tp')
                 ->where($where)
                 ->group_by('DATE_FORMAT(tp.create_time, \'%Y%m\')')
                 ->get()->result_array();

        foreach (@$records as $val) {
            if (isset($months[$val['label']])) {
                $months[$val['label']] = $val['value'];
            }
        }

        $data = json_encode(array('label' => array_keys($months), 'value' => array_values($months)));
        if ($this->input->is_ajax_request()) {
            echo $data;
        }

        return $data;
    }
}