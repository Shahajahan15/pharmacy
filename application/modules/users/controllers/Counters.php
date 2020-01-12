<?php defined('BASEPATH') || exit('No direct script access allowed');

class Counters extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('library/counter_model');
    }

    public function index()
    {
        $adapter = $this->cache->get_adapter();

        //0nly admin role user can reset
        if ($this->session->userdata('role_id') == 1 && $id = $this->input->post('counter_id')) {
            $key = 'counter:' . $id;
            if (ENVIRONMENT != 'development' && $this->cache->{$adapter}->is_supported()) {
                $this->cache->{$adapter}->delete($key);
                Template::set_message('Counter has been successfully reset.', 'success');
                redirect('/admin/counters/users');
            }
        }

        $counters = $this->counter_model->find_all();
        $records = [];
        foreach ($counters as $counter) {
            if (ENVIRONMENT != 'development' && $this->cache->{$adapter}->is_supported()) {
                $key = 'counter:' . $counter->counter_id;
                if ($counter->counter_id == $this->cache->{$adapter}->get($key)) {
                    $records[] = $counter;
                }
            }
        }

        Template::set('records', $records);
        Template::set('toolbar_title', 'Counter Reset');
        Template::set_view('users/counter_reset_list');
        Template::render();
    }
}