<?php 


function get_data($param){
		$ci = get_instance();
		$auth = [
			'auth' 	=> $ci->db->auth
		];

		if ($param == 'project') {
			$ch = curl_init('http://api.multifab.co.id/v1/erp/project_list');
		} else if ($param == 'cost') {
			$ch = curl_init('http://api.multifab.co.id/v1/erp/cost_center');
		} else if ($param == 'users') {
			$ch = curl_init('http://api.multifab.co.id/v1/users/list');
		} else if ($param == 'bu'){
			$ch = curl_init('http://api.multifab.co.id/v1/erp/business_unit');
		}

		//START 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $auth);
		// execute!
		$response = curl_exec($ch);
		curl_close($ch);
		// do anything you want with your response
		return json_decode($response);
}

function cek_login($data){

		$ch = curl_init('http://api.multifab.co.id/v1/users/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);

		// do anything you want with your response
		return json_decode($response);
}

function business_unit($data){
		$ch = curl_init('http://api.multifab.co.id/v1/erp/business_unit/user');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		// execute!
		$response = curl_exec($ch);

		// close the connection, release resources used
		curl_close($ch);

		// do anything you want with your response
		return json_decode($response);
}