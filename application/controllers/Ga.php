<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ga extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("superadmin_model");
		$this->load->model("spd_model");
	}

	public function index(){

		$data['title'] = 'Rosalina Dashboard';
		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('home/index');
		$this->load->view('home/footer-content');
		$this->load->view('home/footer');
		
	}

	public function all(){
		$data['title'] = 'List SPD';
		$data['spd'] = $this->db->get('tbl_spd')->result();

		$this->load->view('spd/structure/header-list', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('ga/list', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer-list');
	}

	public function driver(){
		$data['title'] = 'List Driver';
		$data['driver'] = $this->db->get('tbl_mbl_driver');

		$this->load->view('spd/structure/header-list', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('ga/driver', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer-list');	
	}

	public function addDriver(){
		$driver = [
			'name' => $this->input->post("name_driver"),
			'contact' => $this->input->post("contact"),
			'status' => $this->input->post("active"),
			'created_at' => time()
		];

		$this->db->insert('tbl_mbl_driver', $driver);
		redirect('ga/driver');
	}

	public function kendaraan(){
		$data['title'] = 'List Kendaraan';
		$data['mobil'] = $this->spd_model->getCarLocation('tbl_mbl');
		$data['location'] = $this->db->get('tbl_mbl_location');

		$this->load->view('spd/structure/header-list', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('ga/kendaraan', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer-list');	
	}

	public function addKendaraan(){
		$mbl = [
			'name_mbl' => $this->input->post('name_mobil'),
			'id_mbl_location' => $this->input->post('location'), 
			'nmr_plat' => $this->input->post('plat'),
			'status' => $this->input->post('active'),
			'created_at' => time()
		];

		$this->db->insert('tbl_mbl', $mbl);
		redirect('ga/kendaraan');
	}

	public function allocationDriver(){
		$data['title'] = 'Alocation Driver';
		$data['spd'] = $this->db->get('tbl_spd')->result();
		$data['location'] = $this->db->get('tbl_mbl_location');

		$this->load->view('spd/structure/header-list', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('ga/allocation', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer-list');	
	}

}