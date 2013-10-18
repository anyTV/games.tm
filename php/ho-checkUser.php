<?php
$base = 'https://api.hasoffers.com/Api?';
error_reporting(0);
$conf =  json_decode(file_get_contents('hasoffers.conf'),true);
$params = array(
  'Format' => 'json'
,'Target' => 'Authentication'
  ,'Method' => 'findUserByCredentials'
  ,'Service' => 'HasOffers'
  ,'Version' => 2
    ,'NetworkId' => $conf['NetworkId']
    ,'NetworkToken' => $conf['NetworkToken']
   ,'email' => $_GET['email']
	,'password' => $_GET['password']
);
 
$url = $base . http_build_query( $params );
 
$result = file_get_contents( $url );
 $resultarray =  json_decode($result);
//  $arr = array();
// echo '<pre>';
// print_r( $resultarray);
// echo '</pre>';
// foreach ($resultarray->response->data as $r) {
//   array_push($arr, array("url" => $r->click_url));
// }
echo $resultarray->response->data->user_id;

?>