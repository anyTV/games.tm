<?php 
	error_reporting(0);
	include('mysql.conf');
	if (mysqli_connect_errno($con))
	{echo "Failed to connect to MySQL: " . mysqli_connect_error();}

	mysql_select_db("anytv_gbase2") or die(mysql_error());

	$result = mysql_query("SELECT images FROM gimages where images like '%-logo%' ");
	$arr = array();
	$ctr = 0 ;
	// echo 'asdsa';
	// var_dump(getimagesize('http://www.gameplay.tm/re/server/php/files/allods_online-logo.jpg'));
	while($row = mysql_fetch_assoc($result)){
		// $imageURL = 'http://www.gameplay.tm/re/server/php/files/'.$row['images'];
		// $header_response = get_headers($imageURL, 1);
		// if ( strpos( $header_response[0], "404" ) !== false )
		// {
		//    array_push($arr, array('img' => 'default1.jpg', 'cls' => '' , 'comparer' =>  $row['images']) );
		// } 
		// else 
		// {
		   // FILE EXISTS!!
			array_push($arr, array('img' => $row['images'], 'cls' => '' , 'comparer' =>  $row['images'] ) );
		// }

	}
	shuffle($arr);
	$arr[0]['cls'] = "active";
	echo json_encode($arr);
?>