<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>IClient Side</title>
<link href="<?=base_url()?>assets/css/client.css" rel="stylesheet" type="text/css"/>
<?php if(isset($server)){?>
<script>
	var client_domain = "<?=$server?>";
	<?php } ?>
	<?php if(isset($chat)){?>
	var review_chat = "<?=$chat?>";
	<?php } ?>
</script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/client.js"></script>
</head>
<body>
	
<div id="container">
<?php if(!isset($chat)){?>
<div id="request_chat_div">
<?php if(isset($msg)){?>
	<?=$msg?>
<?php }?>
<form action="<?=base_url()?>client/onlineWindow/<?=$server?>" method="post">
<input type="text" name="name" placeholder="Your name"/>
<input type="text" name="email" placeholder="Your email"/>
<input type="text" name="mobile" placeholder="Your mobile"/>
<input type="submit" value="request"/>
</form>
</div>
<?php }else{?>
<div id="chat_container_div">
<a href="<?=base_url()?>client/closeChat"><input type="button" value="close chat" id="close_chat_btn"/></a>
<div id="chat_box_div">
<?php if(isset($messages)){?>
<?php if(count($messages)>0 && $messages!=1){?>
<?php foreach($messages as $message){?>
<label><?= $message->message?></label>
<br/>
<?php }?>
<?php }?>
<?php }?>
</div>
<div id="chat_panel_div">
<form action="#" method="post" id="send_message_form">
<textarea name="message"></textarea>
<input type="submit" value="send"/>
</form>
</div>
</div>
<?php }?>
</div>
</body>
</html>
