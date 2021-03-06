<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_role_model extends CI_Model{
    
    public function userRollSubmit($id,$ug_id,$group,$data){
        $this->db->trans_begin();
        if($id != ''){
            //----------update---------------------
            $this->db->where('id',$id);
            $this->db->update('users',$data);
            
            if($ug_id != ''){
                //-----------update in user gruop table-----------
                $this->db->where('id',$ug_id);
                $this->db->update('users_groups',array('user_id'=>$id,'group_id'=>$group));
            }
            
            
            //-----------log report---------------
            $event = 'Update User Role';
            $user = $this->session->userdata('user_id');
            $table_name = 'users';
            $table_id = $id;
            $this->my_function->add_log($user,$event,$table_name,$table_id);
        }else{
            //---------insert----------------------
            $this->db->insert('users',$data);
            
            $last_id = $this->db->insert_id();
            //------------insert in user gruop table-----------
            $this->db->insert('users_groups',array('user_id'=>$last_id,'group_id'=>$group));
            
            
            //-----------log report---------------
            $event = 'Add New User';
            $user = $this->session->userdata('user_id');
            $table_name = 'users';
            $table_id = $this->db->insert_id();
            $this->my_function->add_log($user,$event,$table_name,$table_id);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }
    }//------end of function----------------------------
    
    public function deleteRecord($delete_id){
        
        $this->db->where('id',$delete_id);
        return $this->db->update('users',array('active'=>0));
    }
    
    
    
    
}