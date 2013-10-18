<?php
// Call set_include_path() as needed to point to your client library.
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_YouTubeService.php';
require_once 'google-api-php-client/src/contrib/Google_Oauth2Service.php';

$OAUTH2_CLIENT_ID = '544761083604-31cq4c42g8rt34d32hdmcokkaip012k1.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = '5JMEtO5i-3ngG5QNFTf1lYcl';
$DEVELOPER_KEY = 'AIzaSyAhkr8hbq6J0_4HD8ANO4DQqPoHmQFiFDY';


$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$redirect = filter_var('http://localhost/youtube_thumbnails/google_login.php');
$client->setRedirectUri($redirect);
$client->setDeveloperKey($DEVELOPER_KEY);



$arrScope = array("https://www.googleapis.com/auth/youtubepartner",
                    "https://www.googleapis.com/auth/youtube.upload",
                    "https://www.googleapis.com/auth/youtube",
                    //"https://www.googleapis.com/auth/userinfo.profile",
                    "https://www.googleapis.com/auth/userinfo.email");
                    //"https://www.googleapis.com/auth/plus.me",
                   // "https://www.googleapis.com/auth/plus.login");
$client->setScopes($arrScope);
// $client->setApprovalPrompt("force");
// $client->setAccessType("online");

$youtube = new Google_YoutubeService($client);
$user = new Google_Oauth2Service($client);

?>