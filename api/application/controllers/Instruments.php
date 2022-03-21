<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Instruments extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Instruments');
    }

    public function create(){
        $this->load->model('instrument');
        echo json_encode($this->instrument->createInstrument());
    }

    public function get($id = null){
        $this->load->model('instrument');
        $where = (!is_null($id)) ? array('idinstrument' => $id) : $id;
        $instrument = $this->instrument->readInstrument($where);
        $resp = array();
        foreach($instrument->result() as $i):
            $i->formationhasinstrument = $this->instrument->verifyFormationHasInstrument($i->idinstrument);
            array_push($resp, $i);
        endforeach;
        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('instrument');
        $where = array('idinstrument' => $id);
        echo json_encode($this->instrument->updateInstrument($where));
    }

    public function delete($id){
        $this->load->model('instrument');
        $where = array('idinstrument' => $id);
        echo json_encode($this->instrument->deleteInstrument($where));
    }
}