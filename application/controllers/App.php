<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	
	public function index()
	{
        $data['title'] = "Toque Divino - App";
		$this->load->view('frontend/app', $data);
	}
}
