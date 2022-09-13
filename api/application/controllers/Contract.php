<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Contract extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Contract');
    }

    public function create(){
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('graduation_committe');
        $this->load->model('event');
        $this->load->model('signature');
        $this->load->model('inscribe');
        $this->load->model('tax');
        

        $idInscribe = $_POST['idinscribe'];

        $inscribe = $this->inscribe->readInscribe(array('idinscribe' => $idInscribe));

        $idAccount = $inscribe->row()->account_idaccount;

        $tax = json_decode($_POST['variant_tax']);
        foreach($tax as $t):
            if($t->type == 2){
                $dataVariantTax = array(
                    'value' => $t->vValue,
                    'tax_idtax' => $t->idtax,
                    'inscribe_idinscribe' => $idInscribe
                );
                $this->tax->createVariantTax($dataVariantTax);
            }
        endforeach;
        $this->agreement->createAgreement();

        $idAgreement = $this->db->insert_id();
        
        $dataSignature = array(
            'name' => $_POST['inscribeaccountable'],
            'type' => 1,
            'font' => 'gf-fuggles',
            'status' => 1,
            'inuse' => 1,
            'account_idaccount' => $idAccount
        );
        $this->signature->createSignature($dataSignature);
        $idSignature = $this->db->insert_id();

        $dataAHS = array(
            'agreement_idagreement' => $idAgreement,
            'signature_idsignature' => $idSignature,
            'inscribe_idinscribe' => $idInscribe,
            'sign' => 0
        );
        $this->agreement->createAgreementHasSignature($dataAHS);
        $this->signature->updateInUse($idSignature);

        $whereSignature = array('type !=' => 1, 'status' => 1);
        $defaultSignatures = $this->signature->readSignature($whereSignature);

        foreach($defaultSignatures->result() as $sign):
            $dataDAHS = array(
                'agreement_idagreement' => $idAgreement,
                'signature_idsignature' => $sign->idsignature,
                'inscribe_idinscribe' => $idInscribe,
                'sign' => 0
            );
            $this->agreement->createAgreementHasSignature($dataDAHS);
            $this->signature->updateInUse($sign->idsignature);
        endforeach;

        if($_POST['selectengaged'] == "true"){
            $this->engaged->createEngaged();
        }
    
        if($_POST['selectgraduationcommitte'] == "true"){
            $this->graduation_committe->createCommitte();
        }

        $this->event->createEvent();

        if($this->inscribe->updateStatusInscribe($idInscribe, 2)){
            $resp['msg'] = 'Contrato gerado com sucesso';
            $resp['icon'] = 'success';
        } else{
            $resp['msg'] = 'Problemas em atualizar o status do cadastro';
            $resp['icon'] = 'error';
        }

        echo json_encode($resp);
    }

    public function validate($id){
        $this->load->model('agreement');
        $this->load->model('inscribe');
        $this->load->model('tax');

        $tax = json_decode($_POST['variant_tax']);
        foreach($tax as $t):
            if($t->type == 2){
                $dataVariantTax = array(
                    'value' => $t->vValue,
                    'tax_idtax' => $t->idtax,
                    'inscribe_idinscribe' => $id
                );
                $this->tax->createVariantTax($dataVariantTax);
            }
        endforeach;

        $data = array(
            'value' => $_POST['value'],
            'discount' => $_POST['discount'],
            'addition' => $_POST['addition'],
            'down_payment' => $_POST['downpayment'],
            'down_payment_date' => $_POST['downpaymentdate']
        );

        $this->inscribe->updateStatusInscribe($id, 2);

        $where = array('inscribe_idinscribe' => $id);
        if($this->agreement->updateAgreement($where, $data)){
            $resp = array('msg' => 'Contrato validado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Contrato não pode ser validado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function generateCustomers(){
        $this->load->model('agreement');
        $this->load->model('signature');
        $this->load->model('inscribe');

        $idInscribe = $_POST['idinscribe'];

        $inscribe = $this->inscribe->readInscribe(array('idinscribe' => $idInscribe));

        $idAccount = $inscribe->row()->account_idaccount;

        $this->agreement->createAgreement();

        $idAgreement = $this->db->insert_id();

        $dataSignature = array(
            'name' => $inscribe->row()->accountable,
            'type' => 1,
            'font' => 'gf-fuggles',
            'status' => 1,
            'inuse' => 1,
            'account_idaccount' => $idAccount
        );
        $this->signature->createSignature($dataSignature);
        $idSignature = $this->db->insert_id();

        $dataAHS = array(
            'agreement_idagreement' => $idAgreement,
            'signature_idsignature' => $idSignature,
            'inscribe_idinscribe' => $idInscribe,
            'sign' => 0
        );
        $this->agreement->createAgreementHasSignature($dataAHS);
        $this->signature->updateInUse($idSignature);

        $whereSignature = array('type !=' => 1, 'status' => 1);
        $defaultSignatures = $this->signature->readSignature($whereSignature);

        foreach($defaultSignatures->result() as $sign):
            $dataDAHS = array(
                'agreement_idagreement' => $idAgreement,
                'signature_idsignature' => $sign->idsignature,
                'inscribe_idinscribe' => $idInscribe
            );
            $this->agreement->createAgreementHasSignature($dataDAHS);
            $this->signature->updateInUse($sign->idsignature);
        endforeach;

        if($this->inscribe->updateStatusInscribe($idInscribe, 1)){
            $resp['msg'] = 'Contrato gerado com sucesso';
            $resp['icon'] = 'success';
        } else{
            $resp['msg'] = 'Problemas em atualizar o status do cadastro';
            $resp['icon'] = 'error';
        }

        echo json_encode($resp);
    }

    public function get($status = null){
        $this->load->model('inscribe');
        $this->load->model('account');
        $this->load->model('formation');
        $this->load->model('service');
        $this->load->model('tax');
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('event');
        $this->load->model('graduation_committe');
        $this->load->model('signature');
        $resp = [];
        $where = (is_null($status)) ? ['status >' => '1'] : ['status' => $status];
        $contract = $this->inscribe->readInscribe($where);
        foreach($contract->result() as $c):
            $c->address = json_decode($c->address);
            $account = $this->account->readAccount(array('idaccount' => $c->account_idaccount));
            $c->account = $account->row();
            $composition = $this->inscribe->readComposition($c->idinscribe);
            foreach($composition->result() as $comp):
                $formation = $this->formation->readFormation(array('idformation' => $comp->formation_idformation));
                $c->formation = $formation->row();
                $services = $this->service->readService(array('idservice' => $comp->service_idservice));
                $service = $services->row();
                // foreach($services->result() as $service):
                    $service->status = ($service->status == 1) ? true : false;
                    $serviceHasTax = $this->service->readServiceHasTax($service->idservice);
                    if($serviceHasTax->num_rows() != 0){
                        foreach($serviceHasTax->result() as $sht):
                            $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                            $data[] = $tax->row();
                        endforeach;
                        $service->taxas = $data;
                        $data = array();
                    } else{
                        $service->taxas = null;
                    }
                    // $serv[] = $service;
                // endforeach;
                $c->service = $service;
            endforeach;
            $engaged = $this->engaged->readEngaged(array('inscribe_idinscribe' => $c->idinscribe));
            $c->engaged = ($engaged->num_rows() != 0) ? $engaged->row() : null;
            $committe = $this->graduation_committe->readCommitte(array('inscribe_idinscribe' => $c->idinscribe));
            if($committe->num_rows() != 0){
                $committe = $committe->row();
                $committe->committe_name = json_decode($committe->committe_name);
                $c->graduationcommitte = $committe;
            } else{
                $c->graduationcommitte = null;
            }
            
            $event = $this->event->readEvent(array('inscribe_idinscribe' => $c->idinscribe));
            $event = $event->row();
            $event->address = json_decode($event->address);
            $c->event = $event;
            $agreement = $this->agreement->readAgreement(array('inscribe_idinscribe' => $c->idinscribe));
            $c->agreement = $agreement->row();
            if($agreement->num_rows() != 0){
                $total = $agreement->row()->value - $agreement->row()->discount + $agreement->row()->addition;
                $c->agreement->totalValue = (string) $total;

                $whereAHS = array(
                    'inscribe_idinscribe' => $c->idinscribe,
                    'agreement_idagreement' => $c->agreement->idagreement
                );
                $signatures = array();
                
                $agreementHasSignature = $this->agreement->readAgreementHasSignature($whereAHS);
                foreach($agreementHasSignature->result() as $a):
                    $signature = $this->signature->readSignature(array('idsignature' => $a->signature_idsignature));
                    if($signature->row()->type == 1){
                        $c->agreement->sign = $a->sign;
                    }
                    array_push($signatures, 
                        array(
                            'name' => $signature->row()->name, 
                            'type' => $signature->row()->type, 
                            'font' => $signature->row()->font, 
                            'sign' => $a->sign, 
                            'idaccount' => $signature->row()->account_idaccount
                            )
                    );
                endforeach;
                $c->signatures = $signatures;
            }
            $resp[] = $c;
        endforeach;
        echo json_encode($resp);
    }

    public function getAgreement($id){
        $this->load->model('agreement');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->agreement->readAgreement($where);
        echo json_encode($resp->row());
    }

    public function cancel($id){
        $this->load->model('inscribe');
        $resp = ($this->inscribe->updateStatusInscribe($id,3)) ? array('msg' => 'Contrato cancelado', 'icon' => 'success') : array('msg' => 'Contrato não pode ser cancelado', 'icon' => 'error');
        echo json_encode($resp);
    }

    public function undo($id){
        $this->load->model('inscribe');
        $resp = ($this->inscribe->updateStatusInscribe($id, 2)) ? array('msg' => 'Contrato restaurado', 'icon' => 'success') : array('msg' => 'Contrato não pode ser restaurado', 'icon' => 'error');
        echo json_encode($resp);
    }

    public function remove($id){
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('graduation_committe');
        $this->load->model('event');
        $this->load->model('signature');
        $this->load->model('inscribe');
        $this->load->model('tax');

        $signatureID = array();
        $agreementID = array();

        $where = array(
            'inscribe_idinscribe' => $id
        );
        $agreementHasSignature = $this->agreement->readAgreementHasSignature($where);

        foreach($agreementHasSignature->result() as $ahs):
            $signatureID[] = $ahs->signature_idsignature;
            if(!in_array($ahs->agreement_idagreement, $agreementID)){
                $agreementID[] = $ahs->agreement_idagreement;
            } 
        endforeach;

        $this->agreement->deleteAgreementHasSignature(array('inscribe_idinscribe' => $id));

        foreach($signatureID as $sign):
            $signature = $this->signature->readSignature(array('idsignature' => $sign));
            $signature = $signature->row();
            if($signature->type == 1){
                $this->signature->deleteContractorSignature($signature->idsignature);
            } else{
                $this->signature->verifyInUse($signature->idsignature);
            }
        endforeach;

        foreach($agreementID as $agree):
            $this->agreement->deleteAgreement(array('idagreement' => $agree));
        endforeach;

        $whereEvent = array('inscribe_idinscribe' => $id);
        $this->event->deleteEvent($whereEvent);

        $whereVariantTax = array('inscribe_idinscribe' => $id);
        $this->tax->deleteVariantTax($whereVariantTax);

        $whereEngaged = array('inscribe_idinscribe' => $id);
        $engaged = $this->engaged->readEngaged($whereEngaged);
        if($engaged->num_rows() != 0){
            $this->engaged->deleteEngaged($whereEngaged);
        }

        $whereGraduationCommitte = array('inscribe_idinscribe' => $id);
        $graduationCommitte = $this->graduation_committe->readCommitte($whereGraduationCommitte);
        if($graduationCommitte->num_rows() != 0){
            $this->graduation_committe->deleteCommitte($whereGraduationCommitte);
        }

       if($this->inscribe->updateStatusInscribe($id, 0)){
           $resp = array('msg' => 'Contrato removido com sucesso. A inscrição foi mantida.', 'icon' => 'success');
       } else{
           $resp = array('msg' => 'Contrato não pode ser removido.', 'icon' => 'error');
       }

        echo json_encode($resp);
    }

    public function delete($id){
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('graduation_committe');
        $this->load->model('event');
        $this->load->model('signature');
        $this->load->model('inscribe');
        $this->load->model('tax');
        $this->load->model('account');

        $signatureID = array();
        $agreementID = array();

        $where = array(
            'inscribe_idinscribe' => $id
        );
        $agreementHasSignature = $this->agreement->readAgreementHasSignature($where);

        foreach($agreementHasSignature->result() as $ahs):
            $signatureID[] = $ahs->signature_idsignature;
            if(!in_array($ahs->agreement_idagreement, $agreementID)){
                $agreementID[] = $ahs->agreement_idagreement;
            } 
        endforeach;

        $this->agreement->deleteAgreementHasSignature(array('inscribe_idinscribe' => $id));

        foreach($signatureID as $sign):
            $signature = $this->signature->readSignature(array('idsignature' => $sign));
            $signature = $signature->row();
            if($signature->type == 1){
                $this->signature->deleteContractorSignature($signature->idsignature);
            } else{
                $this->signature->verifyInUse($signature->idsignature);
            }
        endforeach;

        foreach($agreementID as $agree):
            $this->agreement->deleteAgreement(array('idagreement' => $agree));
        endforeach;

        $whereEvent = array('inscribe_idinscribe' => $id);
        $this->event->deleteEvent($whereEvent);

        $whereVariantTax = array('inscribe_idinscribe' => $id);
        $this->tax->deleteVariantTax($whereVariantTax);

        $whereEngaged = array('inscribe_idinscribe' => $id);
        $engaged = $this->engaged->readEngaged($whereEngaged);
        if($engaged->num_rows() != 0){
            $this->engaged->deleteEngaged($whereEngaged);
        }

        $whereGraduationCommitte = array('inscribe_idinscribe' => $id);
        $graduationCommitte = $this->graduation_committe->readCommitte($whereGraduationCommitte);
        if($graduationCommitte->num_rows() != 0){
            $this->graduation_committe->deleteCommitte($whereGraduationCommitte);
        }

        $idAccount = $this->inscribe->returnAccountIdByInscribeId($id);

        $whereComposition = array('inscribe_idinscribe' => $id);
        $this->inscribe->deleteComposition($whereComposition);        

        $whereInscribe = array('idinscribe' => $id);
        $respInscribe = $this->inscribe->deleteInscribe($whereInscribe);
        $this->account->deleteAccountByID($idAccount);
        if($respInscribe['icon'] == 'success'){
            $resp = array('msg' => 'Contrato excluído com sucesso', 'icon' => 'success');
        } else if($respInscribe['icon'] == 'error'){
            $resp = array('msg' => 'Contrato não pode ser excluído', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

}