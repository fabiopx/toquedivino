<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Instrument extends Crud{
    public $name;
    public $sound;
    public $image;

    public function createInstrument(){
        $this->name = $_POST['name'];
        $this->image = $_POST['image'];
        $data = $this;

        if($this->create('instrument', $data)){
            return $this->response('success', 'Instrumento cadastrado com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function readInstrument($where= null){
        if(!is_null($where)){
            return $this->ready('instrument', $where);
        } else{
            return $this->ready('instrument');
        }
    }

    public function updateInstrument($where){
        $this->name = $_POST['name'];
        $this->image = $_POST['image'];
        $data = $this;
        $this->db->db_debug = false;
        if($this->update('instrument', $where, $data)){
            return $this->response('success', 'Instrumento editado com sucesso');
        } else{
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteInstrument($where){
        $this->db->db_debug = false;
        $response = $this->delete('instrument', $where);
        if($response !== false){
            return  $this->response('success', 'Instrumento excluído com sucesso');
        } else{
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function verifyFormationHasInstrument($idInstrument){
        $where = array('instrument_idinstrument' => $idInstrument);
        $resp = $this->ready('formation_has_instrument', $where);
        return ($resp->num_rows() != 0) ? true : false;
    }
}