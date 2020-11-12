<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data['title'] 	= 'Rosalina | Role Management';
		$data['role']	= $this->db->get("tbl_user_role");

		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('role/index', $data);
		$this->load->view('home/footer-content');
		$this->load->view('superadmin/structure/footer');
	}

	public function detail($id){
		$data['title'] 	= 'Rosalina | Role Detail';
		$data['menu']	= $this->db->get('tbl_user_menu');
		$data['role']	= $this->db->get_where('tbl_user_role', ['ID' => $id])->row();
		$data['access'] = $this->db->get_where("tbl_user_access_menu", ['id_role' => $id])->row();;
		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('role/detail', $data);
		$this->load->view('home/footer-content');
		$this->load->view('superadmin/structure/footer');
	}

	public function detailProses(){
		$akses = $this->input->post('access');
		$button = $this->input->post('send');
		$name = $this->input->post('nameRole');
		$id = $this->input->post('idRole');

		if ($button == 'cancel') {
			redirect('role');
		} else {
			$this->db->set('id_menu', json_encode($akses));			
			$this->db->where("id_role", $id);
			$this->db->update('tbl_user_access_menu');

			$this->db->set('role', $name);
			$this->db->where('ID', $id);
			$this->db->update('tbl_user_role');

			redirect('role');
		}
	}
}