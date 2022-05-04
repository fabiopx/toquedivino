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

        $inscribe = $this->inscribe->readInscribe(['idinscribe' => $inscribe]);
        $budget = $this->budget->readBudget(['inscribe_idinscribe' => $inscribe]);
        $resp = ['msg' => $budget->row()->code, 'icon' => 'success'];

        // $data = [
        //     'code' => code_generate(),
        //     'date' => date('Y-m-d'),
        //     'value' => $budget->row()->value,
        //     'discount' => $budget->row()->discount,
        //     'addition' => $budget->row()->addition,
        //     'down_payment' => $budget->row()->down_payment,
        //     'down_payment_date' => $budget->row()->down_payment_date,
        //     'inscribe_idinscribe' => $inscribe
        // ];
        // $createdAgreement = $this->agreement->createAgreement($data);
        // if($createdAgreement){
        //     $idAgreement = $this->db->insert_id();
        //     $engaged = $this->engaged->readEngaged(['inscribe_idinscribe' => $inscribe]);
        //     if($engaged->num_rows() != 0){
        //         $EHSGroom = $this->engaged->readEngagedHasSignature(['engaged' => 1, 'engaged_idengaged' => $engaged->row()->idengaged]);
        //         $EHSBride = $this->engaged->readEngagedHasSignature(['engaged' => 2, 'engaged_idengaged' => $engaged->row()->idengaged]);
        //         $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $EHSGroom->row()->signature_idsignature, 'inscribe_idinscribe' => $inscribe]);
        //         $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $EHSBride->row()->signature_idsignature, 'inscribe_idinscribe' => $inscribe]);
        //     } else{
        //         $dataSignature = [
        //             'name' => $inscribe->row()->accountable,
        //             'type' => 1,
        //             'font' => 'gf_fuggles',
        //             'status' => 1,
        //             'inuse' => 0,
        //             'account_idaccount' => $inscribe->row()->idaccount
        //         ];
        //         $this->signature->createSignature($dataSignature);
        //         $idSignature = $this->db->insert_id();
        //         $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $idSignature, 'inscribe_idinscribe' => $inscribe]);
        //     }
        //     $resp = ['msg' => 'Contrato gerado com sucesso', 'icon' => 'success'];
        // } else{
        //     $this->db->debud_db = false;
        //     $error = $this->db->error();
        //     $resp = ['msg' => $error['message'], 'icon' => 'error'];
        // }
        echo json_encode($resp);
    }

    public function getCustomer($inscribe){
        $this->load->model('agreement');
        $where = ['inscribe_idinscribe' => $inscribe];
        $resp = $this->agreement->readAgreement($where);
        echo json_encode($resp->row());
    }

    public function updateCustomer($inscribe){
        //
    }

    public function deleteCustomer($inscribe){
        //
    }
}