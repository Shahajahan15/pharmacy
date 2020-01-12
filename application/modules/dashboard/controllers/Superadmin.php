<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard controller
 */
class Superadmin extends Admin_Controller
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
        Assets::add_module_js('dashboard', 'dashboard.js');
	}

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{
        $sendData = array(
            'cashIn' => (float)$this->getTodayCashIn(),
            'opd' => (int)$this->getTodayOPD(),
            'ipd' => (int)$this->getIPD(),
            'emergency' => (int)$this->getEmergency(),
            'cashDailyChartData' => $this->getCashDailyChartData(),
            'cashMonthlyChartData' => $this->getCashMonthlyChartData(),
            'ticketDailyChartData' => $this->getTicketDailyChartData(),
            'ticketMonthlyChartData' => $this->getTicketMonthlyChartData(),
            'patientDailyChartData' => $this->getPatientDailyChartData(),
            'patientMonthlyChartData' => $this->getPatientMonthlyChartData(),
            'bedStatus' => $this->getBedStatus(),
            'cashCollection' => $this->getCashCollection(),
        );

        Template::set('toolbar_title', 'Dashboard');
        Template::set($sendData);
        $this->view('dashboard/admin', Template::getData());

       
	}

    public function getTodayCashIn()
    {
        $where['DATE(collection_date) >='] = date('Y-m-d');
        $data = @$this->db
                 ->select('
                        SUM(IF(tp.transaction_type = 3, 0, tp.amount)) as amount
                    ')
                 ->from('transaction_payment tp')
                 ->where($where)
                 ->get()->row()->amount;


        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

    public function getTodayOPD()
    {
        $where['DATE(created_time) >='] = date('Y-m-d');
        $data = @$this->db
                 ->select('COUNT(IF(is_refund = 0, 1, NULL)) as value')
                 ->from('outdoor_patient_ticket')
                 ->where($where)
                 ->get()->row()->value;

        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

    public function getIPD()
    {        
        $data = @$this->db
                 ->select('COUNT(IF(status = 0, 1, NULL)) as value')
                 ->from('admission_patient')
                 ->get()->row()->value;

        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

    public function getEmergency() 
    {
        $where['DATE(created_time) >='] = date('Y-m-d');
        $where['is_refund ='] = 0;
        $data = @$this->db
                 ->select('COUNT(IF(ticket_type = 2, 1, NULL)) as value')
                 ->from('outdoor_patient_ticket')
                 ->where($where)
                 ->get()->row()->value;

        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

	public function getTicketDailyChartData($length = 12)
    {
        $days = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $days[date("d, M", strtotime(date('Y-m-d') . " -$i days"))] = 0;
        }

        $lastDays = date("Y-m-d", strtotime(date('Y-m-d') . "-" . ($length - 1) . " days"));
        $where['DATE(created_time) >='] = $lastDays;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(created_time, \'%d,%b\') AS label,
                        COUNT(IF(is_refund = 0, 1, NULL)) as value
                    ')
                 ->from('outdoor_patient_ticket')
                 ->where($where)
                 ->group_by('DATE(created_time)')
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

    public function getTicketMonthlyChartData($length = 12)
    {
        $months = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $months[date("M, y", strtotime(date('Y-m-d') . " -$i months"))] = 0;
        }

        $lastMonths = date("Y-m", strtotime(date('Y-m-01') . "-" . ($length - 1) . " months"));        
        $where['DATE(created_time) >='] = $lastMonths;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(created_time, \'%b,%y\') AS label,
                        COUNT(IF(is_refund = 0, 1, NULL)) as value
                    ')
                 ->from('outdoor_patient_ticket')
                 ->where($where)                 
                 ->group_by('DATE_FORMAT(created_time, \'%Y%m\')')
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

    public function getPatientDailyChartData($length = 12)
    {
        $days = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $days[date("d, M", strtotime(date('Y-m-d') . " -$i days"))] = 0;
        }

        $lastDays = date("Y-m-d", strtotime(date('Y-m-d') . "-" . ($length - 1) . " days"));
        $where['DATE(booked_time) >='] = $lastDays;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(booked_time, \'%d,%b\') AS label,
                        COUNT(1) as value
                    ')
                 ->from('admission_patient')
                 ->where($where)
                 ->group_by('DATE(booked_time)')
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

    public function getPatientMonthlyChartData($length = 12)
    {
        $months = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $months[date("M, y", strtotime(date('Y-m-d') . " -$i months"))] = 0;
        }

        $lastMonths = date("Y-m", strtotime(date('Y-m-01') . "-" . ($length - 1) . " months"));
        $where['DATE(booked_time) >='] = $lastMonths;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(booked_time, \'%b,%y\') AS label,
                        COUNT(1) as value
                    ')
                 ->from('admission_patient')
                 ->where($where)
                 ->group_by('DATE_FORMAT(booked_time, \'%Y%m\')')
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

    public function getCashDailyChartData($length = 12) 
    {
        $days = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $days[date("d, M", strtotime(date('Y-m-d') . " -$i days"))] = 0;
        }

        $lastDays = date("Y-m-d", strtotime(date('Y-m-d') . "-" . ($length - 1) . " days"));
        $where['DATE(tp.collection_date) >='] = $lastDays;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(tp.collection_date, \'%d,%b\') AS label,
                        IF(tp.transaction_type = 3, 0, SUM(tp.amount)) as value
                    ')
                 ->from('transaction_payment tp')
                 ->where($where)
                 ->group_by('DATE(tp.collection_date)')
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

    public function getCashMonthlyChartData($length = 12)
    {
        $months = array();
        for ($i = $length - 1; $i >= 0; $i--) {
            $months[date("M, y", strtotime(date('Y-m-d') . " -$i months"))] = 0;
        }

        $lastMonths = date("Y-m", strtotime(date('Y-m-01') . "-" . ($length - 1) . " months"));
        $where['DATE(tp.collection_date) >='] = $lastMonths;
        $records = $this->db
                 ->select('
                        DATE_FORMAT(tp.collection_date, \'%b,%y\') AS label,
                        IF(tp.transaction_type = 3, 0, SUM(tp.amount)) as value
                    ')
                 ->from('transaction_payment tp')
                 ->where($where)
                 ->group_by('DATE_FORMAT(tp.collection_date, \'%Y%m\')')
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

    public function getBedStatus()
    {
        
        $bed = $this->db
            ->select('
                    COUNT(IF(bed_status = 1, 1, NULL)) AS free,
                    COUNT(IF(bed_status IN (2,3), 1, NULL)) AS reserved
                ')
            ->get('admission_bed')
            ->row();

        $data = array(
            array('label' => 'Free', 'value' => $bed ? $bed->free : 0 ),
            array('label' => 'Reserved', 'value' => $bed ? $bed->reserved : 0 )
        );

        if ($this->input->is_ajax_request()) {
            echo json_encode($data);
        }

        return $data;
    }

    public function getCashCollection()
    {

        $results = $this->db
                 ->select('
                        lc.counter_name,
                        SUM(IF(tp.transaction_type = 3, 0, tp.amount)) as amount
                    ')
                 ->from('lib_counter lc')
                 ->join('transaction_payment tp', 'lc.counter_id = tp.counter_id AND DATE(tp.collection_date) = "'.date('Y-m-d'). '"', 'left')
                 ->group_by('lc.counter_id')
                 ->get()->result_array();

        $data = array();
        foreach (@$results as $value) {
            $data[$value['counter_name']] = $value['amount'];
        }

        $data = json_encode(array('label' => array_keys($data), 'value' => array_values($data)));
        if ($this->input->is_ajax_request()) {
            echo $data;
        }

        return $data;
    }
}