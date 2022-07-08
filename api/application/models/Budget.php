<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Budget extends Crud{
    public $code;
    public $date;
    public $value;
    public $discount;
    public $addition;
    public $down_payment;
    public $down_payment_date;
    public $expires_in;
    public $status;
    public $inscribe_idinscribe;

    public function createBudget(){
        $this->code = code_generate();
        $this->date = $this->input->post('date');
        $this->value = $this->input->post('value');
        $this->discount = $this->input->post('discount');
        $this->addition = $this->input->post('addition');
        $this->down_payment = $this->input->post('down_payment');
        $this->down_payment_date = $this->input->post('down_payment_date');
        $this->expires_in = $this->input->post('expires_in');
        $this->status = $this->input->post('status');
        $this->inscribe_idinscribe = $this->input->post('inscribe_idinscribe');
        $data = $this;

        return $this->create('budget', $data);
    }

    public function readBudget($where = null){
        if(is_null($where)){
            return $this->ready('budget');
        } else{
            return $this->ready('budget', $where);
        }
    }

    public function updateBudget($where, $data){
        return $this->update('budget', $where, $data);
    }

    public function deleteBudget($where){
        return $this->delete('budget', $where);
    }
}