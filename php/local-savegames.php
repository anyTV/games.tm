<?php
if(isset($_GET))
{
$conf =  json_decode(file_get_contents('hasoffers.conf'),true);
  $base = 'https://api.hasoffers.com/Api?'; 
  $params = array(
   'Format' => 'json'
   ,'Target' => 'Offer'
   ,'Method' => 'findAll'
   ,'Service' => 'HasOffers'
   ,'Version' => 3
   ,'NetworkId' => $conf['NetworkId']
   ,'NetworkToken' => $conf['NetworkToken']
   ,'limit' => 50000
   ,'fields' => array(
    'name', 'id', 'default_payout', 'status', 'redirect_offer_id', 'preview_url'
    )
   ,'sort' => array('Offer.name' => 'asc')
   ,'filters' => array(

    'OR' => array(
     array('status' => 'active')
     ,array('status' => 'paused')
     //  , 'Offer.name' => array(
     //  'conditional' => 'NOT_LIKE'
     //  ,'values' => 'Create'
     // )
    )
     
    // ,array('default_payout' => 
    //  array('GREATER_THAN' => 0.00)
    // )
   )
   ,'contain[]'=>'Country'
  );
  //
  $url = $base . http_build_query($params);
   
  $result = file_get_contents($url);
  $arr = json_decode($result);
    // echo '<pre>' . print_r($arr,true) . '</pre>';
  $response = array();
  $names = array();
  $ctr =1;
  $m = new MongoClient();
  $games = $m->games->hasoffers;

  $cursor = $games->find(array(),array("name"=>1, 'pic'=>1, '_id'=>0));
  $saves = iterator_to_array($cursor);
  // var_dump($saves);
  $haspics = array();
  foreach($saves as $g){
    if(strpos($g['pic'], '.') !== FALSE && strpos($g['pic'], 'tm')=== FALSE){
      array_push($haspics, array('name'=>$g['name'],'pic'=>$g['pic']));
    }
  }
  foreach($arr->response->data->data as $d){
    if(!in_array($d->Offer->name, $names)){
    array_push($names, $d->Offer->name);
    $pict = str_replace(' ', '_', strtolower($d->Offer->name));
      foreach ($haspics as $pic) {
        if($d->Offer->name == $pic['name']){
          $pict = $pic['pic'];
        }
      }
    array_push($response, array("name" => $d->Offer->name , "website"=>$d->Offer->preview_url, 'clicked'=>false,'fid'=>$ctr++, 'pic' => $pict,  "id"=> "", "country"=> array(), "pay" => $d->Offer->default_payout, 'state' => $d->Offer->status, "videos"=>array(), "redirect_offer_id" => $d->Offer->redirect_offer_id, 'realstate'=>''));
    }
  }
  foreach($arr->response->data->data as $d){
    foreach($names as $key => $value){
      if($d->Offer->name==$names[$key]){
        $response[$key]["id"] .= $d->Offer->id . ',';
        // array()
         if(count((array)$d->Country) > 100){
           $response[$key]["redirect_offer_id"] = $d->Offer->id;
           // echo "REDIRECT" . $d->Offer->id;
         }
        if(floatval($d->Offer->default_payout)!=0.00 && count((array)$d->Country) < 100)
         array_push($response[$key]["country"], array( "country"=>$d->Country, "state" => $d->Offer->status, "pay"=>$d->Offer->default_payout));
         // $response[$key]["country"] .= array('country' . $d->Offer->country . ',');
        // $response[$key]["country"] .= array('country' . $d->Offer->country . ',');
      }
    }
  }
    // echo '<pre>' . print_r($response,true) . '</pre>';
foreach ($response as $key => $value) {
  $pay = $response[$key]['pay'];
  $count=0;
  $prevState = "";
  foreach ( $response[$key]['country'] as $x) {
    if($x['pay']>$pay){
      $pay = $x['pay'];
      $response[$key]['pay'] = $pay;
      $count++;
      if($count==1){
        $prevState = $x['state'];
      }
    }
     if($x['state']=="active" && floatval($x['pay'])!=0.00)
      $response[$key]['state'] = "active";
    if($count==1){

      $response[$key]['state'] = $prevState;
      $count =0;
    }

  }
}

    // echo '<pre>' . print_r($response,true) . '</pre>';
  
  // $m = new MongoClient();
  // $games = $m->games->hasoffers;


  $games->remove();
  
  foreach ($response as $r){
  $games->insert($r);
  }
  echo json_encode($response);

  file_put_contents('default-games.php', json_encode($response));

}
?>