<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Service extends Crud{
    public $name;
    public $description;
    public $status;

    public function createService(){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->status = 1;
        $data = $this;
        if($this->create('service', $data)){
            $idServ = $this->db->insert_id();
            $taxas = json_decode($_POST['taxas']);
            $this->createServiceHasTax($idServ, $taxas);
            return $this->response('success', 'Serviço criado com sucesso'); 
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function createServiceHasTax($id, $tax){
        if(is_array($tax)){
            foreach($tax as $t){
                $data = array('tax_idtax' => $t, 'service_idservice' => $id);
                $this->db->insert('service_has_tax', $data);
            }
        } else{
            exit;
        }
    }

    public function readService($where = null){
        if(!is_null($where)){;
            return $this->ready('service', $where);
        } else{
            return $this->ready('service');
        }
    }

    public function readServiceHasTax($id){
        $where = array('service_idservice' => $id);
        return $this->ready('service_has_tax', $where);
    }

    public function updateService($id){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->status = 1;
        $data = $this;

        $taxas = json_decode($_POST['taxas']);
        $sht = array();

        $servHasTax = $this->readServiceHasTax($id);
        foreach($servHasTax->result() as $s):
            $sht[] = $s->tax_idtax;
        endforeach;

        $add = array_diff($taxas, $sht);
        $del = array_diff($sht, $taxas);
        $this->createServiceHasTax($id, $add);
        $this->deleteServiceHasTax($id, $del);

        $where = array('idservice' => $id);
        if($this->update('service', $where, $data)){
            return $this->response('success', 'Serviço atualizado com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteService($id){
        if(!$this->isComposing($id)){
            $where = array('service_idservice' => $id);
            $this->delete('service_has_tax', $where);
            $where = array('idservice' => $id);
            $response = $this->delete('service', $where);
            if($response !== false){
                return $this->response('sucess', 'Serviço excluído com sucesso');
            } else{
                $this->db->db_debug = false;
                $error = $this->db->error();
                return $this->response('error', $error['message']);
            }
        } else{
            return $this->response('error', 'Este serviço não pode ser deletado pois compõe uma inscrição ou contrato');
        }
        
    }

    public function deleteServiceHasTax($id, $data = null){
        foreach($data as $d):
            $where = array('service_idservice' => $id, 'tax_idtax' => $d);
            $this->delete('service_has_tax', $where);
        endforeach;
    }

    private function isComposing($id){
        $where = array('service_idservice' => $id);
        $composition = $this->ready('composition', $where);
        return ($composition->num_rows() != 0) ? true : false;
    }
}