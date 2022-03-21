<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Formation extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Formation');
    }

    public function create(){
        $this->load->model('formation');
        echo json_encode($this->formation->createFormation());
    }

    public function get($id = null){
        $this->load->model('formation');
        $this->load->model('instrument');
        $resp = array();
        $where = (!is_null($id)) ? array('idformation' => $id) : $id;
        $formation = $this->formation->readFormation($where);
        foreach($formation->result() as $f){
            $formationHasInstrument = $this->formation->readFormationHasInstrument($f->idformation);
            if($formationHasInstrument->num_rows() != 0){
                foreach($formationHasInstrument->result() as $fhi){
                    $instrument = $this->instrument->readInstrument(array('idinstrument' => $fhi->instrument_idinstrument));
                    $data[] = $instrument->row();
                }
                $f->instruments = $data;
                $data = array();
            } else{
                $f->instruments = null;
            }

            $resp[] = $f;
            
        }
        echo json_encode($resp);
    }

    public function getByInstruments(){
        $this->load->model('formation');
        $arr = json_decode($_POST['instruments']);
        // $arr = $_POST['instruments'];
        $resp = array();
        $arrIdFormation = array();

        foreach($arr as $a):
            $fhi = $this->formation->returnFormationByInstruments($a);
            $arrIdFormation = $fhi->result();
        endforeach;

        foreach($arrIdFormation as $id):
            $where = array('idformation' => $id->formation_idformation);
            $formation = $this->formation->readFormation($where);
            $resp[] = $formation->row();
        endforeach;

        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('formation');
        echo json_encode($this->formation->updateFormation($id));
    }

    public function delete($id){
        $this->load->model('formation');
        echo json_encode($this->formation->deleteFormation($id));
    }
}