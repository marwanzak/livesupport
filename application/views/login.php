<html>
<head>

</head>
<body>
<?php if(isset($msg)){
	echo "message: ".$msg;
}?>
<form action="<?=base_url()?>login/process" method="post">
<input type="text" name="operator" placeholder="operator"/>
<input type="text" name="username" placeholder="username"/>
<input type="password" name="password" placeholder="password"/>
<input type="submit" value="login"/>
</form>
</body>
</html>