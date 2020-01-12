<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use app\classes\filters\lists\hrm\EmployeeList;
/**
 * Patient Admission  controller
 */
class Employee extends Admin_Controller
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

        $this->load->model('employee_model', NULL, TRUE);// didar
        $this->load->model('library/designation_info_model', NULL, TRUE);// didar

        $this->load->model('employee_exam_model', NULL, TRUE);
        $this->load->model('emp_personal_info_model', NULL, TRUE);
        //  $this->load->model('emp_personal_info_model', NULL, TRUE);

        $this->load->model('emp_contact_info_model', NULL, TRUE);
        $this->load->model('emp_curriculum_info_model', NULL, TRUE);
        $this->load->model('employee_family_model', NULL, TRUE);
        $this->load->model('emp_job_experience_model', NULL, TRUE);
        $this->load->model('emp_training_model', NULL, TRUE);
        $this->load->model('emp_important_documentation_model', NULL, TRUE);
        $this->load->model('employee_weekend_model', NULL, TRUE);
        $this->load->config('config_employee');
        $this->lang->load('common');
        $this->lang->load('employee');
        Assets::add_module_js('hrm', 'employee.js');

        //Template::set_block('sub_nav', 'employee/_sub_nav_employee');
    }
    /* Construct End Here*/

    //--------------------------------------------------------------------
    /**
     * Displays a list of form data.
     *
     * @return void
     */
    //--------------------------------------------------------------------

    public function show_list()
    {

        $this->auth->restrict('HRM.Employee.View');
        $this->load->library('pagination');
        $offset = (int)$this->input->get('per_page');
        $limit = isset($_POST['per_page']) ? $this->input->post('per_page') : 25;
        $sl = $offset;
        $data['sl'] = $sl;

        $search_box = $this->searchpanel->getSearchBox($this->current_user);
        $search_box['common_text_search_flag'] = 0;
        $search_box['common_text_search_label'] = 'Employee Code';
        $search_box['ticket_no_flag'] = 0;
        $search_box['sex_list_flag'] = 1;
        $search_box['appointment_type_flag'] = 0;
        $search_box['empType_list_flag'] = 1;
        $search_box['employee_name_list_flag'] = 1;
        $search_box['employee_mobile_list_flag'] = 1;
        $search_box['employee_name_flag'] = 0;
        $search_box['employee_code_flag'] = 1;

        $search_box['designation_list_flag'] = 1;
        $search_box['department_test_list_flag'] = 1;

        $search_box['by_date_flag'] = 0;
        $search_box['from_date_flag'] = 1;
        $search_box['to_date_flag'] = 1;

        $con['hrm_ls_employee.status >='] = 0;

        if (count($_POST) > 0) {
            //echo"<pre>";print_r($_POST);die();
            if ($this->input->post('employee_name')) {
                $con['hrm_ls_employee.EMP_ID like'] = '%' . trim($this->input->post('employee_name')) . '%';
            }
            if ($this->input->post('employee_mobile')) {
                $con['hrm_ls_employee.EMP_ID like'] = '%' . trim($this->input->post('employee_mobile')) . '%';
            }
            if ($this->input->post('emp_code')) {
                $con['hrm_ls_employee.EMP_CODE like'] = '%' . trim($this->input->post('emp_code')) . '%';
            }
            if ($this->input->post('employee_type')) {
                $con['hrm_ls_employee.EMP_TYPE'] = $this->input->post('employee_type');
            }
            if ($this->input->post('designation')) {
                $con['hrm_ls_employee.EMP_DESIGNATION'] = $this->input->post('designation');
            }
            if ($this->input->post('department')) {
                $con['hrm_ls_employee.EMP_DEPARTMENT'] = $this->input->post('department');
            }
            if ($this->input->post('sex')) {
                $con['hrm_ls_employee.GENDER LIKE'] = '%' . trim($this->input->post('sex')) . '%';
            }
            if ($this->input->post('from_date')) {
                $con['hrm_ls_employee.JOINNING_DATE >='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('from_date'))));
            }
            if ($this->input->post('to_date')) {
                $con['hrm_ls_employee.JOINNING_DATE <='] = date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('to_date'))));
            }
        }

        $records = $this->db->select('
				SQL_CALC_FOUND_ROWS 
				bf_hrm_ls_employee.*,lib_designation_info.DESIGNATION_NAME,
				lib_department.department_name,
				bf_lib_employee_type_setup.emp_type,
				bf_hrm_ls_emp_contacts.MOBILE
			', false)
            ->join('lib_designation_info', 'lib_designation_info.DESIGNATION_ID=bf_hrm_ls_employee.EMP_DESIGNATION')
            ->join('lib_department', 'lib_department.dept_id=bf_hrm_ls_employee.EMP_DEPARTMENT')
            ->join('bf_lib_employee_type_setup', 'bf_lib_employee_type_setup.id=bf_hrm_ls_employee.EMP_TYPE', 'left')
            ->join('bf_hrm_ls_emp_contacts', 'bf_hrm_ls_emp_contacts.EMP_ID=bf_hrm_ls_employee.EMP_ID', 'left')
            ->where($con)
            ->order_by('EMP_ID', 'DESC')
            ->limit($limit, $offset)
            ->get('bf_hrm_ls_employee')
            ->result();
        //echo"<pre>";print_r($records);die();

        //====== Load Static List value from Config ==========

        $data['records'] = $records;

        $total = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

        $this->pager['base_url'] = site_url() . '/admin/employee/hrm/show_list' . '?';
        $this->pager['total_rows'] = $total;
        $this->pager['per_page'] = $limit;
        $this->pager['page_query_string'] = TRUE;
        $this->pagination->initialize($this->pager);
        $data['sex'] = $this->config->item('gender_list');
        $data['empType'] = $this->db->get('bf_lib_employee_type_setup')->result();

        $list_view = 'employee/employee_list';

        if ($this->input->is_ajax_request()) {
            echo $this->load->view($list_view, $data, true);
            exit;
        }


        Template::set('list_view', $list_view);
        Template::set($data);
        Template::set('search_box', $search_box);
        Template::set('toolbar_title', 'Employee List');
        Template::set_view('report_template');
        Template::set_block('sub_nav', 'employee/_sub_nav_employee');
        Template::render();

    }
//    List view with datatable and new Search panel
    public function show_list_new()
    {
        $this->auth->restrict('HRM.Employee.View');

        $obj = new EmployeeList();
        $data['columns'] = $obj->getColumns();

        if ( $this->isAjax() ) {
            return $this->json($obj->execute());
        }

        Template::set($data);
        Template::set('filter', $obj->getFilters());
        Template::set('toolbar_title', 'Employee List');

        $this->view('hrm.employee.employee_list', Template::getData());
    }


    public function view_employee()
    {
        $id = $this->uri->segment(5);
        $designation_list = $this->designation_info_model->find_all();
        //$designation_list = $this->Emp_curriculum_exam_model->find_all();

        $sex = $this->config->item('gender_list');
        $marital_status = $this->config->item('marital_status');

        $this->db->select("
							em.*,lib_designation_info.DESIGNATION_NAME,lib_department.department_name,exam_exam.exam_name,exam_exam_board.exam_board,bf_hrm_ls_emp_education.PASS_YEAR,bf_hrm_ls_emp_education.SCORE,bf_hrm_ls_emp_education.EARNED_SCORE,bf_hrm_ls_emp_contacts.TELEPHONE,bf_hrm_ls_emp_contacts.ALTERNATIVE_MOBILE,bf_hrm_ls_emp_contacts.MOBILE,bf_hrm_ls_emp_contacts.EMAIL,bf_hrm_ls_emp_contacts.PERMANENT_CITY_VILLAGE,bf_hrm_ls_emp_contacts.PRESENT_CITY_TOWN						
						 ");

        $this->db->from('hrm_ls_employee as em');
        $this->db->where("em.EMP_ID", "$id");
        $this->db->join('lib_designation_info', 'lib_designation_info.	DESIGNATION_ID = em.EMP_DESIGNATION', 'left');
        $this->db->join('lib_department', 'lib_department.dept_id= em.EMP_DEPARTMENT', 'left');
        $this->db->join('bf_hrm_ls_emp_education', 'bf_hrm_ls_emp_education.EMP_ID= em.EMP_ID', 'left');
        $this->db->join('exam_exam', 'exam_exam.id= bf_hrm_ls_emp_education.EXAMCODE_ID', 'left');
        $this->db->join('exam_exam_board', 'exam_exam_board.id= bf_hrm_ls_emp_education.BOARD_UNIV', 'left');

        $this->db->join('bf_hrm_ls_emp_contacts', 'bf_hrm_ls_emp_contacts.EMP_ID= em.EMP_ID', 'left');
        //$this->db->distinct("di.DESIGNATION_ID");

        $records = $this->employee_model->find($id);
        //print_r($records);exit();
        $edu_info = $this->db->select("exam_exam.exam_name,exam_exam_board.exam_board,bf_hrm_ls_emp_education.PASS_YEAR,bf_hrm_ls_emp_education.SCORE,bf_hrm_ls_emp_education.EARNED_SCORE")
            ->from('hrm_ls_employee as em')
            ->where("em.EMP_ID", "$id")
            ->join('bf_hrm_ls_emp_education', 'bf_hrm_ls_emp_education.EMP_ID= em.EMP_ID', 'left')
            ->join('exam_exam', 'exam_exam.id= bf_hrm_ls_emp_education.EXAMCODE_ID', 'left')
            ->join('exam_exam_board', 'exam_exam_board.id= bf_hrm_ls_emp_education.BOARD_UNIV', 'left')
            ->get()
            ->result();
        $family_info = $this->employee_family_model->where('EMP_ID', $id)->find_all();

        $sendData = array(
            'records' => $records,
            'sex' => $sex,
            'marital_status' => $marital_status,
            // 'edu_info'=>$edu_info
            'familys' => $family_info,
            'relation' => $this->config->item('family_relation'),

        );
        //  echo '<pre>';print_r($sendData);exit();
        //      $data=array();
        Template::set('edu_info', $edu_info);

        Template::set('sendData', $sendData);

        Template::set_view('employee/view_employee');
        //Template::set_block('sub_nav', 'employee/_sub_nav_employee');

        Template::render();
    }

    //=====Employee Tab Start Here========//
    public function employee_tab()
    {
        $this->load->model('users/user_model');
        $employeeId = (int)$this->uri->segment(5);
        $user = $this->user_model->find_by(array('employee_id' => $employeeId));
        $user_id = $user ? $user->id : 0;

        $tab_active = $this->uri->segment(6);
        //echo ($tab_active); Exit;
        if (trim($tab_active) == "") {
            $tab_active = "#emp_personal_info";
        } else {
            $tab_active = "#" . $tab_active;
        }
        $tab_url = $this->getTabURL($tab_active);
        Template::set("toolbar_title", "Employee Create");
        Template::set("employeeId", $employeeId);
        Template::set("tab_active", $tab_active);
        Template::set("tab_url", $tab_url);
        Template::set("user_id", (int)$user_id);
        Template::set_block('sub_nav', 'employee/_sub_nav_employee');

        Template::render();
    }

    // Load Redirect Tab
    public function getTabURL($tab_id)
    {
        $tabURL = "";
        if (trim($tab_id) == "#emp_personal_info") {
            $tabURL = "employee/hrm/employee_personal_info/";
        } elseif (trim($tab_id) == "#employee_contact_info") {
            $tabURL = "employee/hrm/employee_contact_info/";
        } elseif (trim($tab_id) == "#employee_family_info") {
            $tabURL = "employee/hrm/employee_family_info/";
        } elseif (trim($tab_id) == "#emp_curriculam_info") {
            $tabURL = "employee/hrm/emp_curriculam_info/";
        } elseif (trim($tab_id) == "#employee_job_experience_info") {
            $tabURL = "employee/hrm/employee_job_experience_info/";
        } elseif (trim($tab_id) == "#employee_training_info") {
            $tabURL = "employee/hrm/employee_training_info/";
        } elseif (trim($tab_id) == "#emp_important_documentation") {
            $tabURL = "employee/hrm/emp_important_documentation/";
        } elseif (trim($tab_id) == "#employee_bank_info") {
            $tabURL = "employee/hrm/employee_bank_info/";
        } elseif (trim($tab_id) == "#employee_posting_info") {
            $tabURL = "employee/hrm/employee_posting_info/";
        } elseif (trim($tab_id) == "#emp_weekend_define") {
            $tabURL = "employee/hrm/emp_weekend_define/";
        } elseif (trim($tab_id) == "#emp_policy_tagging") {
            $tabURL = "employee/hrm/emp_policy_tagging/";
        } elseif (trim($tab_id) == "#emp_appointment_info") {
            $tabURL = "employee/hrm/employeeAppointmentInfo/";
        }


        return $tabURL;
    }


    //========Start Personal Info ========//
    public function employee_personal_info()
    {
        $this->auth->restrict('HRM.Employee.View');

        $employeeId = (int)$this->uri->segment(5);

        if (isset($_POST['save'])) {
            $this->db->trans_start();
            //echo '<pre>'; print_r($_POST);die();
            $EMP_ID = $this->saveEmployee($employeeId);

            if ((int)$EMP_ID > 0) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $EMP_ID . ' : ' . $this->input->ip_address(), 'hrm_ls_employee');
                Template::set_message(lang('bf_msg_create_success'), 'success');

                $this->db->trans_complete();

                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $EMP_ID . '/employee_contact_info');

            } else {
                $this->db->trans_rollback();

                Template::set_message(lang('bf_msg_create_failure') . $this->employee_model->error, 'error');
            }


            $this->db->trans_complete();

        }

        $this->load->model('library/department_model', NULL, TRUE);
        $this->load->model('library/designation_info_model', NULL, TRUE);
        $this->load->model('library/grade_info_model', NULL, TRUE);
        $this->load->model('library/quata_info_model', NULL, TRUE);

        $employee_details = 's';
        if ($employeeId > 0) {
            $employee_details = $this->employee_model->find($employeeId);

        }

        $data = array();
        $data['employee_details'] = $employee_details;
        $data['marital_status'] = $this->config->item('marital_status');
        $data['status'] = $this->config->item('status');
        $data['nationality'] = $this->config->item('nationality');
        $data['emp_type'] = $this->db->get('bf_lib_employee_type_setup')->result();
        $data['blood_group'] = $this->config->item('blood_group');
        $data['religion'] = $this->config->item('religion');
        $data['gender_list'] = $this->config->item('gender_list');
        $data['job_nature'] = $this->config->item('job_nature');

        $data['grade_list'] = $this->grade_info_model->find_all();
        $data['department_list'] = $this->department_model->find_all();
        $data['designation_list'] = $this->designation_info_model->find_all();
        //print_r($data['designation_list']);
        $data['employeeId'] = $employeeId;

        echo $this->load->view('employee/employee_personal_info', $data, TRUE);
        Template::set_block('sub_nav', 'employee/_sub_nav_employee');

    }

    //==============Save Personal info =========//
    private function saveEmployee($id = 0)
    {

        // make sure we only pass in the fields we want
        $data = array();
        $data['EMP_NAME'] = $this->input->post('hrm_employee_name');
        $data['EMP_FATHER_NAME'] = $this->input->post('hrm_employee_father');
        $data['EMP_MOTHER_NAME'] = $this->input->post('hrm_employee_mother');
        $data['BIRTH_DATE'] = date("Y-m-d", strtotime($this->input->post('hrm_employee_birth_day')));
        $data['BIRTH_PLACE'] = $this->input->post('hrm_employee_birth_place');
        $data['EMP_BLOOD_GROUP'] = $this->input->post('hrm_blood_group');
        $data['GENDER'] = $this->input->post('hrm_employee_sex');
        $data['RELIGION'] = $this->input->post('hrm_employee_religion');
        $data['MARITAL_STATUS'] = $this->input->post('hrm_employee_marital_status');
        $data['NATIONAL_ID'] = $this->input->post('hrm_employee_national_id');
        $data['PASSPORT_NO'] = $this->input->post('hrm_employee_passport_no');
        $data['DRIVING_LICENCE'] = $this->input->post('hrm_employee_driving_licence');
        $data['NATIONALITY'] = $this->input->post('hrm_employee_nationality');
        $data['EMP_GRADE'] = $this->input->post('hrm_employee_grade');
        $data['EMP_TYPE'] = $this->input->post('hrm_employee_type');
        $data['EMPLOYEE_ID'] = $this->input->post('employee_id');
        $data['EMP_JOB_NATURE'] = $this->input->post('hrm_employee_job_nature');
        $data['EMP_DEPARTMENT'] = $this->input->post('employee_emp_department');
        $data['EMP_DESIGNATION'] = $this->input->post('hrm_employee_designation');
        $data['QUALIFICATION'] = $this->input->post('emp_designation_two');
        $data['JOINNING_DATE'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('employee_joining_date'))));
        $data['JOB_CONFIRM_DATE'] = date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('employee_confirmarion_date'))));
        //$data['QUOTA_ID'] 						= 1;
        $data['STATUS'] = $this->input->post('employee_status');
        $data['CODE'] = $this->employee_model->get_doctor_code($data['EMP_TYPE']);
        $data['EMP_CODE'] = $this->employee_model->get_emp_code();
        //echo '<pre>';print_r($data);exit;
        // print_r($_FILES);
        // exit();
        if ($id == 0) {
            //echo "Insert";exit;
            $this->auth->restrict('HRM.Employee.Create');
            $data['CREATED_BY'] = $this->current_user->id;

            $return = $this->employee_model->insert($data);

            if ($_FILES['hrm_employee_photo']['name'] && $return) {
                $this->do_upload($return);
            }

            return $return;

        } elseif ($id > 0) {
            //echo $id;exit;
            $employeeId = $id;
            $data['MODIFY_BY'] = $this->current_user->id;
            $data['MODIFIED_DATE'] = date('Y-m-d H:i:s');
            unset($data['CODE']);
            unset($data['EMP_CODE']);
            $return = $this->employee_model->update($employeeId, $data);

            if ($_FILES['hrm_employee_photo']['name'] && $return) {
                $this->do_upload($employeeId);
            }

            return $employeeId;
        }


    }


    ///upload picture
    public function do_upload($employeeId)
    {
        $config['upload_path'] = "../public/assets/images/employee_img/";
        $config['allowed_types'] = 'jpg|jpeg|gif|png';
        //$config['max_size'] = '1000';
        //$config['max_width'] = '1024';
        //$config['max_height'] = '768';

        $file_name = $_FILES['hrm_employee_photo']['name'];
        $file_ex = explode(".", $file_name);
        $ext = end($file_ex);
        $config['file_name'] = $employeeId . '.' . $ext;

        $emp_photo = $this->db->where('EMP_ID', $employeeId)->get('hrm_ls_employee')->row()->EMP_PHOTO;
        print_r($emp_photo);
        exit();
        if ($emp_photo) {
            $path = FCPATH . 'assets/images/employee_img/' . $emp_photo;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('hrm_employee_photo')) {
            //echo 'hi';exit;
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('employee/employee_personal_info', $error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $imageName = $data['upload_data']['file_name'];
            //print_r($imageName);

            $pictureName = array(
                //'EMP_PHOTO' => $employeeId.'.'.$ext
                'EMP_PHOTO' => $imageName
            );
            // print_r($pictureName);exit;
            $this->db->update('hrm_ls_employee', $pictureName, array('EMP_ID' => $employeeId));
        }
    }


    // Nasir  create = 29_9_15  modify =  start National Id Check========
    public function checkNationalidAjax()
    {

        $employeeNationalid = $this->input->post('employeeNationalid');

        if (trim($employeeNationalid) != '') {
            $res = $this->db->query("SELECT NATIONAL_ID FROM bf_hrm_ls_employee WHERE  NATIONAL_ID  LIKE '%$employeeNationalid%'");
            $result = $res->num_rows();
            if ($result > 0) {
                echo 'Number Already Exist !!';

            } else {

            }

        }
    }

    // Nasir  create = 29_9_15 modify =  end National Id========


    // Nasir  create = 29_9_15  modify =  start Passport Check========
    public function checkPassportAjax()
    {

        $employeePassportNo = $this->input->post('employeePassportNo');

        if (trim($employeePassportNo) != '') {
            $res = $this->db->query("SELECT PASSPORT_NO FROM bf_hrm_ls_employee WHERE  PASSPORT_NO  LIKE '%$employeePassportNo%'");
            $result = $res->num_rows();
            if ($result > 0) {
                echo 'Number Already Exist !!';

            } else {

            }

        }
    }
    // Nasir  create = 29_9_15 modify =  end Passport Id========


    // Nasir  create = 29_9_15  modify =  start Driving Licence Check========
    public function checkDrivingLicenceAjax()
    {

        $employeeDrivingLicence = $this->input->post('employeeDrivingLicence');

        if (trim($employeeDrivingLicence) != '') {
            $res = $this->db->query("SELECT DRIVING_LICENCE FROM bf_hrm_ls_employee WHERE  DRIVING_LICENCE  LIKE '%$employeeDrivingLicence%'");

            $result = $res->num_rows();
            if ($result > 0) {
                echo 'Number Already Exist !!';

            } else {

            }

        }
    }

    // Nasir  create = 29_9_15 modify =  end Driving Licence========


    //==========Employee Personal end========//

    //======= Start Employee Contact Info==========//

    public function employee_contact_info()
    {
        $this->auth->restrict('HRM.Employee.View');
        $this->load->model('library/division_model', NULL, TRUE);
        $this->load->model('library/district_model', NULL, TRUE);
        $this->load->model('library/area_model', NULL, TRUE);
        $this->load->model('library/trtarea_model', NULL, TRUE);

        $employeeId = (int)$this->uri->segment(5);

        $data = array();

        //===== Load Employee Contact Info =======
        if ($employeeId) {
            // bf_zone_area = thana or police station
            // bf_zone_trtarea = post office

            $this->db->select("
								postOfice.division_no as permanentDivisionId,
								postOfice.district_no as permanentDistrictId,
								postOfice.area_no as permanentPoliceStationId,
								ec.PERMANENT_POST_OFFICE_ID  as perPostId,								
								ec.PERMANENT_CITY_VILLAGE as permanentCityVillage,
								ec.TELEPHONE as telephone,
								ec.EMAIL  as email,
								ec.MOBILE  as mobile,
								ec.ALTERNATIVE_MOBILE  as alternative_mobile
							 ");

            $this->db->from('hrm_ls_emp_contacts as ec');
            $this->db->where("ec.EMP_ID", "$employeeId");
            $this->db->join('zone_trtarea as postOfice', 'postOfice.trt_id = ec.PERMANENT_POST_OFFICE_ID', 'left');
            $this->db->distinct("ec.EMP_CONTACTS_ID");

            $permanentAddressRes = $this->emp_contact_info_model->find_by('ec.EMP_ID', $employeeId);


            $this->db->select("
								postOfice.division_no as presentDivisionId,
								postOfice.district_no as presentDistrictId,
								postOfice.area_no as presentPoliceStationId,
								ec.PRESENT_POST_OFFICE_ID as prePostId,
								ec.PRESENT_CITY_TOWN as presentCityVillage,
							 ");

            $this->db->from('hrm_ls_emp_contacts as ec');
            $this->db->where("ec.EMP_ID", "$employeeId");

            $this->db->join('zone_trtarea as postOfice', 'postOfice.trt_id = ec.PRESENT_POST_OFFICE_ID', 'left');
            $this->db->distinct("ec.EMP_CONTACTS_ID");


            $presentAddressRes = $this->emp_contact_info_model->find_by('ec.EMP_ID', $employeeId);
        }


        //===== Start Save Employee Contact Info =======
        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            $this->db->trans_start();
            $EMP_BANK_ID = $this->saveEmployeeAddress();
            $this->db->trans_complete();

            if ((int)$EMP_BANK_ID > 0) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $employeeId . ' : ' . $this->input->ip_address(), 'hrm_ls_emp_contacts');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_curriculam_info');
            } else {
                Template::set_message(lang('bf_msg_create_failure') . $this->emp_contact_info_model->error, 'error');
            }
        }

        //===== Load  Address for same=======//
        $data['division'] = $this->division_model->find_all();
        $data['district'] = $this->district_model->find_all();
        $data['policeStation'] = $this->area_model->find_all();
        $data['postOffice'] = $this->trtarea_model->find_all();
        $data['permanentAddress'] = $permanentAddressRes;
        $data['presentAddress'] = $presentAddressRes;
        $data['employeeId'] = $employeeId;

        echo $this->load->view('employee/employee_contact_info', $data, TRUE);
    }


    //========= Get Post Office Options List by Ajax Function =========
    public function getTRTAreaListAjax()
    {
        $this->load->model('library/list_model', NULL, true);
        $groupDropDown = "";
        $groupId = 0;
        $divisionId = $this->input->post('divisionId');
        $districtId = $this->input->post('districtId');
        $areaId = $this->input->post('areaId');
        if (intval($areaId) > 0 && intval($areaId) > 0) {

            /**
             * Summary
             *
             * @param Int $divisionId ,    The division Id of the record to get all area under this division
             * @param Int $districtId ,    The district Id of the record to get all area under this district
             * @param Int $areaId ,        The area Id of the record to get all Thana area under this area
             *
             * @return array
             */

            $this->load->library('library/GetTRTAreaListAjaxService');

            $GetTRTAreaListAjaxService = new GetTRTAreaListAjaxService($this);

            $area_list = $GetTRTAreaListAjaxService->setDivisionNo($divisionId)
                ->setDistrictNo($districtId)
                ->setAreaNo($areaId)
                ->execute();

            if (is_array($area_list)) {
                $options = array();
                foreach ($area_list as $result) {
                    $options[$result["trt_id"]] = $result["trt_name"];
                }
                $groupDropDown = $this->list_model->getDropdownOption($options, "#employee_permanent_post_office");
            }
        }
        echo json_encode($groupDropDown);
    }

    private function saveEmployeeAddress($type = 'insert', $id = 0)
    {
        $EMP_ID = (int)$this->uri->segment(5);
        $countEmpId = $this->emp_contact_info_model->count_by('EMP_ID', $EMP_ID);

        if ($EMP_ID && $countEmpId > 0) {
            echo $type = 'update';
        } else {
            echo $type = 'insert';
        }
        //echo '<pre>';print_r($_POST);exit;
        // make sure we only pass in the fields we want
        $data = array();
        $data['EMP_ID'] = $this->input->post('empId');
        $data['PRESENT_POST_OFFICE_ID'] = $this->input->post('employee_mailing_post_office');
        $data['PRESENT_CITY_TOWN'] = $this->input->post('employee_mailing_village');
        $data['PERMANENT_POST_OFFICE_ID'] = $this->input->post('employee_permanent_post_office');
        $data['PERMANENT_CITY_VILLAGE'] = $this->input->post('employee_permanent_village');
        $data['EMAIL'] = $this->input->post('employee_email_address');
        $data['TELEPHONE'] = $this->input->post('employee_telephone_no');
        $data['MOBILE'] = $this->input->post('employee_mobile_no');
        $data['ALTERNATIVE_MOBILE'] = $this->input->post('employee_alternative_mobile_no');

        if ($type == 'insert') {
            $this->auth->restrict('HRM.Employee.Create');
            $data['CREATED_BY'] = $this->current_user->id;
            $return = $this->emp_contact_info_model->insert($data);
        } elseif ($type == 'update') {
            $data['MODIFY_BY'] = $this->current_user->id;
            $data['MODIFY_DATE'] = date('Y-m-d H:i:s');
            $return = $this->db->update('hrm_ls_emp_contacts', $data, array('EMP_ID' => $EMP_ID));
        }
        return $return;
    }

    // Nasir  create = 22_9_15  modify =  start ========

    // === Email Check
    public function checkEmailAjax()
    {
        $employee_email = $this->input->post('employee_email');

        if (trim($employee_email) != '') {
            if (!filter_var($employee_email, FILTER_VALIDATE_EMAIL)) {
                echo 'Invalid Email';

            } else {

            }

        }
    }

    // Nasir  create = 22_9_15 modify =  end ========

    //======= End Employee Contact Info==========//


    //=================== family info start ========================

    public function employee_family_info()
    {
        $this->auth->restrict('HRM.Employee.View');
        $employeeId = (int)$this->uri->segment(5);
        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_curriculam_info');
        }

        if ($employeeId) {
            $records = $this->employee_family_model->find_by('hrm_ls_emp_family.EMP_ID', $employeeId);

            $data = array();

            //$data['emp_family_details'] 	= $records;
            $data['employee_id'] = $employeeId;
            $data['relation'] = $this->config->item('family_relation');

            echo $this->load->view('employee/employee_family_info', $data, TRUE);

            echo $this->showFamilyInfo($employeeId);
          
        }

    }

    //====== Ajax Function of Employee Family information ====//
    public function familyInfoAjax()
    {

        $this->load->model('employee_family_model', NULL, TRUE);
        $this->load->library('GetEmployeefamilyInfo', NULL, TRUE);
        $data = array();
        $data['EMP_ID'] = $this->input->post('employee_id');
        $data['NAME'] = $this->input->post('NAME');
        //  $data['NAME_BENGALI'] = $this->input->post('NAME_BENGALI');
        $data['RELATION'] = $this->input->post('RELATION');
        $data['BIRTH_DATE'] = custom_date_format($this->input->post('patient_mst_dob'),'Y-m-d');
        //  $data['AGE'] = $this->input->post('AGE');
        $data['OCCPATION'] = $this->input->post('OCCPATION');
        $data['NID'] = $this->input->post('NID');
        //   $data['OCCPATION_BENGALI'] = $this->input->post('OCCPATION_BENGALI');
        $data['CONTACT_NO'] = $this->input->post('CONTACT_NO');
        $relationtype = $this->input->post('RELATION');

        if ($relationtype) {

            $EMP_FAMILY_ID = $this->input->post('EMP_FAMILY_ID_TARGET');

            if ($EMP_FAMILY_ID > 0) {
                $this->auth->restrict('HRM.Employee.Edit');
                $data['MODIFY_BY'] = $this->current_user->id;
                $data['RECORD_MODIFY_DATE_TIME'] = date('Y-m-d H:i:s');
                $this->employee_family_model->update($EMP_FAMILY_ID, $data);
                $returnId = $EMP_FAMILY_ID;
            } else {
                // insert new data
                $this->auth->restrict('HRM.Employee.Create');
                $data['RECORD_MODIFY_DATE_TIME'] = null;
                $data['CREATED_BY'] = $this->current_user->id;
                $data['MODIFY_BY'] = null;

                //echo $data['RELATION'] ; die;
                /*  $EMP_ID = $this->input->post('employee_id');
                  $NAME = $this->input->post('NAME');
                  $RELATION = $this->input->post('RELATION');
                  $res = $this->db->query("SELECT EMP_FAMILY_ID FROM bf_hrm_ls_emp_family WHERE  RELATION  LIKE '%$RELATION%' and NAME LIKE '%$NAME%'  and EMP_ID='$EMP_ID'");
                  $result = $res->num_rows();*/
                $returnId = $this->employee_family_model->insert($data);
            }

            if ($_FILES['hrm_family_photo']['name'] && $returnId) {
                $this->do_upload_family($returnId);
            }
        }
        $empId = $this->input->post('employee_id');
        $this->showFamilyInfo($empId);
    }

    public function do_upload_family($emp_family_id)
    {
        $config['upload_path'] = "../public/assets/images/employee_family/";
        $config['allowed_types'] = '*';
        //$config['max_size'] = '1000';
        //$config['max_width'] = '1024';
        //$config['max_height'] = '768';

        $file_name = $_FILES['hrm_family_photo']['name'];
        $file_ex = explode(".", $file_name);
        $ext = end($file_ex);
        $config['file_name'] = $emp_family_id . '.' . $ext;

        $emp_photo = $this->db->where('EMP_FAMILY_ID', $emp_family_id)->get('hrm_ls_emp_family')->row()->FMY_PHOTO;

        if ($emp_photo) {
            $path = FCPATH . 'assets/images/employee_family/' . $emp_photo;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('hrm_family_photo')) {
            //DO NOTHING
        } else {
            $data = array('upload_data' => $this->upload->data());
            $imageName = $data['upload_data']['file_name'];
            //print_r($imageName);

            $pictureName = array(
                //'EMP_PHOTO' => $employeeId.'.'.$ext
                'FMY_PHOTO' => $imageName
            );
            // print_r($pictureName);exit;
            $this->db->update('hrm_ls_emp_family', $pictureName, array('EMP_FAMILY_ID' => $emp_family_id));
        }
    }

    public function showFamilyInfo($empId = 0)
    {
        $empId = $empId ? $empId : (int)$this->input->post('employeeId');

        if (!(int)$empId) {
            return false;
        }

        $this->load->library('GetEmployeefamilyInfo');
        $GetEmployeefamilyInfo = new GetEmployeefamilyInfo($this);
        $records = $GetEmployeefamilyInfo
            ->setempId($empId)
            ->execute();
        echo $this->load->view('employee/employee_family_list', array('records' => $records, 'empId' => $empId), TRUE);
    }

    //=====show family information for editing
    public function getFamilyInfoAjax()
    {
        if ($EMP_FAMILY_ID = (int)$this->input->post('EMP_FAMILY_ID_EDIT')) {
            $familyData = $this->employee_family_model->find_by('hrm_ls_emp_family.EMP_FAMILY_ID', $EMP_FAMILY_ID);
            $NAME = $familyData->NAME;
            //  $NAME_BENGALI = $familyData->NAME_BENGALI;
            $RELATION = $familyData->RELATION;
            $NATIONAL_ID = $familyData->NID;
            $BIRTH_DATE = date("d/m/Y", strtotime($familyData->BIRTH_DATE));
            //   $AGE = $familyData->AGE;
            $OCCPATION = $familyData->OCCPATION;
            //   $OCCPATION_BENGALI = $familyData->OCCPATION_BENGALI;
            $CONTACT_NO = $familyData->CONTACT_NO;

            echo "
				$('#NAME').val('$NAME');
			
				$('#RELATION').val('$RELATION');
				$('#patient_mst_dob').val('$BIRTH_DATE');
				
				
				$('#OCCPATION').val('$OCCPATION');
				$('#NATIONAL_ID').val('$NATIONAL_ID');
			    
				$('#CONTACT_NO').val('$CONTACT_NO');
				$('#EMP_FAMILY_ID_TARGET').val('$EMP_FAMILY_ID');	
			";
        }

    }

    //Delete Family  Data
    public function deleteFamily()
    {

        $this->load->model('employee_family_model', NULL, TRUE);

        $empId = $this->input->post('employeeId');
        $familyidDelet = $this->input->post('EMP_FAMILY_ID_DELETE');


        if ($familyidDelet) {
            $this->employee_family_model->delete($familyidDelet);

            $this->load->library('doctrine');
            $this->load->library('GetEmployeefamilyInfo');

            $GetEmployeefamilyInfo = new GetEmployeefamilyInfo($this);
            $records = $GetEmployeefamilyInfo
                ->setempId($empId)
                ->execute();
            echo
                "<tr class='active strong'>
					<td colspan='7' align='center'>" . lang("EMPLOYMENT_HISTORY") . "</td>
				</tr>
				
				<tr>
				    <th>Photo</th>						
					<th>" . lang("NAME") . "</th>
					<th>" . lang("RELATION") . "</th>
					<th>NID</th>
					<th>" . lang("OCCPATION") . "</th>
					<th>" . lang("CONTACT_NO") . "</th>
					<th>" . lang("emp_action") . "</th>
				</tr>";

            foreach ($records as $recorded): $familyInfoRecord = (object)$recorded;

                $EMP_FAMILY_ID = $familyInfoRecord->EMP_FAMILY_ID;
                $empId = $this->input->post('employeeId');
                $img = ($familyInfoRecord->FMY_PHOTO == "") ? base_url("assets/images/profile/default.png") : base_url("assets/images/employee_family/" . $familyInfoRecord->FMY_PHOTO);
                echo "
					<tr>
					  <td><img id='previewing' src=' " . $img . " ' width='50' height='70' align: left;/></td>
						<td>" . $familyInfoRecord->NAME . "</td>
						<td>" . $relationtypes[$familyInfoRecord->RELATION] . "</td>
						<td>" . $familyInfoRecord->NID . "</td>						
						<td>" . $familyInfoRecord->OCCPATION . "</td>
						<td>" . $familyInfoRecord->CONTACT_NO . "</td>
						<td>
							<span onclick='deleteFamilyInfo($EMP_FAMILY_ID,$empId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>				
							<span onclick='editFamilyInfo($EMP_FAMILY_ID,$empId)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>	
						</td>
					</tr>";
            endforeach;

        }


    }

    // ==================== end family info =========================//


    //============Employee Curriculum Start============//
    public function emp_curriculam_info()
    {
        $this->load->model('emp_curriculum_exam_model');
        $this->load->model('emp_curriculum_board_model');
        $employeeId = (int)$this->uri->segment(5);
        $data = array();


        //===== Start Save  Employee Curriculum Info =======
        if (isset($_POST['save'])) {
            redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_important_documentation');
        }

        //Config files
        $data['division_class'] = $this->config->item('division_class');
        $data['employeeId'] = $employeeId;
        $data['exam_name'] = $this->emp_curriculum_exam_model->find_all();
        $data['exam_board'] = $this->emp_curriculum_board_model->find_all();

        echo $this->load->view('employee/emp_curriculam_info', $data, TRUE);
    }


    //====== Ajax Function of Employee Education ====//

    public function educationAjax()
    {
        $division_class = $this->config->item('division_class');
        $this->load->model('emp_curriculum_info_model', NULL, TRUE);
        $this->load->library('doctrine');
        $this->load->library('GetEmployeeCurriculumListService');

        if (!$this->input->post('onlyView')) {
            $data = array();
            $data['EMP_ID'] = $this->input->post('employeeId');
            $data['BOARD_UNIV'] = $this->input->post('boardName');
            $data['PASS_YEAR'] = $this->input->post('passYaer');
            $data['SCORE'] = $this->input->post('score');
            $data['EARNED_SCORE'] = $this->input->post('cgpa');
            $data['CLASS_DIVISION'] = $this->input->post('examResult');
            $data['EXAMCODE_ID'] = $this->input->post('examName');
            $data['CREATED_BY'] = $this->current_user->id;
            //print_r($data);exit;
            $this->emp_curriculum_info_model->insert($data);
        }

        $empId = $this->input->post('employeeId');

        $getEmployeeCurriculumHistory = new GetEmployeeCurriculumListService($this);
        $records = $getEmployeeCurriculumHistory
            ->setempId($empId)
            ->execute();


        echo "
			
			<tr class='active'>						
				<th>" . lang("emp_qualification_exam_name") . "</th>
				<th>" . lang("emp_qualification_board_name") . "</th>
				<th>" . lang("emp_qualification_pass_yaer") . "</th>
				<th>" . lang("emp_qualification_score") . "</th>
				<th>" . lang("emp_qualification_cgpa") . "</th>
				<th>" . lang("emp_qualification_exam_result") . "</th>
				<th>" . lang("emp_action") . "</th>
			</tr>";

        $i = 0;
        foreach ($records as $recorded): $educationRecord = (object)$recorded;
            $i++;
            //If we use (active strong) then row will be colour.
            $tr = ($i % 2 == 0) ? "info" : "success";
            $division = $division_class[$educationRecord->CLASS_DIVISION];


            $educationId = $educationRecord->EMP_EDUCATION_ID;

            echo "
		
			<tr class='$tr'>
				<td>" . $educationRecord->exam_name . "</td>
				<td>" . $educationRecord->board . "</td>
				<td>" . $educationRecord->PASS_YEAR . "</td>						
				<td>" . $educationRecord->SCORE . "</td>						
				<td>" . $educationRecord->EARNED_SCORE . "</td>
				<td>" . $division . "</td>
				<td>
					<span onclick='deleteEducation($educationId,$empId)' class='btn btn-primary-delete glyphicon glyphicon-remove-circle'> </span>				
				
				</td>
			</tr>";
        endforeach;

    }


    //Delete Education Data
    public function deleteService()
    {
        $division_class = $this->config->item('division_class');
        $this->load->model('emp_curriculum_info_model', NULL, TRUE);

        $empId = $this->input->post('employeeId');
        $curriIdDelet = $this->input->post('curriIdDelet');

        if ($curriIdDelet) {
            $this->emp_curriculum_info_model->delete($curriIdDelet);

            $this->load->library('doctrine');
            $this->load->library('GetEmployeeCurriculumListService');

            $getEmployeeCurriculumHistory = new GetEmployeeCurriculumListService($this);
            $records = $getEmployeeCurriculumHistory
                ->setempId($empId)
                ->execute();


            echo "
				
				<tr class='active'>						
					<th>" . lang("emp_qualification_exam_name") . "</th>
					<th>" . lang("emp_qualification_board_name") . "</th>
					<th>" . lang("emp_qualification_pass_yaer") . "</th>
					<th>" . lang("emp_qualification_score") . "</th>
					<th>" . lang("emp_qualification_cgpa") . "</th>
					<th>" . lang("emp_qualification_exam_result") . "</th>
					<th>" . lang("emp_action") . "</th>
				</tr>";
            $i = 0;
            foreach ($records as $recorded): $educationRecord = (object)$recorded;
                $i++;
                //If we use (active strong) then row will be colour.
                $division = $division_class[$educationRecord->CLASS_DIVISION];

                $educationId = $educationRecord->EMP_EDUCATION_ID;
                $tr = ($i % 2 == 0) ? "info" : "success";
                echo "
			
				<tr class='$tr'>
					<td>" . $educationRecord->exam_name . "</td>
					<td>" . $educationRecord->board . "</td>
					<td>" . $educationRecord->PASS_YEAR . "</td>						
					<td>" . $educationRecord->SCORE . "</td>						
					<td>" . $educationRecord->EARNED_SCORE . "</td>
					<td>" . $division . "</td>
					<td>
						<span onclick='deleteEducation($educationId,$empId)' class='btn btn-primary-delete glyphicon glyphicon-remove-circle'> </span>	
					</td>
				</tr>";
            endforeach;

        }

    }
    //=======   Employee Education End ==========//


//=================== Job Experience info start ========================
    // Employee Job Experience information
    //=================== Job Experience info start ========================
    public function employee_job_experience_info()
    {
        $this->auth->restrict('HRM.Employee.View');
        $employeeId = (int)$this->uri->segment(5);

        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_important_documentation');
        }

        if ($employeeId) {
            $records = $this->emp_job_experience_model->find_by('hrm_ls_emp_job_experience.EMP_ID', $employeeId);
            $this->load->model('library/designation_info_model', NULL, TRUE);
            $data = array();
            $data['deignation_list'] = $this->designation_info_model->find_all_by(array('STATUS' => 1, 'IS_DELETED' => 0));
            $data['emp_job_experience_details'] = $records;
            echo $this->load->view('employee/emp_job_experience', $data, TRUE);
        }
    }


    //====== Ajax Function of Employee Job Experience ====//
    public function experienceAjax()
    {
        $this->load->model('emp_job_experience_model', NULL, TRUE);
        $this->load->library('GetEmployeeJobExperience', NULL, TRUE);

        $data = array();
        $data['EMP_ID'] = $this->input->post('employeeId');
        $data['ORGANIZATION'] = $this->input->post('ORGANIZATION');
        $data['ORGANIZATION_BENGALI'] = $this->input->post('ORGANIZATION_BENGALI');
        $data['ORGANIZATION_ADDRESS'] = $this->input->post('ORGANIZATION_ADDRESS');
        $data['ORGANIZATION_ADDRESS_BENGALI'] = $this->input->post('ORGANIZATION_ADDRESS_BENGALI');
        $data['POSITION'] = $this->input->post('POSITION');
        $data['YEAR_START'] = date("Y-m-d", strtotime($this->input->post('YEAR_START')));
        $data['YEAR_END'] = date("Y-m-d", strtotime($this->input->post('YEAR_END')));
        $data['REASON_FOR_LEAVING'] = $this->input->post('REASON_FOR_LEAVING');
        $data['REASON_FOR_LEAVING_BENGALI'] = $this->input->post('REASON_FOR_LEAVING_BENGALI');
        $data['CONTACT_PERSON'] = $this->input->post('CONTACT_PERSON');
        $data['CONTACT_PERSON_BENGALI'] = $this->input->post('CONTACT_PERSON_BENGALI');
        $data['CONTACT_NUMBER'] = $this->input->post('CONTACT_NUMBER');
        $data['CREATED_BY'] = $this->current_user->id;

        $EMP_JOB_EXP_ID = $this->input->post('EMP_JOB_EXP_ID');
        $POSITION = $this->input->post('POSITION');

        if ($POSITION > 0) {
            if ($EMP_JOB_EXP_ID > 0) {
                // update target data
                $this->emp_job_experience_model->update($EMP_JOB_EXP_ID, $data);
            } else {
                // insert new data
                $this->emp_job_experience_model->insert($data);
            }
        }

        $empId = $this->input->post('employeeId');

        $this->load->library('doctrine');
        $this->load->library('GetEmployeeJobExperience');
        $GetEmployeeJobExperience = new GetEmployeeJobExperience($this);
        $records = $GetEmployeeJobExperience
            ->setempId($empId)
            ->execute();
        echo "<tr class='active strong'>
					<td colspan='7' align='center'>" . lang("EMPLOYMENT_HISTORY") . "</td>
				</tr>
				
				<tr>						
					<th>" . lang("ORGANIZATION") . "</th>
					<th>" . lang("POSITION") . "</th>
					<th>" . lang("YEAR_START") . "</th>
					<th>" . lang("YEAR_END") . "</th>
					<th>" . lang("REASON_FOR_LEAVING") . "</th>
					<th>" . lang("CONTACT_PERSON") . "</th>
					<th>" . lang("emp_action") . "</th>
				</tr>";

        foreach ($records as $recorded): $jobExperienceRecord = (object)$recorded;
            $EMP_JOB_EXP_ID = $jobExperienceRecord->EMP_JOB_EXP_ID;
            $empId = $this->input->post('employeeId');
            echo "			
				<tr>
					<td>" . $jobExperienceRecord->ORGANIZATION . "</td>
					<td>" . $jobExperienceRecord->DESIGNATION_NAME . "</td>
					<td>" . $jobExperienceRecord->YEAR_START . "</td>						
					<td>" . $jobExperienceRecord->YEAR_END . "</td>						
					<td>" . $jobExperienceRecord->REASON_FOR_LEAVING . "</td>
					<td>" . $jobExperienceRecord->CONTACT_PERSON . "</td>
					<td>
						<span onclick='deleteExperience($EMP_JOB_EXP_ID,$empId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>				
						<span onclick='editExperience($EMP_JOB_EXP_ID,$empId)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>	
					</td>
				</tr>";
        endforeach;
    }


    //=====show for edit experience
    public function getJobExperienceAjax()
    {
        if ($EMP_JOB_EXP_ID = (int)$this->input->post('employment_id_Edit')) {
            $experienceData = $this->emp_job_experience_model->find_by('hrm_ls_emp_job_experience.EMP_JOB_EXP_ID', $EMP_JOB_EXP_ID);
            $ORGANIZATION = $experienceData->ORGANIZATION;
            $ORGANIZATION_BENGALI = $experienceData->ORGANIZATION_BENGALI;
            $ORGANIZATION_ADDRESS = $experienceData->ORGANIZATION_ADDRESS;
            $ORGANIZATION_ADDRESS_BENGALI = $experienceData->ORGANIZATION_ADDRESS_BENGALI;
            $POSITION = $experienceData->POSITION;
            $YEAR_START = $experienceData->YEAR_START;
            $YEAR_END = $experienceData->YEAR_END;
            $REASON_FOR_LEAVING = $experienceData->REASON_FOR_LEAVING;
            $REASON_FOR_LEAVING_BENGALI = $experienceData->REASON_FOR_LEAVING_BENGALI;
            $CONTACT_PERSON = $experienceData->CONTACT_PERSON;
            $CONTACT_NUMBER = $experienceData->CONTACT_NUMBER;
            $CONTACT_PERSON_BENGALI = $experienceData->CONTACT_PERSON_BENGALI;

            echo "
				$('#ORGANIZATION').val('$ORGANIZATION');
				$('#ORGANIZATION_BENGALI').val('$ORGANIZATION_BENGALI');
				$('#ORGANIZATION_ADDRESS').val('$ORGANIZATION_ADDRESS');
				$('#ORGANIZATION_ADDRESS_BENGALI').val('$ORGANIZATION_ADDRESS_BENGALI');
				$('#POSITION').val('$POSITION');
				$('#YEAR_START').val('$YEAR_START');
				$('#YEAR_END').val('$YEAR_END');
				$('#REASON_FOR_LEAVING').val('$REASON_FOR_LEAVING');
				$('#REASON_FOR_LEAVING_BENGALI').val('$REASON_FOR_LEAVING_BENGALI');
				$('#CONTACT_NUMBER').val('$CONTACT_NUMBER');
				$('#CONTACT_PERSON').val('$CONTACT_PERSON');
				$('#CONTACT_PERSON_BENGALI').val('$CONTACT_PERSON_BENGALI');
				$('#EMP_JOB_EXP_ID').val('$EMP_JOB_EXP_ID');	
			";
        }
    }


    //Delete Employment Data
    public function deleteEmployment()
    {
        $this->load->model('emp_job_experience_model', NULL, TRUE);

        $empId = $this->input->post('employeeId');
        $employmentidDelet = $this->input->post('employment_id_Delete');

        if ($employmentidDelet) {
            $this->emp_job_experience_model->delete($employmentidDelet);

            $this->load->library('doctrine');
            $this->load->library('GetEmployeeJobExperience');

            $GetEmployeeJobExperience = new GetEmployeeJobExperience($this);
            $records = $GetEmployeeJobExperience
                ->setempId($empId)
                ->execute();
            echo
                "<tr class='active strong'>
					<td colspan='7' align='center'>" . lang("EMPLOYMENT_HISTORY") . "</td>
				</tr>
				
				<tr>						
					<th>" . lang("ORGANIZATION") . "</th>
					<th>" . lang("POSITION") . "</th>
					<th>" . lang("YEAR_START") . "</th>
					<th>" . lang("YEAR_END") . "</th>
					<th>" . lang("REASON_FOR_LEAVING") . "</th>
					<th>" . lang("CONTACT_PERSON") . "</th>
					<th>" . lang("emp_action") . "</th>
				</tr>";

            foreach ($records as $recorded): $jobExperienceRecord = (object)$recorded;
                $EMP_JOB_EXP_ID = $jobExperienceRecord->EMP_JOB_EXP_ID;
                $empId = $this->input->post('employeeId');
                echo "
				
					<tr>
						<td>" . $jobExperienceRecord->ORGANIZATION . "</td>
						<td>" . $jobExperienceRecord->DESIGNATION_NAME . "</td>
						<td>" . $jobExperienceRecord->YEAR_START . "</td>						
						<td>" . $jobExperienceRecord->YEAR_END . "</td>						
						<td>" . $jobExperienceRecord->REASON_FOR_LEAVING . "</td>
						<td>" . $jobExperienceRecord->CONTACT_PERSON . "</td>
						<td>
							<span onclick='deleteExperience($EMP_JOB_EXP_ID,$empId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>				
							<span onclick='editExperience($EMP_JOB_EXP_ID,$empId)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>	
						</td>
					</tr>";
            endforeach;
        }
    }
    // ==================== End Job Experience info  ===================


    //=================== Training info start ========================
    public function employee_training_info()
    {
        $this->auth->restrict('HRM.Employee.View');
        $employeeId = (int)$this->uri->segment(5);

        if (isset($_POST['save'])) {
            redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_important_documentation');
        }

        $this->load->model('library/training_type_model', null, TRUE);

        $trainingType = $this->training_type_model->select('TRAINING_TYPE_ID,TRAINING_TYPE_NAME')->find_all_by(array('TYPE_STATUS' => 1, 'IS_DELETED' => 0));

        $data = array();
        $data['trainingType'] = $trainingType;
        echo $this->load->view('employee/emp_training_info', $data, TRUE);
    }

    //====== Ajax Function of Employee Training ====//
    public function trainingAjax()
    {
        $this->load->model('emp_training_model', NULL, TRUE);
        $this->load->library('GetEmployeeTraining', NULL, TRUE);

        $data = array();
        $data['EMP_ID'] = $this->input->post('employeeId');
        $data['TRAINING_NAME'] = $this->input->post('TRAINING');
        $data['CERTIFICATE_FLAG'] = $this->input->post('CERTIFICATE_FLAG');
        $data['CONDUCTED_BY'] = $this->input->post('CONDUCTED_BY');
        $data['CONDUCTED_BY_BENGALI'] = $this->input->post('CONDUCTED_BY_BENGALI');
        $data['COMPLETION_DATE'] = date("Y-m-d", strtotime($this->input->post('COMPLETION_DATE')));
        $data['CREATED_BY'] = $this->current_user->id;

        if ($this->input->post('TRAINING') != '') {
            $EMP_TRAINING_ID = $this->input->post('EMP_TRAINING_ID');

            if ($EMP_TRAINING_ID > 0) {
                $this->emp_training_model->update($EMP_TRAINING_ID, $data);
            } else {
                $this->emp_training_model->insert($data);
            }
        }

        $empId = $this->input->post('employeeId');

        $this->load->library('doctrine');
        $this->load->library('GetEmployeeTraining');
        $GetEmployeeTraining = new GetEmployeeTraining($this);
        $records = $GetEmployeeTraining
            ->setempId($empId)
            ->execute();
        echo "<tr class='active strong'>
					<td colspan='7' align='center'>" . lang("TRAINING_HISTORY") . "</td>
				</tr>
				
				<tr>						
					<th>" . lang("TRAINING") . "</th>
					<th>" . lang("CONDUCTED_BY") . "</th>
					<th>" . lang("COMPLETION_DATE") . "</th>
					<th>" . lang("CERTIFICATE_FLAG") . "</th>					
					<th>" . lang("emp_action") . "</th>
				</tr>";

        foreach ($records as $recorded): $trainingRecord = (object)$recorded;

            $EMP_TRAINING_ID = $trainingRecord->EMP_TRAINING_ID;

            $empId = $this->input->post('employeeId');

            if ($trainingRecord->CERTIFICATE_FLAG == 1) {
                $CERTIFICATE = "Yes";
            } else {
                $CERTIFICATE = "No";
            }

            echo "			
				<tr>
					<td>" . $trainingRecord->TRAINING_TYPE_NAME . "</td>
					<td>" . $trainingRecord->CONDUCTED_BY . "</td>
					<td>" . $trainingRecord->COMPLETION_DATE . "</td>						
					<td>" . $CERTIFICATE . "</td>											
					<td>
						<span onclick='deleteTrainingHistory($EMP_TRAINING_ID,$empId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>
						<span onclick='editTrainingHistory($EMP_TRAINING_ID)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>		
					</td>
				</tr>";
        endforeach;
    }


//=====show for edit training
    public function getTrainingAjax()
    {
        if ($training_id_Edit = (int)$this->input->post('training_id_Edit')) {
            $this->load->model('emp_training_model', NULL, TRUE);
            $this->load->library('GetEmployeeTraining', NULL, TRUE);

            $this->load->library('doctrine');
            $this->load->library('GetEmployeeTraining');

            $GetEmployeeTraining = new GetEmployeeTraining($this);
            $records = $GetEmployeeTraining
                ->setEmpTrainingId($training_id_Edit)
                ->execute();

            foreach ($records as $recorded): $trainingRecord = (object)$recorded;

                $TRAINING_NAME = $trainingRecord->TRAINING_NAME;
                $CONDUCTED_BY = $trainingRecord->CONDUCTED_BY;
                $CONDUCTED_BY_BENGALI = $trainingRecord->CONDUCTED_BY_BENGALI;
                $COMPLETION_DATE = $trainingRecord->COMPLETION_DATE;
                $CERTIFICATE_FLAG = $trainingRecord->CERTIFICATE_FLAG;
                $EMP_TRAINING_ID = $trainingRecord->EMP_TRAINING_ID;

                echo "	
				$('#TRAINING').val('$TRAINING_NAME');
				$('#CONDUCTED_BY').val('$CONDUCTED_BY');
				$('#CONDUCTED_BY_BENGALI').val('$CONDUCTED_BY_BENGALI');
				$('#COMPLETION_DATE').val('$COMPLETION_DATE');
				$('#EMP_TRAINING_ID').val('$EMP_TRAINING_ID');					
				";

                if ($CERTIFICATE_FLAG == 1) {
                    echo "$('#CERTIFICATE_FLAG').prop('checked', true);";
                } else {
                    echo "$('#CERTIFICATE_FLAG').prop('checked', false);";
                }
            endforeach;
        }
    }


    //Delete TRAINING Data
    public function deleteTraining()
    {
        $this->load->model('emp_training_model', NULL, TRUE);

        $empId = $this->input->post('employeeId');
        $trainingidDelet = $this->input->post('training_id_Delete');

        if ($trainingidDelet) {
            $this->emp_training_model->delete($trainingidDelet);
            $this->load->library('doctrine');
            $this->load->library('GetEmployeeTraining');

            $GetEmployeeTraining = new GetEmployeeTraining($this);
            $records = $GetEmployeeTraining
                ->setempId($empId)
                ->execute();
            echo
                "<tr class='active strong'>
					<td colspan='7' align='center'>" . lang("TRAINING_HISTORY") . "</td>
				</tr>
				
				<tr>						
					<th>" . lang("TRAINING") . "</th>
					<th>" . lang("CONDUCTED_BY") . "</th>
					<th>" . lang("COMPLETION_DATE") . "</th>
					<th>" . lang("CERTIFICATE_FLAG") . "</th>					
					<th>" . lang("emp_action") . "</th>
				</tr>";

            foreach ($records as $recorded): $trainingRecord = (object)$recorded;

                $EMP_TRAINING_ID = $trainingRecord->EMP_TRAINING_ID;
                $empId = $this->input->post('employeeId');

                if ($trainingRecord->CERTIFICATE_FLAG == 1) {
                    $CERTIFICATE = "Yes";
                } else {
                    $CERTIFICATE = "No";

                }

                echo "			
				<tr>
					<td>" . $trainingRecord->TRAINING_NAME . "</td>
					<td>" . $trainingRecord->CONDUCTED_BY . "</td>
					<td>" . $trainingRecord->COMPLETION_DATE . "</td>						
					<td>" . $CERTIFICATE . "</td>											
					<td>
						<span onclick='deleteTrainingHistory($EMP_TRAINING_ID,$empId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>
						<span onclick='editTrainingHistory($EMP_TRAINING_ID)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>		
					</td>
				</tr>";

            endforeach;
        }

    }
    // ==================== End Training info  =========================//


    //=================== Important documentation status start ========================
    public function emp_important_documentation()
    {
        $this->auth->restrict('HRM.Employee.View');
        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            $this->db->trans_start();
            $EMP_IMP_DOC_ID = $this->saveImportant_documents();
            $this->db->trans_complete();

            if ((int)$EMP_IMP_DOC_ID > 0) {
                // Log the activity
                $employeeId = (int)$this->uri->segment(5);
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $EMP_IMP_DOC_ID . ' : ' . $this->input->ip_address(), 'hrm_ls_documentation_status');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/employee_bank_info');
            } else {
                Template::set_message(lang('bf_msg_create_failure') . $this->emp_important_documentation_model->error, 'error');
            }
        }

        $this->load->model('emp_important_documentation_model', NULL, TRUE);
        $employeeId = (int)$this->uri->segment(5);
        if ($employeeId) {
            $important_documentation_status = $this->emp_important_documentation_model->find_by('hrm_ls_documentation_status.EMP_ID', $employeeId);
        }
        $data = array();
        $data['important_documentation_status'] = $important_documentation_status;
        $data['EMP_ID'] = $employeeId;
        echo $this->load->view('employee/emp_important_documentation', $data, TRUE);
    }

    //==============Save documentation info =========//
    private function saveImportant_documents($type = 'insert', $id = 0)
    {
        $EMP_ID = (int)$this->uri->segment(5);

        $countEmpId = $this->emp_important_documentation_model->count_by('EMP_ID', $EMP_ID);

        if ($EMP_ID && $countEmpId > 0) {
            $type = 'update';
        } else {
            $type = 'insert';
        }

        //echo '<pre>';print_r($_POST);exit;
        // make sure we only pass in the fields we want
        $data = array();
        $data['EMP_ID'] = $EMP_ID;
        $data['NID_STATUS'] = $this->input->post('NID_STATUS');
        $data['PV_STATUS'] = $this->input->post('PV_STATUS');
        $data['JAS_STATUS'] = $this->input->post('JAS_STATUS');
        $data['NDA_STATUS'] = $this->input->post('NDA_STATUS');
        $data['DL_STATUS'] = $this->input->post('DL_STATUS');
        $data['PASSPORT_STATUS'] = $this->input->post('PASSPORT_STATUS');
        $data['EDUCATIONAL_STATUS'] = $this->input->post('EDUCATIONAL_STATUS');

        if ($type == 'insert') {
            $this->auth->restrict('HRM.Employee.Create');

            $data['CREATED_BY'] = $this->current_user->id;

            $return = $this->emp_important_documentation_model->insert($data);

        } elseif ($type == 'update') {
            $data['MODIFY_BY'] = $this->current_user->id;
            $data['MODIFIED_DATE'] = date('Y-m-d H:i:s');
            $return = $this->db->update('hrm_ls_documentation_status', $data, array('EMP_ID' => $EMP_ID));
        }
        return $return;
    }
    //=================== Important documents end ========================


    //=================== Posting info start ========================
    public function employee_posting_info()
    {
        $this->auth->restrict('HRM.Employee.View');
        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            $this->db->trans_start();
            $EMP_POSTING_ID = $this->saveEmpposting();
            $this->db->trans_complete();

            if ((int)$EMP_POSTING_ID > 0) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $EMP_POSTING_ID . ' : ' . $this->input->ip_address(), 'hrm_ls_emp_posting');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/employee_bank_info');
            } else {
                Template::set_message(lang('bf_msg_create_failure') . $this->emp_posting_info_model->error, 'error');
            }
        }

        $this->load->model('emp_posting_info_model', NULL, TRUE);
        $employeeId = (int)$this->uri->segment(5);
        $posting_details = "";
        if ($employeeId) {
            $posting_details = $this->emp_posting_info_model->find_by('hrm_ls_emp_posting.EMP_ID', $employeeId);
        }

        $this->load->model('library/designation_info_model', NULL, TRUE);
        $this->load->model('library/branch_info_model', NULL, true);
        $branchName = $this->branch_info_model->find_all_by(array('STATUS' => 1, 'IS_DELETED' => 0));
        $deignation_list = $this->designation_info_model->find_all_by(array('STATUS' => 1, 'IS_DELETED' => 0));

        $branch_category = $this->config->item('branch_category');

        $data = array();
        $data['posting_details'] = $posting_details;
        $data['branchName'] = $branchName;
        $data['deignation_list'] = $deignation_list;
        $data['branch_category'] = $branch_category;
        $data['EMP_ID'] = $employeeId;

        echo $this->load->view('employee/employee_posting_info', $data, TRUE);

    }

    //==============Save posting info =========//
    private function saveEmpposting($type = 'insert', $id = 0)
    {
        // make sure we only pass in the fields we want
        $employeeId = (int)$this->uri->segment(5);
        $this->load->model('emp_posting_info_model', NULL, TRUE);
        $data = array();
        $data['EMP_ID'] = $employeeId;
        $data['POSITION_ID'] = $this->input->post('emp_position_select');
        $data['BRANCH_NAME'] = $this->input->post('emp_branch_select');
        $data['BRANCH_CATEGORY'] = $this->input->post('emp_branch_category_select');
        $data['DIVISION'] = 1;
        $data['JOB_RESPONSIBILITY'] = $this->input->post('emp_job_responsibility');
        $data['EMP_JOB_RESPONSIBILITY_BENGALI'] = $this->input->post('emp_job_responsibility_bengali');
        $data['IS_TRANSFER'] = 0;
        $data['CREATED_BY'] = $this->current_user->id;
        $data['BUSINESS_DATE'] = date("Y-m-d");
        //$data['STATUS'] 						= $this->input->post('employee_status');


        if ($type == 'insert') {
            $this->auth->restrict('HRM.Employee.Create');

            $data['CREATED_BY'] = $this->current_user->id;

            $return = $this->emp_posting_info_model->insert($data);


        } elseif ($type == 'update') {
            $employeeId = (int)$this->uri->segment(5);
        }
        return $return;
    }

    //=================== Employee Bank info start ========================
    public function employee_bank_info()
    {
        $this->load->model('employee_bank_info_model');

        $this->auth->restrict('HRM.Employee.View');

        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            $this->db->trans_start();
            $EMP_BANK_ID = $this->saveBank_info();
            $this->db->trans_complete();

            if ((int)$EMP_BANK_ID > 0) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $EMP_BANK_ID . ' : ' . $this->input->ip_address(), 'hrm_ls_bank_info');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_weekend_define');
            } else {
                Template::set_message(lang('bf_msg_create_failure') . $this->employee_bank_info_model->error, 'error');
            }
        }
        $this->load->model('library/branch_info_model');
        $employeeId = (int)$this->uri->segment(5);
        $where = array('is_deleted' => 0, 'status' => 1);
        $branch_list = $this->branch_info_model->select('*')->find_all_by($where);
        if ($employeeId) {
            $emp_bank_details = $this->employee_bank_info_model->find_by('hrm_ls_bank_info.EMP_ID', $employeeId);
            if ($emp_bank_details) {
                $where = array('is_deleted' => 0, 'status' => 1, 'bank_id' => $emp_bank_details->EMP_BANK_ID);
                $branch_list = $this->branch_info_model->select('*')->find_all_by($where);
            }

        }

        $this->load->model('library/bank_setup_model');

        $where = array('is_deleted' => 0, 'status' => 1);
        $banklist = $this->bank_setup_model->select('*')->find_all_by($where);

        //print_r($branch_list);exit;

        $data = array();
        $data['emp_bank_details'] = $emp_bank_details;
        $data['EMP_ID'] = $employeeId;
        $data['banklist'] = $banklist;
        $data['branch_list'] = $branch_list;

        echo $this->load->view('employee/employee_bank_info', $data, TRUE);
    }

    public function getBranchNameByBankId()
    {
        $id = $this->input->post('id', TRUE);
        $result = $this->db
            ->select('id,branch_name')
            ->where('bank_id', $id)
            ->get('lib_branch_info')
            ->result();
        $options = '';
        $options .= '<select class="form-control chosenCommon chosen-single" name="BRANCH_ID" id="BRANCH_ID" required="" tabindex="4">>';
        $options .= '<option value="0">Select One</option>';
        if ($result) {
            foreach ($result as $row) {
                $options .= '<option value="' . $row->id . '">' . $row->branch_name . '</option>';
            }
        }
        $options .= '</select>';

        echo json_encode($options);
    }

    //==============Save Bank info =========//
    private function saveBank_info($type = 'insert', $id = 0)
    {
        $EMP_ID = (int)$this->uri->segment(5);
        $countEmpId = $this->employee_bank_info_model->count_by('EMP_ID', $EMP_ID);

        if ($EMP_ID && $countEmpId > 0) {
            $type = 'update';
        } else {
            $type = 'insert';
        }

        // make sure we only pass in the fields we want
        //echo '<pre>';print_r($_POST);exit;
        $data = array();
        $data['EMP_ID'] = (int)$this->uri->segment(5);
        $data['EMP_BANK_ID'] = (int)$this->input->post('BANK_NAME');
        $data['ACCOUNT_NAME'] = $this->input->post('ACCOUNT_NAME');
        $data['ACCOUNT_NO'] = $this->input->post('ACCOUNT_NO');
        $data['BRANCH_ID'] = (int)$this->input->post('BRANCH_ID');

        if ($type == 'insert') {
            $this->auth->restrict('HRM.Employee.Create');

            $data['CREATED_BY'] = $this->current_user->id;
            $this->db->insert('bf_hrm_ls_bank_info', $data);
            $return = $this->db->insert_id();

        } elseif ($type == 'update') {
            $data['MODIFY_BY'] = $this->current_user->id;
            $data['MODIFY_DATE'] = date('Y-m-d H:i:s');
            $return = $this->db->update('hrm_ls_bank_info', $data, array('EMP_ID' => $EMP_ID));
        }
        return $return;
    }
    // ==================== end Bank info =========================//


    //====================== Weekend Start ===============================

    public function emp_weekend_define()
    {

        $this->auth->restrict('HRM.Employee.Create');


        $EMP_ID = $this->uri->segment(5);

        $employee_weekend_details = $this->employee_weekend_model->find_by('hrm_ls_weekend.EMP_ID', $EMP_ID);

        if ($employee_weekend_details) {
            $EMP_WEEKEND_ID = $employee_weekend_details->EMP_WEEKEND_ID;

        }


        if (isset($_POST['save'])) {
            if ($EMP_WEEKEND_ID > 0) {
                $this->saveWeekend_info($EMP_WEEKEND_ID);

                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $EMP_WEEKEND_ID . ' : ' . $this->input->ip_address(), 'hrm_ls_weekend');
                Template::set_message(lang('bf_msg_edit_success'), 'success');
                /*redirect(SITE_AREA .'/employee/hrm/employee_tab/'.$EMP_ID.'/emp_policy_tagging'); */
                redirect(SITE_AREA . '/employee/hrm/show_list');

            } else {
                $EMP_WEEKEND_ID = $this->saveWeekend_info($EMP_WEEKEND_ID);

                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $EMP_WEEKEND_ID . ' : ' . $this->input->ip_address(), 'hrm_ls_weekend');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                /*redirect(SITE_AREA .'/employee/hrm/employee_tab/'.$EMP_ID.'/emp_policy_tagging');	*/
                redirect(SITE_AREA . '/employee/hrm/show_list');
            }

        }

        $data = array();
        $data['employee_weekend_details'] = $employee_weekend_details;
        $data['days'] = $this->config->item('days');

        echo $this->load->view('employee/employee_weekend_form', $data, TRUE);

    }


    //==============Save Weekend info =========//

    private function saveWeekend_info($EMP_WEEKEND_ID)
    {
        // make sure we only pass in the fields we want
        $data = array();

        $employeeId = (int)$this->uri->segment(5);
        $data['EMP_ID'] = $employeeId;
        $data['SU_ID'] = $this->input->post('Sunday');
        $data['MO_ID'] = $this->input->post('Monday');
        $data['TU_ID'] = $this->input->post('Tuesday');
        $data['WE_ID'] = $this->input->post('Wednesday');
        $data['TH_ID'] = $this->input->post('Thursday');
        $data['FR_ID'] = $this->input->post('Friday');
        $data['SA_ID'] = $this->input->post('Saturday');


        if ($EMP_WEEKEND_ID == 0) {
            $this->auth->restrict('HRM.Employee.Create');
            $data['RECORD_MODIFY_DATE_TIME'] = null;
            $data['CREATED_BY'] = $this->current_user->id;
            $data['MODIFY_BY'] = null;
            $result = $this->employee_weekend_model->insert($data);
            //print $this->db->last_query(); die;
        } elseif ($EMP_WEEKEND_ID > 0) {
            $this->auth->restrict('HRM.Employee.Edit');
            $data['MODIFY_BY'] = $this->current_user->id;
            $data['RECORD_MODIFY_DATE_TIME'] = date('Y-m-d H:i:s');

            $result = $this->employee_weekend_model->update($EMP_WEEKEND_ID, $data);

        }

        return $result;
    }

//===================  Employee Weekend end ========================


    //====================== Policy Tagging Start ===============================

    public function emp_policy_tagging()
    {
        $this->auth->restrict('HRM.Employee.Create');
        $this->load->model('absent_leave_mst_model', NULL, true);
        $this->load->model('maternity_leave_model', NULL, true);
        $this->load->model('medical_policy_master_model', NULL, true);
        $this->load->model('policy_bonus_master_model', NULL, true);
        $this->load->model('policy_leave_mst_model', NULL, true);
        $this->load->model('policy_shift_model', NULL, true);
        $this->load->model('policy_tagging_model', NULL, true);

        $this->lang->load('policy_tagging');

        $EMP_ID = $this->uri->segment(5);

        $employee_policy_details = $this->policy_tagging_model->find_all_by('EMP_ID', $EMP_ID);


        if (isset($_POST['save'])) {
            if ($EMP_ID > 0) {
                $this->savePolicyTagging($EMP_ID);

                log_activity($this->current_user->id, lang('bf_act_edit_record') . ': ' . $EMP_ID . ' : ' . $this->input->ip_address(), 'hrm_policy_tagging');
                Template::set_message(lang('bf_msg_edit_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $EMP_ID . '/employeeAppointmentInfo');
            } else {
                $EMP_TAGGING_ID = $this->savePolicyTagging($EMP_ID);

                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $EMP_TAGGING_ID . ' : ' . $this->input->ip_address(), 'hrm_policy_tagging');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $EMP_ID . '/employeeAppointmentInfo');
            }

        }

        $where = ['LEAVE_POLICY_STATUS' => 1, 'IS_DELETED' => 0];
        $leave_policy_details = $this->policy_leave_mst_model->find_all_by($where);

        $where = ['STATUS' => 1, 'IS_DELETED' => 0];
        $medical_policy_details = $this->medical_policy_master_model->find_all_by($where);

        $where = ['ABSENT_POLICY_STATUS' => 1, 'IS_DELETED' => 0];
        $absent_policy_details = $this->absent_leave_mst_model->find_all_by($where);

        $where = ['STATUS' => 1];
        $shift_policy_details = $this->policy_shift_model->find_all_by($where);

        $where = ['MATERNITY_STATUS' => 1, 'IS_DELETED' => 0];
        $maternity_policy_details = $this->maternity_leave_model->find_all_by($where);

        $where = ['STATUS' => 1, 'IS_DELETED' => 0];
        $bonus_policy_details = $this->policy_bonus_master_model->find_all_by($where);

        $sendData = array(
            'leave_policy_details' => $leave_policy_details,
            'medical_policy_details' => $medical_policy_details,
            'absent_policy_details' => $absent_policy_details,
            'shift_policy_details' => $shift_policy_details,
            'maternity_policy_details' => $maternity_policy_details,
            'bonus_policy_details' => $bonus_policy_details,
            'employee_policy_details' => $employee_policy_details

        );


        $data = array();
        $data['sendData'] = $sendData;
        echo $this->load->view('employee/employee_policy_tagging_form', $data, TRUE);

    }


    //==============Save policyn tagging info =========//

    private function savePolicyTagging($EMP_ID)
    {
        //echo '<pre>';print_r($_POST);exit;
        $employeeId = (int)$this->uri->segment(5);
        $policyTypeChecked = $this->input->post('policyChecked');
        $length = count($policyTypeChecked);

        $leave_policy = $this->input->post('leave_policy');
        $medical_policy = $this->input->post('medical_policy');
        $absent_policy = $this->input->post('absent_policy');
        $shifting_policy = $this->input->post('shifting_policy');
        $maternity_policy = $this->input->post('maternity_policy');
        $bonus_policy = $this->input->post('bonus_policy');

        $policyArray = array();
        if ($leave_policy != '') {
            $policyArray[] = $leave_policy;

        }

        if ($medical_policy != '') {
            $policyArray[] = $medical_policy;

        }

        if ($absent_policy != '') {
            $policyArray[] = $absent_policy;

        }
        if ($shifting_policy != '') {
            $policyArray[] = $shifting_policy;

        }
        if ($maternity_policy != '') {
            $policyArray[] = $maternity_policy;

        }
        if ($bonus_policy != '') {
            $policyArray[] = $bonus_policy;

        }

        //print_r($policyTypeChecked);

        //print_r($policyArray); die;

        $insertData = array();

        for ($i = 0; $i < $length; $i++) {
            $where = ['EMP_ID' => $employeeId, 'POLICY_TYPE' => $policyTypeChecked[$i]];
            $this->policy_tagging_model->delete_where($where);

            $insertData [] = array(

                'EMP_ID' => $employeeId,
                'POLICY_ID' => $policyArray[$i],
                'POLICY_TYPE' => $policyTypeChecked[$i],
                'CREATED_BY' => $this->current_user->id,
                'CREATED_DATE' => date('Y-m-d h:i:s'),
                'STATUS' => 1
            );
        }
        $return = $this->db->insert_batch('hrm_policy_tagging', $insertData);


        return $return;

    }

//===================  Employee policyn tagging end ========================


//=================== Employee Appoint Info Start ==========================//

    /**
     * User given date convert to mysql date formate
     * $givenDate it will get given date.
     */

    public function dateConvertForMysql($givenDate)
    {
        $mysqlDate = "";
        if ($date = explode('/', $givenDate)) {
            $mysqlDate = $date[2] . '-' . $date[1] . '-' . $date[0];
        }
        return $mysqlDate;
    }

    /**
     * Save and update employee appointment data depending saveAppointmentInfo() function pram
     * $type = insert then insert data , $type = update then update data
     */
    public function employeeAppointmentInfo()
    {
        $this->auth->restrict('HRM.Employee.View');
        $this->load->model('employee_appoinment_model', NULL, TRUE);

        if (isset($_POST['save'])) {
            $employeeId = (int)$this->uri->segment(5);
            $this->db->trans_start();
            $empAppointmentId = $this->saveAppointmentInfo();
            $this->db->trans_complete();

            if ((int)$empAppointmentId > 0) {
                // Log the activity
                log_activity($this->current_user->id, lang('bf_act_create_record') . ': ' . $empAppointmentId . ' : ' . $this->input->ip_address(), 'hrm_emp_appointment_info');
                Template::set_message(lang('bf_msg_create_success'), 'success');
                redirect(SITE_AREA . '/employee/hrm/employee_tab/' . $employeeId . '/emp_policy_tagging');
            } else {
                Template::set_message(lang('bf_msg_create_failure') . $this->employee_appoinment_model->error, 'error');
            }
        }

        $employeeId = (int)$this->uri->segment(5);

        $appointment_details = "";

        if ($employeeId) {
            $appointment_details = $this->employee_appoinment_model->find_by('hrm_emp_appointment_info.EMP_ID', $employeeId);
        }

        $data = array();
        $data['appointment_details'] = $appointment_details;
        $data['EMP_ID'] = $employeeId;

        echo $this->load->view('employee/emp_appointment_info_form', $data, TRUE);
    }

    /*
    * Summary : If "$EMP_ID" is all ready exists than '$type' = update, Action would be update,
    * 		    If "$EMP_ID" is not exists than '$type' = insert, Action would be insert.
    */
    private function saveAppointmentInfo($type = 'insert', $id = 0)
    {
        $EMP_ID = (int)$this->uri->segment(5);

        $countEmpId = $this->employee_appoinment_model->count_by('EMP_ID', $EMP_ID);

        if ($EMP_ID && $countEmpId > 0) {
            $type = 'update';
        } else {
            $type = 'insert';
        }

        // make sure we only pass in the fields we want
        $data = array();
        $data['EMP_ID'] = (int)$this->uri->segment(5);
        $data['EMP_VISUAL_ID'] = $this->input->post('emp_visual_id');
        $data['FILE_NO'] = $this->input->post('file_no');
        $data['APPLICATION_NO'] = $this->input->post('application_no');
        $data['CIRCULAR_NO'] = $this->input->post('circular_no');
        $data['CIRCULAR_DATE'] = $this->dateConvertForMysql($this->input->post('circular_date'));
        $data['WRITTEN_EXAM_DATE'] = $this->dateConvertForMysql($this->input->post('written_exam_date'));
        $data['VIVA_EXAM_DATE'] = $this->dateConvertForMysql($this->input->post('viva_exam_date'));
        $data['APPOINTMENT_LETTER_NO'] = $this->input->post('appointment_letter_no');
        $data['FIRST_REFERENCE_PERSON_NAME'] = $this->input->post('first_reference_person_name');
        $data['FIRST_REFERENCE_PERSON_DESCRIPTION'] = trim($this->input->post('first_reference_person_description'));
        $data['SECOND_REFERENCE_PERSON_NAME'] = $this->input->post('second_reference_person_name');
        $data['SECOND_REFERENCE_PERSON_DESCRIPTION'] = trim($this->input->post('second_reference_person_description'));

        $this->load->model('employee_appoinment_model', NULL, TRUE);

        if ($type == 'insert') {
            $this->auth->restrict('HRM.Employee.Create');
            $data['CREATED_BY'] = $this->current_user->id;
            $return = $this->employee_appoinment_model->insert($data);
        } elseif ($type == 'update') {
            $data['MODIFY_BY'] = $this->current_user->id;
            $data['MODIFIED_DATE'] = date('Y-m-d H:i:s');
            $return = $this->db->update('hrm_emp_appointment_info', $data, array('EMP_ID' => $EMP_ID));
        }
        return $return;
    }
    //=================== Employee Appoint Info End ==========================//


}

