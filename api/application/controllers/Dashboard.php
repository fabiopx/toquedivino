<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	
	public function index()
	{
        $data['title'] = "Toque Divino - Dashboard";
		$data['user'] = 'Administrador';
		$this->load->view('backend/dashboard', $data);
	}
}
