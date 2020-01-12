<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Department controller
 */
class Department extends Admin_Controller
{

	//--------------------------------------------------------------------
	/**
	 * Constructor
	 *
	 * @return void
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('department_model', NULL, TRUE);
		$this->load->model('hrm/employee_model', NULL, TRUE);// didar
		$this->lang->load('initial');
		$this->lang->load('common');

		Template::set_block('sub_nav', 'initial/_sub_nav_department');

	}

	//--------------------------------------------------------------------

	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function show_list()
        {
            $this->auth->restrict('Library.Department.View');

            //======= Delete Multiple=======
            $checked = $this->input->post('checked');
            if (is_array($checked) && count($checked))
            {
				$this->auth->restrict('Library.Department.Delete');
				$result = FALSE;

				$data = array();
				$data['is_deleted'] 		= 1;
				$data['deleted_by']			= $this->current_user->id;
				$data['deleted_date']    	= date('Y-m-d H:i:s');

				foreach ($checked as $pid)
				{
					$result = $this->department_model->update($pid,$data);
				}

				if ($result){
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_delete_record')  .' : '. $this->input->ip_address(), 'library_department');

				Template::set_message(count($checked) .' '. lang('library_delete_success'), 'success');
				}else{
				Template::set_message(lang('library_delete_failure') . $this->department_model->error, 'error');
				}
            }
            $records = $this->department_model->find_all_by('is_deleted',0);
            Template::set('toolbar_title', lang("library_initial_department_view"));
            Template::set('records', $records);

            Template::set_view('initial/department_list');
            Template::render();
        }



	/**
	 * Creates a Group Details object.
	 *
	 * @return void
	 **/

	public function department_create()
    {
        //TODO you code here

		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_department())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('library_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'lib_department');

				Template::set_message(lang('department_details_create_success'), 'success');
				redirect(SITE_AREA .'/department/library/show_list');
			}
			else
			{
				Template::set_message(lang('record_create_failure').$this->department_model->error, 'error');
			}
		}


		$employee = $this->employee_model->find_all_by('STATUS', 1);
		$library_department['department_name']       = $this->input->post('lib_department_department_name');
		$library_department['department_phone']      = $this->input->post('lib_department_department_phone');

		Template::set('toolbar_title', lang("library_initial_department_new"));
		Template::set('library_department', $library_department);
		Template::set('employee', $employee);
		Template::set_view('initial/department_create');
		Template::render();
    }


	//--------------------------------------------------------------------
	/**
	 * Allows editing of Group Details data.
	 *
	 * @return void
	 */

	public function edit()
	{
		$id = $this->uri->segment(5);
		if (empty($id))
		{
			Template::set_message(lang('group_details_invalid_id'), 'error');
			redirect(SITE_AREA .'/department/library/show_list');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Library.Department.Edit');

			if ($this->save_department('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('group_details_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'library_department');

				Template::set_message(lang('department_details_update_success'), 'success');
				redirect(SITE_AREA .'/department/library/show_list');
			}
			else
			{
				Template::set_message(lang('department_details_edit_failure') . $this->department_model->error, 'error');
			}
		}




		$employee = $this->employee_model->find_all_by('STATUS', 1);
		Template::set('library_department', $this->department_model->find($id));
		Template::set('employee', $employee);
		Template::set('toolbar_title', lang('department_details_edit'));

        Template::set_view('initial/department_create');
		Template::render();
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_department($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want

		$data = array();
		$data['department_name']            		= $this->input->post('lib_department_department_name');

		$data['department_head']            		= $this->input->post('library_initial_department_head');
		$data['department_code']            		= $this->input->post('lib_department_code');
		$data['department_phone']          			= $this->input->post('lib_department_department_phone');
		$data['status']          					= $this->input->post('bf_status');

		if ($type == 'insert')
		{
			$this->auth->restrict('Library.Department.Create');
			$data['created_by']          			= $this->current_user->id;
			$id = $this->department_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			}
			else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$data['modify_by']          = $this->current_user->id;
			$data['modify_date']    	= date('Y-m-d H:i:s');
			$this->auth->restrict('Library.Department.Edit');
			$return = $this->department_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------

	public function checkDepartmentNameAjax()
	{

		$departmentName			= $this->input->post('departmentName');

		if(trim($departmentName)!= '')
		{
			$res =$this->db->query("SELECT department_name FROM bf_lib_department WHERE  department_name  LIKE '%$departmentName%'");

			$result = $res->num_rows();
			if($result > 0 )
			{
				echo 'Department Name Already Exist !!';

			}else
			{

			}

		}
	}

	public function search_department()
    {
        $query = $this->input->get('q');

        $where = array(
            'department_name LIKE' => '%'.$query.'%',
        );

        $departments = $this->department_model->as_array()->limit(50)->find_all_by($where, null, 'or');

        $items = array();
        // $assoc = array();
        foreach($departments as $department) {
            $items[] = array(
                'id' => $department['dept_id'],
                'text' => $department['department_name'],
            );

            // $assoc[$patient['id']] = $patient;
        }

        $status_code = 200;
        $json = array(
            'items' => $items,
            // 'assoc' => $assoc,
        );

        return $this->output->set_status_header($status_code)
                            ->set_content_type('application/json')
                            ->set_output(json_encode($json));
    }


}