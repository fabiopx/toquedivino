<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Leads extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Leads');
    }

    public function create(){
        $this->load->model('leads');
        echo json_encode($this->leads->createLead());
    }

    public function get($id = null){
        $this->load->model('leads');
        $where = (!is_null($id)) ? array('idpre_inscribe' => $id) : $id;
        $leads = $this->leads->readLead($where);
        echo json_encode($leads->result());
    }

    public function sendEmail(){
        $this->load->model('leads');
        $this->load->library('email');
        $data = array(
            'action' => 1,
            'datetime' => $_POST['datetime'],
            'pre_inscribe_idpre_inscribe' => $_POST['idpre_inscribe']
        );

        $this->leads->createLogMarketing($data);

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from('emerson@toquedivino.com', 'Toque Divino');
        $this->email->to($_POST['email']);
        $this->email->subject('Continue seu cadastro');
        $this->email->message($_POST['msg']);
        if($this->email->send()){
            $resp['msg'] = 'E-mail enviado com sucesso';
            $resp['icon'] = 'success';
        } else{
            $resp['msg'] = 'O e-mail não pode ser enviado';
            $resp['icon'] = 'error';
        }
        echo json_encode($resp);
    }

    public function sendWhatsapp(){
        $this->load->model('leads');
        $data = array(
            'action' => 2,
            'datetime' => $_POST['datetime'],
            'pre_inscribe_idpre_inscribe' => $_POST['idpre_inscribe']
        );

        echo json_encode($this->leads->createLogMarketing($data));
    }

    public function getLogMarketing($id){
        $this->load->model('leads');
        $where = array('pre_inscribe_idpre_inscribe' => $id, 'action' => 1);
        $email = $this->leads->readLogMarketing($where);
        $emailCount = ['E-mail', $email->num_rows()];
        $where = array('pre_inscribe_idpre_inscribe' => $id, 'action' => 2);
        $whats = $this->leads->readLogMarketing($where);
        $whatsCount = ['WhatsApp', $whats->num_rows()];
        $head = ['Tipo de Marketing', 'Valor'];
        $resp = array(
            $head, $emailCount, $whatsCount 
        );

        echo json_encode($resp);
    }

    public function delete(){
        $this->load->model('leads');
        $ip = $_POST['ip'];
        $mobile = $_POST['mobile'];
        $where = array('ip' => $ip, 'mobile' => $mobile);
        $resp = ($this->leads->deleteLead($where)) ? 'Lead excluída com sucesso' : 'Lead não pode ser excluída';

        echo json_encode($resp);
    }
}