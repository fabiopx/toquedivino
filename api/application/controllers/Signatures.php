<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Signatures extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Signature');
    }

    public function create(){
        $this->load->model('signature');
        echo json_encode($this->signature->createSignature());
    }

    public function get(){
        $this->load->model('signature');
        $signature = $this->signature->readSignature();
        $resp = [];
        foreach($signature->result() as $sign):
            $sign->status = ($sign->status == 0) ? false : true;
            $resp[] = $sign;

        endforeach;
        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('signature');
        echo json_encode($this->signature->updateSignature($id));
    }

    public function delete($id){
        $this->load->model('signature');
        echo json_encode($this->signature->deleteSignature($id));
    }

    public function verifySignCustomers($id){
        $this->load->model('signature');
        $this->load->model('agreement');
        $this->load->model('account');

        $where = array('account_idaccount' => $id);
        $signature = $this->signature->readSignature($where);
        if($signature->num_rows() != 0){
            $ahs = $this->agreement->readAgreementHasSignature(['signature_idsignature' => $signature->row()->idsignature]);
            $resp = ($ahs->row()->sign == 1) ? true : false;
        } else{
            $resp = false;
        }
        echo json_encode($resp);
    }

    public function getSignaturesContract($idInscribe){
        $this->load->model('signature');
        $this->load->model('agreement');
        $this->load->model('account');

        $where = ['inscribe_idinscribe' => $idInscribe];
        $ahs = $this->agreement->readAgreementHasSignature($where);
        $resp = [];

        foreach($ahs->result() as $s):
            $signature = $this->signature->readSignature(['idsignature' => $s->signature_idsignature]);
            $signature = $signature->row();
            $signature->sign = ($s->sign == 0) ? false : true;
            $signature->date = $s->date;
            $resp[] = $signature;
        endforeach;

        echo json_encode($resp);
    }

    public function signWithPassword(){
        $this->load->model('account');
        $this->load->model('signature');

        $resp = $this->account->signWithPassword();

        if($resp){
            $this->signature->markAsSigned($_POST['idsignature']);
            $response = ['msg' => 'Contrato assinado com sucesso', 'icon' => 'success'];
        } else{
            $response = ['msg' => 'Senha não corresponde', 'icon' => 'error'];
        }

        echo json_encode($response);
    }
}