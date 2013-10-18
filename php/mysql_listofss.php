<?php 
error_reporting(0);
	include('mysql.conf');
	if (mysqli_connect_errno($con))
	{echo "Failed to connect to MySQL: " . mysqli_connect_error();}

	mysql_select_db("anytv_gbase2") or die(mysql_error());

	$result = mysql_query("SELECT images FROM gimages where images like '%".$_GET['g']."-ss%'  limit 5");
	$arr = array();
	$ctr = 0 ;
	while($row = mysql_fetch_assoc($result)){
		
		array_push($arr, array('img' => $row['images'], 'cls' => '' ) );
	}
	if(mysql_num_rows($result)==0){
		array_push($arr, array('img' => $_GET['g'] . '-ss.jpg', 'cls' => '' ) );
		array_push($arr, array('img' => $_GET['g'] . '-ss1.jpg', 'cls' => '' ) );
		echo "false";
		return;
	}
	shuffle($arr);
	$arr[0]['cls'] = "active";
	echo json_encode($arr);
?>