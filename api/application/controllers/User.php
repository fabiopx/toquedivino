<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class User extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: User');
    }

    public function create(){
        $this->load->model('account');
        echo json_encode($this->account->createAccount());
    }
    public function get($id = null){
        $this->load->model('account');
        $resp = array();
        $where = (!is_null($id)) ? array('idaccount' => $id) : $id;
        $users = $this->account->readAccount($where);
        if(is_null($id)){
            foreach($users->result() as $user):
                $user->status = ($user->status == 1) ? true : false;
                $resp[] = $user;
            endforeach;
        } else{
            $users = $users->row();
            $users->status = ($users->status == 1) ? true : false;
            $resp = $users;
        }
        
        echo json_encode($resp);
    }

    public function verifyEmail(){
        $this->load->model('account');
        $account = $this->account->readAccount(['email' => $this->input->post('email')]);
        $resp = ($account->num_rows() != 0) ? true : false;
        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->updateAccount($where));
    }

    public function updateCustomer($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->updateAccountCustomers($where));
    }

    public function delete($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->deleteAccount($where));
    }

    public function login(){
        $this->load->model('account');
        echo json_encode($this->account->login());
    }

    public function loginCustomer(){
        $this->load->model('account');
        echo json_encode($this->account->loginCustomer());
    }
}