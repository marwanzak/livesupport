<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Client extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( "functions" );
	}
	public function index() {
		echo crypt ( "123123", "123123" );
	}
	
	// get operator status
	public function getOperatorStatus() {
		if ($_GET) {
			$status = $this->functions->getOperatorStatus ( $_GET ["domain"] );
			$json = array (
					"status" => $status 
			);
			if (isset ( $_GET ["callback"] )) {
				header ( 'Content-Type: application/javascript' );
				exit ( $_GET ["callback"] . "(" . json_encode ( $json ) . ")" );
			} else {
				header ( 'Content-Type: application/json' );
				exit ( json_encode ( $json ) );
			}
		}
		return false;
	}
	
	// javascript output for client website
	public function clientJs() {
		if ($_GET) {
			$data ["server"] = $_GET ["server"];
			header ( 'Content-Type: application/javascript' );
			$content = $this->load->view ( "clientJs", $data, true );
			exit ( $content );
		}
	}
	
	// get operator status logo
	public function operatorLogo() {
		if ($_GET) {
			$query = $this->db->get_where ( "operators", array (
					"domain" => $_GET ["server"] 
			) );
			if ($query->num_rows == 1) {
				$operator = $query->row ();
				$query = $this->db->get_where ( "settings", array (
						"operator" => $operator->id 
				) );
				if ($query->num_rows () == 1) {
					$settings = $query->row ();
					// Output the image to browser
					header ( "Pragma: no-cache" );
					header ( "cache-Control: no-cache, must-revalidate" ); // HTTP/1.1
					header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); // Date in the past
					header ( 'Content-Type: image/gif' );
					switch ($operator->status) {
						case 1 :
							exit ( file_get_contents ( $settings->online_logo ) );
							break;
						case 0 :
							exit ( file_get_contents ( $settings->offline_logo ) );
							break;
					}
				}
			}
		}
	}
	
	// open client chat window
	public function openChat() {
		if ($this->session->userdata ( "lc_chat_id" )) {
			$data ["chat"] = $this->session->userdata ( "lc_chat_id" );
			$data ["server"] = $this->session->userdata ( "lc_server" );
			$data["messages"] = $this->functions->getChatMessages($data["server"],$data["chat"]);
			$this->load->view ( "onlinewindow", $data );
		} else {
			if (isset ( $_GET ["server"] )) {
				$status = $this->functions->getOperatorStatus ( $_GET ["server"] );
				if ($status == 1) {
					$this->onlineWindow ( $_GET ["server"] );
				} else {
					$this->offlineWindow ();
				}
			}
		}
	}
	
	// open online window to client
	public function onlineWindow($server = "") {
		if ($server != "") {
			if ($this->session->userdata ( "lc_chat_id" )) {
				$data ["chat"] = $this->session->userdata ( "lc_chat_id" );
				$data ["server"] = $this->session->userdata ( "lc_server" );
				$data["messages"] = $this->functions->getChatMessages($data["server"],$data["chat"]);
				$this->load->view ( "onlinewindow", $data );
			} elseif (! $this->session->userdata ( "lc_chat_id" )) {
				if (! $_POST) {
					$data ["server"] = $server;
					$this->load->view ( "onlinewindow", $data );
				} else {
					$domain = "http://" . $server . "/liveclient/client.php?";
					$chat = file_get_contents ( $domain . "action=request_chat&email=" . $_POST ["email"] . "&mobile=" . $_POST ["mobile"] . "&name=" . $_POST ["name"] );
					if ($chat > 0) {
						$this->session->set_userdata ( array (
								"lc_chat_id" => $chat 
						) );
						$this->session->set_userdata ( array (
								"lc_server" => $server 
						) );
						$data ["server"] = $this->session->userdata ( "lc_server" );
						$data ["chat"] = $chat;
						$this->load->view ( "onlinewindow", $data );
					} else {
						$data ["msg"] = "error eccured when request chat from client";
						$this->load->view ( "onlinewindow", $data );
					}
				}
			}
		} else {
			echo "no server";
		}
	}
	// open offline window to client
	public function offlineWindow() {
		$this->load->view ( "offlinewindow" );
	}
	
	// close chat from guest
	public function closeChat() {
		$chat = $this->session->userdata ( "lc_chat_id" );
		$domain_name = $this->session->userdata ( "lc_server" );
		$request = $this->functions->closeChat($domain_name, $chat);
		if ($request == 1) {
			$this->session->unset_userdata ( "lc_server" );
			$this->session->unset_userdata ( "lc_chat_id" );
		}
		$this->onlineWindow ( $domain_name );
	}
	
	// send message
	public function sendMessage() {
		$this->load->model ( "functions" );
		$chat = $this->session->userdata ( "lc_chat_id" );
		$domain = $this->session->userdata ( "lc_server" );
		echo $this->functions->sendMessage ( array (
				"message" => urlencode ( $_POST ["message"] ),
				"user" => "-1",
				"chat" => $chat,
				"domain" => $domain 
		)
		 );
	}
}