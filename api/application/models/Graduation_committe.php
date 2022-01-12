<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Graduation_committe extends Crud{
    public $committe_name;
    public $inscribe_idinscribe;

    public function createCommitte(){
        $this->committe_name = $_POST['committe_name'];
        $this->inscribe_idinscribe = $_POST['inscribe_idinscribe'];
        $data = $this;

        return $this->create('graduation_committe', $data);
    }

    public function readCommitte($where = null){
        if(!is_null($where)){
            return $this->ready('graduation_committe', $where);
        } else{
            return $this->ready('graduation_committe');
        }
    }

    public function updateCommitte($where){
        $this->committe_name = $_POST['committe_name'];
        $this->inscribe_idinscribe = $_POST['inscribe_idinscribe'];
        $data = $this;

        return $this->update('graduation_committe', $where, $data);
    }

    public function deleteCommitte($where){
        return $this->delete('graduation_committe', $where);
    }
}