<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Agreement extends Crud{
    public $code;
    public $date;
    public $value;
    public $discount;
    public $addition;
    public $down_payment;
    public $down_payment_date;
    public $inscribe_idinscribe;

    public function createAgreement(){
        $this->code = $_POST['codecontract'];
        $this->date = $_POST['date'];
        $this->value = $_POST['value'];
        $this->discount = $_POST['discount'];
        $this->addition = $_POST['addition'];
        $this->down_payment = $_POST['downpayment'];
        $this->down_payment_date = $_POST['downpaymentdate'];
        $this->inscribe_idinscribe = $_POST['idinscribe'];
        $data = $this;

        return $this->create('agreement', $data);
        
    }

    public function createAgreementHasSignature($data){
        return $this->create('agreement_has_signature', $data);
    }

    public function readAgreement($where = null){
        if(!is_null($where)){
            return $this->ready('agreement', $where);
        } else{
            return $this->ready('agreement');
        }
    }

    public function readAgreementHasSignature($where = null){
        if(is_null($where)){
            return $this->ready('agreement_has_signature');
        } else{
            return $this->ready('agreement_has_signature', $where);
        }
    }

    public function updateAgreement($where, $data){
        return $this->update('agreement', $where, $data);
    }

    public function deleteAgreement($where){
        return $this->delete('agreement', $where);
    }

    public function deleteAgreementHasSignature($where){
        return $this->delete('agreement_has_signature', $where);
    }
}