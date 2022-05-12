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

        echo json_encode($resp);
    }

    public function get($inscribe = null){
        $this->load->model('budget');
        $where = is_null($inscribe) ? $inscribe : ['inscribe_idinscribe' => $inscribe];
        $budget = $this->budget->readBudget($where);
        $resp = empty($budget->result()) ? null : $budget->result();
        echo json_encode($resp);

    }

    public function getById($id){
        $this->load->model('budget');
        $where = ['idbudget' => $id];
        $budget = $this->budget->readBudget($where);
        $resp = ($budget->num_rows() == 0) ? null : $budget->row();
        echo json_encode($resp);
    }

    public function isBudget($id){
        $this->load->model('budget');
        $where = array('inscribe_idinscribe' => $id);
        $budget = $this->budget->readBudget($where);
        echo json_encode(!empty($budget->result()));
    }

    public function verifyBudgetCancel($id){
        $this->load->model('budget');
        $where = array('inscribe_idinscribe' => $id, 'status !=' => 4);
        $budget = $this->budget->readBudget($where);
        $resp = ($budget->num_rows() == 0) ? true : false;

        echo json_encode($resp);
    }

    public function verifyBudgetExpires($inscribe){
        $this->load->model('budget');
        $this->load->model('agreement');
        $budget = $this->budget->readBudget(['inscribe_idinscribe' => $inscribe]);
        $agreement = $this->agreement->readAgreement(['inscribe_idinscribe' => $inscribe]);
        $dateNow = date('Y-m-d');
        foreach($budget->result() as $b):
            $dateBudget = $b->expires_in;
            if($b->status == 0 || $b->status == 1){
                (strtotime($dateNow) > strtotime($dateBudget)) ? $this->budget->updateBudget(['idbudget' => $b->idbudget], ['status' => 3]) : exit;
            } elseif($b->status == 2 && $agreement->num_rows() == 0){
                (strtotime($dateNow) > strtotime($dateBudget)) ? $this->budget->updateBudget(['idbudget' => $b->idbudget], ['status' => 3]) : exit;
            }
        endforeach;
    }

    public function cancel($id){
        $this->load->model('budget');
        $where = array('idbudget' => $id);
        $data = array('status' => 4);
        if($this->budget->updateBudget($where, $data)){
            $resp = array('msg' => 'Orçamento cancelado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Orçamento não pode ser cancelado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }
}