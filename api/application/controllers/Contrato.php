<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contrato extends CI_Controller {

	
	public function index()
	{
        $data['title'] = "Toque Divino - Contrato";
		$this->load->view('backend/contrato', $data);
	}
}