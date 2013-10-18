<?php 
	include('mysql.conf');
	if (mysqli_connect_errno($con))
	{echo "Failed to connect to MySQL: " . mysqli_connect_error();}

	mysql_select_db("anytv_gbase2") or die(mysql_error());

	$result = mysql_query("SELECT description FROM additional_info WHERE hidden = '".mysql_real_escape_string($_GET['g'])."'");
	// echo "SELECT description FROM additional_info WHERE hidden = '".$_GET['g']."'";
	$row = mysql_fetch_assoc($result);
	$myarray = array();
	// array_push($myarray, array('description' => $row['description']));
	
	$result = mysql_query("SELECT images FROM gimages WHERE images  LIKE '".mysql_real_escape_string($_GET["g"])."-cp%'");
	$row1 = mysql_fetch_assoc($result);
	array_push($myarray, array('img' => $row1['images'], 'description' => $row['description']));
	echo json_encode($myarray);
?>