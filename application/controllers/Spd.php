<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spd extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('spd_model');

	}

	public function index(){
	
		
	}

	public function create_spd(){
		$data['title'] 			= 'Create New SPD';
		//$data['project'] 		= get_data('project')->data;
		//$data['cost_center'] 	= get_data('cost')->data;
		//$data['users'] 			= get_data('users')->data;

		$data['driver'] 		= $this->db->get('tbl_mbl_driver');
		$data['kendaraan'] 		= $this->db->get('tbl_spd_kendaraan');
		$data['mobil'] 			= $this->spd_model->getCarLocation();
		$data['swal']			= null;
		if (!$this->session->userdata('site')) {
			$data['swal'] = 'swal';
		}

		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('spd/create-spd', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer');
		
	}

	public function proses_create(){
		if (!$this->session->userdata('site')) {
			redirect('spd/create_spd');
		}
		$save = $this->input->post('save');
		if ($save == 'submit') {
			$save = 1;
		} else {
			$save = 0;
		}

		$create = $this->input->post('create');
		$reqby = $this->input->post('reqby');
		$reqfor = $this->input->post('reqfor');
		
		$pickup = $this->input->post('pickup');
		$lat1 = $this->input->post('lat1');
		$lng1 = $this->input->post('lng1');
		$destination = $this->input->post('destination');
		$lat2 = $this->input->post('lat2');
		$lng2 = $this->input->post('lng2');
		$jarak = $this->input->post('jarak');

		$agenda = $this->input->post('agenda');
		$timedeparture = $this->input->post('timedeparture');
		$expectdate = $this->input->post('expectdate');
		$status = $this->input->post('status');
		$project = $this->input->post('project');
		$cost_center = $this->input->post('cost_center');
		
		$remarks = $this->input->post('remarks');
		$nomor_spd =  $this->generate_number();

		$place = [
			'name_pickup' => $pickup ,
			'kordinat_pickup'=> json_encode(['lat1' => $lat1, 'lng1' => $lng1]),
			'name_destination'=> $destination,
			'kordinat_destination'=> json_encode(['lat2' => $lat2, 'lng1' => $lng2]),
			'jarak'=> $jarak,
			'timestamp' => time(),
			'driver_pickup' => $this->input->post('driver_pickup'),
			'mobil_pickup' => $this->input->post('mobil_pickup'),
			'driver_destination' => $this->input->post('driver_destination'),
			'mobil_destination' => $this->input->post('mobil_destination')
		];

		$this->db->insert('tbl_place', $place);
		$id_place = $this->db->insert_id();


		$spd = [
			'nomor_spd' => $this->generate_number(),
			'request_by' => $this->session->userdata('id_user'),
			'request_for' => $this->session->userdata('id_user'),
			'id_place' => $id_place,
			'departure_date' => $timedeparture,
			'expected_date' => $expectdate,
			'agenda' => $agenda,
			'remarks' => $remarks,
			'attachment' => null,
			'project' => $project,
			'cost_center' => $cost_center,
			'approve' => 1,
			'status' => 1,
			'created_at' => time(),
			'id_bu' => $this->session->userdata('site'),
			'draft' => $save,
		];

		$this->db->insert('tbl_spd', $spd);
		$id_spd = $this->db->insert_id();
		$attach = $this->upload_file($id_spd);
		$this->spd_model->update_lampiran($id_spd, $attach);


		redirect('home');
	}

	private function generate_number(){
		
		$kode1 = 'SPD';
		$kode2 = $this->session->userdata('site');
		$kode3 = date('y');

		$number_increment = $this->db->query("SELECT RIGHT(nomor_spd,4)+1 as nomor FROM tbl_spd WHERE id_bu = '$kode2' order by ID desc limit 1")->row_array();
		$number_generate  ='';
		if (strlen($number_increment['nomor'])==1){
			$number_generate  = $kode1.'.'.$kode2.'.'.$kode3.'000'.$number_increment['nomor'];
		}else if (strlen($number_increment['nomor'])==2){
			$number_generate  = $kode1.'.'.$kode2.'.'.$kode3.'00'.$number_increment['nomor'];
		}else if (strlen($number_increment['nomor'])==3){
			$number_generate  = $kode1.'.'.$kode2.'.'.$kode3.'0'.$number_increment['nomor'];
		}else if (strlen($number_increment['nomor'])==4){
			$number_generate  = $kode1.'.'.$kode2.'.'.$kode3.''.$number_increment['nomor'];
		}else{
			$number_generate  = $kode1.'.'.$kode2.'.'.$kode3.'0001';
		}

		return $number_generate;
	}

	public function v_ckck(){
		$this->load->view('test');
	}

	private function upload_file($id){
		mkdir('uploads/spd/'.$id);

		$config['upload_path']          = 'uploads/spd/'.$id;
		$config['allowed_types']        = 'jpg|png|jpeg|pdf|docx|doc|xls|xlsx';
		$config['max_size']             = 10000;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('attach')){
			$error = array('error' => $this->upload->display_errors());
			var_dump($error);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data['upload_data']['file_name'];
		}
	}

	public function hitung_jarak($unit = 'km', $desimal = 2){
		
		$lokasi1_lat 	= $this->input->post('lat1');
		$lokasi1_long 	= $this->input->post('lng1');
		$lokasi2_lat 	= $this->input->post('lat2');
		$lokasi2_long 	= $this->input->post('lng2');


		// Menghitung jarak dalam derajat
		$derajat = rad2deg(acos((sin(deg2rad($lokasi1_lat))*sin(deg2rad($lokasi2_lat))) + (cos(deg2rad($lokasi1_lat))*cos(deg2rad($lokasi2_lat))*cos(deg2rad($lokasi1_long-$lokasi2_long)))));
		// Mengkonversi derajat kedalam unit yang dipilih (kilometer, mil atau mil laut)
		switch($unit) {
			case 'km':
				$jarak = $derajat * 111.13384; // 1 derajat = 111.13384 km, berdasarkan diameter rata-rata bumi (12,735 km)
			break;
			case 'mi':
				$jarak = $derajat * 69.05482; // 1 derajat = 69.05482 miles(mil), berdasarkan diameter rata-rata bumi (7,913.1 miles)
			break;
			case 'nmi':
				$jarak = $derajat * 59.97662; // 1 derajat = 59.97662 nautic miles(mil laut), berdasarkan diameter rata-rata bumi (6,876.3 nautical miles)
 		}
 		
 		echo round($jarak, $desimal);
	}

	public function list_spd(){
		$data['title'] = 'List SPD';
		$data['spd'] = $this->db->get_where('tbl_spd', ['request_by' => $this->session->userdata('id_user')])->result();

		$this->load->view('spd/structure/header-list', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('spd/list-spd', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer-list');
	}

	public function detail_spd($id){
		$data['title'] 			= 'Detail SPD';
		$data['project'] 		= get_data('project')->data;
		$data['cost_center'] 	= get_data('cost')->data;
		$data['users'] 			= get_data('users')->data;

		$data['driver'] 		= $this->db->get('tbl_mbl_driver');
		$data['kendaraan'] 		= $this->db->get('tbl_spd_kendaraan');
		$data['mobil'] 			= $this->spd_model->getCarLocation();
		$data['swal']			= null;
		if (!$this->session->userdata('site')) {
			$data['swal'] = 'swal';
		}

		$this->load->view('home/header', $data);
		$this->load->view('home/sidebar');
		$this->load->view('home/topbar');
		$this->load->view('spd/create-spd', $data);
		$this->load->view('home/footer-content');
		$this->load->view('spd/structure/footer');
		
	}


}
