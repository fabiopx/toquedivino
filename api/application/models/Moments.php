<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Moments extends Crud{
    public $name;
    public $description;
    public $status;

    public function createMoment(){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->status = $_POST['status'];
        $data = $this;

        
        if($this->create('moments', $data)){
            return $this->response('success', 'Momento criado com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function readMoments($where = null){
        if(!is_null($where)){
            return $this->ready('moments', $where);
        } else{
            return $this->ready('moments');
        }
    }

    public function readMomentsHasMusic($where){
        return $this->ready('moments_has_music', $where);
    }

    public function updateMoments($where){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->status = $_POST['status'];
        $data = $this;

        if($this->update('moments', $where, $data)){
            return $this->response('success', 'Momento editado com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteMoments($where){
        $response = $this->delete('moments', $where);
        if($response !== false){
            return  $this->response('success', 'Momento excluído com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function verifyMusicHasMoments($idMoment){
        $where = array('moments_idmoments' => $idMoment);
        $resp = $this->ready('moments_has_music', $where);
        return ($resp->num_rows() != 0) ? true : false;
    }
}