<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Engagedes extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Engaged');
    }

    public function createCustomers(){
        $this->load->model('engaged');
        $this->load->model('account');
        $this->load->model('signature');
        
        if($this->engaged->createEngaged()){
            $idEngaged = $this->db->insert_id();
            if($_POST['groom_responsible_for'] == "true"){
                $account = [
                    'email' => $_POST['groom_email'],
                    'password' => password_generate(),
                    'status' => 1,
                    'pin' => code_generate(),
                    'access' => 4
                ];
                $this->account->createAccount($account);
                $idAccount = $this->db->insert_id();
                $signature = [
                    'name' => $_POST['groom_name'],
                    'type' => 1,
                    'font' => 'gf_fuggles',
                    'status' => 1,
                    'inuse' => 0,
                    'account_idaccount' => $idAccount
                ];
    
                $this->signature->createSignature($signature);
                $idSignature = $this->db->insert_id();
                $engagedHasSignature = [
                    'engaged_idengaged' => $idEngaged,
                    'signature_idsignature' => $idSignature,
                    'engaged' => 1
                ];

                $this->engaged->createEngagedHasSignature($engagedHasSignature);
            }
            if($_POST['bride_responsible_for'] == "true"){
                $account = [
                    'email' => $_POST['bride_email'],
                    'password' => password_generate(),
                    'status' => 1,
                    'pin' => code_generate(),
                    'access' => 4
                ];
                $this->account->createAccount($account);
                $idAccount = $this->db->insert_id();
                $signature = [
                    'name' => $_POST['bride_name'],
                    'type' => 1,
                    'font' => 'gf_fuggles',
                    'status' => 1,
                    'inuse' => 0,
                    'account_idaccount' => $idAccount
                ];
    
                $this->signature->createSignature($signature);
                $idSignature = $this->db->insert_id();
                $engagedHasSignature = [
                    'engaged_idengaged' => $idEngaged,
                    'signature_idsignature' => $idSignature,
                    'engaged' => 2
                ];

                $this->engaged->createEngagedHasSignature($engagedHasSignature);
            }
            $resp = array('msg' => 'Noivos cadastrados com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Noivos não foram cadastrados', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function getCustomers($id){
        $this->load->model('engaged');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->engaged->readEngaged($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->bride_address = json_decode($resp->bride_address);
            $resp->groom_address = json_decode($resp->groom_address);
            $resp->groom_responsible_for = ($resp->groom_responsible_for == 0) ? false : true;
            $resp->bride_responsible_for = ($resp->bride_responsible_for == 0) ? false: true;
            $resp->selectEngaged = true;
        } else{
            $resp = $resp->row();
        }
        
        echo json_encode($resp);
    }

    public function updateCustomers($id){
        $this->load->model('engaged');
        $this->load->model('signature');
        $this->load->model('account');
        $where = array('idengaged' => $id);

        if($_POST['groom_responsible_for'] == "true"){
            $engagedHasSignature = $this->engaged->readEngagedHasSignature(['engaged' => 1, 'engaged_idengaged' => $id]);
            if($engagedHasSignature->num_rows() == 0){
                $account = [
                    'name' => $_POST['groom_name'],
                    'email' => $_POST['groom_email'],
                    'password' => password_generate(),
                    'status' => 1,
                    'pin' => code_generate(),
                    'access' => 4
                ];
                $this->account->createAccount($account);
                $idAccount = $this->db->insert_id();
                $signature = [
                    'name' => $_POST['groom_name'],
                    'type' => 1,
                    'font' => 'gf_fuggles',
                    'status' => 1,
                    'inuse' => 0,
                    'account_idaccount' => $idAccount
                ];
    
                $this->signature->createSignature($signature);
                $idSignature = $this->db->insert_id();
                $engagedHasSignature = [
                    'engaged_idengaged' => $id,
                    'signature_idsignature' => $idSignature,
                    'engaged' => 1
                ];

                $this->engaged->createEngagedHasSignature($engagedHasSignature);
            }
        } else{
            $engagedHasSignature = $this->engaged->readEngagedHasSignature(['engaged' => 1, 'engaged_idengaged' => $id]);
            if($engagedHasSignature->num_rows() != 0){
                $idSignature = $engagedHasSignature->row()->signature_idsignature;
                $signature = $this->signature->readSignature(['idsignature' => $idSignature]);
                $idAccount = $signature->row()->account_idaccount;
                $this->engaged->deleteEngagedHasSignature(['engaged_idengaged' => $id, 'engaged' => 1]);
                $this->signature->deleteSignature($idSignature);
                $this->account->deleteAccount(['idaccount' => $idAccount]);
            }
        }
        
        if($_POST['bride_responsible_for'] == "true"){
            $engagedHasSignature = $this->engaged->readEngagedHasSignature(['engaged' => 2, 'engaged_idengaged' => $id]);
            if($engagedHasSignature->num_rows() == 0){
                $account = [
                    'name' => $_POST['bride_name'],
                    'email' => $_POST['bride_email'],
                    'password' => password_generate(),
                    'status' => 1,
                    'pin' => code_generate(),
                    'access' => 4
                ];
                $this->account->createAccount($account);
                $idAccount = $this->db->insert_id();
                $signature = [
                    'name' => $_POST['bride_name'],
                    'type' => 1,
                    'font' => 'gf_fuggles',
                    'status' => 1,
                    'inuse' => 0,
                    'account_idaccount' => $idAccount
                ];
    
                $this->signature->createSignature($signature);
                $idSignature = $this->db->insert_id();
                $engagedHasSignature = [
                    'engaged_idengaged' => $id,
                    'signature_idsignature' => $idSignature,
                    'engaged' => 2
                ];

                $this->engaged->createEngagedHasSignature($engagedHasSignature);
            }
            
        } else{
            $engagedHasSignature = $this->engaged->readEngagedHasSignature(['engaged' => 2, 'engaged_idengaged' => $id]);
            if($engagedHasSignature->num_rows() != 0){
                $idSignature = $engagedHasSignature->row()->signature_idsignature;
                $signature = $this->signature->readSignature(['idsignature' => $idSignature]);
                $idAccount = $signature->row()->account_idaccount;
                $this->engaged->deleteEngagedHasSignature(['engaged_idengaged' => $id, 'engaged' => 2]);
                $this->signature->deleteSignature($idSignature);
                $this->account->deleteAccount(['idaccount' => $idAccount]);
            }
        }
        if($this->engaged->updateEngaged($where)){
            $resp = array('msg' => 'Cadastro dos noivos editado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Cadastro dos noivos não pode ser editado', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function createWeddingServices(){
        $this->load->model('engaged');

        if($this->engaged->createWeddingServices()){
            $resp = array('msg' => 'Empresa cadastrada com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Empresa não pode ser cadastrada', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function getWeddingServices($id){
        $this->load->model('engaged');
        $where = array('engaged_idengaged' => $id);
        $resp = $this->engaged->readWeddingServices($where);
        echo json_encode($resp->result());
    }

    public function updateWeddingServices($id){
        $this->load->model('engaged');
        $where = array('idwedding_services' => $id);
        if($this->engaged->updateWeddingServices($where)){
            $resp = array('msg' => "Empresa editada com sucesso", 'icon' => 'success');
        } else{
            $resp = array('msg' => "Empresa não pode ser editada", 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function deleteWeddingService($id){
        $this->load->model('engaged');
        $where = array('idwedding_services' => $id);
        if($this->engaged->deleteWeddingService($where)){
            $resp = array('msg' => "Empresa excluída com sucesso", "icon" => "success");
        } else{
            $resp = array('msg' => 'Empresa não pode ser excluída', "icon" => "error");
        }

        echo json_encode($resp);
    }

    public function createSocialNetworks(){
        $this->load->model('engaged');
        if($this->engaged->createSocialNetworks()){
            $resp = ['msg' => 'Rede social incluída com sucesso!', 'icon' => 'sucess'];
        } else{
            $resp = ['msg' => 'Rede Social não pode ser incluída', 'icon' => 'error']; 
        }
        echo json_encode($resp);
    }

    public function getSocialNetworks($engaged){
        $this->load->model('engaged');
        $where = ['engaged_idengaged' => $engaged];
        $socialNetworks = $this->engaged->readSocialNetWorks($where);
        echo json_encode($socialNetworks->result());
    }

    public function updateSocialNetworks($id){
        $this->load->model('engaged');
        $where = ['id' => $id];
        ($this->engaged->updateSocialNetworks($where))
        ? $resp = ['msg' => 'Rede Social editada com sucesso', 'icon' => 'success']
        : $resp = ['msg' => 'Rede Socialnão pode ser editada', 'icon' => 'error'];
        echo json_encode($resp);
    }

    public function deleteSocialNetworks($id){
        $this->load->model('engaged');
        $where = ['id' => $id];
        if($this->engaged->deleteSocialNetworks($where)){
            $resp = ['msg' => 'Rede Social excluída com sucesso', 'icon' => 'success'];
        } else{
            $resp = ['msg' => 'Rede Social não pode ser excluída', 'icon' => 'error'];
        }
        echo json_encode($resp);
    }
}