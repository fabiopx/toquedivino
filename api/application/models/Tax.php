<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Tax extends Crud{
    public $name;
    public $description;
    public $value;
    public $type;
    public $multiplied;

    public function createTax() {
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->value = brl2decimal($_POST['value']);
        $this->type = $_POST['type'];
        $this->multiplied = $_POST['multiplied'];
        $data = $this;

        if($this->create('tax', $data)){
            return $this->response('success', 'Taxa criada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function createVariantTax($data){
        return $this->create('variant_tax', $data);
    }

    public function readTax($where = null){
        if(!is_null($where)){
            return $this->ready('tax', $where);
        } else{
            return $this->ready('tax');
        }
    }

    public function updateTax($id){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->value = brl2decimal($_POST['value']);
        $this->type = $_POST['type'];
        $this->multiplied = $_POST['multiplied'];
        $data = $this;

        $where = array('idtax' => $id);
        if($this->update('tax', $where, $data)){
            return $this->response('success', 'Taxa editada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteTax($where){
        $response = $this->delete('tax', $where);
        if($response !== false){
            return  $this->response('success', 'Taxa excluída com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteVariantTax($where){
        return $this->delete('variant_tax', $where);
    }

    public function verifyServiceHasTax($idTax){
        $where = array('tax_idtax' => $idTax);
        $sht = $this->ready('service_has_tax', $where);
        return ($sht->num_rows() != 0) ? true : false;
    }
}