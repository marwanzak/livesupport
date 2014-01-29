<?php
class Functions extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	//pretty array print
	public function aprint($array, $method=""){
		echo "<pre>";
		if($method=="")
			print_r($array);
		else
			var_dump($array);
		echo "</pre>";
	}

	//login validate
	public function validate(){
		// grab user input
		$operator = $this->security->xss_clean($this->input->post('operator'));
		$username = $this->security->xss_clean($this->input->post('username'));
		$pass = $this->security->xss_clean($this->input->post('password'));
		// Prep the query
		$query = $this->db->get_where("operators", array("operator"=>$operator));
		if($query->num_rows()!=1){
			return 3; //no such operator
		}else{
			$operator = $query->row();
			if($operator->active != 1){
				return 4; //operator isn't active
			}else{
				$query = $this->db->get_where("users", array(
						"username"=>$username,
						"operator"=>$operator->id
				));
				if($query->num_rows()!=1){
					return 2; //no such user for this operator
				}else{
					$user = $query->row();
					if($user->active != 1){
						return 5; //user is not active
					}else{
						$new_pass = crypt($pass,$user->salt);
						if($new_pass != $user->password){
							return 0; //wrong password
						}else{
							$this->db->where("id",$user->id);
							$this->db->update("users",array("status"=>1));
							$this->db->where("id",$operator->id);
							$this->db->update("operators",array("status"=>1));
							$data = array(
									"validated"=>1,
									"user_id"=>$user->id,
									"operator_id"=>$operator->id,
									"operator"=>$operator->operator,
									"username"=>$user->username,
									"user_name"=>$user->name,
									"domain" => $operator->domain
							);
							$this->session->set_userdata($data);
							return 1; //login validated.
						}
					}
				}
			}
		}
	}

	//get operator status (online, offline)
	public function getOperatorStatus($domain){
		$query = $this->db->get_where("operators", array("domain"=>$domain));
		if($query->num_rows()>0){
			$operator = $query->row();
			return $operator->status;
		}
		return false;
	}
	
	//get active chat of an operator
	public function getActiveChats($domain){
		$domain = "http://".$domain."/liveclient/client.php?";
		$action = "action=activechats";
		$request = file_get_contents($domain.$action);
		$chats = json_decode($request);
		return $chats;
	}
	
	//get active chat messages
	public function getActiveChatsMessages($domain){
		$domain = "http://".$domain."/liveclient/client.php?";
		$action = "action=activechatsmessages";
		$request = file_get_contents($domain.$action);
		$chats = json_decode($request);
		return $chats;
	}
	
	//send message
	public function sendMessage($atts = array()){
		$domain = "http://".$atts["domain"]."/liveclient/client.php?";
		$message = $atts["message"];
		$user = $atts["user"];
		$chat = $atts["chat"];
		$action = "action=sendmessage&username=$user&message=$message&chat=$chat";
		$request = file_get_contents($domain.$action);
		return $request;
	}
	
	//get chat messages
	public function getChatMessages($domain, $chat){
		$domain = "http://".$domain."/liveclient/client.php?";
		$action = "action=chatmessages&chat={$chat}";
		$request = file_get_contents($domain.$action);
		$request = json_decode($request);
		return $request;
	}
	
	//accept chat
	public function acceptChat($domain, $chat){
		$domain = "http://".$domain."/liveclient/client.php?";
		$action = "action=acceptchat&chat={$chat}";
		$request = file_get_contents($domain.$action);
		return $request;
	}
	
	//close chat
	public function closeChat($domain, $chat){
		$domain = "http://" . $domain . "/liveclient/client.php?";
		$action = "action=closechat&chat=$chat";
		$request = file_get_contents ( $domain . $action );
		return $request;
	}
	
}