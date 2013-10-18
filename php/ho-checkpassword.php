<?php
$base = 'https://api.hasoffers.com/Api?';
$conf =  json_decode(file_get_contents('hasoffers.conf'),true);
$params = array(
  'Format' => 'json'
,'Target' => 'AffiliateUser'
  ,'Method' => 'checkPassword'
  ,'Service' => 'HasOffers'
  ,'Version' => 2
  ,'limit' => 100000
    ,'NetworkId' => $conf['NetworkId']
    ,'NetworkToken' => $conf['NetworkToken']
   ,'id' => $_GET['id']
  ,'password' => $_GET['password']
);
 
$url = $base . http_build_query( $params );
 
$result = file_get_contents( $url );
 $resultarray =  json_decode($result);


 echo json_encode($resultarray->response->data);
// echo '<pre>';
// print_r(  $arr );
// echo '</pre>';

?>