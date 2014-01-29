var server_domain = "http://localhost/liveserver/";
var message_ajax = null;
$(document).ready(function() {
	// close chat
	$("#close_chat_btn").on("click", function() {
		// closeChat();
	});

	$("#chat_panel_div textarea[name=message]").keyup(function(e) {
		if (e.which == 13) {
			if (this.value != "" && this.value != " " && this.value != "\n")
				$(this).submit();
			$(this).empty();
			$(this).val("");
		}
	});

	// send message by guest
	$("body").on("submit", "form#send_message_form", function(evt) {
		evt.preventDefault();
		sendMessage("#" + this.id);
		$(this).find("textarea").val("");
		return false;
	});

	// on press enter when typing send the message
	$(".chat_panel textarea[name=message]").keyup(function(e) {
		if (e.which == 13) {
			if (this.value != "" && this.value != " " && this.value != "\n")
				$(this).submit();
			$(this).empty();
			$(this).val("");
		}
	});
	getChatReview();

});

function sendMessage(div) {
	$.ajax({
		url : server_domain + "client/sendMessage",
		data : $(div).serialize(),
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

function closeChat() {
	$.ajax({
		url : server_domain + "client/closeChat",
		success : function(data) {
			if (data == 1) {
				window.location.reload();
			}
		}
	});
}

function getChatReview() {
	$.ajax({
		url : "http://" + client_domain + "/liveclient/client.php",
		data : {
			"action" : "clientreview",
			"chat" : review_chat,
			"message_ajax" : message_ajax
		},
		type : "GET",
		dataType : "json",
		async : true,
		cache : false,
		success : function(data) {
			if (data.messages.messages.length > 0)
				for ( var i = 0; i < data.messages.messages.length; i++) {
					inputMessage(data.messages.messages[i].message);
					var objDiv = document.getElementById("chat_box_div");
					objDiv.scrollTop = objDiv.scrollHeight;
				}

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

// input message in chat box
function inputMessage(message) {
	$("<label/>").text(message).appendTo("<div/>").appendTo("#chat_box_div");
	$("<br/>").appendTo("#chat_box_div");
	return false;
}
