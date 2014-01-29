var client = "<?=$server?>";
var server = "http://localhost/liveserver/";
window.onload = operatorStatus;
//get operator status
function operatorStatus() {
	var logo = document.getElementById("lc_logo");
	logo.src=server+"client/operatorLogo?server="+client;
	var a = document.getElementById("lc_a");
	a.href=server+"client/openChat?server="+client;
	setTimeout(function() {
		operatorStatus();
	}, 5000);
}
