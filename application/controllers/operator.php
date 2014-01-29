<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operator extends CI_Controller {
	var $domain;
	var $user_id;
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model("functions");
		$this->check_isvalidated();
		;
		$this->domain = $this->session->userdata('domain');
		$this->user_id = $this->session->userdata('user_id');
	}
	
	public function index()
	{
		$data["domain"] = $this->session->userdata("domain");
		$data["active_chats"] = $this->functions->getActiveChats($data["domain"]);
		$data["active_messages"] = $this->functions->getActiveChatsMessages($data["domain"]);
		$this->load->view("operator", $data);
	}
	
	//check login validation username and password
	private function check_isvalidated(){
		if(! $this->session->userdata('validated')){
			redirect(base_url().'login');
		}
	}
	
	//log out when user name press logout
	public function logout(){
		$this->db->where("id",$this->session->userdata("operator_id"));
		$this->db->update("operators", array("status"=>0));
		$this->session->sess_destroy();
		redirect(base_url().'login');
	}
	
	//set operator status
	public function setOperatorStatus(){
		if(isset($_GET["status"])){
			$this->db->where("id", $this->session->userdata("operator_id"));
			$query = $this->db->update("operators", array("status"=>$_GET["status"]));
			echo $query;
		}else echo 0;
	}
	
	//send message
	public function sendMessage(){
		$domain = $this->session->userdata ( "domain" );
		$user = $this->session->userdata("user_id");
		echo $this->functions->sendMessage ( array (
				"message" => urlencode ( $_POST ["message"] ),
				"user" => $user,
				"chat" => $_POST["chat"],
				"domain" => $domain
		));
	}
	
	//accept chat by admin users
	public function acceptChat(){
		if($_GET)
			echo $this->functions->acceptChat($this->domain, $_GET["chat"]);
		else 
			echo 0;
	}
	
	//close chat by operator users
	public function closeChat(){
		if($_GET)
			echo $this->functions->closeChat($this->domain, $_GET["chat"]);
		else
			echo 0;
	}
	
	function createDatabase(){
		mysql_connect('localhost','root','123123');
		mysql_query("CREATE USER 'marwan'@'localhost' IDENTIFIED BY '123123';");
		mysql_query("GRANT ALL ON newss.* TO 'marwan'@'localhost'");
		mysql_query("CREATE DATABASE newss");
		mysql_close();
		}
}
