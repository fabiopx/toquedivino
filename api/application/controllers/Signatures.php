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
        echo json_encode($signature->result());
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
            $resp[] = $signature->row();
        endforeach;

        echo json_encode($resp);
    }

    public function signWithPassword(){
        $this->load->model('account');
        $this->load->model('signature');

        $resp = $this->account->signWithPassword();

        if($resp){
            $where = array('account_idaccount' => $_POST['idaccount']);
            $signature = $this->signature->readSignature($where);
            $this->signature->markAsSigned($signature->row()->idsignature);
        }

        echo json_encode($resp);
    }
}