<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Formation extends Crud{
    public $name;
    public $description;
    public $price;
    public $video;

    public function createFormation(){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->price = brl2decimal($_POST['price']);
        $this->video = $_POST['video'];
        $data = $this;

        if($this->create('formation', $data)){
            $idForm = $this->db->insert_id();
            $instruments = json_decode($_POST['instruments']);
            $this->createFormationHasInstrument($idForm, $instruments);
            return $this->response('success', 'Formação criada com sucesso'); 
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function createFormationHasInstrument($id, $instrument){
        if(is_array($instrument)){
            foreach($instrument as $t){
                $data = array('instrument_idinstrument' => $t, 'formation_idformation' => $id);
                $this->db->insert('formation_has_instrument', $data);
            }
        } else{
            exit;
        }
    }

    public function readFormation($where = null){
        if(!is_null($where)){
            return $this->ready('formation', $where);
        } else{
            return $this->ready('formation');
        }
    }

    public function readFormationHasInstrument($id){
        $where = array('formation_idformation' => $id);
        return $this->ready('formation_has_instrument', $where);
    }

    public function returnFormationByInstruments($idInstrument){
        $where = array('instrument_idinstrument' => $idInstrument);
        return $this->ready('formation_has_instrument', $where);
    }

    public function updateFormation($id){
        $this->name = $_POST['name'];
        $this->description = $_POST['description'];
        $this->price = brl2decimal($_POST['price']);
        $this->video = $_POST['video'];
        $data = $this;

        $instruments = json_decode($_POST['instruments']);
        $fhi = array();

        $formHasInst = $this->readFormationHasInstrument($id);
        foreach($formHasInst->result() as $f):
            $fhi[] = $f->instrument_idinstrument;
        endforeach;

        $add = array_diff($instruments, $fhi);
        $del = array_diff($fhi, $instruments);
        $this->createFormationHasInstrument($id, $add);
        $this->deleteFormationHasInstrument($id, $del);

        $where = array('idformation' => $id);
        if($this->update('formation', $where, $data)){
            return $this->response('success', 'Formação atualizada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteFormation($id){
        if(!$this->isComposing($id)){
            $where = array('formation_idformation' => $id);
            $this->delete('formation_has_instrument', $where);
            $where = array('idformation' => $id);
            $response = $this->delete('formation', $where);
            if($response !== false){
                return $this->response('sucess', 'Formação excluída com sucesso');
            } else{
                $this->db->db_debug = false;
                $error = $this->db->error();
                return $this->response('error', $error['message']);
            }
        } else{
            return $this->response('error', 'Esta formação não pode ser excluída pois compõe uma inscrição ou contrato');
        }
        
    }

    public function deleteFormationHasInstrument($id, $data = null){
        foreach($data as $d):
            $where = array('formation_idformation' => $id, 'instrument_idinstrument' => $d);
            $this->delete('formation_has_instrument', $where);
        endforeach;
    }

    private function isComposing($id){
        $where = array('formation_idformation' => $id);
        $composition = $this->ready('composition', $where);
        return ($composition->num_rows() != 0) ? true : false;
    }
}