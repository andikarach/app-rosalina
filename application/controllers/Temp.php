<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Controller untuk proses data sementara
class Temp extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('spd_model');
	}

	public function index(){

	}

	//Dari C-spd->create_spd();
	public function v_cek_select2(){
		$data['kendaraan'] 		= $this->db->get('tbl_spd_kendaraan');
		$this->load->view('test/cek_select2', $data);
		
	}

	public function cekproses(){
		$x = json_encode($this->input->post('x'));
		var_dump(json_decode($x));
	}

	//Dari C-login->index();
	public function v_login(){
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Rosalina Login';
			$this->load->view('login/header', $data);
			$this->load->view('login/index');
			$this->load->view('login/footer');
		} else {
			$this->prc_login();
		}
	}

	private function prc_login(){
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');

		$cek = $this->db->get_where('tbl_user_login', ['email' => $email]);
			if ($cek->num_rows() > 0) {
				$user = [
					'id_user' => $cek->row()->ID,
					'id_role' => $cek->row()->id_role,
					'name'	=> $cek->row()->first_name.' '.$cek->row()->last_name,
					'email'	=> $cek->row()->email,
					'site'	=> 'A001',
					'business_unit' => 'Head Office'
				];
				$this->session->set_userdata($user);
				$this->db->set("last_login", time());
				$this->db->where('ID', $cek->row()->ID);
				$this->db->update('tbl_user_login');
			} else {
				$insert = [
					'email' => $email,
					'first_name' => 'admin',
					'last_name' => 'admin',
					'id_role'	=> 2,
					'last_login' => time(),
					'created_at' => time()
				];

				$this->db->insert('tbl_user_login', $insert);
				$user = $this->db->get_where('tbl_user_login', ['ID' => $this->db->insert_id()])->row();

				$ses = [
					'id_user' => $user->ID,
					'id_role' => $user->id_role,
					'name'	=> $user->first_name.' '.$user->last_name,
					'email'	=> $user->email,
					'site'	=> $bu->prefix,
					'business_unit' => $bu->business_unit
				];

				$this->session->set_userdata($ses);
			}
			redirect('home');
	}

	//dari c-ga->allocationDriver()
	public function v_allocation(){

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
