<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Managers extends CI_Controller{
    public function index(){
        echo json_encode("API :: Toque Divino :: Agreement Manager");
    }

    public function create(){
        $this->load->model('account');
        $this->load->model('signature');
        $this->load->model('manager');

        $post = $this->input->post();

        $dAccount = [
            'email' => $post['email'],
            'password' => password_generate(),
            'status' => 1,
            'pin' => code_generate(),
            'access' => 4
        ];

        $this->account->createAccount($dAccount);
        $idAccount = $this->db->insert_id();

        $dSignature = [
            'name' => $post['name'],
            'type' => 2,
            'font' => 'gf-fuggles',
            'status' => 1,
            'inuse' => 0,
            'account_idaccount' => $idAccount
        ];

        $this->signature->createSignature($dSignature);
        $idSignature = $this->db->insert_id();

        $dManager = [
            'name' => $post['name'],
            'cpf' => $post['cpf'],
            'office' => $post['office'],
            'signature_idsignature' => $idSignature
        ];

        if($this->manager->createManager($dManager)){
            $resp = ['msg' => 'Gestor de contrato criado com sucesso', 'icon' => 'success'];
        } else {
            $resp = ['msg' => 'Gestor de contrato não pode ser criado', 'icon' => 'error'];
        }

        echo json_encode($resp);

    }

    public function get(){
        $this->load->model('manager');
        $this->load->model('signature');
        $this->load->model('account');
        $resp = [];
        $managers = $this->manager->readManagers();

        foreach($managers->result() as $manager):
            $signature = $this->signature->readSignature(['idsignature' => $manager->signature_idsignature]);
            $account = $this->account->readAccount(['idaccount' => $signature->row()->account_idaccount]);
            $manager->email = $account->row()->email;
            $manager->idsignature = $signature->row()->idsignature;
            $manager->idaccount = $account->row()->idaccount;
            $resp[] = $manager;
        endforeach;
        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('manager');
        $this->load->model('account');
        $where = ['id' => $id];
        $manager = $this->manager->updateManager($where);

        if($manager){
            $resp = ['msg' => 'Gestor de contrato editado com sucesso', 'icon' => 'success'];
        } else{
            $resp = ['msg' => 'Não foi possível editar', 'icon' => 'error'];
        }

        echo json_encode($resp);
    }

    public function delete(){
        $this->load->model('manager');
        $this->load->model('account');
        $this->load->model('signature');
        $post = $this->input->post();

        $manager = $this->manager->deleteManager(['id' => $post['idManager']]);
        $signature = $this->signature->deleteSignature(['idsignature' => $post['idSignature']]);
        $account = $this->account->deleteAccount(['idaccount' => $post['idAccount']]);



        if($manager !== false && $signature !== false && $account !== false){
            $resp = ['msg' => 'Gestor de contrato excluído com sucesso', 'icon' => 'success'];
        } else{
            $resp = ['msg' => 'Não foi possível exlcuir gestor de contrato', 'icon' => 'error'];
        }

        echo json_encode($resp);
    }
}