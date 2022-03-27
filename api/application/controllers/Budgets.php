<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Budgets extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Budget');
    }

    public function create(){
        $this->load->model('budget');
        if($this->budget->createBudget()){
            $resp = array('msg' => "Orçamento gerado com sucesso", 'icon' => "success");
        } else{
            $resp = array('msg' => "Não foi possível gerar o orçamento", 'icon' => 'error');
        }
    }

    public function isBudget($id){
        $this->load->model('budget');
        $where = array('inscribe_idinscribe' => $id);
        $budget = $this->budget->readBudget($where);
        return (is_null($budget)) ? false : true;
    }
}