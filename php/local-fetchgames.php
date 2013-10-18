<?php 
  $m = new MongoClient();
  $games = $m->games->hasoffers;
$cursor = $games->find();
// $cursor = $games->find(array(), array('_id' => 0));
$result = array();
$ctr = 0;
foreach ($cursor as $document) {
	array_push($result, $document);
}
// echo '<pre>' . print_r($result,true) . '</pre>';
echo json_encode($result);
// 																																						
?>