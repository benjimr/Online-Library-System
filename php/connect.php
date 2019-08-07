<?php
	$db = mysql_connect('localhost','root','');
	if($db ===FALSE) die('Fail');
	mysql_select_db("Library") or die(mysql_error());
?>