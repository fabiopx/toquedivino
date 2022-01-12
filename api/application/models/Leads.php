<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Leads extends Crud{
    public $datetime;
    public $ip;
    public $origin;
    public $accountable;
    public $phone;
    public $mobile;
    public $email;
    public $flag;

    public function createLead(){
        $this->datetime = $_POST['datetime'];
        $this->ip = $_POST['ip'];
        $this->origin = $_POST['origin'];
        $this->accountable = $_POST['accountable'];
        $this->phone = $_POST['phone'];
        $this->mobile = $_POST['mobile'];
        $this->email = $_POST['email'];
        $this->flag = $_POST['flag'];
        $data = $this;

        $this->db->db_debug = false;
        if($this->create('pre_inscribe', $data)){
            return $this->response('success', 'Lead cadastrada com sucesso');
        } else{
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function readLead($where = null){
        if(!is_null($where)){
            return $this->ready('pre_inscribe', $where);
        } else{
            return $this->ready('pre_inscribe');
        }
    }

    public function createLogMarketing($data){
        $this->db->db_debug = false;
        if($this->create('log_marketing', $data)){
            return $this->response('success', 'Log de marketing cadastrado com sucesso');
        } else{
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function readLogMarketing($where){
        return $this->ready('log_marketing', $where);
    }

    public function returnIdPreInscribe($id){
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->ready('pre_inscribe_has_inscribe', $where);
        if($resp->num_rows() != 0){
            return $resp->row()->pre_inscribe_idpre_inscribe;
        } else{
            return null;
        }
        
    }

    public function updateLead($where, $data){
        return $this->update('pre_inscribe', $where, $data);
    }

    public function deleteLead($where){
        return $this->delete('pre_inscribe', $where);
    }

    public function deletePreInscribeHasInscribe($where){
        return $this->delete('pre_inscribe_has_inscribe', $where);
        
    }

    
}