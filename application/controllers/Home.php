<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("superadmin_model");
	}

	public function index(){

		$data['title'] = 'Rosalina Dashboard';
		
		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('home/index');
		$this->load->view('home/footer-content');
		$this->load->view('home/footer', $data);

		
	}
}