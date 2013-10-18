<?php 
	include('mysql.conf');
	if (mysqli_connect_errno($con))
	{echo "Failed to connect to MySQL: " . mysqli_connect_error();}

	mysql_select_db("anytv_gbase2") or die(mysql_error());
	$game = mysql_real_escape_string($_GET['game']);
	$reason = mysql_real_escape_string($_GET['reason']);
	$result = mysql_query("INSERT INTO rgames (game, reason) VALUES  ('$game', '$reason')");
	echo "INSERT INTO rgames (game, reason) VALUES  ('$game', '$reason')";
	if($result)
		echo 'added';
?>