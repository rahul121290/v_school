<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_ctrl extends CI_Controller {
    function __construct(){
        parent :: __construct();
        $this->load->library(array('ion_auth'));
    }
    
    function _load_view(){
        if ($this->ion_auth->logged_in()){
            $this->data['site_name'] = 'Vivartha';
            $this->data['site_title'] = 'Vivartha';
            $this->load->view("template/temp", $this->data);
        }else{
            redirect('auth/login');
        }
    }
    
    function _admin_class_teacher_access(){
        if ($this->ion_auth->logged_in()){
            $user_id = $this->session->userdata('user_id');
            $session = $this->session->userdata('session_id');
            $school = $this->session->userdata('school_id');
            
            $this->db->select('ct.med_id,ct.class_id,ct.sec_id,ct.sg_id,t.email');
            $this->db->join('teacher t','t.t_id=ct.t_id');
            $this->db->where('t.email=(SELECT `email` FROM `users` WHERE `id` ='.$user_id.')');
            $is_class_teacher = $this->db->get_where('class_teacher ct',array('ct.ses_id'=>$session,'sch_id'=>$school,'ct.status'=>1))->result_array();
            
            if(count($is_class_teacher) > 0){
                $medium = 'med_id='.$is_class_teacher[0]['med_id'];
                $class = 'c_id='.$is_class_teacher[0]['class_id'];
                $section = 'sec_id='.$is_class_teacher[0]['sec_id'];
                $group = 'sg_id='.$is_class_teacher[0]['sg_id'];
            }else{
                $medium = '1=1';
                $class = '1=1';
                $section = '1=1';
                $group = '1=1';
            }
            if($this->ion_auth->is_admin() || count($is_class_teacher) > 0 ){
                $this->data['site_name'] = 'SMIS';
                $this->data['site_title'] = 'smis';
                $this->data['session'] = $this->db->select('ses_id,session_name')->get_where('session',array('status'=>1))->result_array();
                $this->data['exam_type'] = $this->db->select('et_id,et_name')->get_where('exam_type',array('status'=>1))->result_array();
                $this->data['medium'] = $this->db->select('med_id,med_name')->where($medium)->get_where('medium',array('status'=>1))->result_array();
                $this->data['class'] = $this->db->select('c_id,class_name')->where($class)->get_where('class',array('status'=>1))->result_array();
                $this->data['section'] = $this->db->select('sec_id,section_name')->where($section)->get_where('section',array('status'=>1))->result_array();
                $this->data['group'] = $this->db->select('sg_id,sg_name')->where($group)->get_where('sub_group',array('status'=>1))->result_array();
                $this->data['elective'] = $this->db->select('sub_id,sub_name')->join('sub_type st','st.st_id=s.st_id')->where('st.st_name','elective')->get_where('subject s',array('s.status'=>1))->result_array();
                $this->data['hostel'] = $this->db->select('hid,hostel_name')->get_where('hostel',array('ses_id'=>$session,'sch_id'=>$school,'status'=>1))->result_array();
                $this->load->view("template/temp", $this->data);
            }
        }else{
            redirect('auth/login');
        }
    }
    
    function admin_teacher_class_teacher_access(){
        if ($this->ion_auth->logged_in()){
            $user_id = $this->session->userdata('user_id');
            $session = $this->session->userdata('session_id');
            $school = $this->session->userdata('school_id');
            
            $this->db->select('t_id,email');
            $this->db->where('email=(SELECT `email` FROM `users` WHERE `id` ='.$user_id.')');
            $teacher = $this->db->get_where('teacher')->result_array();
            if(count($teacher)>0){
                $this->db->select('sa.med_id,sa.class_id,st.sec_id,sa.st_id,sa.sg_id,sa.sub_id');
                $this->db->join('subject_allocation sa','sa.sa_id=st.sa_id');
                $this->db->join('class c','c.c_id = sa.class_id');
                $this->db->join('section sec','sec.sec_id=st.sec_id');
                $this->db->join('sub_type sub_t','sub_t.st_id=sa.st_id');
                $this->db->join('sub_group sg','sg.sg_id=sa.sg_id');
                $this->db->join('subject sub','sub.sub_id = sa.sub_id');
                $this->db->join('teacher t1','t1.t_id=st.t_id');
                $this->db->where('st.t_id',$teacher[0]['t_id']);
                $subject_teacher = $this->db->get_where('sub_teacher st',array('st.status'=>1))->result_array();
                $medium = array();
                $class = array();
                $sub_group = array();
                $section = array();
                $sub_type = array();
                $subject = array();
                foreach($subject_teacher as $sub_teacher){
                    $medium[] = $sub_teacher['med_id'];
                    $class[] = $sub_teacher['class_id'];
                    $sub_group[] = $sub_teacher['sg_id'];
                    $section[] = $sub_teacher['sec_id'];
                    $sub_type[] = $sub_teacher['st_id'];
                    $subject[] = $sub_teacher['sub_id'];
                }
                $medium =  implode(',', array_unique($medium));
                $class= implode(',', array_unique($class));
                $sub_group= implode(',', array_unique($sub_group));
                $section= implode(',', array_unique($section));
                $sub_type= implode(',', array_unique($sub_type));
                $subject= implode(',', array_unique($subject));
            }else {
                $subject_teacher ='';
            }
          
            if($this->ion_auth->is_admin()){
                $medium = '1=1';
                $class = '1=1';
                $section = '1=1';
                $group = '1=1';
                $st = '1=1';
            }elseif(count($subject_teacher) > 0){
                $medium = 'med_id IN ('.$medium.')';
                $class = 'c_id IN ('.$class.')';
                $section = 'sec_id IN ('.$section.')';
                $group = 'sg_id IN ('.$sub_group.')';
                $st = 'st_id IN ('.$sub_type.')';
            }
            
            $this->data['medium'] = $this->db->select('med_id,med_name')->where($medium)->get_where('medium',array('status'=>1))->result_array();
            $this->data['exam_type'] = $this->db->select('et_id,et_name')->get_where('exam_type',array('status'=>1))->result_array();
            $this->data['class'] = $this->db->select('c_id,class_name')->where($class)->get_where('class',array('status'=>1))->result_array();
            $this->data['section'] = $this->db->select('sec_id,section_name')->where($section)->get_where('section',array('status'=>1))->result_array();
            $this->data['group'] = $this->db->select('sg_id,sg_name')->where($group)->get_where('sub_group',array('status'=>1))->result_array();
            $this->data['sub_type'] = $this->db->select('st_id,st_name')->where($st)->get_where('sub_type',array('status'=>1))->result_array();
            
            $this->data['site_name'] = 'Vivartha';
            $this->data['site_title'] = 'Multi Users';
            $this->load->view("template/temp", $this->data);
        }else{
            redirect('auth/login');
        }
    }
    
    function index(){
        $this->data['page_name'] = 'Dashbord';
        $this->data['main'] = 'dashbord/dashbord';
        $this->_load_view();
    }
    
    function testing(){
        $db1 = $this->load->database('server',TRUE);
        $result = $db1->get('class')->result_array();
        print_r($result); 
    }
	
    function session_master(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'Session Master';
            $this->data['main'] = 'master/session_master';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function school_master(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'School Master';
            $this->data['main'] = 'master/school_master';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function medium_master(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'Medium Master';
            $this->data['main'] = 'master/medium_master';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function class_master(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'Class Master';
            $this->data['main'] = 'master/class_master';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function subject_master(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'Subject Master';
            $this->data['main'] = 'master/subject_master';
            $this->data['sub_type'] = $this->db->select('st_id,st_name')->where('status',1)->get('sub_type')->result_array();
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function subject_allocation(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'Subject Allocation';
            $this->data['main'] = 'master/subject_allocation';
            
            //-----post data in page-----------------------------------------------
            $this->data['medium'] = $this->db->select('med_id,med_name')->where('status',1)->get('medium')->result_array();
            $this->data['class'] = $this->db->select('c_id,class_name')->where('status',1)->get('class')->result_array();
            $this->data['section'] = $this->db->select('sec_id,section_name')->where('status',1)->get('section')->result_array();
            $this->data['sub_type'] = $this->db->select('st_id,st_name')->where('status',1)->get('sub_type')->result_array();
            $this->data['sub_gorup'] = $this->db->select('sg_id,sg_name')->where('status',1)->get('sub_group')->result_array();
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function add_teacher(){
        if($this->ion_auth->is_admin()){
            $this->data['page_name'] = 'Add Teacher';
            $this->data['main'] = 'master/add_teacher';
            $this->data['teachers'] = $this->db->select('*')->get_where('teacher',array('school_id'=>(int)$this->session->userdata('school_id'),'status'=>1))->result_array();
            $this->data['medium'] = $this->db->select('med_id,med_name')->where('status',1)->get('medium')->result_array();
            $this->data['class'] = $this->db->select('c_id,class_name')->where('status',1)->get('class')->result_array();
            $this->data['section'] = $this->db->select('sec_id,section_name')->where('status',1)->get('section')->result_array();
            $this->data['sub_type'] = $this->db->select('st_id,st_name')->where('status',1)->get('sub_type')->result_array();
            $this->data['sub_gorup'] = $this->db->select('sg_id,sg_name')->where('status',1)->get('sub_group')->result_array();
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function subject_teacher(){
        if($this->ion_auth->is_admin()){
            $this->data['sub_type'] = $this->db->select('st_id,st_name')->where('status',1)->get('sub_type')->result_array();
            $this->data['teachers'] = $this->db->select('t_id,teacher_name')->get_where('teacher',array('status'=>1))->result_array();
            $this->data['page_name'] = 'Subject Teacher';
            $this->data['main'] = 'master/subject_teacher';
            $this->_admin_class_teacher_access();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function define_user_role(){
        if($this->ion_auth->is_admin()){
            $school = $this->session->userdata('school_id');
            $this->data['page_name'] = 'Define User Role';
            $this->data['main'] = 'master/user_role';
            $this->data['teachers'] = $this->db->select('t_id,teacher_name')->get_where('teacher',array('status'=>1,'school_id'=>$school))->result_array();
            $this->data['permission'] = $this->db->select('pid,p_name')->get_where('permission',array('status'=>1))->result_array();
            $this->data['groups'] = $this->db->select('id,name')->where('id > 1')->get_where('groups')->result_array();
            $this->data['user_list'] = $this->db->select('id,username,email,pass_hint')->order_by('id','DESC')->where('id NOT IN (1,2)')->get_where('users',array('active'=>1,'status'=>1,'school_id'=>$school))->result_array();
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function add_student(){
        $this->data['page_name'] = 'Add Student';
        $this->data['main'] = 'master/add_student';
        $this->_admin_class_teacher_access();
    }
    
    function student_records(){
        $this->data['page_name'] = 'Student Records';
        $this->data['main'] = 'report/student_records';
        $this->_admin_class_teacher_access();
    }
    
    function attendance_entry(){
        $session = $this->session->userdata('session_id');
        $school = $this->session->userdata('school_id');
        $this->data['page_name'] = 'Attendance Entry';
        $this->data['main'] = 'transaction/attendance_entry';
        $this->_admin_class_teacher_access();
    }
    
    function student_attendance(){
        $this->data['page_name'] = 'Student Attendance';
        $this->data['main'] = 'transaction/student_attendance';
        $this->_admin_class_teacher_access();
    }
    
    function daily_attendance(){
        $this->data['page_name'] = 'Daily Attendance';
        $this->data['main'] = 'transaction/daily_attendance';
        $this->admin_teacher_class_teacher_access();
    }
    
    function marks_entry(){
        $this->data['page_name'] = 'Marks Entry';
        $this->data['main'] = 'transaction/marks_entry';
        $this->admin_teacher_class_teacher_access();
    }
    
    function marks_entry_check(){
        $this->data['page_name'] = 'Marks Entry Check';
        $this->data['main'] = 'production/marks_entry_check';
        $this->_admin_class_teacher_access();    
    }
    
    function furd_report(){
        $this->data['page_name'] = 'Furd Report';
        $this->data['main'] = 'production/furd_report';
        $this->_admin_class_teacher_access();
    }
    
    function teacher_abstract(){
        $this->data['page_name'] = 'Teacher Abstract';
        $this->data['main'] = 'production/teacher_abstract';
        $this->_admin_class_teacher_access();
    }
    
    function generate_marksheet(){
        $this->data['page_name'] = 'Generate Marksheet';
        $this->data['main'] = 'production/generate_marksheet';
        $this->_admin_class_teacher_access();
    }
    
    function add_division(){
        if($this->ion_auth->is_admin()){
            $this->data['medium'] = $this->db->select('med_id,med_name')->get_where('medium',array('status'=>1))->result_array();
            $this->data['page_name'] = 'Add Division';
            $this->data['main'] = 'utilities_and_tools/add_division';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function add_grade(){
        if($this->ion_auth->is_admin()){
            $this->data['medium'] = $this->db->select('med_id,med_name')->get_where('medium',array('status'=>1))->result_array();
            $this->data['page_name'] = 'Add Grade';
            $this->data['main'] = 'utilities_and_tools/add_grade';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function pay_student_fees(){
        if($this->ion_auth->is_admin()){
            $this->data['session'] = $this->db->select('ses_id,session_name')->get_where('session',array('status'=>1))->result_array();
            $this->data['school'] = $this->db->select('sch_id,school_name')->get_where('school',array('status'=>1))->result_array();
            $this->data['medium'] = $this->db->select('med_id,med_name')->get_where('medium',array('status'=>1))->result_array();
            $this->data['class'] = $this->db->select('c_id,class_name')->get_where('class',array('status'=>1))->result_array();
            $this->data['section'] = $this->db->select('sec_id,section_name')->get_where('section',array('status'=>1))->result_array();
            $this->data['group'] = $this->db->select('sg_id,sg_name')->get_where('sub_group',array('status'=>1))->result_array();
            $this->data['month'] = $this->db->select('m_id,m_name')->get_where('month',array('status'=>1))->result_array();
            $this->data['fees_type'] = $this->db->select('ft_id,ft_name')->get_where('fees_type',array('status'=>1))->result_array();
            $this->data['payment_mode'] = $this->db->select('p_id,pm_name')->get_where('payment_mode',array('status'=>1))->result_array();
            
            $this->data['page_name'] = 'Pay Student Fees';
            $this->data['main'] = 'fees_payment/pay_student_fees';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function generate_fees_csv(){
        if($this->ion_auth->is_admin()){
            $this->data['session'] = $this->db->select('ses_id,session_name')->get_where('session',array('status'=>1))->result_array();
            $this->data['school'] = $this->db->select('sch_id,school_name')->get_where('school',array('status'=>1))->result_array();
            $this->data['medium'] = $this->db->select('med_id,med_name')->get_where('medium',array('status'=>1))->result_array();
            $this->data['class'] = $this->db->select('c_id,class_name')->get_where('class',array('status'=>1))->result_array();
            $this->data['section'] = $this->db->select('sec_id,section_name')->get_where('section',array('status'=>1))->result_array();
            $this->data['group'] = $this->db->select('sg_id,sg_name')->get_where('sub_group',array('status'=>1))->result_array();
            $this->data['page_name'] = 'Generate Fees CSV';
            $this->data['main'] = 'fees_payment/generate_fees_csv';
            $this->_load_view();
        }else{
            print_r("You have not permission to access this page");
        }
    }
    
    function export_marksheet(){
        $this->data['page_name'] = 'Furd Report';
        $this->data['main'] = 'production/export_markssheet';
        $this->_admin_class_teacher_access();
    }
    
    function profile(){
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->join('teacher t','t.t_id = u.t_id','LEFT');
        $result = $this->db->get_where('users u',array('u.status'=>1,'u.id'=>$user_id))->result_array();
        
        $this->data['page_name'] = 'Profile';
        $this->data['main'] = 'report/profile';
        $this->data['user_details'] = $result;
        $this->_load_view();
    }
    function updateProfile(){
        $data['teacher_name'] = $this->input->post('teacher_name');
        $data['gender'] = $this->input->post('gender');
        $data['dob'] = $this->input->post('dob');
        $data['phone'] = $this->input->post('phone');
        $data['email'] = $this->input->post('email');
        $data['prmt_address'] = $this->input->post('prmt_address');
        $data['designation'] = $this->input->post('designation');
        $data['qualifications'] = $this->input->post('qualifications');
        $data['old_image'] = $this->input->post('old_image');
        
        $this->load->model('Teacher_model');
        $result = $this->Teacher_model->updateProfile($data);
        if($result){
            echo json_encode(array('msg'=>'Profile Update Successfully.','status'=>200));
        }else{
            echo json_encode(array('msg'=>'Failed, Please Try again.','status'=>500));
        }
    }
    
    function changePassword(){
        $this->load->library('ion_auth');
        $user_id = $this->session->userdata('user_id');
        $data['old_password'] = $this->input->post('old_password');
        $data['password'] = $this->ion_auth_model->hash_password($this->input->post('confirm_password'),FALSE,FALSE);
        $data['pass_hint'] = $this->input->post('confirm_password'); 
        
        $check_old_pass =  $this->db->get_where('users',array('pass_hint'=>$data['old_password'],'id'=>$user_id))->result_array();
        //print_r($this->db->last_query());die;
       if(count($check_old_pass) > 0){
           $this->db->where('id',$user_id);
           $result = $this->db->update('users',array('password'=>$data['password'],'pass_hint'=>$data['pass_hint']));
           if($result){
               echo json_encode(array('msg'=>'Password Update Successfully','status'=>200));
           }else{
               echo json_encode(array('msg'=>'something went wrong.','status'=>500));
           }
       }else{
           echo json_encode(array('msg'=>'old password not match.','status'=>201));
       }
        
    }
    
    
}