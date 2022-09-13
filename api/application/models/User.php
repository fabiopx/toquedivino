<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class User extends Crud{
    public $name;
    public $phone;
    public $mobile;

    public function createUser(){
        $post = $this->input->post();
        $this->name = $post['name'];
        $this->phone = $post['phone'];
        $this->mobile = $post['mobile'];
        $data = $this;

        if($this->create('user', $data)){
            return $this->response('success', 'Usuário cirado com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function readUser($where = null){
        if(!is_null($where)){
            return $this->ready('user', $where);
        } else{
            return $this->ready('user');
        }
    }

    public function updateUser($where){
        $post = $this->input->post();
        $this->name = $post['name'];
        $this->phone = $post['phone'];
        $this->mobile = $post['mobile'];
        $data = $this;

        if($this->update('user', $where, $data)){
            return $this->response('success', 'Usuário editado com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }

    }

    public function deleteUser($where){
        $response = $this->delete('user', $where);
        if($response !== false){
            return  $this->response('success', 'Conta excluída com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }
}