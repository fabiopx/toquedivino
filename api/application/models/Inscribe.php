<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Inscribe extends Crud{
    public $datetime;
    public $accountable;
    public $birthdate;
    public $phone;
    public $mobile;
    public $address;
    public $cpf;
    public $rg;
    public $cnpj;
    public $status;
    public $account_idaccount;

    public function createInscribe(){
        $this->datetime = $_POST['datetime'];
        $this->accountable = $_POST['accountable'];
        $this->birthdate = $_POST['birthdate'];
        $this->phone = $_POST['phone'];
        $this->mobile = $_POST['mobile'];
        $this->address = $_POST['address'];
        $this->cpf = $_POST['cpf'];
        $this->rg = $_POST['rg'];
        $this->status = 0;
        $data = $this;

        $dAccount = array(
            'name' => $data->accountable,
            'email' => $_POST['email'],
            'password' => password_generate(),
            'status' => 1,
            'phone' => $data->phone,
            'pin' => code_generate(),
            'access' => 2
        );

        
        if($this->create('account', $dAccount)){
            $idAccount = $this->db->insert_id();
            $data->account_idaccount = $idAccount;
            
            $dSign = array(
                'name' => $data->accountable,
                'type' => 1,
                'font' => 'gf-fuggles',
                'status' => 1,
                'inuse' => 0,
                'account_idaccount' => $idAccount
            );
            $this->create('signature', $dSign);

            if($this->create('inscribe', $data)){
                $idInscribe = $this->db->insert_id();
                if(isset($_POST['flag'])){
                    $dPreInscribe = array(
                        'pre_inscribe_idpre_inscribe' => $_POST['idpre_inscribe'],
                        'inscribe_idinscribe' => $idInscribe
                    );

                    $this->create('pre_inscribe_has_inscribe', $dPreInscribe);
                    $this->update('pre_inscribe', array('idpre_inscribe' => $_POST['idpre_inscribe']), array('flag' => 1));
                }
                $dComposition = array(
                    'service_idservice' => $_POST['service_idservice'],
                    'formation_idformation' => $_POST['formation_idformation'],
                    'inscribe_idinscribe' => $idInscribe
                );
                if($this->create('composition', $dComposition)){
                    return array('icon' => 'success', 'msg'=>'Inscrição concluída com sucesso', 'idAccount' => $idAccount, 'idInscribe' => $idInscribe);
                } else{
                    return $this->response('error', 'A inscrição não pode ser concluída');
                }
            } else{
                return $this->response('error', 'Cadastro interrompido: inscrição não pode ser efetivada');
            }
        } else{
            return $this->response('error', 'Cadastro interrompido: conta não pode ser criada');
        }
        
    }

    public function readInscribe($where = null){
        if(!is_null($where)){
            return $this->ready('inscribe', $where);
        } else{
            return $this->ready('inscribe');
        }
    }

    public function readComposition($id){
        $where = array('inscribe_idinscribe' => $id);
        return $this->ready('composition', $where);
    }

    public function returnAccountIdByInscribeId($id){
        $where = array('idinscribe' => $id);
        $inscribe = $this->ready('inscribe', $where);
        $inscribe = $inscribe->row();
        return $inscribe->account_idaccount;
    }

    public function calculateValueContract($id){
        $taxSoma = 0;

        $composition = $this->readComposition($id);
        $serviceId = $composition->row()->service_idservice;
        $formationId = $composition->row()->formation_idformation;

        $formation = $this->ready('formation', array('idformation' => $formationId));
        $formationValue = $formation->row()->price;

        $serviceHasTax = $this->ready('service_has_tax', array('service_idservice' => $serviceId));
        foreach($serviceHasTax->result() as $sht):
            $tx = $this->ready('tax', array('idtax' => $sht->tax_idtax));
            if($tx->row()->type == 2){
                $vTax = $this->ready('variant_tax', array('tax_idtax' => $sht->tax_idtax));
                $taxSoma = $taxSoma + ($tx->row()->value * $vTax->row()->value);
            } else{
                $taxSoma = $taxSoma + $tx->row()->value;
            }
        endforeach;
        
        return $formationValue + $taxSoma;

    }

    public function updateInscribe($id){
        $this->datetime = $_POST['datetime'];
        $this->accountable = $_POST['accountable'];
        $this->birthdate = $_POST['birthdate'];
        $this->phone = $_POST['phone'];
        $this->mobile = $_POST['mobile'];
        $this->address = $_POST['address'];
        $this->cpf = $_POST['cpf'];
        $this->rg = $_POST['rg'];
        $this->cnpj = null;
        $this->status = $_POST['status'];
        $this->account_idaccount = $_POST['account_idaccount'];
        $data = $this;

        $dComposition = array(
            'service_idservice' => $_POST['service_idservice'],
            'formation_idformation' => $_POST['formation_idformation'],
            'inscribe_idinscribe' => $id
        );

        $this->update('composition', array('inscribe_idinscribe' => $id), $dComposition);

        $this->update('account', array('idaccount' => $data->account_idaccount), array('name' => $data->accountable, 'email' => $_POST['email']));

        if($this->update('inscribe', array('idinscribe' => $id), $data)){
            return $this->response('success', 'Cadastro editado com sucesso');
        } else{
            return $this->response('error', 'Cadastro não pode ser editado');
        }
    }

    public function updateInscribeCustomers($id){
        $data = array(
            'accountable' => $_POST['accountable'],
            'birthdate' => $_POST['birthdate'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'cpf' => $_POST['cpf'],
            'rg' => $_POST['rg']
        );

        $dComposition = array(
            'service_idservice' => $_POST['idservice'],
            'formation_idformation' => $_POST['idformation'],
            'inscribe_idinscribe' => $id
        );

        $this->update('composition', array('inscribe_idinscribe' => $id), $dComposition);

        if($this->update('inscribe', array('idinscribe' => $id), $data)){
            return $this->response('success', 'Cadastro editado com sucesso');
        } else{
            return $this->response('error', 'Cadastro não pode ser editado');
        }
    }

    public function updateStatusInscribe($id, $status){
        $where = array('idinscribe' => $id);
        return $this->update('inscribe', $where, array('status' => $status));
    }

    public function deleteInscribe($where){
        $response = $this->delete('inscribe', $where);
        if($response !== false){
            return  $this->response('success', 'Inscrição excluída com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteComposition($where){
        $this->delete('composition', $where);
    }
}