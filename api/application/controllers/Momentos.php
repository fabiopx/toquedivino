<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Momentos extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Moments');
    }

    public function create(){
        $this->load->model('moments');
        echo json_encode($this->moments->createMoment());
    }
    public function get($id = null){
        $this->load->model('moments');
        $where = (!is_null($id)) ? array('idmoments' => $id) : $id;
        $moments = $this->moments->readMoments($where)->result();
        foreach($moments as $m):
            $m->status = ($m->status != 0) ? true : false;
            $m->musichasmoments = $this->moments->verifyMusicHasMoments($m->idmoments);
            $resp[] = $m;
        endforeach;
        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('moments');
        $where = array('idmoments' => $id);
        echo json_encode($this->moments->updateMoments($where));
    }

    public function delete($id){
        $this->load->model('moments');
        $where = array('idmoments' =>$id);
        echo json_encode($this->moments->deleteMoments($where));
    }
}