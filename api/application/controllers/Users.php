<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Users extends CI_Controller{

    public function index(){
        echo json_encode('API :: Toque Divino :: User');
    }

    public function create(){
        $this->load->model('account');
        $this->load->model('user');
        $resp = null;
        $post = $this->input->post();

        $account = $this->account->createAccount();
        if($account){
            $idaccount = $this->db->insert_id();

            $data = [
                'name' => $post['name'],
                'phone' => $post['phone'],
                'mobile' => $post['mobile'],
                'account_idaccount' => $idaccount
            ];

            $user = $this->user->createUser($data);

            if($user){
                $resp = $this->user->response('success', 'Usuário cadastrado com sucesso');
            } else{
                $this->db->db_debug = false;
                $error = $this->db->error();
                $resp = $this->user->response('error', $error['message']);
            }
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            $resp = $this->user->response('error', $error['message']);
        }

        echo json_encode($resp);
    }
    public function get(){
        $this->load->model('account');
        $this->load->model('user');
        $resp = [];
        $users = $this->user->readUser();

        foreach($users->result() as $user):
            // $user->status = ($user->status == 1) ? true : false;
            $account = $this->account->readAccount(['idaccount' => $user->account_idaccount]);
            $user->account = $account->row();
            unset($user->account_idaccount);
            $resp[] = $user;
        endforeach;
        
        
        echo json_encode($resp);
    }

    public function verifyEmail(){
        $this->load->model('account');
        $account = $this->account->readAccount(['email' => $this->input->post('email')]);
        $resp = ($account->num_rows() != 0) ? true : false;
        echo json_encode($resp);
    }

    public function update($account){
        $this->load->model('account');
        $this->load->model('user');
        $where = array('idaccount' => $account);
        $respAccount = $this->account->updateAccount($where);

        $post = $this->input->post();
        $data = [
            'name' => $post['name'],
            'phone' => $post['phone'],
            'mobile' => $post['mobile'],
            'account_idaccount' => $account
        ];
        $respUser = $this->user->updateUser(['account_idaccount' => $account], $data);

        if($respAccount && $respUser){
            $resp = ['msg' => 'Usuário editado com sucesso', 'icon' => 'success'];
        } else{
            $resp = ['msg' => 'Usuário não pode ser editado', 'icon' => 'error'];
        }

        echo json_encode($resp);
    }

    public function updateCustomer($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->updateAccountCustomers($where));
    }

    public function delete($id){
        $this->load->model('account');
        $this->load->model('user');
        $user = $this->user->deleteUser(['account_idaccount' => $id]);
        $account = $this->account->deleteAccount(['idaccount' => $id]);
        if($user && $account){
            $resp = ['msg' => 'Usuário excluído com sucesso', 'icon' => 'success'];
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            $resp = ['msg' => $error['message'], 'icon' => 'error'];
        }
        echo json_encode($resp);
    }

    public function login(){
        $this->load->model('account');
        echo json_encode($this->account->login());
    }

    public function loginCustomer(){
        $this->load->model('account');
        $this->load->model('inscribe');
        
        $login = $this->account->loginCustomer();
        $resp = [];

        if($login->num_rows() != 0){
            if($login->row()->access == 2){
                $user = $this->inscribe->readInscribe(['account_idaccount' => $login->row()->idaccount]);
                $resp['userNow']['logged'] = true;
                $resp['userNow']['login'] = false;
                $resp['userNow']['name'] = $user->row()->accountable;
                $resp['userNow']['id'] = $login->row()->idaccount;
                $resp['alert']['status'] = false;
                $resp['alert']['msg'] = '';
            } else{
                $resp['userNow']['logged'] = false;
                $resp['userNow']['login'] = true;
                $resp['userNow']['name'] = 'Usuário';
                $resp['userNow']['id'] = '';
                $resp['alert']['status'] = true;
                $resp['alert']['msg'] = 'Usuário não autorizado.';
            }
            
        } else{
            $resp['userNow']['logged'] = false;
            $resp['userNow']['login'] = true;
            $resp['userNow']['name'] = '';
            $resp['userNow']['id'] = '';
            $resp['alert']['status'] = true;
            $resp['alert']['msg'] = 'Usuário não encontrado. Verifique login e senha';
        }

        echo json_encode($resp);
    }
}