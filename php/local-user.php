<?php
 session_start();
 error_reporting(0);
 $arr = array("email" => '', "id" => 0, "password" => '', "aff_id" => 0);
 // var_dump($arr);
// var_dump($_SESSION['user']);
 switch ($_GET['type']){
 	case 'get': if($_SESSION['user']==null)
                echo json_encode($arr);
                else
                echo json_encode($_SESSION['user']); 
                break;
 	case 'set': $_SESSION['user']['email'] = $_GET['email']; 
                $_SESSION['user']['id'] =  $_GET['id'];
                $_SESSION['user']['password'] =  $_GET['password'];
 				$_SESSION['user']['aff_id'] =  $_GET['aff_id'];
 				 break;
 }

 ?>