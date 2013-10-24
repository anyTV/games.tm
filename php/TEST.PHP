<?php
    
    function my_autoloader($classname) {
        include 'classes/'.$classname.'.php';
    }

    spl_autoload_register('my_autoloader');

    // if(isset($_POST)){
    //     switch (variable) {
    //         case 'value':
    //             # code...
    //             break;
            
    //         default:
    //             # code...
    //             break;
    //     }
    // }

    // $user = new User();
    // $userarr = array('email'=>'georgenet@any.tv','password'=>'demo');
    // $user->signIn($userarr);
    // echo $user->getPlayNowLink(38);
    // $user->getReferrals();
    // $user->signOut();
    // $logged = $user->getSavedUser();
    // var_dump($logged);
    // if($user->signIn($userarr) == false)
    //     echo "failed";
    // $video = new Video();
    // $dates = "2013-10-17,2013-10-22";
    // $ids = explode(",",$dates);
    // echo $video->loadNewestVideos($ids);
    $game = new Game();
    $mongo = new MongoConnector();
    // $mongo->removeGenres();
    $genre = array();
    $genre['initials'] = "BBG";
    $genre['name'] = "Browser Base Game";
    $mongo->addGenre($genre);
    $mygame['name'] = "Andromeda 5";
    $mygame['alias'] = "andromeda5";
    echo $mongo->addFeaturedGame($genre, $mygame);
    // echo json_encode($mongo->getFeaturedOffers());
    // echo $mongo->getFeaturedOffers();
    // echo $game->loadDefaultGames();
    // echo $game->loadSavedGames();
    // $mongo->removeGenres();
    // echo json_encode($game->refreshSavedGames());
?>