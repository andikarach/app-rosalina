<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){

	}

	public function profile(){

	}

	public function changePassword(){
		
	}

	public function changeSite(){
		$data['title'] = 'Rosalina | Change Site';

		$user = [
			'email' => $this->session->userdata("email"),
			'auth' 	=> $this->db->auth
		];

		$data['site'] = business_unit($user)->data;

		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('user/change-site', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer');
	}

	public function proc_site(){
		$site = $this->input->post('site');
		$button = $this->input->post('save');

		if ($button != 'save') {
			redirect('home');
		} else {
			$this->session->set_userdata(['site' => $site]);
			redirect('user/changeSite');
		}
	}
}