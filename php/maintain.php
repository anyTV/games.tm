<?php 
   include('mysql.conf');
    // $con=mysql_connect("localhost","anytv_ron","09213972063");
    if (mysqli_connect_errno($con))
    {echo "Failed to connect to MySQL: " . mysqli_connect_error();}

    mysql_select_db("anytv_gbase2") or die(mysql_error());

// var_dump($_FILES);

    if(isset($_GET)){
        switch ($_GET['mode']) {
            case 'logo' :  changeLogo(); break;
            case 'featured': switch ($_GET['type']) {
                                case 'add': addFeaturedGame($_GET['genre_id'], $_GET['name']); break;
                                case 'delete': deleteFeaturedGame($_GET['game_id']); break;
                            }
            break;
            case 'games': switch ($_GET['type']) {
                                case 'set': setGamesToGenre($_GET['name'], json_decode($_GET['games'][0])); break;
                            }
            case 'user':switch ($_GET['type']) {
                                case 'load': loadSiteUsers(); break;
                                case 'delete': deleteSiteUsers($_GET['id']); break;
                                case 'add': addSiteUsers($_GET['id']); break;
                            }
            break;
            case 'genre':
                    switch ($_GET['type']) {
                        case 'delete': deleteGenre($_GET['id']);break;
                        case 'add': addGenre($_GET['name'], $_GET['ini']);break;
                    }
            break;
            case 'logo':
                switch ($_GET['type']) {
                    case 'change':changeLogo();break;
                }
            break;
            default:
            changeLogo();
        }
    }
    function deleteSiteUsers($id){
        $m = new MongoClient();
        $users = $m->games->siteusers;
        $users->remove( array('aff_id'=>$id));
        echo '1';
    }
    function loadSiteUsers(){
        $m = new MongoClient();
        $users = $m->games->siteusers;
        $cursor = $users->find();
        // $cursor = $games->find(array(), array('_id' => 0));
        $result = array();
        $ctr = 0;
        foreach ($cursor as $document) {
            array_push($result, $document);
        }
        // echo '<pre>' . print_r($result,true) . '</pre>';
        echo json_encode($result);
    }
    function addSiteUsers($id){
        $m = new MongoClient();
        $users = $m->games->siteusers;
        $document = array('aff_id'=>'', 'name'=>'');
        

        $base = 'https://api.hasoffers.com/Api?';
         
        $params = array(
            'Format' => 'json'
            ,'Target' => 'Affiliate'
            ,'Method' => 'findById'
            ,'Service' => 'HasOffers'
            ,'Version' => 2
             ,'NetworkId' => 'mmotm'
            ,'NetworkToken' => 'NETjE4MoLg7NarETCDruHecVmgLHbN'
            ,'id' => $id
            ,'fields' => array('company')
        );
         
        $url = $base . http_build_query( $params );
         
        $result = file_get_contents( $url );
         
        // echo '<pre>';
        // print_r( json_decode( $result ) );
        // echo '</pre>';
        $arr = json_decode( $result );
        if(!empty($arr->response->data))
            $users->insert(array('aff_id'=>$id,'name'=>$arr->response->data->Affiliate->company));
        echo '1';
        // echo '<pre>' . print_r($result,true) . '</pre>';
        // echo json_encode($result);
    }
    function addGenre($name, $ini){
        $result = mysql_query("INSERT INTO genre (genre_name, genre_initials) VALUES ('".mysql_real_escape_string($name)."','".mysql_real_escape_string($ini)."')");
        if($result)
            echo '1';
    }
    function deleteGenre($id){
        $result = mysql_query("DELETE FROM genre where genre_id = $id");
        $result = mysql_query("DELETE FROM genre_game where genre_id = $id");

        if($result)
            echo '1';
    }
    function addFeaturedGame($id, $name){


        $result = mysql_query("INSERT INTO genre_game (`genre_id` ,  `game_name`) VALUES ($id,'".mysql_real_escape_string($name)."')");
        if($result)
            echo '1';

    }
    function deleteFeaturedGame($id){
        $result = mysql_query("DELETE FROM genre_game where game_id = $id");
        if($result)
            echo '1';
    }
    function setGamesToGenre($genre_name, $gamesgiven){
        $m = new MongoClient();
        $games = $m->games->hasoffers;
        // var_dump($gamesgiven);
        foreach ($gamesgiven as $k => $g) {
            // $cursor = $games->findOne(array("fid" => intval($g)), array("_id"=>1) );
            // $document = array(
            // '$set' => array(
            //             "genre" => $genre_name
            //     )
            // var_dump($g);
            // echo $gamesgiven[$k] . ' ' . $genre_name;
            // );
            // $cursor = $games->findOne(array("fid" => intval($gamesgiven[$k])));
            $games->update(array("fid"=>intval($gamesgiven[$k])), array('$set' => array("genre" => $genre_name)));
            $cursor = $games->findOne(array("fid" => intval($gamesgiven[$k])));
            // var_dump($cursor);

        }
        echo '1';
  // echo $id;
         // $res = $games->update(array("_id"=>$id),$document);
    }
    function changeLogo (){
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
              "../upload/" . preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["game"]) . ".".$extension);
              changeMongoPicName(".".$extension);
              // echo  "http://" . $_SERVER['HTTP_HOST'] . "/#/maintainance/".$_POST['gameid'];
              header("Location:". "http://" . $_SERVER['HTTP_HOST'] . "/#/maintainance/".$_POST['gameid']);
              // echo 1;
            }
          }
        else
          {
          echo "Invalid file";
          }
    }
    function changeMongoPicName($ext){

        $m = new MongoClient();
        $games = $m->games->hasoffers;
        $games->update(array("pic"=>$_POST["game"]), array('$set' => array("pic" =>preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["game"]) . $ext)));
        $cursor = $games->findOne(array("pic"=>preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["game"]) . $ext));
        // var_dump($cursor);
    }
?>
