<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Committe extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Committe Graduation');
    }

    public function getCustomers($id){
        $this->load->model('graduation_committe');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->graduation_committe->readCommitte($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->committe_name = json_decode($resp->committe_name);
        } else{
            $resp = $resp->row();
        }
        
        echo json_encode($resp);
    }

    public function createCustomers(){
        $this->load->model('graduation_committe');
        if($this->graduation_committe->createCommitte()){
            $resp = array('msg' => 'Comissão de formatura cadastrada com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Comissão de formatura não pode ser cadastrada', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function updateCustomers($id){
        $this->load->model('graduation_committe');
        $where = array('idgraduation_committe' => $id);
        if($this->graduation_committe->updateCommitte($where)){
            $resp = array('msg' => 'Comissão de formatura editada com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Comissão de formatura não pode ser editada', 'icon' => 'error');
        }
        echo json_encode($resp);
    }
}