<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SuperAdmin extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model("superadmin_model");
	}

	public function index(){
		$data['title'] = 'Rosalina Dashboard';
		
		$this->load->view('superadmin/structure/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('superadmin/index');
		$this->load->view('home/footer-content');
		$this->load->view('superadmin/structure/footer');
	}

	public function tes(){
		$menu = $this->superadmin_model->getMenu($this->session->userdata('id_role'))->result();
		var_dump($menu);

	}
}