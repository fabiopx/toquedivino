<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS'); 
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Repertories extends CI_Controller{
    public function index(){
        echo json_encode('API :: Toque Divino :: Repertory');
    }

    public function getCustomers($id){
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

    public function start(){
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

    public function addItem(){
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

    public function getCustomersID($id){
        $this->load->model('repertory');
        $repertory = $this->repertory->readRepertory($id);
        $repertory = ($repertory->row()) ? $repertory->row()->idrepertory : $repertory->row();
        echo json_encode($repertory);
    }

    public function deleteItem($id, $sequence){
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
}