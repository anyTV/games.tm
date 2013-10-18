<?php
 
$base = 'https://api.hasoffers.com/Api?';
 $conf =  json_decode(file_get_contents('hasoffers.conf'),true);
$params = array(
    'Format' => 'json'
    ,'Target' => 'Affiliate'
    ,'Method' => 'getReferralAffiliateIds'
    ,'Service' => 'HasOffers'
    ,'Version' => 2
    ,'NetworkId' => $conf['NetworkId']
    ,'NetworkToken' => $conf['NetworkToken']
    ,'id' => 12410
);
 
$url = $base . http_build_query( $params );
 
$result = file_get_contents( $url );
 
echo '<pre>';
print_r( json_decode( $result ) );
echo '</pre>';
?>