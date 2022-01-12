<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends CI_Model{

    public function create($table, $data){
        return $this->db->insert($table, $data);
    }

    public function ready($table, $where = null){
        if(!is_null($where)){
            $this->db->where($where);
            return $this->db->get($table);
        } else{
            return $this->db->get($table);
        }
    }

    public function update($table, $where, $data){
        if(!is_null($where)){
            $this->db->where($where);
            return $this->db->update($table, $data);
        } else{
            return $this->db->update($table, $data);
        }
    }

    public function delete($table, $where){
        if(!is_null($where)){
            $this->db->where($where);
            return $this->db->delete($table);
        } else{
            return $this->db->delete($table);
        }
    }

    public function response($type, $msg){
        return ($type == 'success') ? array('msg' => $msg, 'icon' => 'success') : array('msg' => $msg, 'icon' => 'error');
        
    }

    public function printResp($type, $msg){
        echo json_encode(($type == 'success') ? array('msg' => $msg, 'icon' => 'success') : array('msg' => $msg, 'icon' => 'error'));
        exit();
    }
}