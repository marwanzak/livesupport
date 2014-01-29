<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('validated')){
			redirect(base_url().'operator');
		}
	}

	public function index($msg = NULL){
		// Load our view to be displayed
		// to the user
		$data['msg'] = $msg;
		$this->load->view('login', $data);
	}

	public function process(){
		$this->load->model("functions");
		// Validate the user can login
		$result = $this->functions->validate();
		$msg="";
		// Now we verify the result
		switch($result){
			case 4:
				$msg = "operator isn't active";
				break;
			case 5:
				$msg = "user isn't active";
				break;
			case 3:
				$msg = "no such operator";
				break;
			case 2:
				$msg = "no such username";
				break;
			case 0:
				$msg = "wrong password";
				break;
			case 1:
				redirect(base_url()."operator","refresh");
				break;
		}
		$this->index($msg);
	}

}