<?php
    set_time_limit (0);
    class Video { 
        /* Protected Properties
          -------------------------------*/
        protected $_ho;
        protected $_mongoConnector;

        public function __construct(){
            $this->_ho = new HasOffers();
            $this->_mongoConnector = new MongoConnector();
        }
        function getVideosOfUser($affiliate_id){
            $arr = $this->_ho->request(array('Target' => 'Report'
                                    ,'Method' => 'getReferrals'
                                    ,'limit' => 100000
                                    ,'fields' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id', 'Stat.clicks', 'Offer.name', 'Stat.count', 'Stat.date')
                                    ,'groups' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id')
                                    ,'filters' => array('Stat.affiliate_id' => array('conditional' => 'EQUAL_TO', 'values' => $affiliate_id)
                                    , 'Stat.url' => array(
                                      'conditional' => 'LIKE'
                                      ,'values' => array('%v=%')
                                      ))
                                    ,'sort' => array('Stat.clicks' => 'DESC')), false);
            if(empty($arr->data)){ 
              return false;
            }

            $queryId = array();
            $vidstats = array();
            $clicks = 0; $conversions; $oldclicks = 0; $oldconversions = 0; $ctr = 0;
            $searchFinalResponse = array("count" => $arr->count);
            // var_dump($searchFinalResponse);
            // return;
            foreach ( $arr->data as $stat){
              $url = $this->parse_yturl($stat->Stat->url);
              if($url&&!in_array($url, $queryId)){
                array_push($queryId, $url);
                array_push($vidstats, array('url'=>$url, 'clicks'=> 0, 'Sources' => array("Names" => array(), 'clicks' => array())));
              }
            }

            $duplicate = $vidstats;
            foreach ( $arr->data as $stat){
              $url = $this->parse_yturl($stat->Stat->url);
              if($url){
                foreach ($queryId as $key => $value) {
                  if($queryId[$key]==$url){
                     $vidstats[$key]['clicks'] += $stat->Stat->clicks;
                     if(!in_array($stat->Offer->name, $vidstats[$key]['Sources']['Names'])){

                       array_push($vidstats[$key]['Sources']['Names'], $stat->Offer->name);
                       array_push($vidstats[$key]['Sources']['clicks'], $stat->Stat->clicks);
                     }
                     else {
                        $oldkey = array_search($stat->Offer->name, $vidstats[$key]['Sources']['Names']);
                        $vidstats[$key]['Sources']['clicks'][$oldkey] += $stat->Stat->clicks;
                     }
                  }
                }
              }
            }
            // var_dump($v);
            $temporary = array();
            $searchFinalResponse['items'] = array();
            
            $loops = sizeof($queryId)/50;
            $start = 0;
            $limit = 50;
            
            for($i=0;$i<$loops;$i++)
            {
              $st = $i * 50;
              $trimmed = array_slice($queryId, $st,$limit);
              $temporary[$i] = $this->queryVideos($trimmed);
            }

            array_push($searchFinalResponse['items'],$temporary[0]);
            for($i=1;$i<sizeof($temporary);$i++)
            {
              foreach($temporary[$i] as $eachData)
              {
                array_push($searchFinalResponse['items'][0],$eachData);
              }
            }

             $ctr = 0;
            $response_array = array();
            foreach ( $searchFinalResponse['items'][0] as $vid){
             foreach ($vidstats as $stat) {
                if($vid['id']==$stat['url']){
                  $vid['stat'] = ($stat);
                   break;
                }     
             }
              array_push($response_array, $vid);
            }
            // echo '<pre>'  . print_r($response_array,true) . '</pre>';
            return $response_array;
        }
        function getVideosOfGame($ids, $gamemongoid){
            $arr = $this->_ho->request(array('Target' => 'Report'
                                    ,'Method' => 'getReferrals'
                                    ,'limit' => 100000
                                    ,'fields' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id', 'Stat.clicks', 'Offer.name', 'Stat.count', 'Stat.date')
                                    ,'groups' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id')
                                    ,'filters' => array('Stat.offer_id' => array('conditional' => 'EQUAL_TO', 'values' => $ids)
                                    , 'Stat.url' => array(
                                      'conditional' => 'LIKE'
                                      ,'values' => array('%v=%')
                                      ))
                                    ,'sort' => array('Stat.clicks' => 'DESC')), false);
            if(empty($arr->data)){
              return false;
            }
            $queryId = array();
            $vidstats = array();
            $clicks = 0; $conversions; $oldclicks = 0; $oldconversions = 0; $ctr = 0;
            $searchFinalResponse = array("count" => $arr->count);
            foreach ( $arr->data as $stat){
              $url = $this->parse_yturl($stat->Stat->url);
              if($url&&!in_array($url, $queryId)){
                array_push($queryId, $url);
                array_push($vidstats, array('url'=>$url, 'clicks'=> 0));
              }
            }
            $duplicate = $vidstats;
            foreach ( $arr->data as $stat){
              $url = $this->parse_yturl($stat->Stat->url);
              if($url){
                foreach ($queryId as $key => $value) {
                  if($queryId[$key]==$url){
                     $vidstats[$key]['clicks'] += $stat->Stat->clicks;
                  }
                }
              }
            }

            $temporary = array();
            $searchFinalResponse['items'] = array();
            
            $loops = sizeof($queryId)/50;
            $start = 0;
            $limit = 50;
            
            for($i=0;$i<$loops;$i++)
            {
              $st = $i * 50;
              $trimmed = array_slice($queryId, $st,$limit);
              $temporary[$i] = $this->queryVideos($trimmed);
            }

            array_push($searchFinalResponse['items'],$temporary[0]);
            for($i=1;$i<sizeof($temporary);$i++)
            {
              foreach($temporary[$i] as $eachData)
              {
                array_push($searchFinalResponse['items'][0],$eachData);
              }
            }

             $ctr = 0;
            $response_array = array();
            foreach ( $searchFinalResponse['items'][0] as $vid){
             foreach ($vidstats as $stat) {
                if($vid['id']==$stat['url']){
                  $vid['stat'] = ($stat);
                   break;
                }     
             }
              array_push($response_array, $vid);
            }

             $id = new MongoId($gamemongoid);

             $this->_mongoConnector->saveVideosToGame($id,$response_array);
             return $response_array;
        }


        public function getTrendingVideos($dates){
            $arr = $this->_ho->request(array('Target' => 'Report'
                                    ,'Method' => 'getReferrals'
                                    ,'limit' => 100000
                                    ,'fields' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id', 'Stat.clicks', 'Offer.name', 'Stat.count', 'Stat.date')
                                    ,'groups' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id')
                                    ,'filters' => array('Stat.date' => array('conditional' => 'BETWEEN', 'values' => $dates)
                                    , 'Stat.url' => array(
                                      'conditional' => 'LIKE'
                                      ,'values' => array('%v=%')
                                      ))
                                    ,'sort' => array('Stat.clicks' => 'DESC')), false);
            if(empty($arr->data)){ 
              return false;
            }

            $queryId = array();
            $vidstats = array();
            $clicks = 0; $conversions; $oldclicks = 0; $oldconversions = 0; $ctr = 0;
            $searchFinalResponse = array("count" => $arr->count);
            // var_dump($searchFinalResponse);
            // return;
            foreach ( $arr->data as $stat){
              $url = $this->parse_yturl($stat->Stat->url);
              if($url&&!in_array($url, $queryId)){
                array_push($queryId, $url);
                array_push($vidstats, array('url'=>$url, 'clicks'=> 0, 'Sources' => array("Names" => array(), 'clicks' => array())));
              }
            }

            $duplicate = $vidstats;
            foreach ( $arr->data as $stat){
              $url = $this->parse_yturl($stat->Stat->url);
              if($url){
                foreach ($queryId as $key => $value) {
                  if($queryId[$key]==$url){
                     $vidstats[$key]['clicks'] += $stat->Stat->clicks;
                     if(!in_array($stat->Offer->name, $vidstats[$key]['Sources']['Names'])){

                       array_push($vidstats[$key]['Sources']['Names'], $stat->Offer->name);
                       array_push($vidstats[$key]['Sources']['clicks'], $stat->Stat->clicks);
                     }
                     else {
                        $oldkey = array_search($stat->Offer->name, $vidstats[$key]['Sources']['Names']);
                        $vidstats[$key]['Sources']['clicks'][$oldkey] += $stat->Stat->clicks;
                     }
                  }
                }
              }
            }
            // var_dump($v);
            $temporary = array();
            $searchFinalResponse['items'] = array();
            
            $loops = sizeof($queryId)/50;
            $start = 0;
            $limit = 50;
            
            for($i=0;$i<$loops;$i++)
            {
              $st = $i * 50;
              $trimmed = array_slice($queryId, $st,$limit);
              $temporary[$i] = $this->queryVideos($trimmed);
            }

            array_push($searchFinalResponse['items'],$temporary[0]);
            for($i=1;$i<sizeof($temporary);$i++)
            {
              foreach($temporary[$i] as $eachData)
              {
                array_push($searchFinalResponse['items'][0],$eachData);
              }
            }

             $ctr = 0;
            $response_array = array();
            foreach ( $searchFinalResponse['items'][0] as $vid){
             foreach ($vidstats as $stat) {
                if($vid['id']==$stat['url']){
                  $vid['stat'] = ($stat);
                   break;
                }     
             }
              array_push($response_array, $vid);
            }
            // echo '<pre>'  . print_r($response_array,true) . '</pre>';
            return $response_array;
        }
        public function getLatestVideos($dates){

            $_response = $this->_ho->request(array('Target' => 'Report'
                                    ,'Method' => 'getReferrals'
                                    ,'limit' => 100000
                                    ,'fields' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id', 'Stat.clicks', 'Offer.name', 'Stat.count', 'Stat.date')
                                    ,'groups' => array('Stat.url', 'Stat.affiliate_id', 'Stat.offer_id')
                                    ,'filters' => array('Stat.date' => array('conditional' => 'BETWEEN', 'values' => $dates)
                                    , 'Stat.url' => array(
                                      'conditional' => 'LIKE'
                                      ,'values' => array('%v=%')
                                      ))
                                    ,'sort' => array('Stat.clicks' => 'DESC')), false);
            $queryId = array();
             $vidstats = array();
             $clicks = 0; $conversions; $oldclicks = 0; $oldconversions = 0; $ctr = 0;
             $searchFinalResponse = array("count" => $_response->count);
             foreach ( $_response->data as $stat){
               $url = $this->parse_yturl($stat->Stat->url);
               if($url&&!in_array($url, $queryId)){
                 array_push($queryId, $url);
                 array_push($vidstats, array('url'=>$url, 'clicks'=> 0, 'Sources' => array("Names" => array(), 'clicks' => array())));
                 // var_dump($vidstats);
               }
               $offers = array();
               if($url){
                 foreach ($queryId as $key => $value) {
                   if($queryId[$key]==$url){
                      $vidstats[$key]['clicks'] += $stat->Stat->clicks;
                      if(!in_array($stat->Offer->name, $vidstats[$key]['Sources']['Names'])){

                        array_push($vidstats[$key]['Sources']['Names'], $stat->Offer->name);
                        array_push($vidstats[$key]['Sources']['clicks'], $stat->Stat->clicks);
                      }
                      else {
                         $oldkey = array_search($stat->Offer->name, $vidstats[$key]['Sources']['Names']);
                         $vidstats[$key]['Sources']['clicks'][$oldkey] += $stat->Stat->clicks;
                      }
                   }
                 }
               }
             }
             

             $temporary = array();
             $searchFinalResponse['items'] = array();
             
             $loops = sizeof($queryId)/50;
             $start = 0;
             $limit = 50;
             
             for($i=0;$i<$loops;$i++)
             {
               $st = $i * 50;
               $trimmed = array_slice($queryId, $st,$limit);
               $temporary[$i] = $this->queryVideos($trimmed);
               // array_push($searchFinalResponse['items'],queryVideos($trimmed));
             }

             array_push($searchFinalResponse['items'],$temporary[0]);
             for($i=1;$i<sizeof($temporary);$i++)
             {
               foreach($temporary[$i] as $eachData)
               {
                 array_push($searchFinalResponse['items'][0],$eachData);
               }
             }

              $ctr = 0;
             $response_array = array();
             foreach ( $searchFinalResponse['items'][0] as $vid){
               // var_dump($vid);
              foreach ($vidstats as $stat) {
               // var_dump($stat);

                 if($vid['id']==$stat['url']){
                    // array_push($vid, $stat);
                   $vid['stat'] = ($stat);
                    // array_push($vid['stat'], $stat);
                   // var_dump($stat);
                    break;
                 }     
              }
               array_push($response_array, $vid);
             }
              // echo '<pre>'  . sizeof($response_array) . '</pre>';
             $arr = array();
             foreach ($response_array as $k => $v) {
               $input = date('d/m/Y', strtotime($response_array[$k]['snippet']['publishedAt']));
               if($this->isBetween($input)==false){
                 unset($response_array[$k]);
               }
               else {
                 array_push($arr, $response_array[$k]);
               }
             }


            return $arr;
        }

        function parse_yturl($url) {
            $pattern = '#^(?:https?://)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
            // $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
            preg_match($pattern, $url, $matches);
            return (isset($matches[1])) ? $matches[1] : false;
        }
        function queryVideos($trimmed) {
            try{
                require '../php/include/instance.php';
                $csv_ids = $this->generateCsv($trimmed);
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
        function isBetween($date){
          $paymentDate = DateTime::createFromFormat('d/m/Y', $date);
          $contractDateBegin = DateTime::createFromFormat('d/m/Y',date('d/m/Y',strtotime("-5 days")) );
          $contractDateEnd = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));

          if ($paymentDate >= $contractDateBegin && $paymentDate <= $contractDateEnd)
          {
            return true;
          }
          else{
            return false;
          }
        }

    }
?>