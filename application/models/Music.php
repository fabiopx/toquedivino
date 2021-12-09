<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Music extends Crud{
    public $name;
    public $url;
    public $status;

    public function createMusic(){
        $this->name = $_POST['name'];
        $this->url = $_POST['url'];
        $this->status = $_POST['status'];
        $data = $this;

        if($this->create('music', $data)){
            $moments = json_decode($_POST['moments']);
            $id = $this->db->insert_id();
            $this->createMusicHasMoments($id, $moments);
            return $this->response('success', 'Música incluída com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function createMusicHasMoments($id, $moments){
        if(is_array($moments)){
            foreach($moments as $m){
                $data = array('moments_idmoments' => $m, 'music_idmusic' => $id);
                $this->db->insert('moments_has_music', $data);
            }
        } else{
            exit;
        }
    }

    public function readMusic($where = null){
        if(!is_null($where)){
            return $this->ready('music', $where);
        } else{
            return $this->ready('music');
        }
    }

    public function readMusicHasMoments($id){
        $where = array('music_idmusic' => $id);
        return $this->ready('moments_has_music', $where);
    }

    public function updateMusic($id){
        $this->name = $_POST['name'];
        $this->url = $_POST['url'];
        $this->status = $_POST['status'];
        $data = $this;

        $moments = json_decode($_POST['moments']);
        $mhm = array();

        $musHasMom = $this->readMusicHasMoments($id);
        foreach($musHasMom->result() as $m):
            $mhm[] = $m->moments_idmoments;
        endforeach;

        $add = array_diff($moments, $mhm);
        $del = array_diff($mhm, $moments);
        $this->createMusicHasMoments($id, $add);
        $this->deleteMusicHasMoments($id, $del);

        $where = array('idmusic' => $id);
        if($this->update('music', $where, $data)){
            return $this->response('success', 'Música atualizada com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteMusic($id){
        $where = array('music_idmusic' => $id);
        $this->delete('moments_has_music', $where);
        $where = array('idmusic' => $id);
        $response = $this->delete('music', $where);
        if($response !== false){
            return $this->response('sucess', 'Música excluída com sucesso');
        } else{
            $this->db->db_debug = false;
            $error = $this->db->error();
            return $this->response('error', $error['message']);
        }
    }

    public function deleteMusicHasMoments($id, $data = null){
        foreach($data as $d):
            $where = array('music_idmusic' => $id, 'moments_idmoments' => $d);
            $this->delete('moments_has_music', $where);
        endforeach;
    }
}