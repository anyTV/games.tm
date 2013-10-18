<?php
if(isset($_GET))
{
$conf =  json_decode(file_get_contents('hasoffers.conf'),true);
  $base = 'https://api.hasoffers.com/Api?'; 
  $params = array(
   'Format' => 'json'
   ,'Target' => $_GET['target']
   ,'Method' => $_GET['method']
   ,'Service' => 'HasOffers'
   ,'Version' => 3
   ,'NetworkId' => $conf['NetworkId']
   ,'NetworkToken' => $conf['NetworkToken']
   ,'limit' => 50000
   ,'fields' => array(
    'name', 'id'
    )
   ,'sort' => array('Offer.name' => 'asc')
   ,'filters' => array(
    'OR' => array(
     array('status' => 'active')
     ,array('status' => 'pending')
     ,array('status' => 'paused')                                                    
    )
    ,array('default_payout' => 
     array('GREATER_THAN' => 0.00)
    )
    ,array('max_payout' => 
     array('GREATER_THAN' => 0.00)
    )
   )
   ,'contain[]'=>'Country'
  );
  //
  $url = $base . http_build_query($params);
   
  $result = file_get_contents($url);
  $arr = json_decode($result);
  $response = array();
  $names = array();
  foreach($arr->response->data->data as $d){
    if(!in_array($d->Offer->name, $names)){
    array_push($names, $d->Offer->name);
    array_push($response, array("name" => $d->Offer->name , "id"=> ""));
    }
  }
  foreach($arr->response->data->data as $d){
    foreach($names as $key => $value){
      if($d->Offer->name==$names[$key]){
        $response[$key]["id"] .= $d->Offer->id . ',';
        // $allids .= $d->Offer->id . ',';
      }
    }
  }
  $allids = "";
  // echo '<pre>' . print_r($response,true) . '</pre>';
  echo count($response);
  $loops = ceil(count($response)/20);
  $limit = 20;
  for($i=0;$i<$loops;$i++) {
    $st = $i * 20;
    $trimmed = array_slice($response, $st,$limit);
    foreach ($trimmed as $game) {

      $allids .= $game['id'];      
      // echo  $game['id'] . ',';
    }
    var_dump($trimmed);
    echo $allids;
    getVideos(explode(',', $allids));
    // getVideos($trimmed);
    echo "endry<HR>";
    // $temporary[$i] = queryVideos($trimmed);
    // array_push($searchFinalResponse['items'],queryVideos($trimmed));
  }

  echo $loops;



  die();
  $queryId = explode(',', $allids);

  $temporary = array();
  $searchFinalResponse['items'] = array();
  
  $loops = sizeof($queryId)/50;
  $start = 0;
  $limit = 50;
  
  for($i=0;$i<$loops;$i++)
  {
    $st = $i * 50;
    $trimmed = array_slice($queryId, $st,$limit);
    // var_dump($trimmed);
    getVideos($trimmed);
    echo "endry<HR>";
    // $temporary[$i] = queryVideos($trimmed);
    // array_push($searchFinalResponse['items'],queryVideos($trimmed));
  }
  // echo '<pre>' . print_r($response,true) . '</pre>';
 

  // $m = new MongoClient();
  // $games = $m->games->hasoffers;

  // $games->insert(array("games" => $response));




}
function generateCsv($data, $delimiter = ',', $enclosure = '"') {
     $contents = "";
       $handle = fopen('php://temp', 'r+');
       //foreach ($data as $line) {
               fputcsv($handle,$data, $delimiter, $enclosure);
       //}
       rewind($handle);
       while (!feof($handle)) {
               $contents .= fread($handle, 8192);
       }
       fclose($handle);
       return $contents;
}


function getVideos($OR){
$base = 'https://api.hasoffers.com/Api?'; 
  $params = array(
  'Format' => 'json'
  ,'Target' => 'Report'
  ,'Method' => 'getReferrals'
  ,'Service' => 'HasOffers'
  ,'Version' => 2
  ,'NetworkId' => $conf['NetworkId']
  ,'NetworkToken' => $conf['NetworkToken']
   ,'limit' => 50000
  ,'fields' => array(
    'Stat.clicks'
    ,'Stat.conversions'
    ,'Stat.offer_id'
  )
  ,'groups' => array(
    'Stat.url'
  )
   ,'filters' =>  array(
      'Stat.offer_id' => array(
        'conditional' => 'EQUAL_TO'
        ,'values' => $OR
      ),
      'Stat.url' => array(
        'conditional' => 'LIKE'
        ,'values' => array('%v=%')
     ) 
    )
  );

    $url = $base . http_build_query($params);
   
  $result = file_get_contents($url);
  $resultarray =  json_decode($result);

  $returnarray = array();
  $temp = array();
  foreach ( $OR as $id) {
    array_push($temp, array("id"=>$id, "videos"=>""));
  }
  echo '<pre>' . print_r($temp, true) . '</pre>';
  foreach($resultarray->response->data->data as $data){
    foreach ($temp as $key => $value) {
      if($temp[$key]['id'] == $data->Stat->offer_id){
        $url = parse_yturl($data->Stat->url);
        echo $data->Stat->url;
        if($url){
          // var_dump($temp[$key]['videos']);
          // var_dump($url); 
         $temp[$key]["videos"] .= $url . ',';

        }
      }
    }
    // array_push($returnarray, $data);
    // $url = parse_url($data->Stat->url);
    // if($url){

    // }
  }
  echo '<pre>' . print_r($temp, true) . '</pre>';

    // echo '<pre>' . print_r($temp, true) . '</pre>';
    echo '<pre>' . print_r($resultarray, true) . '</pre>';
    // echo '<pre>' . print_r($returnarray, true) . '</pre>';

}

function parse_yturl($url) {
    $pattern = '#^(?:https?://)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
    // $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
    preg_match($pattern, $url, $matches);
    return (isset($matches[1])) ? $matches[1] : false;
  }
function queryVideos($trimmed) {
    try{
        require 'include/instance.php';
        $csv_ids = generateCsv($trimmed);
        // echo print_r ($csv_ids);
        $searchResponse = $youtube->videos->listVideos('id,snippet,statistics',
          array('id' => $csv_ids,
            'fields' => 'items(id,snippet(title,publishedAt,channelId,channelTitle,thumbnails(default)),statistics(viewCount))'
        ));
      
        return $searchResponse['items'];
    } 
    catch (Google_ServiceException $e) {
     echo htmlspecialchars($e->getMessage());
    } 
    catch (Google_Exception $e) {
     echo htmlspecialchars($e->getMessage());
    }
}
?>