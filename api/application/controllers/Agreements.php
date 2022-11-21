<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Agreements extends CI_controller
{
    public function index()
    {
        echo json_encode('API :: Toque Divino :: Agreements');
    }

    public function createCustomer($idInscribe)
    {
        $this->load->model('agreement');
        $this->load->model('budget');
        $this->load->model('engaged');
        $this->load->model('signature');
        $this->load->model('inscribe');

        $inscribe = $this->inscribe->readInscribe(['idinscribe' => $idInscribe]);
        $budget = $this->budget->readBudget(['inscribe_idinscribe' => $idInscribe, 'status' => 1]);

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
        if ($createdAgreement) {
            $idAgreement = $this->db->insert_id();
            $this->budget->updateBudget(['idbudget' => $budget->row()->idbudget], ['status' => 2]);
            $this->inscribe->updateStatusInscribe($idInscribe, 2);
            $agreementsManagers = $this->agreement->readAgreementsManagers();
            foreach ($agreementsManagers->result() as $am) :
                $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $am->signature_idsignature, 'inscribe_idinscribe' => $idInscribe]);
            endforeach;
            $engaged = $this->engaged->readEngaged(['inscribe_idinscribe' => $idInscribe]);
            if ($engaged->num_rows() != 0) {
                if ($engaged->row()->groom_responsible_for == 0 && $engaged->row()->bride_responsible_for == 0) {
                    $dataSignature = [
                        'name' => $inscribe->row()->accountable,
                        'type' => 1,
                        'font' => 'gf_fuggles',
                        'status' => 1,
                        'inuse' => 0,
                        'account_idaccount' => $inscribe->row()->account_idaccount
                    ];
                    $this->signature->createSignature($dataSignature);
                    $idSignature = $this->db->insert_id();
                    $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $idSignature, 'inscribe_idinscribe' => $idInscribe]);
                } else {
                    $EHSGroom = $this->engaged->readEngagedHasSignature(['engaged' => 1, 'engaged_idengaged' => $engaged->row()->idengaged]);
                    $EHSBride = $this->engaged->readEngagedHasSignature(['engaged' => 2, 'engaged_idengaged' => $engaged->row()->idengaged]);
                    if ($EHSGroom->num_rows() != 0) {
                        $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $EHSGroom->row()->signature_idsignature, 'inscribe_idinscribe' => $idInscribe]);
                    }
                    if ($EHSBride->num_rows() != 0) {
                        $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $EHSBride->row()->signature_idsignature, 'inscribe_idinscribe' => $idInscribe]);
                    }
                }
            } else {
                $dataSignature = [
                    'name' => $inscribe->row()->accountable,
                    'type' => 1,
                    'font' => 'gf_fuggles',
                    'status' => 1,
                    'inuse' => 0,
                    'account_idaccount' => $inscribe->row()->account_idaccount
                ];
                $this->signature->createSignature($dataSignature);
                $idSignature = $this->db->insert_id();
                $this->agreement->createAgreementHasSignature(['agreement_idagreement' => $idAgreement, 'signature_idsignature' => $idSignature, 'inscribe_idinscribe' => $idInscribe]);
            }
            $resp = ['msg' => 'Contrato gerado com sucesso', 'icon' => 'success'];
        } else {
            $this->db->debud_db = false;
            $error = $this->db->error();
            $resp = ['msg' => $error['message'], 'icon' => 'error'];
        }
        echo json_encode($resp);
    }

    public function getCustomer($inscribe)
    {
        $this->load->model('agreement');
        $where = ['inscribe_idinscribe' => $inscribe];
        $agreement = $this->agreement->readAgreement($where);
        if ($agreement->num_rows() != 0) {
            $agreement = $agreement->row();
            $agreement->value_total = $agreement->value - $agreement->discount + $agreement->addition;
            $resp = $agreement;
        } else {
            $resp = null;
        }

        echo json_encode($resp);
    }

    public function updateCustomer($inscribe)
    {
        //
    }

    public function deleteCustomer($inscribe)
    {
        //
    }
}
