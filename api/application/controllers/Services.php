<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, content-type, X-Token, x-token');

class Services extends CI_Controller
{
    public function index()
    {
        echo json_encode('API :: Toque Divino :: Service');
    }

    public function create()
    {
        $this->load->model('service');
        echo json_encode($this->service->createService());
    }

    public function get($id = null)
    {
        $this->load->model('service');
        $this->load->model('tax');
        (is_null($id)) ? $resp = array() : $resp = '';
        $data = array();
        $where = (!is_null($id)) ? array('idservice' => $id) : $id;
        $services = $this->service->readService($where);
        if (is_null($id)) {
            foreach ($services->result() as $service) {
                $service->status = ($service->status == 1) ? true : false;
                $serviceHasTax = $this->service->readServiceHasTax($service->idservice);
                if ($serviceHasTax->num_rows() != 0) {
                    foreach ($serviceHasTax->result() as $sht) {
                        $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                        array_push($data, $tax->row());
                    }
                    $service->taxas = $data;
                    $data = array();
                } else {
                    $service->taxas = null;
                }

                array_push($resp, $service);
            }
        } else {
            $services = $services->row();
            $services->status = ($services->status == 1) ? true : false;
            $serviceHasTax = $this->service->readServiceHasTax($services->idservice);
            if ($serviceHasTax->num_rows() != 0) {
                foreach ($serviceHasTax->result() as $sht) {
                    $tax = $this->tax->readTax(array('idtax' => $sht->tax_idtax));
                    array_push($data, $tax->row());
                }
                $services->taxas = $data;
                $data = array();
            } else {
                $services->taxas = null;
            }
            $resp = $services;
        }

        echo json_encode($resp);
    }

    public function update($id)
    {
        $this->load->model('service');
        echo json_encode($this->service->updateService($id));
    }

    public function delete($id)
    {
        $this->load->model('service');
        echo json_encode($this->service->deleteService($id));
    }
}
