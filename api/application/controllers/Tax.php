<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Tax extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Tax');
    }

    public function create(){
        $this->load->model('tax');
        echo json_encode($this->tax->createTax());
    }

    public function get($id = null){
        $this->load->model('tax');
        $where = (!is_null($id)) ? array('idtax' => $id) : $id;
        $tax = $this->tax->readTax($where);
        $resp = array();
        foreach($tax->result() as $t):
            $t->servicehastax = $this->tax->verifyServiceHasTax($t->idtax);
            array_push($resp, $t);
        endforeach;
        echo json_encode($resp);
    }

    public function getByService($id){
        $this->load->model('service');
        $this->load->model('tax');
        $serviceHasTax = $this->service->readServiceHasTax($id);
        $resp = array();
        if($serviceHasTax->num_rows() != 0){
            foreach($serviceHasTax->result() as $sht):
                $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                foreach($tax->result() as $t):
                    $t->vValue = 0;
                    $resp[] = $t;
                endforeach;
            endforeach;
        } else{
            $resp = null;
        }
        
        echo json_encode($resp);
                
    }

    public function update($id){
        $this->load->model('tax');
        echo json_encode($this->tax->updateTax($id));
    }

    public function delete($id){
        $this->load->model('tax');
        $where = array('idtax' => $id);
        echo json_encode($this->tax->deleteTax($where));
    }
}