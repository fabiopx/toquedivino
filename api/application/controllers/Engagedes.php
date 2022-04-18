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
        if($this->engaged->createEngaged()){
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
        $where = array('idengaged' => $id);
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

    public function createSocialNetworks($id){
        //
    }

    public function getSocialNetworks($id){
        //
    }

    public function UpdateSocialNetworks($id){
        //
    }

    public function deleteSocialNetworks($id){
        //
    }
}