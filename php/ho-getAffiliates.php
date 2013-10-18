<?php
$base = 'https://api.hasoffers.com/Api?';
$conf =  json_decode(file_get_contents('hasoffers.conf'),true);
$params = array(
  'Format' => 'json'
,'Target' => 'AffiliateUser'
  ,'Method' => 'findAll'
  ,'Service' => 'HasOffers'
  ,'Version' => 2
  ,'limit' => 100000
    ,'NetworkId' => $conf['NetworkId']
    ,'NetworkToken' => $conf['NetworkToken']
   ,'fields' => array(
     'id', 'email', 'first_name')
   // ,'filters' => array('status' => 'active')
  //  ,'offer_id' => 206
  // ,'affiliate_id' => 12410
);
 
$url = $base . http_build_query( $params );
 
$result = file_get_contents( $url );
 $resultarray =  json_decode($result);
 $arr = array();
// echo '<pre>';
// print_r( json_decode( $resultarray ) );
// echo '</pre>';
foreach ($resultarray->response->data->data as $r) {
	array_push($arr, array("id" => $r->AffiliateUser->id, "e" => $r->AffiliateUser->email, "f" => $r->AffiliateUser->first_name));
}

 echo json_encode($arr);
// echo '<pre>';
// print_r(  $arr );
// echo '</pre>';

?>