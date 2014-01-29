<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Server Operator</title>
<link href="<?=base_url()?>assets/css/operator.css" rel="stylesheet"
	type="text/css" />
<script src="<?=base_url()?>assets/js/jquery.js"></script>
<script src="<?=base_url()?>assets/js/operator.js"></script>
<script type="text/javascript">
		var client_domain = "<?=$domain?>";
	</script>
</head>
<body>
	<div id="container">
		<a href="<?=base_url()?>operator/logout">logout</a>
		<div id="chats_nav_div">
		<?php if(count($active_chats)>0 && $active_chats!=1){?>
<?php foreach($active_chats as $chat){?>
<div class="chat_btn_div <?=($chat->accepted==1?'accepted_chat':'unaccepted_chat')?>">
<input type="button" value="<?=$chat->name?>" class="accept_btn" />
<input type="hidden" value="<?=$chat->id?>" name="chat"/>
<input type="button" value="*" class="close_btn"/>
</div>
<?php }?>
<?php }?>
</div>
<?php if(count($active_chats)>0 && $active_chats!=1){?>
<?php foreach($active_chats as $chat){?>
<div class="chat_container_div" id="chat_container_<?=$chat->id?>">
			<div class="chat_box_div" id="chat_box_<?=$chat->id?>">
			<?php foreach($active_messages->messages as $chat_messages){?>
			<?php foreach($chat_messages as $message){?>
			<?php if($message->chat == $chat->id){?>
				<label><?=$message->message?></label>
				<br/>
			<?php }?>
			<?php }?>
			<?php }?>
			</div>
			<div class="chat_panel_div">
				<form action="#" method="post" class="send_message_form">
					<input type="hidden" name="chat" value="<?= $chat->id ?>" />
					<textarea name="message"></textarea>
					<input type="submit" value="send" />
				</form>
			</div>
		</div>
<?php }?>
<?php }?>
	</div>

</body>
</html>