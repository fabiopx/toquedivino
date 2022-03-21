<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Music extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Music');
    }

    public function create(){
        $this->load->model('music');
        $resp = $this->music->createMusic();
        echo json_encode($resp);
    }

    public function get($id = null){
        $this->load->model('music');
        $this->load->model('moments');
        $resp = array();
        $where = (!is_null($id)) ? array('idmusic' => $id) : $id;
        $music = $this->music->readMusic($where);
        foreach($music->result() as $m):
            $m->status = ($m->status == 1) ? true : false;
            $musicHasMoments = $this->music->readMusicHasMoments($m->idmusic);
            if($musicHasMoments->num_rows() != 0){
                foreach($musicHasMoments->result() as $mhm):
                    $moments = $this->moments->readMoments(array('idmoments' => $mhm->moments_idmoments));
                    $moments->row()->status = ($moments->row()->status != 0) ? true : false;
                    $data[] = $moments->row();
                endforeach;
                $m->moments = $data;
                $data = array();
            } else{
                $m->moments = null;
            }
            $resp[] = $m;
        endforeach;
        echo json_encode($resp);
    }

    public function update($id){
        $this->load->model('music');
        echo json_encode($this->music->updateMusic($id));
    }

    public function delete($id){
        $this->load->model('music');
        echo json_encode($this->music->deleteMusic($id));
    }
}