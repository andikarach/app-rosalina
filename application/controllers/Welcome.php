<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Welcome extends CI_Controller {

	public function index(){
		$this->load->view('welcome_message');
	}

	public function cekBu(){
		$bunit = [
				'email' 	=> 'andika.rachadi@multifab.co.id',
				'auth' 		=> $this->db->auth,
			];
		$bu = business_unit($bunit)->data;
		var_dump($bu);

	}

	public function maps(){
		$this->load->view('maps');
	}
	public function maps2(){
		$this->load->view('maps2');
	}

	public function disable_click(){
		$this->load->view('test');
	}
	
	public function men(){
		$access = $this->db->get_where('tbl_user_access_menu', ['id_role'=> '1'])->row();
		$acc = json_decode($access->id_menu);

		foreach ($acc as $x) {
			echo $x.'<br>';
		}
	}

	public function sweetAlert(){
		$this->load->view("test");
	}

	public function testSend(){
		$mail = new PHPMailer;
	
		$mail->isSMTP();
		$mail->Host 		= 'smtp.gmail.com';
		$mail->SMTPAuth 	= true;
		$mail->Username 	= 'okumura16rin@gmail.com';
		$mail->Password 	= 'voidgenome31';
		$mail->SMTPSecure   = 'tls';
		$mail->Port 		= 587;

		$mail->setFrom('andika.rach4@gmail.com', 'TEST');
		$mail->addReplyTo('no-reply@gmail.com', 'TEST');

		// Menambahkan penerima
		// $mail->addAddress('andika.rachadi@multifab.co.id'); 
		$mail->addAddress('okumura16rin@gmail.com'); 
		 

		// Menambahkan cc atau bcc 
		// $mail->addCC('admin@domain.co.id');
		// $mail->addBCC('bcc@contoh.com');

		// Subjek email
		$mail->Subject = 'TEST';
		 
		$mail->isHTML(true);

		// Konten/isi email
		$mailContent =  "<h1>Tess Kirim Email</h1> <p>JAJAL.</p>";
		$mail->Body  =  $mailContent;
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		 
		// Kirim email
		if(!$mail->send()){
		    echo 'Pesan tidak dapat dikirim.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}else{
		    echo 'Pesan telah terkirim';
		}
	}

	public function callHelper(){
		sendMail();
	}

	public function cvtImg(){
		$url = base_url().'assets/img/profile/default.jpg';
		$img = file_get_contents($url); 
		  
		// Encode the image string data into base64 
		$im = base64_encode($img); 
		  
		// Display the output 
		$data['link'] = $im;

		$this->load->view('convert', $data);
	}
}
