<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Signature extends Crud{
    public $name;
    public $type;
    public $font;
    public $status;
    public $inuse;
    public $account_idaccount;

    public function createSignature($data = null){
        if(is_null($data)){
            $this->name = $_POST['name'];
            $this->type = $_POST['type'];
            $this->font = $_POST['font'];
            $this->status = $_POST['status'];
            $this->inuse = 0;
            $this->account_idaccount = $_POST['account_idaccount'];
            $data = $this;
        }
        
        return $this->create('signature', $data);
    }



    public function readSignature($where = null){
        if(!is_null($where)){
            return $this->ready('signature', $where);
        } else{
            return $this->ready('signature');
        }
    }

    public function updateSignature($id){
        $this->name = $_POST['name'];
        $this->type = $_POST['type'];
        $this->font = $_POST['font'];
        $this->status = $_POST['status'];
        $this->inuse = ($this->verifyInUse($id)) ? 1 : 0;
        $this->account_idaccount = $_POST['account_idaccount'];
        $data = $this;

        $this->db->db_debug = false;
        $where = array('idsignature' => $id);
        if($this->update('signature', $where, $data)){
            return $this->response('success', 'Assinatura editada com sucesso');
        } else{
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function updateInUse($id){
        $inuse = ($this->verifyInUse($id)) ? 1 : 0;
        $data = array('inuse' => $inuse);
        $where = array('idsignature' => $id);
        return $this->update('signature', $where, $data);
    }

    public function deleteSignature($where){
        return $this->delete('signature', $where);
    }

    public function deleteContractorSignature($id){
        $where = array('idsignature' => $id, 'type' => 1);
        return $this->delete('signature', $where);
    }

    public function verifyInUse($id){
        $ahs = $this->ready('agreement_has_signature', array('signature_idsignature' => $id));
        return ($ahs->num_rows() != 0) ? true : false;
    }

    public function markAsSigned($id){
        $where = ['signature_idsignature' => $id];
        $data = [
            'sign' => 1,
            'date' => $_POST['date'],
            'ip' => $_POST['ip'],
            'geolocation' => $_POST['geolocation'],
            'hash' => $_POST['hash']
        ];
        return $this->update('agreement_has_signature', $where, $data);
    }
}