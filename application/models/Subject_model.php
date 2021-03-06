<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject_model extends CI_Model {
   
    public function submitSubGroup($id,$data){
        if($id !=''){
            //------------update subject gorup-----------------
             $this->db->where('sg_id',$id)->update('sub_group',$data);
             
             //-----------log report---------------
             $event = 'Update Subject Group';
             $user = $this->session->userdata('user_id');
             $table_name = 'sub_group';
             $table_id = $id;
             $this->my_function->add_log($user,$event,$table_name,$table_id);
             return true;
        }else{
            //------------insert subject gorup-----------------
            $this->db->insert('sub_group',$data);
            
            //-----------log report---------------
            $event = 'Add Subject Group';
            $user = $this->session->userdata('user_id');
            $table_name = 'sub_group';
            $table_id = $this->db->insert_id();
            $this->my_function->add_log($user,$event,$table_name,$table_id);
            return true;
        }
    }
    
    public function deleteSubGroup($delete_id){
        //------------delete subject gorup-----------------
        return $this->db->where('sg_id',$delete_id)->update('sub_group',array('status'=>0));
    }
   
    /*--------------------subject type-------------------------------*/
    public function submitSubjectType($st_id,$data){
        if($st_id != ''){
            //------------update subject gorup-----------------
            $this->db->where('st_id',$st_id)->update('sub_type',$data);
            
            //-----------log report---------------
            $event = 'Update Subject Type';
            $user = $this->session->userdata('user_id');
            $table_name = 'sub_type';
            $table_id = $st_id;
            $this->my_function->add_log($user,$event,$table_name,$table_id);
            return true;
        }else{
            //------------insert subject gorup-----------------
            $this->db->insert('sub_type',$data);
            
            //-----------log report---------------
            $event = 'Add New Subject Type';
            $user = $this->session->userdata('user_id');
            $table_name = 'sub_type';
            $table_id = $this->db->insert_id();
            $this->my_function->add_log($user,$event,$table_name,$table_id);
            return true;
        }
    }
    
    public function deleteSubType($delete_id){
        //------------delete subject gorup-----------------
        return $this->db->where('st_id',$delete_id)->update('sub_type',array('status'=>0));
        
    }
    
   /*-------------------subject------------------------------------*/
    public function subjectSubmit($sub_id,$data){
        if($sub_id != ''){
            //------------update subject gorup-----------------
            $this->db->where('sub_id',$sub_id)->update('subject',$data);
            
            //-----------log report---------------
            $event = 'Update Subject';
            $user = $this->session->userdata('user_id');
            $table_name = 'subject';
            $table_id = $sub_id;
            $this->my_function->add_log($user,$event,$table_name,$table_id);
            return true;
        }else{
            //------------insert subject gorup-----------------
            $this->db->insert('subject',$data);
            
            //-----------log report---------------
            $event = 'Add New Subject';
            $user = $this->session->userdata('user_id');
            $table_name = 'subject';
            $table_id = $this->db->insert_id();
            $this->my_function->add_log($user,$event,$table_name,$table_id);
            return true;
        }
    }
    
    public function delete_subject($sub_id){
        //------------delete subject gorup-----------------
        return $this->db->where('sub_id',$sub_id)->update('subject',array('status'=>0));
    }
    
}