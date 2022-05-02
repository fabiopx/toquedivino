<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Agreements extends CI_controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Agreements');
    }

    public function createCustomer($inscribe){
        $this->load->model('agreement');
        $this->load->model('budget');
        $this->load->model('engaged');
        $this->load->model('signature');
        $this->load->model('inscribe');

        $budget = $this->budget->readBudget(['inscribe_idinscribe' => $inscribe]);
        $engaged = $this->engaged->readEngaged(['inscribe_idinscribe' => $inscribe]);
        if($engaged->num_rows() != 0){
            $EHSGroom = $this->engaged->readEngagedHasSignature(['engaged' => 1, 'engaged_idengaged' => $engaged->row()->idengaged]);
            $EHSBride = $this->engaged->readEngagedHasSignature(['engaged' => 2, 'engaged_idengaged' => $engaged->row()->idengaged]);
            $signatureGroom = $this->signature->readSignature(['idsignature' => $EHSGroom->row()->signature_idsignature]);
            $signatureBride = $this->signature->readSignature(['idsignature' => $EHSBride->row()->signature_idsignature]);
        } else{
            $signature = "";
        }
    }

    public function getCustomer($inscribe){
        $this->load->model('agreement');
        $where = array('inscribe_idinscribe' => $inscribe);
        $resp = $this->agreement->readAgreement($where);
        echo json_encode($resp->row());
    }

    public function updateCustomer($inscribe){
        //
    }
}