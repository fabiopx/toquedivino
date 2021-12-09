<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

	
	public function index()
	{
        $data['title'] = "Toque Divino - Área do Cliente";
		$this->load->view('frontend/customers', $data);
	}
}
