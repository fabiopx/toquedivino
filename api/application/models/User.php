<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class User extends Crud{
    public $name;
    public $phone;
    public $mobile;
    public $account_idaccount;

    public function createUser($data = null){
        if(is_null($data)){
            $post = $this->input->post();
            $this->name = $post['name'];
            $this->phone = $post['phone'];
            $this->mobile = $post['mobile'];
            $this->account = $post['account_idaccount'];
            $data = $this;
        }
        

        return $this->create('user', $data);
    }

    public function readUser($where = null){
        if(!is_null($where)){
            return $this->ready('user', $where);
        } else{
            return $this->ready('user');
        }
    }

    public function updateUser($where, $data = null){
        if(is_null($data)){
            $post = $this->input->post();
            $this->name = $post['name'];
            $this->phone = $post['phone'];
            $this->mobile = $post['mobile'];
            $this->account = $post['account_idaccount'];
            $data = $this;
        }  

        return $this->update('user', $where, $data);

    }

    public function deleteUser($where){
        return $this->delete('user', $where);
    }
}