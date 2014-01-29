var base_url = "http://localhost/liveserver/";
var chat_ajax = null;
var message_ajax = null;

$(document).ready(function(){
	
	//loop to set operator status
	//setInterval(setStatus, 2000);
	
	//accept chat by user
	$("#chats_nav_div").on("click", ".chat_btn_div .accept_btn", function(){
		var btn = $(this);
		var chat = btn.parent().find("input[name=chat]").val();
		acceptChat(chat, function(data){
			if(data == 1){
				btn.parent().removeClass("unaccepted_chat").addClass("accepted_chat");
				btn.prop("disabled","disabled");
			}
		});
	});
	
	$("#chats_nav_div").on("click", ".chat_btn_div .close_btn", function(){
		var btn = $(this);
		var chat = btn.parent().find("input[name=chat]").val()
		closeChat(chat, function(data){
			if(data==1){
				btn.parent().remove();
				$("#chat_container_"+chat).remove();
			}
		}); 
	});
	
	// on press enter when typing send the message
	$("#container").on("keyup", "div.chat_panel_div form.send_message_form textarea[name=message]", function(e) {
		if (e.which == 13) {
			if(this.value!="" && this.value!=" " && this.value!="\n")
			$(this).submit();
			$(this).empty();
			$(this).val("");
		}
	});
	
	// send message by guest
	$("body").on("submit", "form.send_message_form", function(evt) {
		evt.preventDefault();
		sendMessage($(this).serialize());
		$(this).find("textarea").val("");
		return false;
	});
	
	//get chat review new chats and new messages
	getChatReview();
	
});

function setStatus(){
	$.ajax({
		url:base_url + "operator/setOperatorStatus",
		data:{"status":"1"},
		type:"get",
		success: function(data){
			return false;
		}
	});
	return false;
}
/*
function sendMessage(div){
	$.ajax({
		url:client_domian+"/liveclient/client.php",
		data:{"action":"sendmessage"+}
	});
}
*/
//var message_ajax = null;
function getChatReview() {
	$.ajax({
		url : "http://"+client_domain + "/liveclient/client.php",
		data : {
			"action" : "operator_review",
			"chat_ajax" : chat_ajax,
			"message_ajax" : message_ajax
			//"message_ajax" : message_ajax
		},
		type : "GET",
		dataType : "json",
		async : true,
		cache : false,
		success : function(data) {
			if (data.messages.messages.length > 0)
				for ( var i = 0; i < data.messages.messages.length; i++) {
					inputMessage(data.messages.messages[i].chat,
						data.messages.messages[i].message);
				var objDiv = document.getElementById("chat_box_"
						+ data.messages.messages[i].chat);
				objDiv.scrollTop = objDiv.scrollHeight;
			
				}
			var chats = data.chats.chats;
			if(chats.length>0){
				for(var j = 0; j<chats.length; j++){
					inputChat(chats[j].id, chats[j].name);
					inputChatContainer(chats[j].id);
				}
			}

			chat_ajax = data.chats.last;
			message_ajax = data.messages.last;

			setTimeout(function() {
				getChatReview();
			}, 1000);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			setTimeout(function() {
				getChatReview();
			}, 15000);
		}
	});
}

//send message function
function sendMessage(form_data) {
	$.ajax({
		url : base_url+"operator/sendMessage",
		data : form_data,
		type : "post",
		success : function(data) {
			if (data != 1) {
				alert("message hasn't recieved!");
			}
			return false;
		}
	});
	return false;
}

//input message in chat box
function inputMessage(chat_id, message) {

	$("<label/>").text(message).appendTo("<div/>").appendTo(
			"#chat_box_" + chat_id);

	$("<br/>").appendTo("#chat_box_" + chat_id);
	return false;
}

function inputChat(chat_id, name){
	$("<div/>")
	.addClass("chat_btn_div")
	.addClass("unaccepted_chat")
	.appendTo("#chats_nav_div");
	
	$("#chats_nav_div div").last().append($("<input type='button' class='accept_btn'/>").val(name));
	$("#chats_nav_div div").last().append($("<input type='button' value='*' class='close_btn'/>"));
	$("#chats_nav_div div").last().append($("<input type='hidden' name='chat'/>").val(chat_id));
	return false;
}

function inputChatContainer(chat){
	$("<div class='chat_container_div'/>")
	.prop("id", "chat_container_"+chat)
	.appendTo("div#container");
	
	$("div#container div").last().append($("<div class='chat_box_div'/>").prop("id", "chat_box_"+chat));
	$("div#container div").last().after($("<div class='chat_panel_div'/>"));
	$("div#container div").last().append($("<form action='#' method='post' class='send_message_form'/>"));
	$("div#container form").last().append($("<input type='hidden' name='chat'/>").val(chat));
	$("div#container form").last().append($("<textarea name='message'/>"));
	$("div#container form").last().append($("<input type='submit' value='send'/>"));
	/*
	<div class="chat_container_div" id="chat_container_<?=$chat->id?>">
	<div class="chat_box_div" id="chat_box_<?=$chat->id?>">
	</div>
	<div class="chat_panel_div">
		<form action="#" method="post" class="send_message_form">
			<input type="hidden" name="chat" value="<?= $chat->id ?>" />
			<textarea name="message"></textarea>
			<input type="submit" value="send" />
		</form>
	</div>
</div>
*/
}

function acceptChat(chat, handle){
	$.ajax({
		url:base_url+"operator/acceptChat?chat="+chat,
		success:function(data){
			handle(data);
			return false;
		}
	});
	return false;
}

//function to close a chat
function closeChat(chat, handle){
	$.ajax({
		url:base_url+"operator/closeChat",
		data:{"chat":chat},
		type:"get",
		success:function(data){
			handle(data);
			return false;
		}
	});
	return false;
}
