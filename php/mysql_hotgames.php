<?php 
    require('mysql.conf');
    if (mysqli_connect_errno($con))
    {echo "Failed to connect to MySQL: " . mysqli_connect_error();}

    mysql_select_db("anytv_gbase2") or die(mysql_error());

    $result = mysql_query("SELECT * FROM genre ");
    $arr = array();

    while($row = mysql_fetch_assoc($result)){
        $temparray = array('genre_id' => $row['genre_id'], 'burn' => false, 'genre_initials' => $row['genre_initials'], 'genre_name' => $row['genre_name'], 'games' => array());
        $result2 = mysql_query("SELECT game_name, game_id FROM genre_game where genre_id = " . $row['genre_id']);
        while($row2 = mysql_fetch_assoc($result2)){
            array_push($temparray['games'], array('active' => false, 'game_name' => $row2['game_name'], 'game_id' => $row2['game_id']));
        }
        array_push($arr, $temparray);
    }
    echo json_encode($arr);
?>