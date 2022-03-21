<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Uploads extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Uploads');
    }

    public function uploadPhoto(){
        $config['upload_path']    = './assets/uploads/';
        $config['allowed_types']  = 'gif|jpg|png|jpeg';
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('file')){
            $resp['msg'] = $this->upload->display_errors();
            $resp['type'] = 'error';
        } else{
            $resp['msg'] = 'Upload realizado com sucesso. ';
            $resp['type'] = 'success';
        }

        echo json_encode($resp);
    }

}