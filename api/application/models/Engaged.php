<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Engaged extends Crud{
    public $groom_name;
    public $groom_address;
    public $groom_phone;
    public $groom_mobile;
    public $groom_photo;
    public $groom_cpf;
    public $groom_rg;
    public $groom_birthdate;
    public $groom_email;
    public $groom_responsible_for;
    public $bride_name;
    public $bride_address;
    public $bride_phone;
    public $bride_mobile;
    public $bride_photo;
    public $bride_cpf;
    public $bride_rg;
    public $bride_birthdate;
    public $bride_email;
    public $bride_responsible_for;
    public $inscribe_idinscribe;

    public function createEngaged(){
        $this->groom_name = $_POST['groom_name'];
        $this->groom_address = $_POST['groom_address'];
        $this->groom_phone = $_POST['groom_phone'];
        $this->groom_mobile = $_POST['groom_mobile'];
        $this->groom_photo = (isset($_POST['groom_photo'])) ? $_POST['groom_photo'] : null;
        $this->groom_cpf = $_POST['groom_cpf'];
        $this->groom_rg = $_POST['groom_rg'];
        $this->groom_birthdate = $_POST['groom_birthdate'];
        $this->groom_email = $_POST['groom_email'];
        $this->groom_responsible_for = ($_POST['groom_responsible_for'] == "true") ? 1 : 0;
        $this->bride_name = $_POST['bride_name'];
        $this->bride_address = $_POST['bride_address'];
        $this->bride_phone = $_POST['bride_phone'];
        $this->bride_mobile = $_POST['bride_mobile'];
        $this->bride_photo = (isset($_POST['bride_photo'])) ? $_POST['bride_photo'] : null;
        $this->bride_cpf = $_POST['bride_cpf'];
        $this->bride_rg = $_POST['bride_rg'];
        $this->bride_birthdate = $_POST['bride_birthdate'];
        $this->bride_email = $_POST['bride_email'];
        $this->bride_responsible_for = ($_POST['bride_responsible_for'] == "true") ? 1 : 0;
        $this->inscribe_idinscribe = $_POST['idinscribe'];
        $data = $this;

        return $this->create('engaged', $data);
    }

    public function createWeddingServices(){
        $data = array(
            'companyname' => $_POST['companyname'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'contactname' => $_POST['contactname'],
            'engaged_idengaged' => $_POST['engaged_idengaged']
        );

        return $this->create('wedding_services', $data);
    }

    public function createSocialNetworks(){
        $data = [
            'name' => $_POST['name'],
            'engaged' => $_POST['engaged'],
            'icon' => $_POST['icon'],
            'link' => $_POST['link'],
            'engaged_idengaged' => $_POST['engaged_idengaged']
        ];

        return $this->create('social_networks', $data);
    }

    public function createEngagedHasSignature($data){
        return $this->create('engaged_has_signature', $data);
    }

    public function readEngaged($where = null){
        if(!is_null($where)){
            return $this->ready('engaged', $where);
        } else{
            return $this->ready('engaged');
        }
    }

    public function readWeddingServices($where = null){
        if(!is_null($where)){
            return $this->ready('wedding_services', $where);
        } else{
            return $this->ready('wedding_services');
        }
    }

    public function readSocialNetworks($where = null){
        return (!is_null($where)) ? $this->ready('social_networks', $where) : $this->ready('social_networks');
    }

    public function readEngagedHasSignature($where = null){
        return (!is_null($where)) ? $this->ready('engaged_has_signature', $where) : $this->ready('engaged_has_signature');
    }

    public function updateEngaged($where){
        $this->groom_name = $_POST['groom_name'];
        $this->groom_address = $_POST['groom_address'];
        $this->groom_phone = $_POST['groom_phone'];
        $this->groom_mobile = $_POST['groom_mobile'];
        $this->groom_photo = (isset($_POST['groom_photo'])) ? $_POST['groom_photo'] : null;
        $this->groom_cpf = $_POST['groom_cpf'];
        $this->groom_rg = $_POST['groom_rg'];
        $this->groom_birthdate = $_POST['groom_birthdate'];
        $this->groom_email = $_POST['groom_email'];
        $this->groom_responsible_for = ($_POST['groom_responsible_for'] == "true" ? 1 : 0);
        $this->bride_name = $_POST['bride_name'];
        $this->bride_address = $_POST['bride_address'];
        $this->bride_phone = $_POST['bride_phone'];
        $this->bride_mobile = $_POST['bride_mobile'];
        $this->bride_photo = (isset($_POST['bride_photo'])) ? $_POST['bride_photo'] : null;
        $this->bride_cpf = $_POST['bride_cpf'];
        $this->bride_rg = $_POST['bride_rg'];
        $this->bride_birthdate = $_POST['bride_birthdate'];
        $this->bride_email = $_POST['bride_email'];
        $this->bride_responsible_for = ($_POST['bride_responsible_for'] == "true") ? 1 : 0;
        $this->inscribe_idinscribe = $_POST['idinscribe'];
        $data = $this;

        return $this->update('engaged', $where, $data);
    }

    public function updateWeddingServices($where){
        $data = array(
            'companyname' => $_POST['companyname'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'contactname' => $_POST['contactname'],
            'engaged_idengaged' => $_POST['engaged_idengaged']
        );

        return $this->update('wedding_services',$where, $data);
    }

    public function updateSocialNetworks($where){
        $data = [
            'name' => $_POST['name'],
            'engaged' => $_POST['engaged'],
            'icon' => $_POST['icon'],
            'link' => $_POST['link'],
            'engaged_idengaged' => $_POST['engaged_idengaged']
        ];

        return $this->update('social_networks', $where, $data);
    }

    public function deleteEngaged($where){
        return $this->delete('engaged', $where);
    }

    public function deleteWeddingService($where){
        return $this->delete('wedding_services', $where);
    }

    public function deleteSocialNetworks($where){
        return $this->delete('social_networks', $where);
    }

    public function deleteEngagedHasSignature($where){
        return $this->delete('engaged_has_signature', $where);
    }
}