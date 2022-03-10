<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');


class Api extends CI_Controller{

    
    public function index(){
        echo json_encode(':: API :: Toque Divino ::');
    }

    //Uploads
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


    //Account
    public function createUser(){
        $this->load->model('account');
        echo json_encode($this->account->createAccount());
    }
    public function getUsers($id = null){
        $this->load->model('account');
        $resp = array();
        $where = (!is_null($id)) ? array('idaccount' => $id) : $id;
        $users = $this->account->readAccount($where);
        if(is_null($id)){
            foreach($users->result() as $user):
                $user->status = ($user->status == 1) ? true : false;
                $resp[] = $user;
            endforeach;
        } else{
            $users = $users->row();
            $users->status = ($users->status == 1) ? true : false;
            $resp = $users;
        }
        
        echo json_encode($resp);
    }

    public function updateUser($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->updateAccount($where));
    }

    public function updateUserCustomers($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->updateAccountCustomers($where));
    }

    public function deleteUser($id){
        $this->load->model('account');
        $where = array('idaccount' => $id);
        echo json_encode($this->account->deleteAccount($where));
    }

    public function login(){
        $this->load->model('account');
        echo json_encode($this->account->login());
    }

    public function loginCustomer(){
        $this->load->model('account');
        echo json_encode($this->account->loginCustomer());
    }

    //Services
    public function createService(){
        $this->load->model('service');
        echo json_encode($this->service->createService());
    }

    public function getServices($id = null){
        $this->load->model('service');
        $this->load->model('tax');
        $resp = array();
        $where = (!is_null($id)) ? array('idservice' => $id) : $id;
        $services = $this->service->readService($where);
        foreach($services->result() as $service){
            $service->status = ($service->status == 1) ? true : false;
            $serviceHasTax = $this->service->readServiceHasTax($service->idservice);
            if($serviceHasTax->num_rows() != 0){
                foreach($serviceHasTax->result() as $sht){
                    $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                    $data[] = $tax->row();
                }
                $service->taxas = $data;
                $data = array();
            } else{
                $service->taxas = null;
            }
            
            $resp[] = $service;
        }
        echo json_encode($resp);
    }

    public function updateService($id){
        $this->load->model('service');
        echo json_encode($this->service->updateService($id));
    }

    public function deleteService($id){
        $this->load->model('service');
        echo json_encode($this->service->deleteService($id));
    }

    //Tax
    public function createTax(){
        $this->load->model('tax');
        echo json_encode($this->tax->createTax());
    }

    public function getTax($id = null){
        $this->load->model('tax');
        $where = (!is_null($id)) ? array('idtax' => $id) : $id;
        $tax = $this->tax->readTax($where);
        $resp = array();
        foreach($tax->result() as $t):
            $t->servicehastax = $this->tax->verifyServiceHasTax($t->idtax);
            array_push($resp, $t);
        endforeach;
        echo json_encode($resp);
    }

    public function getTaxByService($id){
        $this->load->model('service');
        $this->load->model('tax');
        $serviceHasTax = $this->service->readServiceHasTax($id);
        $resp = array();
        if($serviceHasTax->num_rows() != 0){
            foreach($serviceHasTax->result() as $sht):
                $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                foreach($tax->result() as $t):
                    $t->vValue = 0;
                    $resp[] = $t;
                endforeach;
            endforeach;
        } else{
            $resp = null;
        }
        
        echo json_encode($resp);
                
    }

    public function updateTax($id){
        $this->load->model('tax');
        echo json_encode($this->tax->updateTax($id));
    }

    public function deleteTax($id){
        $this->load->model('tax');
        $where = array('idtax' => $id);
        echo json_encode($this->tax->deleteTax($where));
    }

    //Instruments
    public function createInstrument(){
        $this->load->model('instrument');
        echo json_encode($this->instrument->createInstrument());
    }

    public function getInstrument($id = null){
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

    public function updateInstrument($id){
        $this->load->model('instrument');
        $where = array('idinstrument' => $id);
        echo json_encode($this->instrument->updateInstrument($where));
    }

    public function deleteInstrument($id){
        $this->load->model('instrument');
        $where = array('idinstrument' => $id);
        echo json_encode($this->instrument->deleteInstrument($where));
    }

    //Formation
    public function createFormation(){
        $this->load->model('formation');
        echo json_encode($this->formation->createFormation());
    }

    public function getFormation($id = null){
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

    public function getFormationByInstruments(){
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

    public function updateFormation($id){
        $this->load->model('formation');
        echo json_encode($this->formation->updateFormation($id));
    }

    public function deleteFormation($id){
        $this->load->model('formation');
        echo json_encode($this->formation->deleteFormation($id));
    }

    //Moments
    public function createMoments(){
        $this->load->model('moments');
        echo json_encode($this->moments->createMoment());
    }
    public function getMoments($id = null){
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

    public function updateMoments($id){
        $this->load->model('moments');
        $where = array('idmoments' => $id);
        echo json_encode($this->moments->updateMoments($where));
    }

    public function deleteMoments($id){
        $this->load->model('moments');
        $where = array('idmoments' =>$id);
        echo json_encode($this->moments->deleteMoments($where));
    }

    //Music
    public function createMusic(){
        $this->load->model('music');
        $resp = $this->music->createMusic();
        echo json_encode($resp);
    }

    public function getMusic($id = null){
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

    public function updateMusic($id){
        $this->load->model('music');
        echo json_encode($this->music->updateMusic($id));
    }

    public function deleteMusic($id){
        $this->load->model('music');
        echo json_encode($this->music->deleteMusic($id));
    }

    //Leads
    public function createLead(){
        $this->load->model('leads');
        echo json_encode($this->leads->createLead());
    }

    public function getPreInscribe($id = null){
        $this->load->model('leads');
        $where = (!is_null($id)) ? array('idpre_inscribe' => $id) : $id;
        $leads = $this->leads->readLead($where);
        echo json_encode($leads->result());
    }

    public function sendEmailPreInscribe(){
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

    public function sendWhatsappPreInscribe(){
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

    public function deleteLead(){
        $this->load->model('leads');
        $ip = $_POST['ip'];
        $mobile = $_POST['mobile'];
        $where = array('ip' => $ip, 'mobile' => $mobile);
        $resp = ($this->leads->deleteLead($where)) ? 'Lead excluída com sucesso' : 'Lead não pode ser excluída';

        echo json_encode($resp);
    }

    //Inscribe
    public function createInscribe(){
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
                               <img src="https://toquedivino.com/divine/assets/img/logotipo.png" alt="logotipo" width="200" />
                            </td>
                            <td>Olá '.$account->name.'</td>
                        </tr>
                        <tr>
                            <td>Acesso: {unwrap}https://toquedivino.com/divine/customers{/unwrap}</td>
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

    public function createInscribeApp(){
        $this->load->model('inscribe');
        $this->load->model('account');
        $this->load->model('event');
        $this->load->library('email');

        $this->deleteLead();
        
        $resp = $this->inscribe->createInscribe();
        if($resp['icon'] == 'success'){

            // $data = array(
            //     'name' => $_POST['eventname'],
            //     'date' => $_POST['eventdate'],
            //     'time' => $_POST['eventtime'],
            //     'address' => $_POST['eventaddress'],
            //     'inscribe_idinscribe' => $resp['idInscribe']
            // );

            // $this->event->createEventApp($data);
            
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
                               <img src="https://app.cerimonialtoquedivino.com.br/assets/img/logotipo.png" alt="logotipo" width="200" />
                            </td>
                            <td>Olá '.$account->name.'</td>
                        </tr>
                        <tr>
                            <td>Acesso: {unwrap}https://toquedivino.com/divine/customers{/unwrap}</td>
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

    public function getInscribe(){
        $this->load->model('inscribe');
        $this->load->model('tax');
        $this->load->model('account');
        $this->load->model('formation');
        $this->load->model('service');
        $resp = array();
        $serv = array();
        $where = array('status' => 0);
        $inscribe = $this->inscribe->readInscribe($where);
        foreach($inscribe->result() as $i):
            $i->address = json_decode($i->address);
            $account = $this->account->readAccount(array('idaccount' => $i->account_idaccount));
            $i->account = $account->row();
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

    public function getInscribeCustomers($id){
        $this->load->model('inscribe');
        $this->load->model('formation');
        $this->load->model('service');
        $where = array('account_idaccount' => $id);
        $resp = $this->inscribe->readInscribe($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->address = json_decode($resp->address);
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


    public function updateInscribe($id){
        $this->load->model('inscribe');
        echo json_encode($this->inscribe->updateInscribe($id));
    }

    public function updateInscribeCustomers($id){
        $this->load->model('inscribe');
        echo json_encode($this->inscribe->updateInscribeCustomers($id));
    }

    public function deleteInscribe($id){
        $this->load->model('inscribe');
        $this->load->model('leads');
        $this->load->model('account');

        $where = array('inscribe_idinscribe' => $id);
        $this->inscribe->deleteComposition($where);

        $idPreInscribe = $this->leads->returnIdPreInscribe($id);

        if(!is_null($idPreInscribe)){
            $this->leads->deletePreInscribeHasInscribe($where);

            $this->leads->updateLead(array('idpre_inscribe' => $idPreInscribe), array('flag' => 0));
        }

        $idAccount = $this->inscribe->returnAccountIdByInscribeId($id);

        $whereInscribe = array('idinscribe' => $id);
        $resp = $this->inscribe->deleteInscribe($whereInscribe);
        $this->account->deleteAccountByID($idAccount);
        echo json_encode($resp);
    }

    public function createEventCustomers(){
        $this->load->model('event');
        if($this->event->createEvent()){
            $resp = array('msg' => 'Evento criado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Evento não pode ser criado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function getEventCustomers($id){
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

    public function updateEventCustomers($id){
        $this->load->model('event');
        $where = array('idevent' => $id);
        if($this->event->updateEvent($where)){
            $resp = array('msg' => 'Evento editado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Evento não pode ser editado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function createEngagedCustomers(){
        $this->load->model('engaged');
        if($this->engaged->createEngaged()){
            $resp = array('msg' => 'Noivos cadastrados com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Noivos não foram cadastrados', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function getEngagedCustomers($id){
        $this->load->model('engaged');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->engaged->readEngaged($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->bride_address = json_decode($resp->bride_address);
            $resp->groom_address = json_decode($resp->groom_address);
            $resp->groom_responsible_for = ($resp->groom_responsible_for == 0) ? false : true;
            $resp->bride_responsible_for = ($resp->bride_responsible_for == 0) ? false: true;
            $resp->selectEngaged = true;
        } else{
            $resp = $resp->row();
        }
        
        echo json_encode($resp);
    }

    public function updateEngagedCustomers($id){
        $this->load->model('engaged');
        $where = array('idengaged' => $id);
        if($this->engaged->updateEngaged($where)){
            $resp = array('msg' => 'Cadastro dos noivos editado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Cadastro dos noivos não pode ser editado', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function createWeddingServices(){
        $this->load->model('engaged');

        if($this->engaged->createWeddingServices()){
            $resp = array('msg' => 'Empresa cadastrada com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Empresa não pode ser cadastrada', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function getWeddingServices($id){
        $this->load->model('engaged');
        $where = array('engaged_idengaged' => $id);
        $resp = $this->engaged->readWeddingServices($where);
        echo json_encode($resp->result());
    }

    public function updateWeddingServices($id){
        $this->load->model('engaged');
        $where = array('idwedding_services' => $id);
        if($this->engaged->updateWeddingServices($where)){
            $resp = array('msg' => "Empresa editada com sucesso", 'icon' => 'success');
        } else{
            $resp = array('msg' => "Empresa não pode ser editada", 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function deleteWeddingService($id){
        $this->load->model('engaged');
        $where = array('idwedding_services' => $id);
        if($this->engaged->deleteWeddingService($where)){
            $resp = array('msg' => "Empresa excluída com sucesso", "icon" => "success");
        } else{
            $resp = array('msg' => 'Empresa não pode ser excluída', "icon" => "error");
        }

        echo json_encode($resp);
    }

    public function getCommitteCustomers($id){
        $this->load->model('graduation_committe');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->graduation_committe->readCommitte($where);
        if($resp->num_rows() != 0){
            $resp = $resp->row();
            $resp->committe_name = json_decode($resp->committe_name);
        } else{
            $resp = $resp->row();
        }
        
        echo json_encode($resp);
    }

    public function createCommitteCustomers(){
        $this->load->model('graduation_committe');
        if($this->graduation_committe->createCommitte()){
            $resp = array('msg' => 'Comissão de formatura cadastrada com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Comissão de formatura não pode ser cadastrada', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function updateCommitteCustomers($id){
        $this->load->model('graduation_committe');
        $where = array('idgraduation_committe' => $id);
        if($this->graduation_committe->updateCommitte($where)){
            $resp = array('msg' => 'Comissão de formatura editada com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Comissão de formatura não pode ser editada', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function getRepertoryCustomers($id){
        $this->load->model('repertory');
        $this->load->model('moments');
        $this->load->model('music');
        $resp = array();
        $repertory = $this->repertory->readRepertory($id);
        if($repertory->num_rows() != 0){
            $mhr = $this->repertory->readMomentsHasRepertory($repertory->row()->idrepertory);
            if($mhr->num_rows() != 0){
                foreach($mhr->result() as $m):
                    $moments = $this->moments->readMoments(array('idmoments' => $m->moments_idmoments));
                    $music = $this->music->readMusic(array('idmusic' => $m->music_idmusic));
                    $resp[] = array(
                        'id' => $m->id,
                        'repertory' => $repertory->row()->idrepertory,
                        'moments' => $moments->row(), 
                        'music' => $music->row(),
                        'sequence' => $m->sequence
                    );
                endforeach;
            } else{
                $resp[] = array(
                    'moments' => array('idmoments' => '0', 'name' => 'Momentos'), 
                    'music' => array('idmusic' => '0', 'name' => 'Música', 'url' => '')
                );
            }
            
        } else{
            $resp = $repertory->row();
        }
        
        echo json_encode($resp);
    }

    public function startRepertory(){
        $this->load->model('repertory');
        if($this->repertory->initRepertory()){
            $resp = array('msg' => 'Repertório inicializado com sucesso', 'icon' => 'success');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            $resp = array('msg' => $error['message'], 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function addRepertoryItem(){
        $this->load->model('repertory');
        if($this->repertory->addRepertoryItem()){
            $resp = array('msg' => 'Música adicionada ao repertório com sucesso', 'icon' => 'success');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            $resp = array('msg' => $error['message'], 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function getRepertoryCustomersID($id){
        $this->load->model('repertory');
        $repertory = $this->repertory->readRepertory($id);
        echo json_encode($repertory->row()->idrepertory);
    }

    public function deleteRepertoryItem($id, $sequence){
        $this->load->model('repertory');
        // $this->db->db_debug = false;

        $where = array('id' => $id);
        $response = $this->repertory->delRepertoryItem($where);
        if($response !== false){
            $this->repertory->orderSequence($sequence);
            $resp = array('msg' => 'Item excluído do repertório com sucesso', 'icon' => 'success');
        } else{
            $error = $this->db->error();
            $resp = array('msg' => $error['message'], 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    public function getMaxSequence($repertory){
        $this->load->model('repertory');

        $sequence = $this->repertory->readMaxSequence($repertory);

        $seq = $sequence->row();

        $resp = $seq->sequence;

        echo json_encode($resp);
    }

    public function sequenceUp($id){
        $this->load->model('repertory');

        $repertory = $this->input->post('repertory');
        $sequence = $this->input->post('sequence');

        $sequenceUP = $sequence - 1;

        $seqToUp = $this->repertory->readSequenceToUpdate($repertory, $sequenceUP);

        $this->repertory->updateSequence($id, $sequenceUP);
        $this->repertory->updateSequence($seqToUp->row()->id, $sequence);
    }

    public function sequenceDown($id){
        $this->load->model('repertory');

        $repertory = $this->input->post('repertory');
        $sequence = $this->input->post('sequence');

        $sequenceDown = $sequence + 1;

        $seqToDown = $this->repertory->readSequenceToUpdate($repertory, $sequenceDown);

        $this->repertory->updateSequence($id, $sequenceDown);
        $this->repertory->updateSequence($seqToDown->row()->id, $sequence);
    }

    public function getMomentsCustomers(){
        $this->load->model('moments');
        $resp = $this->moments->readMoments();
        echo json_encode($resp->result());
    }

    public function getMusicCustomers(){
        $this->load->model('music');
        $this->load->model('moments');
        $resp = array();
        $moments = array();
        $musics = $this->music->readMusic();
        foreach($musics->result() as $music):
            $mhm = $this->music->readMusicHasMoments($music->idmusic);
            foreach($mhm->result() as $m):
                $moment = $this->moments->readMoments(array('idmoments' => $m->moments_idmoments));
                array_push($moments, $moment->row());
            endforeach;
            $music->moments = $moments;
            $moments = [];
            $resp[] = $music;
        endforeach;
        echo json_encode($resp);
    }

    //Contracts
    public function createContract(){
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('graduation_committe');
        $this->load->model('event');
        $this->load->model('signature');
        $this->load->model('inscribe');
        $this->load->model('tax');
        

        $idInscribe = $_POST['idinscribe'];

        $inscribe = $this->inscribe->readInscribe(array('idinscribe' => $idInscribe));

        $idAccount = $inscribe->row()->account_idaccount;

        $tax = json_decode($_POST['variant_tax']);
        foreach($tax as $t):
            if($t->type == 2){
                $dataVariantTax = array(
                    'value' => $t->vValue,
                    'tax_idtax' => $t->idtax,
                    'inscribe_idinscribe' => $idInscribe
                );
                $this->tax->createVariantTax($dataVariantTax);
            }
        endforeach;
        $this->agreement->createAgreement();

        $idAgreement = $this->db->insert_id();
        
        $dataSignature = array(
            'name' => $_POST['inscribeaccountable'],
            'type' => 1,
            'font' => 'gf-fuggles',
            'status' => 1,
            'inuse' => 1,
            'account_idaccount' => $idAccount
        );
        $this->signature->createSignature($dataSignature);
        $idSignature = $this->db->insert_id();

        $dataAHS = array(
            'agreement_idagreement' => $idAgreement,
            'signature_idsignature' => $idSignature,
            'inscribe_idinscribe' => $idInscribe,
            'sign' => 0
        );
        $this->agreement->createAgreementHasSignature($dataAHS);
        $this->signature->updateInUse($idSignature);

        $whereSignature = array('type !=' => 1, 'status' => 1);
        $defaultSignatures = $this->signature->readSignature($whereSignature);

        foreach($defaultSignatures->result() as $sign):
            $dataDAHS = array(
                'agreement_idagreement' => $idAgreement,
                'signature_idsignature' => $sign->idsignature,
                'inscribe_idinscribe' => $idInscribe,
                'sign' => 0
            );
            $this->agreement->createAgreementHasSignature($dataDAHS);
            $this->signature->updateInUse($sign->idsignature);
        endforeach;

        if($_POST['selectengaged'] == "true"){
            $this->engaged->createEngaged();
        }
    
        if($_POST['selectgraduationcommitte'] == "true"){
            $this->graduation_committe->createCommitte();
        }

        $this->event->createEvent();

        if($this->inscribe->updateStatusInscribe($idInscribe, 2)){
            $resp['msg'] = 'Contrato gerado com sucesso';
            $resp['icon'] = 'success';
        } else{
            $resp['msg'] = 'Problemas em atualizar o status do cadastro';
            $resp['icon'] = 'error';
        }

        echo json_encode($resp);
    }

    public function validateContract($id){
        $this->load->model('agreement');
        $this->load->model('inscribe');
        $this->load->model('tax');

        $tax = json_decode($_POST['variant_tax']);
        foreach($tax as $t):
            if($t->type == 2){
                $dataVariantTax = array(
                    'value' => $t->vValue,
                    'tax_idtax' => $t->idtax,
                    'inscribe_idinscribe' => $id
                );
                $this->tax->createVariantTax($dataVariantTax);
            }
        endforeach;

        $data = array(
            'value' => $_POST['value'],
            'discount' => $_POST['discount'],
            'addition' => $_POST['addition'],
            'down_payment' => $_POST['downpayment'],
            'down_payment_date' => $_POST['downpaymentdate']
        );

        $this->inscribe->updateStatusInscribe($id, 2);

        $where = array('inscribe_idinscribe' => $id);
        if($this->agreement->updateAgreement($where, $data)){
            $resp = array('msg' => 'Contrato validado com sucesso', 'icon' => 'success');
        } else{
            $resp = array('msg' => 'Contrato não pode ser validado', 'icon' => 'error');
        }

        echo json_encode($resp);
    }

    public function generateContractCustomers(){
        $this->load->model('agreement');
        $this->load->model('signature');
        $this->load->model('inscribe');

        $idInscribe = $_POST['idinscribe'];

        $inscribe = $this->inscribe->readInscribe(array('idinscribe' => $idInscribe));

        $idAccount = $inscribe->row()->account_idaccount;

        $this->agreement->createAgreement();

        $idAgreement = $this->db->insert_id();

        $dataSignature = array(
            'name' => $inscribe->row()->accountable,
            'type' => 1,
            'font' => 'gf-fuggles',
            'status' => 1,
            'inuse' => 1,
            'account_idaccount' => $idAccount
        );
        $this->signature->createSignature($dataSignature);
        $idSignature = $this->db->insert_id();

        $dataAHS = array(
            'agreement_idagreement' => $idAgreement,
            'signature_idsignature' => $idSignature,
            'inscribe_idinscribe' => $idInscribe,
            'sign' => 0
        );
        $this->agreement->createAgreementHasSignature($dataAHS);
        $this->signature->updateInUse($idSignature);

        $whereSignature = array('type !=' => 1, 'status' => 1);
        $defaultSignatures = $this->signature->readSignature($whereSignature);

        foreach($defaultSignatures->result() as $sign):
            $dataDAHS = array(
                'agreement_idagreement' => $idAgreement,
                'signature_idsignature' => $sign->idsignature,
                'inscribe_idinscribe' => $idInscribe
            );
            $this->agreement->createAgreementHasSignature($dataDAHS);
            $this->signature->updateInUse($sign->idsignature);
        endforeach;

        if($this->inscribe->updateStatusInscribe($idInscribe, 1)){
            $resp['msg'] = 'Contrato gerado com sucesso';
            $resp['icon'] = 'success';
        } else{
            $resp['msg'] = 'Problemas em atualizar o status do cadastro';
            $resp['icon'] = 'error';
        }

        echo json_encode($resp);
    }

    public function getContracts($status = null){
        $this->load->model('inscribe');
        $this->load->model('account');
        $this->load->model('formation');
        $this->load->model('service');
        $this->load->model('tax');
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('event');
        $this->load->model('graduation_committe');
        $this->load->model('signature');
        $resp = array();
        $serv = array();
        $where = (is_null($status)) ? array('status >' => 0, 'status <' => 3): array('status' => $status);
        $contract = $this->inscribe->readInscribe($where);
        foreach($contract->result() as $c):
            $c->address = json_decode($c->address);
            $account = $this->account->readAccount(array('idaccount' => $c->account_idaccount));
            $c->account = $account->row();
            $composition = $this->inscribe->readComposition($c->idinscribe);
            foreach($composition->result() as $comp):
                $formation = $this->formation->readFormation(array('idformation' => $comp->formation_idformation));
                $c->formation = $formation->row();
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
                    // $serv[] = $service;
                // endforeach;
                $c->service = $service;
            endforeach;
            $engaged = $this->engaged->readEngaged(array('inscribe_idinscribe' => $c->idinscribe));
            $c->engaged = ($engaged->num_rows() != 0) ? $engaged->row() : null;
            $committe = $this->graduation_committe->readCommitte(array('inscribe_idinscribe' => $c->idinscribe));
            if($committe->num_rows() != 0){
                $committe = $committe->row();
                $committe->committe_name = json_decode($committe->committe_name);
                $c->graduationcommitte = $committe;
            } else{
                $c->graduationcommitte = null;
            }
            
            $event = $this->event->readEvent(array('inscribe_idinscribe' => $c->idinscribe));
            $event = $event->row();
            $event->address = json_decode($event->address);
            $c->event = $event;
            $agreement = $this->agreement->readAgreement(array('inscribe_idinscribe' => $c->idinscribe));
            $c->agreement = $agreement->row();
            $total = $agreement->row()->value - $agreement->row()->discount + $agreement->row()->addition;
            $c->agreement->totalValue = (string) $total;
            $whereAHS = array(
                'inscribe_idinscribe' => $c->idinscribe,
                'agreement_idagreement' => $c->agreement->idagreement
            );
            $signatures = array();
            
            $agreementHasSignature = $this->agreement->readAgreementHasSignature($whereAHS);
            foreach($agreementHasSignature->result() as $a):
                $signature = $this->signature->readSignature(array('idsignature' => $a->signature_idsignature));
                if($signature->row()->type == 1){
                    $c->agreement->sign = $a->sign;
                }
                array_push($signatures, 
                    array(
                        'name' => $signature->row()->name, 
                        'type' => $signature->row()->type, 
                        'font' => $signature->row()->font, 
                        'sign' => $a->sign, 
                        'idaccount' => $signature->row()->account_idaccount
                        )
                );
            endforeach;
            $c->signatures = $signatures;
            $resp[] = $c;
        endforeach;
        echo json_encode($resp);
    }

    public function getAgreement($id){
        $this->load->model('agreement');
        $where = array('inscribe_idinscribe' => $id);
        $resp = $this->agreement->readAgreement($where);
        echo json_encode($resp->row());
    }

    public function contractCancel($id){
        $this->load->model('inscribe');
        $resp = ($this->inscribe->updateStatusInscribe($id,3)) ? array('msg' => 'Contrato cancelado', 'icon' => 'success') : array('msg' => 'Contrato não pode ser cancelado', 'icon' => 'error');
        echo json_encode($resp);
    }

    public function contractUndo($id){
        $this->load->model('inscribe');
        $resp = ($this->inscribe->updateStatusInscribe($id, 2)) ? array('msg' => 'Contrato restaurado', 'icon' => 'success') : array('msg' => 'Contrato não pode ser restaurado', 'icon' => 'error');
        echo json_encode($resp);
    }

    public function removeContract($id){
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('graduation_committe');
        $this->load->model('event');
        $this->load->model('signature');
        $this->load->model('inscribe');
        $this->load->model('tax');

        $signatureID = array();
        $agreementID = array();

        $where = array(
            'inscribe_idinscribe' => $id
        );
        $agreementHasSignature = $this->agreement->readAgreementHasSignature($where);

        foreach($agreementHasSignature->result() as $ahs):
            $signatureID[] = $ahs->signature_idsignature;
            if(!in_array($ahs->agreement_idagreement, $agreementID)){
                $agreementID[] = $ahs->agreement_idagreement;
            } 
        endforeach;

        $this->agreement->deleteAgreementHasSignature(array('inscribe_idinscribe' => $id));

        foreach($signatureID as $sign):
            $signature = $this->signature->readSignature(array('idsignature' => $sign));
            $signature = $signature->row();
            if($signature->type == 1){
                $this->signature->deleteContractorSignature($signature->idsignature);
            } else{
                $this->signature->verifyInUse($signature->idsignature);
            }
        endforeach;

        foreach($agreementID as $agree):
            $this->agreement->deleteAgreement(array('idagreement' => $agree));
        endforeach;

        $whereEvent = array('inscribe_idinscribe' => $id);
        $this->event->deleteEvent($whereEvent);

        $whereVariantTax = array('inscribe_idinscribe' => $id);
        $this->tax->deleteVariantTax($whereVariantTax);

        $whereEngaged = array('inscribe_idinscribe' => $id);
        $engaged = $this->engaged->readEngaged($whereEngaged);
        if($engaged->num_rows() != 0){
            $this->engaged->deleteEngaged($whereEngaged);
        }

        $whereGraduationCommitte = array('inscribe_idinscribe' => $id);
        $graduationCommitte = $this->graduation_committe->readCommitte($whereGraduationCommitte);
        if($graduationCommitte->num_rows() != 0){
            $this->graduation_committe->deleteCommitte($whereGraduationCommitte);
        }

       if($this->inscribe->updateStatusInscribe($id, 0)){
           $resp = array('msg' => 'Contrato removido com sucesso. A inscrição foi mantida.', 'icon' => 'success');
       } else{
           $resp = array('msg' => 'Contrato não pode ser removido.', 'icon' => 'error');
       }

        echo json_encode($resp);
    }

    public function deleteContract($id){
        $this->load->model('agreement');
        $this->load->model('engaged');
        $this->load->model('graduation_committe');
        $this->load->model('event');
        $this->load->model('signature');
        $this->load->model('inscribe');
        $this->load->model('tax');
        $this->load->model('account');

        $signatureID = array();
        $agreementID = array();

        $where = array(
            'inscribe_idinscribe' => $id
        );
        $agreementHasSignature = $this->agreement->readAgreementHasSignature($where);

        foreach($agreementHasSignature->result() as $ahs):
            $signatureID[] = $ahs->signature_idsignature;
            if(!in_array($ahs->agreement_idagreement, $agreementID)){
                $agreementID[] = $ahs->agreement_idagreement;
            } 
        endforeach;

        $this->agreement->deleteAgreementHasSignature(array('inscribe_idinscribe' => $id));

        foreach($signatureID as $sign):
            $signature = $this->signature->readSignature(array('idsignature' => $sign));
            $signature = $signature->row();
            if($signature->type == 1){
                $this->signature->deleteContractorSignature($signature->idsignature);
            } else{
                $this->signature->verifyInUse($signature->idsignature);
            }
        endforeach;

        foreach($agreementID as $agree):
            $this->agreement->deleteAgreement(array('idagreement' => $agree));
        endforeach;

        $whereEvent = array('inscribe_idinscribe' => $id);
        $this->event->deleteEvent($whereEvent);

        $whereVariantTax = array('inscribe_idinscribe' => $id);
        $this->tax->deleteVariantTax($whereVariantTax);

        $whereEngaged = array('inscribe_idinscribe' => $id);
        $engaged = $this->engaged->readEngaged($whereEngaged);
        if($engaged->num_rows() != 0){
            $this->engaged->deleteEngaged($whereEngaged);
        }

        $whereGraduationCommitte = array('inscribe_idinscribe' => $id);
        $graduationCommitte = $this->graduation_committe->readCommitte($whereGraduationCommitte);
        if($graduationCommitte->num_rows() != 0){
            $this->graduation_committe->deleteCommitte($whereGraduationCommitte);
        }

        $idAccount = $this->inscribe->returnAccountIdByInscribeId($id);

        $whereComposition = array('inscribe_idinscribe' => $id);
        $this->inscribe->deleteComposition($whereComposition);        

        $whereInscribe = array('idinscribe' => $id);
        $respInscribe = $this->inscribe->deleteInscribe($whereInscribe);
        $this->account->deleteAccountByID($idAccount);
        if($respInscribe['icon'] == 'success'){
            $resp = array('msg' => 'Contrato excluído com sucesso', 'icon' => 'success');
        } else if($respInscribe['icon'] == 'error'){
            $resp = array('msg' => 'Contrato não pode ser excluído', 'icon' => 'error');
        }
        echo json_encode($resp);
    }

    //Assinatura
    public function createSignature(){
        $this->load->model('signature');
        echo json_encode($this->signature->createSignature());
    }

    public function getSignature(){
        $this->load->model('signature');
        $signature = $this->signature->readSignature();
        echo json_encode($signature->result());
    }

    public function updateSignature($id){
        $this->load->model('signature');
        echo json_encode($this->signature->updateSignature($id));
    }

    public function deleteSignature($id){
        $this->load->model('signature');
        echo json_encode($this->signature->deleteSignature($id));
    }

    public function verifySignCustomers($id){
        $this->load->model('signature');
        $this->load->model('agreement');

        $where = array('account_idaccount' => $id);
        $signature = $this->signature->readSignature($where);
        if($signature->num_rows() != 0){
            $ahs = $this->agreement->readAgreementHasSignature(array('signature_idsignature' => $signature->row()->idsignature));
            $resp = ($ahs->row()->sign == 1) ? true : false;
        } else{
            $resp = false;
        }
        echo json_encode($resp);
    }

    public function signWithPassword(){
        $this->load->model('account');
        $this->load->model('signature');

        $resp = $this->account->signWithPassword();

        if($resp){
            $where = array('account_idaccount' => $_POST['idaccount']);
            $signature = $this->signature->readSignature($where);
            $this->signature->markAsSigned($signature->row()->idsignature);
        }

        echo json_encode($resp);
    }

    public function teste($id){
        $this->load->model('inscribe');
        echo json_encode($this->inscribe->calculateTotalValueContract($id));
    }
}