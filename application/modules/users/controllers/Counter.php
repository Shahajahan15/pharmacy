<?php defined('BASEPATH') || exit('No direct script access allowed');

class Counter extends Front_Controller
{

    /**
     * Setup
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('library/counter_model');
    }

    public function index()
    {
        if ($this->session->userdata('role_id') != 10 || $this->session->has_userdata('counter_id')) {
            redirect('/');
        }

        $adapter = $this->cache->get_adapter();

        if ($id = $this->input->post('counter_id')) {
            $key = 'counter:' . $id;
            if (ENVIRONMENT != 'development' && $this->cache->{$adapter}->is_supported()) {
                $data = $this->cache->{$adapter}->get($key);
                if ($data == $id) {
                    Template::set_message('This counter has been already booked. You can choose another one.', 'error');
                    redirect('/admin/counter/users');
                }
            }
            // Save into the cache for 10 hours. [FYI: 30 minutes = 7200]
            if (ENVIRONMENT != 'development' && $this->cache->{$adapter}->is_supported()) {
                $this->cache->{$adapter}->save($key, $id, 144000);
            }
            $this->session->set_userdata('counter_id', $id);
            redirect('/');
        }

        $counters = $this->counter_model->where(array('counter_status' => 1))->find_all();
        $freeCounters = [];
        foreach ($counters as $counter) {
            if(ENVIRONMENT != 'development' && $this->cache->{$adapter}->is_supported()) {
                $key = 'counter:' . $counter->counter_id;
                if ($counter->counter_id == $this->cache->{$adapter}->get($key)) {
                    continue;
                }
            }

            $freeCounters[] = $counter;
        }

        Template::set('counters', $freeCounters);
        Template::set('page_title', 'Select Counter');
        Template::set_view('users/counter');
        Template::render('login');
    }
}