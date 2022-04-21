<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Account extends Crud{
    public $name;
    public $email;
    public $password;
    public $status;
    public $phone;
    public $photo;
    public $pin;
    public $access;

    public function createAccount($data = null){
        if(is_null($data)){
            $this->name = $_POST['name'];
            $this->email = $_POST['email'];
            $this->password = $_POST['password'];
            $this->status = $_POST['status'];
            $this->phone = $_POST['phone'];
            $this->photo = $_POST['photo'];
            $this->pin = code_generate();
            $this->access = $_POST['access'];
            $data = $this;
        }
        
        if($this->create('account', $data)){
            return $this->response('success', 'Conta criada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
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

    public function updateAccount($where){
        $this->name = $_POST['name'];
        $this->email = $_POST['email'];
        $this->password = $_POST['password'];
        $this->status = $_POST['status'];
        $this->phone = $_POST['phone'];
        $this->photo = $_POST['photo'];
        $this->pin = code_generate();
        $this->access = $_POST['access'];
        $data = $this;

        if($this->update('account', $where, $data)){
            return $this->response('success', 'Conta editada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function updateAccountCustomers($where){
        $data = array(
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'photo' => $_POST['photo'],
        );

        if($this->update('account', $where, $data)){
            return $this->response('success', 'Conta editada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteAccount($where){
        $response = $this->delete('account', $where);
        if($response !== false){
            return  $this->response('success', 'Conta excluída com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
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

        $login = $this->ready('account', $where);
        $resp = array();

        if($login->num_rows() != 0){
            if($login->row()->access == 0){
                $resp['userNow']['logged'] = true;
                $resp['userNow']['login'] = false;
                $resp['userNow']['name'] = $login->row()->name;
                $resp['userNow']['id'] = $login->row()->idaccount;
                $resp['userNow']['photo'] = ($login->row()->photo) ?: 'assets/profile.svg';
                $resp['alert']['status'] = false;
                $resp['alert']['msg'] = '';
            } else{
                $resp['userNow']['logged'] = false;
                $resp['userNow']['login'] = true;
                $resp['userNow']['name'] = 'Usuário';
                $resp['userNow']['id'] = '';
                $resp['userNow']['photo'] = 'assets/profile.svg';
                $resp['alert']['status'] = true;
                $resp['alert']['msg'] = 'Usuário não autorizado.';
            }
            
        } else{
            $resp['userNow']['logged'] = false;
            $resp['userNow']['login'] = true;
            $resp['userNow']['name'] = '';
            $resp['userNow']['id'] = '';
            $resp['userNow']['photo'] = 'assets/profile.svg';
            $resp['alert']['status'] = true;
            $resp['alert']['msg'] = 'Usuário não encontrado. Verifique login e senha';
        }

        return $resp;
    }

    public function loginCustomer(){
        $where = array(
            'email' => $_POST['email'],
            'password' => $_POST['password']
        );

        $login = $this->ready('account', $where);
        $resp = array();

        if($login->num_rows() != 0){
            if($login->row()->access == 2){
                $resp['userNow']['logged'] = true;
                $resp['userNow']['login'] = false;
                $resp['userNow']['name'] = $login->row()->name;
                $resp['userNow']['id'] = $login->row()->idaccount;
                $resp['userNow']['photo'] = ($login->row()->photo) ?: 'assets/profile.svg';
                $resp['alert']['status'] = false;
                $resp['alert']['msg'] = '';
            } else{
                $resp['userNow']['logged'] = false;
                $resp['userNow']['login'] = true;
                $resp['userNow']['name'] = 'Usuário';
                $resp['userNow']['id'] = '';
                $resp['userNow']['photo'] = 'assets/profile.svg';
                $resp['alert']['status'] = true;
                $resp['alert']['msg'] = 'Usuário não autorizado.';
            }
            
        } else{
            $resp['userNow']['logged'] = false;
            $resp['userNow']['login'] = true;
            $resp['userNow']['name'] = '';
            $resp['userNow']['id'] = '';
            $resp['userNow']['photo'] = 'assets/profile.svg';
            $resp['alert']['status'] = true;
            $resp['alert']['msg'] = 'Usuário não encontrado. Verifique login e senha';
        }

        return $resp;
    }

    public function signWithPassword(){
        $where = array('password' => $_POST['password'], 'idaccount' => $_POST['idaccount']);
        $sign = $this->ready('account', $where);

        return ($sign->num_rows() != 0) ? true : false;
    }

}