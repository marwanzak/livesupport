var chat_ajax = null;
var message_ajax = null;

$(document).ready(function(){
	// on press enter when typing send the message
	$("#chat_panel_div textarea[name=message]").keyup(function(e) {
		if (e.which == 13) {
			if(this.value!="" && this.value!=" " && this.value!="\n")
			$(this).submit();
			$(this).empty();
			$(this).val("");
		}
	});
});
