<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Agreements extends CI_controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Agreements');
    }

    public function get($id){
        $this->load->model('agreement');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->agreement->readAgreement($where);
        echo json_encode($resp->row());
    }
}