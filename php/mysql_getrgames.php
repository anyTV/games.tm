<?php 
	include('mysql.conf');
	if (mysqli_connect_errno($con))
	{echo "Failed to connect to MySQL: " . mysqli_connect_error();}

	mysql_select_db("anytv_gbase2") or die(mysql_error());

	$result = mysql_query("SELECT game FROM rgames");
	$arr = array();

	while($row = mysql_fetch_assoc($result)){
		array_push($arr, $row['game']);
	}
	echo json_encode($arr);
?>