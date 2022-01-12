<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'models/Crud.php');

class Event extends Crud{
    public $name;
    public $date;
    public $time;
    public $address;
    public $inscribe_idinscribe;

    public function createEvent(){
        $this->name = $_POST['eventname'];
        $this->date = $_POST['eventdate'];
        $this->time = $_POST['eventtime'];
        $this->address = $_POST['eventaddress'];
        $this->inscribe_idinscribe = $_POST['idinscribe'];
        $data = $this;

        return $this->create('event', $data);
    }

    public function createEventApp($data){
        return $this->create('event', $data);
    }

    public function readEvent($where = null){
        if(!is_null($where)){
            return $this->ready('event', $where);
        } else{
            return $this->ready('event');
        }
    }

    public function deleteEvent($where){
        return $this->delete('event', $where);
    }

    public function updateEvent($where){
        $this->name = $_POST['eventname'];
        $this->date = $_POST['eventdate'];
        $this->time = $_POST['eventtime'];
        $this->address = $_POST['eventaddress'];
        $this->inscribe_idinscribe = $_POST['idinscribe'];
        $data = $this;

        return $this->update('event', $where, $data);
    }
}