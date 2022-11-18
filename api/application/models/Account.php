<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Account extends Crud{
    public $email;
    public $password;
    public $status;
    public $pin;
    public $access;

    public function createAccount($data = null){
        if(is_null($data)){
            $this->email = $_POST['email'];
            $this->password = $_POST['password'];
            $this->status = $_POST['status'];
            $this->pin = code_generate();
            $this->access = $_POST['access'];
            $data = $this;
        }
        
        return $this->create('account', $data);
    }

    public function readAccount($where = null){
        if(!is_null($where)){
            return $this->ready('account', $where);
        } else{
            return $this->ready('account');
        }
    }

    public function verifyAccountInUse($id){
        $where = array('account_idaccount' => $id);
        $service = $this->ready('service', $where);
        return ($service->num_rows() != 0) ? true : false;
    }

    public function updateAccount($where, $data = null){
        if(is_null($data)){
            $this->email = $_POST['email'];
            $this->password = $_POST['password'];
            $this->status = $_POST['status'];
            $this->pin = code_generate();
            $this->access = $_POST['access'];
            $data = $this;
        }        

        return $this->update('account', $where, $data);
    }

    public function updateAccountCustomers($where){
        $data = array(
            'email' => $_POST['email'],
            'password' => $_POST['password']
        );

        return $this->update('account', $where, $data);
    }

    public function deleteAccount($where){
        return $this->delete('account', $where);
    }

    public function deleteAccountByID($id){
        $where = array('idaccount' => $id);
        return $this->delete('account', $where);
    }

    public function login(){
        $where = array(
            'email' => $_POST['email'],
            'password' => $_POST['password']
        );

        return $this->ready('account', $where);
    }

    public function signWithPassword(){
        $where = array('password' => $_POST['password'], 'idaccount' => $_POST['idaccount']);
        $sign = $this->ready('account', $where);

        return ($sign->num_rows() != 0) ? true : false;
    }

}