<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Events extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Events');
    }

    public function createCustomers(){
        $this->load->model('event');
        if($this->event->createEvent()){
            $resp = array('msg' => 'Evento criado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Evento não pode ser criado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function getCustomers($id){
        $this->load->model('event');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->event->readEvent($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->address = json_decode($resp->address);
        } else{
            $resp = $resp->row();
        }
        
        echo json_encode($resp);
    }

    public function verify($id){
        $this->load->model('event');
        $where = ['inscribe_idinscribe' => $id];
        $event = $this->event->readEvent($where);
        $resp = ($event->num_rows() != 0) ? true : false;
        echo json_encode($resp);
    }

    public function updateCustomers($id){
        $this->load->model('event');
        $where = array('idevent' => $id);
        if($this->event->updateEvent($where)){
            $resp = array('msg' => 'Evento editado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Evento não pode ser editado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }
}