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
    ,'id' => $_GET['id']
);
 
$url = $base . http_build_query( $params );
 
$result = json_decode(file_get_contents( $url ));
 // var_dump($result);
echo $result->response->data->AffiliateUser->affiliate_id;
?>