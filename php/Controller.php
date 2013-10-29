<?php
    include('mysql.conf');
    function my_autoloader($classname) {
        include 'classes/'.$classname.'.php';
    }

    spl_autoload_register('my_autoloader');

    if(isset($_GET)){
        switch ($_GET['type']) {
            case 'User':
                switch ($_GET['action']) {
                    case 'getUser': getUser(); break;
                    case 'signIn': signIn(); break;
                    case 'signOut': signOut(); break;
                    case 'getReferrals': getReferrals(); break;
                    case 'getPlayNowLink': getPlayNowLink(); break;
                }
                break;
            case 'Game':
                switch ($_GET['action']) {
                    case 'loadDefaultGames':loadDefaultGames();break;
                    case 'loadSavedGames':loadSavedGames();break;
                    case 'refreshSavedGames':refreshSavedGames();break;
                    case 'getCurrentGame': getCurrentGame($_GET['alias']); break;
                    case 'loadHotGames': loadHotGames(); break;
                }
                break;
            case 'Video':
                switch ($_GET['action']) {
                    case 'getVideosOfGame': getVideosOfGame($_GET['ids'], $_GET['gamemongoid']); break;
                    case 'getTrendingVideos': getTrendingVideos($_GET['dates']); break;
                    case 'getLatestVideos': getLatestVideos($_GET['dates']); break;
                }
                break;
            case 'Maintain':
                switch ($_GET['action']) {
                    case 'logo' :  changeLogo(); break;
                    case 'removeLogo': removelogo(); break;
                    case 'featured': switch ($_GET['subaction']) {
                                        case 'add': addFeaturedGame($_GET['genre_id'], $_GET['name'], $_GET['alias']); break;
                                        case 'delete': deleteFeaturedGame($_GET['game_id'],$_GET['genre_id']); break;
                                    }
                    break;
                    case 'games': switch ($_GET['subaction']) {
                                        case 'set': setGamesToGenre($_GET['name'], json_decode($_GET['games'][0])); break;
                                    }
                    case 'user':switch ($_GET['subaction']) {
                                        case 'load': loadSiteUsers(); break;
                                        case 'delete': deleteSiteUser($_GET['id']); break;
                                        case 'add': addSiteUser($_GET['id']); break;
                                    }
                    break;
                    case 'genre':
                            switch ($_GET['subaction']) {
                                case 'delete': deleteGenre($_GET['id']);break;
                                case 'add': addGenre($_GET['genre_name'], $_GET['genre_initials']);break;
                            }
                    break;

               }
            break;
        }
    }
    // MAINTAINANCE

    function LoadSiteUsers(){
        $mongo = new MongoConnector();
        echo $mongo->loadSiteUsers();
    }
    function addSiteUser($id){
        $mongo = new MongoConnector();
        echo $mongo->addSiteUser(($id));
        // echo $mongo->loadSiteUsers();
    }
    function deleteSiteUser($user_id){
        $mongo = new MongoConnector();
        $mongo->deleteSiteUser($user_id);
    }
    function deleteFeaturedGame($id,$genre_id){
        $mongo = new MongoConnector();
        echo json_encode($mongo->deleteFeaturedGame($id,$genre_id));
    }
    function addFeaturedGame($genre_id, $name, $alias){
        $mongo = new MongoConnector();
        $game = array("name"=>($name),"alias"=>($alias));
        echo json_encode($mongo->addFeaturedGame($genre_id,$game));
        // echo json_encode($mongo->getFeaturedOffers());
    }
    function deleteGenre($genre_id){
        $mongo = new MongoConnector();
        $mongo->removeGenre($genre_id);
    }
    function addGenre($name, $initials){
        $genre = array("name"=>($name),"initials"=>($initials));
        $mongo = new MongoConnector();
        echo json_encode($mongo->addGenre($genre));
    }
    function changeLogo (){
        $mongo = new MongoConnector();
        $mongo->changeLogo($_POST['gamealias']);
    }
    function removeLogo(){
        $mongo = new MongoConnector();
        $mongo->removelogo($_GET['game']);
    }
    // VIDEOS
    function getTrendingVideos($dates){
        $dates = explode(',', $dates);
        $video = new Video();
        echo json_encode($video->getTrendingVideos($dates));
    }
    function getLatestVideos($dates){
        $dates = explode(',', $dates);
        $video = new Video();
        echo json_encode($video->getLatestVideos($dates),true);
    }
    function getVideosOfGame($ids, $mongoid){
        $ids = explode(',',$ids);
        $video = new Video();

        $videos = $video->getVideosOfGame($ids, $mongoid);
        if($videos == false){
            echo "";
        }
        else{
            
            echo json_encode($videos,true);
        }
    }
    // GAME
    function getCurrentGame($alias){
        $game = new Game();
        echo json_encode($game->getCurrentGame($alias));
    }
    function loadHotGames(){
        $game = new Game();
        echo urldecode(json_encode($game->loadHotGames()));
    }
    function loadDefaultGames(){
        $game = new Game();
        echo ($game->loadDefaultGames());
    }
    function loadSavedGames(){
        $game = new Game();
        echo json_encode($game->loadSavedGames());
    }
    function refreshSavedGames(){
        $game = new Game();
        echo json_encode($game->refreshSavedGames());
    }
    // USER1
    function getUser(){
        $user = new User();
        echo json_encode($user->getSavedUser());
    }
    function signOut(){
        $user = new User();
        $user->signOut();
        echo json_encode($user->getSavedUser());
    }
    function signIn(){
        $user = new User();
        $userarr = json_decode($_GET['user'],true);
        if($user->signIn($userarr)===false)
           echo "0";
        else 
           echo json_encode($user->getSavedUser());
    }
    function getReferrals(){
        $user = new User();
        echo json_encode($user->getReferrals());
    }
    function getPlayNowLink(){
        $user = new User();
        echo $user->getPlayNowLink($_GET['offer_id']);
    }
?>