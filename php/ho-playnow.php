<?php
$base = 'https://api.hasoffers.com/Api?';
$conf =  json_decode(file_get_contents('hasoffers.conf'),true);
$params = array(
  'Format' => 'json'
  ,'Target' => 'AffiliateUser'
  ,'Method' => 'findById'
  ,'Service' => 'HasOffers'
  ,'Version' => 2
  ,'NetworkId' => $conf['NetworkId']
  ,'NetworkToken' => $conf['NetworkToken']
  ,'limit' => 10000
  ,'id' => $_GET['affiliate']
  ,'fields' => array('affiliate_id')
);

$url = $base . http_build_query( $params );

$result = file_get_contents( $url );
$au = json_decode( $result );
// echo '<pre>';
$affiliate_id = $au->response->data->AffiliateUser->affiliate_id;
// echo $affiliate_id;

$base = 'https://api.hasoffers.com/Api?';
// error_reporting(0);
$params = array(
  'Format' => 'json'
,'Target' => 'Offer'
  ,'Method' => 'generateTrackingLink'
  ,'Service' => 'HasOffers'
  ,'Version' => 2
   ,'NetworkId' => $conf['NetworkId']
   ,'NetworkToken' => $conf['NetworkToken']
   ,'offer_id' => $_GET['offer']
  ,'affiliate_id' => $affiliate_id
);
 // var_dump(explode(',', $_GET['affiliate']));
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

 echo $resultarray->response->data->click_url;

?>