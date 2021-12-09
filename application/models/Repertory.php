<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Repertory extends Crud{
    public $moments_idmoments;
    public $repertory_idrepertory;
    public $music_idmusic;
    public $sequence;

    public function initRepertory(){
        $data = array('inscribe_idinscribe' => $_POST['idinscribe']);
        return $this->create('repertory', $data);
    }

    public function addRepertoryItem(){
        $this->moments_idmoments = $_POST['idmoments'];
        $this->repertory_idrepertory = $_POST['idrepertory'];
        $this->music_idmusic = $_POST['idmusic'];
        $this->sequence = $_POST['sequence'];
        $data = $this;

        return $this->create('moments_has_repertory', $data);
    }

    public function readRepertory($id){
        $where = array('inscribe_idinscribe' => $id);
        return $this->ready('repertory', $where);
    }

    public function readMomentsHasRepertory($id){
        $where = array('repertory_idrepertory' => $id);
        return $this->ready('moments_has_repertory', $where);
    }

    public function delRepertoryItem($where){
        return $this->delete('moments_has_repertory', $where);
    }
}