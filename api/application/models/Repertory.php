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
        $this->sequence = $this->nextSequence($_POST['idrepertory']);
        $data = $this;

        return $this->create('moments_has_repertory', $data);
    }

    public function readRepertory($id){
        $where = array('inscribe_idinscribe' => $id);
        return $this->ready('repertory', $where);
    }

    public function readMomentsHasRepertory($id){
        $where = array('repertory_idrepertory' => $id);
        $this->db->order_by('sequence');
        return $this->ready('moments_has_repertory', $where);
    }

    public function readMaxSequence($id){
        $this->db->select_max('sequence');
        $where = array('repertory_idrepertory' => $id);
        return $this->ready('moments_has_repertory', $where);
    }

    public function readSequenceToUpdate($repertory, $sequence){
        $where = array('repertory_idrepertory' => $repertory, 'sequence' => $sequence);
        return $this->ready('moments_has_repertory', $where);
    }

    public function updateSequence($id, $sequence){
        $where = array('id' => $id);
        $data = array('sequence' => $sequence);
        return $this->update('moments_has_repertory', $where, $data);
    }

    public function nextSequence($id){
        $sequence = $this->readMaxSequence($id);

        $seq = $sequence->row();

        $seq = is_null($seq->sequence) ? 0 : $seq->sequence;

        return $seq + 1;
    }
    
    public function orderSequence($sequence){
        $this->db->set('sequence', 'sequence-1', FALSE);
        $this->db->where('sequence >', $sequence);
        $this->db->update('moments_has_repertory');

    }

    public function delRepertoryItem($where){
        return $this->delete('moments_has_repertory', $where);
    }
}