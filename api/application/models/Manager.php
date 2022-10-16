<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'models/Crud.php');

class Manager extends Crud
{
    public $name;
    public $cpf;
    public $office;
    public $signature_idsignature;

    public function createManager($data = null)
    {
        $post = $this->input->post();
        if (is_null($data)) {
            $this->name = $post['name'];
            $this->cpf = $post['cpf'];
            $this->office = $post['office'];
            $this->signature_idsignature = $post['signature_idsignature'];
            $data = $this;
        }

        return $this->create('agreements_managers', $data);
    }

    public function readManagers($where = null){
        if(!is_null($where)){
            return $this->ready('agreements_managers', $where);
        } else{
            return $this->ready('agreements_managers');
        }
    }

    public function updateManager($where)
    {
        $post = $this->input->post();
        $this->name = $post['name'];
        $this->cpf = $post['cpf'];
        $this->office = $post['office'];
        $this->signature_idsignature = $post['signature_idsignature'];
        $data = $this;

        return $this->update('agreements_managers', $where, $data);
    }

    public function deleteManager($where){
        return $this->delete('agreements_managers', $where);
    }
}
