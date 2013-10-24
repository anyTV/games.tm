<?php
    set_time_limit (0);
    class Game{
        /* Protected Properties
            -------------------------------*/
        protected $_ho;
        protected $_mongoConnector;

        public function __construct(){
            $this->_ho = new HasOffers();
            $this->_mongoConnector = new MongoConnector();
        }

        public function loadDefaultGames(){
            $games = file_get_contents("../php/default-games.php");
            return $games;
        }

        public function loadSavedGames(){
            return ($this->_mongoConnector->getSavedGames());
        }

        public function refreshSavedGames(){
            // LOAD FROM HASOFFERS
            $arr = $this->_ho->request(array('Target' => 'Offer'
                                    ,'Method' => 'findAll'
                                    ,'limit' => 100000
                                    ,'fields' => array('name', 'id', 'default_payout', 'status', 'redirect_offer_id', 'preview_url')
                                    ,'sort' => array('Offer.name' => 'asc')
                                    ,'filters' => array('OR' => array(
                                        array('status' => 'active'),array('status' => 'paused')
                                        )
                                    ),'contain[]'=>'Country'), false);

            $response = array();
            $names = array();
            // SAVE LOGOS AND SAVE INITIAL VALUES
            $haspics = $this->_mongoConnector->saveLogos();

            foreach($arr->data as $d){
                if(end(explode(" ", strtolower($d->Offer->name)))=="createtest")
                    continue;
                if(!in_array($d->Offer->name, $names)){
                    array_push($names, $d->Offer->name);
                    $pict = "";
                    $alias = preg_replace("/[^a-zA-Z0-9]+/", "", strtolower($d->Offer->name));
                    foreach ($haspics as $pic) {
                        if($d->Offer->name == $pic['name']){
                            $pict = $pic['pic'];
                        }
                    }
                array_push($response, array("name" => $d->Offer->name , "website"=>$d->Offer->preview_url, 'clicked'=>false, 'pic' => $pict, "alias"=>$alias, "id"=> "", "country"=> array(), "pay" => $d->Offer->default_payout, 'state' => $d->Offer->status, "videos"=>array(), "redirect_offer_id" => $d->Offer->redirect_offer_id, 'realstate'=>''));
                    // array_push($response, array("name" => $d->Offer->name , "website"=>$d->Offer->preview_url, 
                    //     "clicked"=>false,"fid"=>$ctr++, "pic" => $pict, "alias"=>$alias, "id"=> "", "country"=> array(),

                    //     "videos"=>array(), "redirect_offer_id" => $d->Offer->redirect_offer_id,));
                }
            }
            // var_dump($response);
            // GROUP COUNTRIES
              foreach($arr->data as $d){
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
            $this->_mongoConnector->updateGames($response);
            return ($response);

        }

        public function loadHotGames(){
            return $this->_mongoConnector->getFeaturedOffers();
        }
        public function getCurrentGame($alias){
            return $this->_mongoConnector->getCurrentGame($alias);
        }
    }

?>