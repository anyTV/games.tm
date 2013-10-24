<?php 
    
    class MongoConnector {
        /* Protected Properties
            -------------------------------*/
        protected $_m;
        protected $_games;
        protected $_siteusers;
        protected $_featuredoffers;
        protected $_ho;
        public function __construct(){
            if ($_SERVER['HTTP_HOST'] == 'www.games.tm'){
                $this->_m = new MongoClient(); 
            }
            else{
                $this->_m = new MongoClient(); 
            }
            $this->_ho = new HasOffers();
            $this->_games = $this->_m->games->hasoffers;
            $this->_siteusers = $this->_m->games->_siteusers;
            $this->_featuredoffers = $this->_m->games->_featuredoffers;

        }
        // USERS
        function isIdAdmin($affiliate_id){
            if(intval($affiliate_id) === 1000)
                return true;

            $cursor = $this->_siteusers->findOne(array("affiliate_id"=>$affiliate_id));
            if($cursor===null){
                return false;
            }
            else{
                return true;
            }
        }
        // GAMES
        function getSavedGames(){
            $cursor = $this->_games->find();
            $cursor->sort(array('alias'=>1));
            $result = array();
            foreach ($cursor as $document) {
                array_push($result, $document);
            }
            return $result;
        }
        function updateGames($games){
            $this->_games->remove();
            foreach($games as $g){
                // var_dump($g);
                // break;
                $this->_games->insert($g);
            }
            file_put_contents('../php/default-games.php', json_encode($games));
        }
        function getCurrentGame($alias){
            $cursor = $this->_games->findOne(array("alias" => $alias));
            $result = array();
            $ctr = 0;
            // var_dump($cursor);

            // var_dump($cursor);
            return $cursor;
        }
        // VIDEOS
        function saveVideosToGame($videos, $id){
            $document = array(
                      '$set' => array(
                                  "videos" => $videos
                          )
                      );
            $res = $this->_games->update(array("_id"=>$id),$document);
        }
        // MAINTAINANCE
        function saveLogos(){
            $cursor = $this->_games->find(array(),array("name"=>1, 'pic'=>1, '_id'=>0));
            $haspics = array();
            foreach($cursor as $g){
                if(!empty($g)){
                    if(strpos($g['pic'], '.') !== FALSE){
                        array_push($haspics, array('name'=>$g['name'],'pic'=>$g['pic']));
                    }
                }
            }
            return $haspics;
        }
        
        
        function removeGenre($genre_id){
            $this->_featuredoffers->remove(array('_id'=>new MongoId($genre_id)));
        }
        function removeGenres(){
            $this->_featuredoffers->remove();
        }
        function addGenre($genre){
            $id = new MongoId();
            $document = array('_id'=>$id, 'burn' => false, 'genre_initials' => $genre['initials'], 'genre_name' => $genre['name'], 'games' => array());
            $this->_featuredoffers->insert($document);
            $cursor = $this->_featuredoffers->findOne( array('_id'=>$id));

            // var_dump($cursor);
            return $cursor;
        }
        function getFeaturedOffers(){
            $cursor = $this->_featuredoffers->find(array());
            $result = array();
            foreach ($cursor as $document) {
                array_push($result, $document);
            }
            // var_dump($result);
            return $result;
        }
        function addFeaturedGame($genre_id, $game){
            $id = new MongoId();
            $game = array( '_id'=>$id, 'name'=>$game['name'], 'alias'=>$game['alias'], 'active'=>false);
            $document = array(
                        '$addToSet' => array(
                                    "games" => $game
                            ));
            $this->_featuredoffers->update(array('_id'=>new MongoId($genre_id)), $document );

            $cursor =  $this->_featuredoffers->findOne(array('_id'=>new MongoId($genre_id)));
            return $cursor;
            // var_dump($cursor);
        }
        function deleteFeaturedGame($id,$genre_id){
            $document = array(
                        '$pull' => array(
                                    "games" => array('_id'=>new MongoId($id))
                            ));
           
            $cursor = $this->_featuredoffers->update(array('_id'=>new MongoId($genre_id)),$document);
            $cursor =  $this->_featuredoffers->findOne(array('_id'=>new MongoId($genre_id)));
            return $cursor;
        }
        function changeLogo ($alias){
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 20000)
            && in_array($extension, $allowedExts))
              {
              if ($_FILES["file"]["error"] > 0)
                {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
                }
              else
                {
                  move_uploaded_file($_FILES["file"]["tmp_name"],
                  "../upload/" . $alias . ".".$extension);
                  $this->changeMongoPicName($alias,$extension);
                  // echo  "http://" . $_SERVER['HTTP_HOST'] . "/#/maintainance/".$_POST['gameid'];
                  header("Location:". "http://" . $_SERVER['HTTP_HOST'] . "/#/maintainance/".$alias);
                  // echo 1;
                }
              }
            else
              {
              echo "Invalid file";
              }
        }
        function changeMongoPicName($game,$ext){
            $this->_games->update(array("alias"=>$game), array('$set' => array("pic" => $game.'.'. $ext)));
            // $cursor = $this->_games->findOne(array("alias"=>$game));
            // var_dump($cursor);
        }
        function removeLogo($game){
            $cursor = $this->_games->findOne(array("alias"=>$game));
            // foreach ($cursor as $document) {
            //     var_dump($document);
            if(unlink("../upload/" . $cursor['pic'])){
                $this->_games->update(array("alias"=>$game), array('$set' => array("pic" => '')));
            }
            else{
                echo 'fail';
            }
            // }
            // $cursor = $this->_games->findOne(array("alias"=>$game));
            // var_dump($cursor);
        }
        function addSiteUser($user_id){
            $document = array('aff_id'=>'', 'name'=>'');
            $arr = $this->_ho->request(array('Target' => 'Affiliate'
                                    ,'Method' => 'findById'
                                    ,'limit' => 100000
                                    ,'id' => $user_id
                                    ,'fields' => array('company')), false);
                // var_dump($document);
            if(!empty($arr)){
                $document = array('affiliate_id'=>$user_id,'name'=>$arr->Affiliate->company);
                $this->_siteusers->insert($document);
            }
            else
                return '0';

        }
        function deleteSiteUser($affiliate_id){
            $this->_siteusers->remove(array('affiliate_id'=>$affiliate_id));
        }
        function loadSiteUsers(){
            $cursor = $this->_siteusers->find();
            // echo "load";
            // var_dump($cursor);
            // $cursor = $games->find(array(), array('_id' => 0));
            $result = array();
            $ctr = 0;
            foreach ($cursor as $document) {
                array_push($result, $document);
            }
            // echo '<pre>' . print_r($result,true) . '</pre>';
            return json_encode($result);
        }


    }

?>