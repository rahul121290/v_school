<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_fee_ctrl extends CI_Controller {
    var $permission;
    function __construct(){
        parent :: __construct();
        $this->load->library('My_function');
        $this->load->model('Student_fee_model');
    }
    
    function studentList(){
        $data['ses_id'] = $this->input->post('session');
        $data['sch_id'] = $this->input->post('school');
        $data['medium'] = $this->input->post('medium');
        $data['class_id'] = $this->input->post('class_name');
        $data['sec_id'] = $this->input->post('section');
        $data['search_data'] = $this->input->post('search_data');
        
        $condition = 's.status = 1';
        if(!empty($data['ses_id'])){
            $condition .= ' AND s.ses_id = '.$data['ses_id']; 
        }
        if(!empty($data['sch_id'])){
            $condition .= ' AND s.sch_id = '.$data['sch_id'];
        }
        if(!empty($data['medium'])){
            $condition .= ' AND s.medium = '.$data['medium'];
        }
        if(!empty($data['class_id'])){
            $condition .= ' AND s.class_id = '.$data['class_id'];
        }
        if(!empty($data['sec_id'])){
            $condition .= ' AND s.sec_id = '.$data['sec_id'];
        }
        if(!empty($data['search_data'])){
            $condition .= ' AND s.adm_no = '.$data['search_data'];
        }
        
        $this->db->select('s.ses_id,s.sch_id,s.medium,s.adm_no,c.class_name,sec.section_name,s.name,s.f_name,IFNULL(sg.sg_name,"") sg_name,s.contact_no');
        $this->db->join('class c','c.c_id=s.class_id AND c.status = 1');
        $this->db->join('section sec','sec.sec_id=s.sec_id','LEFT');
        $this->db->join('medium m','m.med_id=s.medium');
        $this->db->join('sub_group sg','sg.sg_id = s.sub_group','LEFT');
        $this->db->where($condition);
        $result = $this->db->get_where('students s')->result_array();
        if(count($result) > 0){
            echo json_encode(array('data'=>$result,'status'=>200));
        }else{
            echo json_encode(array('msg'=>'record not found.','status'=>500));
        }
    }
      
    
    function feeDetailList(){
        $ses_id = $this->input->post('ses_id');
        $sch_id = $this->input->post('sch_id');
        $med_id = $this->input->post('med_id');
        $adm_no = $this->input->post('adm_no');
        
        $this->db->select('s.*,s.staff_child staff_child_id,m.med_name,fc.fc_name,bs.bus_stoppage,bs.price bus_fee,c.class_name,IFNULL(sc.name,"") staff_child');
        $this->db->join('class c','c.c_id = s.class_id AND c.status = 1');
        $this->db->join('fee_criteria fc','fc.fc_id = s.fee_criteria AND fc.status = 1');
        $this->db->join('staff_child sc','sc.sc_id = s.staff_child','LEFT');
        $this->db->join('medium m','m.med_id = s.medium');
        $this->db->join('bus_structure bs','bs.bs_id = s.bus_id AND bs.ses_id = s.ses_id AND bs.status = 1','LEFT');
        $this->db->where(array('s.ses_id'=>$ses_id,'s.sch_id'=>$sch_id,'s.medium'=>$med_id,'s.adm_no'=>$adm_no));
        $result['student'] = $this->db->get_where('students s',array('s.status'=>1))->result_array();
        
        if(count($result['student']) > 0){
            $condition = 'fsm.status = 1';
            $condition .=' AND fsm.ses_id = '.$ses_id;
            $condition .=' AND fsm.sch_id = '.$sch_id;
            $condition .=' AND fsm.med_id = '.$med_id;
            $condition .=' AND fsm.class_id = '.$result['student'][0]['class_id'];
            $condition .=' AND cfs.fc_id = '.$result['student'][0]['fee_criteria'];
            $condition .=' AND cfs.staff_child = '.$result['student'][0]['staff_child_id'];
            
            $session_fee = '0';
            $month_fee = '0';
            $this->db->select('GROUP_CONCAT(session_fee_ids) as session_fee, GROUP_CONCAT(month_ids) as month_fee');
            $ckeck_paid_fee = $this->db->get_where('student_fee',array('status'=>1,'ses_id'=>$ses_id,'sch_id'=>$sch_id,'med_id'=>$med_id,'adm_no'=>$adm_no))->result_array();
            
            if(count($ckeck_paid_fee) > 0){
                if($ckeck_paid_fee[0]['session_fee'] != null){
                    $session_fee = $ckeck_paid_fee[0]['session_fee'];
                }
                if($ckeck_paid_fee[0]['month_fee'] != null){
                    $month_fee = $ckeck_paid_fee[0]['month_fee'];
                }
            }
            
            $this->db->select('ft.ft_id,ft.name,fc.fc_id,fc.fc_name,cfs.amount,IF(t1.name IS NULL,"Pending","Paid") fee_status');
            $this->db->join('fee_structure_master fsm','fsm.fs_id = cfs.fsm_id');
            $this->db->join('fee_criteria fc','fc.fc_id = cfs.fc_id');
            $this->db->join('fee_type ft','ft.ft_id = cfs.ft_id');
            $this->db->join('(SELECT * FROM fee_type WHERE ft_id IN ('.$session_fee.')) t1','t1.ft_id = ft.ft_id','LEFT');
            $this->db->where($condition);
            if($result['student'][0]['std_status'] == 'old_student'){ //----old student not show admission fee----
                $this->db->where('ft.ft_id <> 1');
            }
            if($result['student'][0]['class_id'] < 12){ //---nursury to class VIII not show lab fee and option sub fee----
                $this->db->where('ft.ft_id <> IN (3,4)');
            }
            $this->db->where('ft.ft_id <> 5'); //---tution fee always not including in seesion fee-----------
            $result['session_fee'] = $this->db->get_where('class_fee_structure cfs')->result_array();
            //print_r($this->db->last_query());die;
            
            
            $this->db->select('fm.*,DATE_FORMAT(fm.due_date,"%d-%m-%Y") as show_due_date,t1.amount tution_fee');
            $this->db->join('(SELECT fsm.ses_id,cfs.amount
                             FROM class_fee_structure cfs
                             JOIN fee_structure_master fsm ON fsm.fs_id = cfs.fsm_id
                             WHERE fsm.status = 1 AND fsm.ses_id = '.$ses_id.' AND fsm.sch_id = '.$sch_id.' AND fsm.med_id = '.$med_id.' AND fsm.class_id = '.$result['student'][0]['class_id'].' AND cfs.fc_id = 1
                             AND cfs.ft_id = 5) t1','t1.ses_id = fm.ses_id',false);
            $this->db->where('fm_id NOT IN ('.$month_fee.')');
            //$this->db->join('(SELECT * FROM fee_month WHERE fm_id IN ('.$month_fee.')) t2','t2.fm_id = fm.fm_id','LEFT');
            $fee_month = $this->db->get_where('fee_month fm',array('fm.status'=>1))->result_array();
            
            $result['fee_month'] = array();
            if(count($fee_month) > 0){
                $current_date = date('Y-m-d');
                foreach($fee_month as $month){
                    $temp = array();
                    $temp['fm_id'] =  $month['fm_id'];
                    $temp['name'] =  $month['name'];
                    $temp['fee'] =  $month['tution_fee'] * $month['total_month'];
                    $temp['due_date'] = $month['show_due_date'];
                    //------------class 10th and class 12 not march bus fee not include-------------
                    if(($result['student'][0]['class_id'] == 13 && $month['fm_id'] == 9) || ($result['student'][0]['class_id'] == 15 && $month['fm_id'] == 9)){
                        $temp['bus_fee'] = $result['student'][0]['bus_fee'] * 1;
                    }else{
                        $temp['bus_fee'] = $result['student'][0]['bus_fee'] * $month['bus_month'];
                    }
                    
                    //--------------get late fee--------------
                    $last_date_of_month =  date("Y-m-t", strtotime($month['due_date']));
                    $temp['late_fee'] = 0;
                    if(strtotime($current_date) > strtotime($last_date_of_month)){
                        $temp['late_fee'] = 200;
                    }else if(strtotime($current_date) > strtotime($month['due_date']) ){
                        $datediff = strtotime($current_date) - strtotime($month['due_date']);
                        $datediff =  round($datediff / (60 * 60 * 24));
                        $temp['late_fee'] = 5 * $datediff;
                    }
                    //--------------**********--------------
                    $result['fee_month'][] = $temp;
                }
            }
            
            //-------------fee waiver-----------------------------
            $this->db->select('*');
            $this->db->where(array('ses_id'=>$ses_id,'sch_id'=>$sch_id,'med_id'=>$med_id,'month_id'=>date('m'),'adm_no'=>$adm_no));
            $result['fee_waiver'] = $this->db->get_where('fee_waiver',array('status'=>1))->result_array();
            
            echo json_encode(array('data'=>$result,'status'=>200));
        }else{
            echo json_encode(array('msg'=>'Student record not found.','status'=>500));
        }
    }
    
    function fee_waiver_apply(){
        $data['ses_id'] = $this->input->post('ses_id');
        $data['sch_id'] = $this->input->post('sch_id');
        $data['med_id'] = $this->input->post('med_id');
        $data['adm_no'] = $this->input->post('adm_no');
        $data['month_id'] = $this->input->post('month_id');
        $amount = $this->input->post('amount');
        $otp = $this->my_function->generateNumericOTP();
        
        $this->db->trans_begin();
        $this->db->select('*');
        $this->db->where($data);
        $result = $this->db->get_where('fee_waiver',array('status'=>1))->result_array();
        if(count($result) > 0){
            //------------Update------------------
            $this->db->where($data);
            $this->db->update('fee_waiver',array(
                'amount' => $amount,
                'otp' => $otp,
                'remark' => 'offline',
                'applied_on' => date('Y-m-d h:i:s'),
                'created_by' => $this->session->userdata('user_id')
            ));
        }else{
            //------------insert------------------
            $data['amount'] = $amount;
            $data['otp'] = $otp;
            $data['remark'] = 'offline';
            $data['applied_on'] = date('Y-m-d h:i:s');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $this->db->insert('fee_waiver',$data);
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('msg'=>'Apply fee waiver failed, Please try again.','status'=>500));
        }else {
            $this->db->trans_commit();
            $this->db->select('*');
            $student = $this->db->get_where('students',array(
                'ses_id' => (int)$data['ses_id'],
                'sch_id' => (int)$data['sch_id'],
                'medium' => (int)$data['med_id'],
                'adm_no' => $data['adm_no'],
                'status' => 1))->result_array();
            
            if(count($student)> 0 ){
                $mobile = '8817721954';
                $msg = 'Fee waiver request OTP is '.$otp.' for '.$student[0]['name'].', Admn No. '.$data['adm_no'].'. Amt: '.$amount.' - '.$this->session->userdata('username');
                //$this->my_function->send_sms($mobile,$msg);
            }else{
                print_r('OTP Not Send.');die;
            }
            echo json_encode(array('msg'=>'Apply fee waiver successfully.','status'=>200));
        }
    }
    
    function check_otp(){
        $data['ses_id'] = $this->input->post('ses_id');
        $data['sch_id'] = $this->input->post('sch_id');
        $data['med_id'] = $this->input->post('med_id');
        $data['adm_no'] = $this->input->post('adm_no');
        $data['month_id'] = $this->input->post('month_id');
        $data['otp'] = $this->input->post('otp');
        
        $this->db->select('fw_id');
        $this->db->where($data);
        $result = $this->db->get_where('fee_waiver',array('status'=>1))->result_array();
        if(count($result) > 0){
            $this->db->where('fw_id',$result[0]['fw_id']);
            $this->db->update('fee_waiver',array('otp'=>''));
            
            echo json_encode(array('msg'=>'successfull','status'=>200));
        }else{
            echo json_encode(array('msg'=>'Enter valid OTP','status'=>500));
        }
        
    }
    
    
    
    function fee_payment(){
        $this->db->select('MAX(receipt_no) as receipt_no');
        $receipt = $this->db->get_where('student_fee')->result_array();
        
        $data['receipt_no'] = $receipt[0]['receipt_no'] + 1;
        $data['ses_id'] = $this->input->post('ses_id');
        $data['sch_id'] = $this->input->post('sch_id');
        $data['med_id'] = $this->input->post('med_id');
        $data['adm_no'] = $this->input->post('adm_no');
        $data['pay_month'] = date('m');
        
        $data['session_fee_ids'] = $this->input->post('session_fee');
        if(empty($data['session_fee_ids'])){
            $data['session_fee_ids'] = null;
        }
        $data['month_ids'] = $this->input->post('month_ids');
        $data['late_fee'] = $this->input->post('late_fee');
        $data['paid_amount'] = $this->input->post('paid_amount');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $pay_option = json_decode($this->input->post('pay_option'),true);
       
        $pay_method = [];
        foreach($pay_option as $pay){
           $temp = [];
           $temp['method_name'] = $pay[0]['pay_method'];
           $temp['amount'] = $pay[1]['amount'];
           $temp['card_name'] = $pay[2]['card'];
           if(empty($temp['card_name'])){
               $temp['card_name'] = null;
           }
           $temp['trans_amount'] = $pay[3]['trns_amount'];
           if(empty($temp['trans_amount'])){
               $temp['trans_amount'] = null;
           }
           $temp['method_no'] = $pay[4]['method_no'];
           if(empty($temp['method_no'])){
               $temp['method_no'] = null;
           }
           $temp['method_date'] = $pay[5]['date'];
           if(empty($temp['method_date'])){
               $temp['method_date'] = null;
           }
           $temp['bank_name'] = $pay[6]['bank_name'];
           if(empty($temp['bank_name'])){
               $temp['bank_name'] = null;
           }
           $temp['created_at'] = date('Y-m-d H:i:s');
           $temp['created_by'] = $this->session->userdata('user_id');
           $pay_method[] = $temp;    
       }
       
      $result =  $this->Student_fee_model->fee_payment($data,$pay_method);
      if($result){
          $mobile = $this->input->post('contact_no');
          $sms = 'Dear Parent, the fee payment of '.$this->input->post('student_name').' has been received. Amount : '.$data['paid_amount'].'. Regards SVR';
          // print_r($sms);die;
          //$this->my_function->send_sms($mobile,$sms);
          echo json_encode(array('msg'=>'Submit successfully','receipt_no'=>$data['receipt_no'],'status'=>200));
      }else{
          echo json_encode(array('msg'=>'Submit Failed, Please try again.','status'=>500));
      }
       
    }
    
}