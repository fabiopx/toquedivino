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

    public function createCustomer($idInscribe){
        $this->load->model('agreement');
        $this->load->model('budget');
        $this->load->model('engaged');
        $this->load->model('signature');
        $this->load->model('inscribe');

        $where = ['idinscribe' => $idInscribe];
        $inscribe = $this->inscribe->readInscribe($where);
        $whereBudget = ['inscribe_idinscribe' => $idInscribe];
        $budget = $this->budget->readBudget($whereBudget);

        $data = [
            'code' => code_generate(),
            'date' => date('Y-m-d'),
            'value' => $budget->row()->value,
            'discount' => $budget->row()->discount,
            'addition' => $budget->row()->addition,
            'down_payment' => $budget->row()->down_payment,
            'down_payment_date' => $budget->row()->down_payment_date,
            'inscribe_idinscribe' => $idInscribe
        ];
        $createdAgreement = $this->agreement->createAgreement($data);
        if($createdAgreement){
            $idAgreement = $this->db->insert_id();
            $engaged = $this->engaged->readEngaged(['inscribe_idinscribe' => $idInscribe]);
            if($engaged->num_rows() != 0){
                $EHSGroom = $this->engaged->readEngagedHasSignature(['engaged' => 1, 'engaged_idengaged' => $engaged->row()->idengaged]);
                $EHSBride = $this->engaged->readEngagedHasSignature(['engaged' => 2, 'engaged_idengaged' => $engaged->row()->idengaged]);
                $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $EHSGroom->row()->signature_idsignature, 'inscribe_idinscribe' => $idInscribe]);
                $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $EHSBride->row()->signature_idsignature, 'inscribe_idinscribe' => $idInscribe]);
            } else{
                $dataSignature = [
                    'name' => $inscribe->row()->accountable,
                    'type' => 1,
                    'font' => 'gf_fuggles',
                    'status' => 1,
                    'inuse' => 0,
                    'account_idaccount' => $inscribe->row()->idaccount
                ];
                $this->signature->createSignature($dataSignature);
                $idSignature = $this->db->insert_id();
                $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $idSignature, 'inscribe_idinscribe' => $idInscribe]);
            }
            $resp = ['msg' => 'Contrato gerado com sucesso', 'icon' => 'success'];
        } else{
            $this->db->debud_db = false;
            $error = $this->db->error();
            $resp = ['msg' => $error['message'], 'icon' => 'error'];
        }
        echo json_encode($resp);
    }

    public function getCustomer($inscribe){
        $this->load->model('agreement');
        $where = ['inscribe_idinscribe' => $inscribe];
        $agreement = $this->agreement->readAgreement($where);
        $resp = ($agreement->num_rows() == 0) ? null : $agreement->row();
        echo json_encode($resp);
    }

    public function updateCustomer($inscribe){
        //
    }

    public function deleteCustomer($inscribe){
        //
    }
}