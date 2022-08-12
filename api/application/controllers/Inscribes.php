<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Inscribes extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Inscribe');
    }

    public function create(){
        $this->load->model('inscribe');
        $this->load->model('account');
        $this->load->library('email');
        
        $resp = $this->inscribe->createInscribe();
        if($resp['icon'] == 'success'){
            
            $account = $this->account->readAccount(array('idaccount' => $resp['idAccount']));
            $account = $account->row();

            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            $this->email->from('contato@toquedivino.com', 'Toque Divino');
            $this->email->to($account->email);
            $this->email->subject('Seja bem-vindo '. $account->name);
            $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>Demystifying Email Design</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    </head>
                    <body style="margin: 0; padding: 0;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>
                               <img src="https://api.cerimonialoquedivino.com.br/assets/img/logotipo.png" alt="logotipo" width="200" />
                            </td>
                            <td>Olá '.$account->name.'</td>
                        </tr>
                        <tr>
                            <td>Acesso: {unwrap}https://app.cerimonialtoquedivino.com.br/customer/home{/unwrap}</td>
                        </tr>
                        <tr>
                            <td>Login: '.$account->email.'</td>
                        </tr>
                        <tr>
                            <td>Senha: '.$account->password.'</td>
                        </tr>
                        </table>
                    </body>
                    </html>';
            $this->email->message($msg);
            $this->email->send();

            echo json_encode($resp);

        } else{
            echo json_encode($resp);
        }
    }

    public function createApp(){
        $this->load->model('inscribe');
        $this->load->model('account');
        $this->load->model('event');
        $this->load->library('email');

        // $this->deleteLead();
        
        $resp = $this->inscribe->createInscribe();
        if($resp['icon'] == 'success'){
            
            $account = $this->account->readAccount(['idaccount' => $resp['idAccount']]);
            $account = $account->row();

            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            $this->email->from('contato@toquedivino.com', 'Toque Divino');
            $this->email->to($account->email);
            $this->email->subject('Seja bem-vindo '. $account->name);
            $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>Demystifying Email Design</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    </head>
                    <body style="margin: 0; padding: 0;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>
                               <img src="https://api.cerimonialtoquedivino.com.br/assets/img/logotipo.png" alt="logotipo" width="200" />
                            </td>
                            <td>Olá '.$account->name.'</td>
                        </tr>
                        <tr>
                            <td>Acesso: {unwrap}https://app.cerimonialtoquedivino.com.br/customer/home{/unwrap}</td>
                        </tr>
                        <tr>
                            <td>Login: '.$account->email.'</td>
                        </tr>
                        <tr>
                            <td>Senha: '.$account->password.'</td>
                        </tr>
                        </table>
                    </body>
                    </html>';
            $this->email->message($msg);
            $this->email->send();

            echo json_encode($resp);

        } else{
            echo json_encode($resp);
        }
    }

    public function get(){
        $this->load->model('inscribe');
        $this->load->model('tax');
        $this->load->model('account');
        $this->load->model('formation');
        $this->load->model('service');
        $this->load->model('budget');
        $resp = array();
        $serv = array();
        $inscribe = $this->inscribe->readInscribe();
        foreach($inscribe->result() as $i):
            $i->action = null;
            $i->address = json_decode($i->address);
            $account = $this->account->readAccount(array('idaccount' => $i->account_idaccount));
            $i->account = $account->row();
            $budget = $this->budget->readBudget(['inscribe_idinscribe' => $i->idinscribe]);
            $i->budget = $budget->result();
            $composition = $this->inscribe->readComposition($i->idinscribe);
            foreach($composition->result() as $comp):
                $formation = $this->formation->readFormation(array('idformation' => $comp->formation_idformation));
                $i->formation = $formation->row();
                $services = $this->service->readService(array('idservice' => $comp->service_idservice));
                $service = $services->row();
                // foreach($services->result() as $service):
                    $service->status = ($service->status == 1) ? true : false;
                    $serviceHasTax = $this->service->readServiceHasTax($service->idservice);
                    if($serviceHasTax->num_rows() != 0){
                        foreach($serviceHasTax->result() as $sht):
                            $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                            $data[] = $tax->row();
                        endforeach;
                        $service->taxas = $data;
                        $data = array();
                    } else{
                        $service->taxas = null;
                    }
                    $serv[] = $service;
                // endforeach;
                $i->service = $service;
            endforeach;
            $resp[] = $i;
        endforeach;

        echo json_encode($resp);
    }

    public function getCustomers($id){
        $this->load->model('inscribe');
        $this->load->model('formation');
        $this->load->model('service');
        $where = array('account_idaccount' => $id);
        $resp = $this->inscribe->readInscribe($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->address = ($resp->address != null) ? json_decode($resp->address): json_decode('{"street": "", "number": "", "complement": "", "neighborhood": "", "city": "", "zipcode": "", "state": "", "country": ""}');
            $composition = $this->inscribe->readComposition($resp->idinscribe);
            foreach($composition->result() as $comp):
                $formation = $this->formation->readFormation(array('idformation' => $comp->formation_idformation));
                $resp->formation = $formation->row();
                $service = $this->service->readService(array('idservice' => $comp->service_idservice));
                $resp->service = $service->row();
            endforeach;
        } else{
            $resp = $resp->row();
        }
        
        echo json_encode($resp);
    }

    public function verifyStatus($id){
        $this->load->model('inscribe');
        $inscribe = $this->inscribe->readInscribe(['idinscribe' => $id]);
        $inscribe = $inscribe->row();
        echo json_encode($inscribe->status);
    }

    public function update($id){
        $this->load->model('inscribe');
        $resp = ($this->inscribe->updateInscribe($id)) ? ['msg' => 'Cadastro atualizado com sucesso', 'icon' => 'success'] : ['msg' => 'Cadastro não pode ser atualizado', 'icon' => 'error'];
        echo json_encode($resp);
    }

    public function validate($id){
        $this->load->model('inscribe');
        
        $resp = ($this->inscribe->updateStatusInscribe($id, 2)) ? ['msg' => 'Cadastro validado com sucesso', 'icon' => 'success'] : ['msg' => 'Cadastro não pode ser validado', 'icon' => 'error'];
        echo json_encode($resp);
    }

    public function updateCustomers($id){
        $this->load->model('inscribe');
        echo json_encode($this->inscribe->updateInscribeCustomers($id));
    }

    public function delete($id){
        $this->load->model('inscribe');
        $this->load->model('lead');
        $this->load->model('account');
        $this->load->model('signature');

        
        $this->inscribe->deleteComposition(['inscribe_idinscribe' => $id]);

        $idPreInscribe = $this->lead->returnIdPreInscribe($id);

        if(!is_null($idPreInscribe)){
            $this->lead->deletePreInscribeHasInscribe(['inscribe_idinscribe' => $id]);

            $this->lead->updateLead(array('idpre_inscribe' => $idPreInscribe), array('flag' => 0));
        }

        $idAccount = $this->inscribe->returnAccountIdByInscribeId($id);
        $this->signature->deleteSignature(['account_idaccount' => $idAccount]);
        $resp = $this->inscribe->deleteInscribe(['idinscribe' => $id]);
        $this->account->deleteAccountByID($idAccount);
        echo json_encode($resp);
    }

    public function variantTax(){
        $this->load->model('inscribe');
        echo json_encode($this->inscribe->createVariantTax());
    }

}