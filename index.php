<?php
header('Cache-Control: private, max-age=0');
header('Expires: -1');
header('Content-Type: text/html; charset=UTF-8');
?>
<html ng-app="Game">
<head>
  <TITLE>Games on AnyTV!</TITLE>
  <meta property="og:title" content="Games on AnyTV!">
  <meta property="og:type" content="article">
  <meta property="og:url" content="http://www.games.tm">
  <meta property="og:image" content="http://www.games.tm/img/games-home_logo.png">
  <meta property="og:site_name" content="Games">
  <meta property="og:description" content="Play free games! Earn about $1.00 every time someone new plays a game after watching your video or livestream!">
  <meta name="Description" content="Play free games! Earn about $1.00 every time someone new plays a game after watching your video or livestream!">
  <meta name="keywords" content="Games, GamesTeam, GamesTM AnyTV, Gameplay, Game Videos" />


  <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet"> -->
  <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
  <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet"> -->
  <!-- <link rel="stylesheet" href="css/bootstrap-responsvie.css"> -->
  <link rel="stylesheet" href="css/post2.css?20131017">
  <script src="http://code.angularjs.org/1.0.7/angular.js"></script>
  <script src="js/ui-bootstrap-tpls-0.6.0.js"></script>
  <script src="js/app.js?20131017"></script>
  <script src="js/control.js?20131017"></script>
  <script src="js/video.js?20131017"></script>

</head>
<body >

    <header ng-controller = "HeadController" ng-init = "init()">
      <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <!-- <nav class="navbar navbar-default navbar-fixed-top" ng-hide="navTop" role="navigation"> -->
          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand"  ><img src="/img/logo.png" alt="Games on anyTV!" class="logo" ng-click="showSide(); clicked =true;"></a><a href="/"><img src="/img/logotext.png" alt="Games on anyTV!" ></a>
          </div>
          <div class="collapse navbar-collapse navbar-ex1-collapse">
              <form class="navbar-form navbar-left" role="search">
                  <div class="form-group ">
                      <div class="input-group">
                          <input type="text" class="form-control searchbar" placeholder="Search game here.."
                          typeahead-editable="false" typeahead-on-select='find($item)' ng-model="searchbar"
                          typeahead="g.name for g in games | filter:$viewValue | limitTo:8">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                      </div>
                  </div>
              </form>
              <ul class="nav navbar-nav navbar-right">
                  <li>
                      <a href="#" class="sites-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-th"></i></a>
                      <ul class="dropdown-menu othersites arrow_box">
                        <li>
                          <a href="http://www.dashboard.tm">
                            <img class='sitelogos' src="img/favicon/dashboard.png">
                            <div>
                                Dashboard
                            </div>
                          </a>
                          <a href="http://www.any.tv">
                            <img class='sitelogos' src="img/favicon/anytv.png">
                            <div>
                                any.TV
                            </div>
                          </a>
                          <a href="http://www.community.tm">
                            <img class='sitelogos' src="img/favicon/community.png">
                            <div>
                                Community
                            </div>
                          </a>
                          <a href="http://www.any.tv">
                            <img class='sitelogos' src="img/favicon/hb_favicon.png">
                            <div>
                                Heartbeat
                            </div>
                          </a>
                          <a href="http://www.mmo.tm">
                            <img class='sitelogos' src="img/favicon/mmo.png">
                            <div>
                                MMO
                            </div>
                          </a>
                          <a href="http://www.gameplay.tm">
                            <img class='sitelogos' src="img/favicon/games_favicon.png">
                            <div>
                              Gameplay
                            </div>
                          </a>
                          <a href="http://www.youtube.com/anyTVnetwork">
                            <img class='sitelogos' src="img/favicon/youtube.png">
                            <div>
                                YouTube
                            </div>
                          </a>
                          <a href="http://www.twitter.com/anyTVnetwork">
                            <img class='sitelogos' src="img/favicon/twitter.png">
                            <div>
                                Twitter
                            </div>
                          </a>
                          <a href="http://www.facebook.com/anyTVnetwork">
                            <img class='sitelogos' src="img/favicon/facebook.png">
                            <div>
                                Facebook
                            </div>
                          </a>
                        </li>
                        
                      </ul>
                  </li>
                  <li><button data-toggle="modal" href=".refModal" ng-show="user.email!=''" class="btn btn-primary referlink" ng-click="referLink(user.id)">Get your Refer-a-Friend link</button></li>
                  <li class="dropdown">
                      <a href=".myModal1"  data-toggle="modal" ng-show="user.email==''" class="btn btn-primary sign-in">Sign in</a>
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" ng-show="user.email!=''">{{user.email}}<b class="caret"></b></a>
                      <ul class="dropdown-menu user-menu">
                          <li><a href="/php/form-post.php" target="_blank" class="">Check Earnings</a></li>
                          <li><a href="#/referrals" class="">My Referrals</a></li>
                          <li><a href="#/maintainance" target='_blank' class="" ng-show="showMaintainance" ng-click="">Site Maintenance</a></li>
                          <li><a href="/" ng-click="Signout()" class="">Sign out</a></li>
                      </ul>
                  </li>
              </ul>
          </div>

      </nav>
    <!--   <div ng-class="{true:'subnav stick', false:'hidden'}[navTop]" #="subnav" scroll="navTop" ng-click="showSide()">
       <a class="btn-lg" ng-click="showSide()">
         <span class="glyphicon glyphicon-star"></span> GAMELIST
       </a>
       <a ng-click="showSide()"><i  class="glyphicon glyphicon-list"></i></a>

      </div> -->
      <div class="modal fade modal-login myModal1" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" >
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h3><img src="https://media.go2app.org/user_content/brand/logos/mmotm/logo_1362130084.png"> </h3>
            </div>
            <div class="modal-body">

              <div role="form" id="loginform">
                <form>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>

                    <input value="" type="text"class="form-control" id="hemail" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password </label>
                    <input value="" type="password" class="form-control" id="hpassword" placeholder="Password">
                  </div>
                  <button data-loading-text="Signing in..." id="loginbtn1" class="btn btn-primary" ng-click="Login1()">Sign in</button>
                  <span class="pull-right">
                  New to any.tv? <a class="btn btn-danger" href="http://www.dashboard.tm/signup/1000"> Create an account</a>
                  </span>
                </form>
              </div>

              <div id="waitform">
               <div id="loginheader">
                 <div class="alert logmess {{loginmessage.cls}} " id="loginmessage"> {{loginmessage.message}} </div>
               </div>
             </div>

           </div>
         </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
     </div>
     <div class="modal fade modal-login refModal" id="refModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header no-border" >
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h1>Refer-a-Friend </h1>
           </div>
           <div class="modal-body">
            <h6 class="text-center">Spread this link to others to earn 10% lifetime bonus!</h6>
            <div>
              <div class="pull-left col-lg-12">
                <input type='text' id='refertext' class="form-control text-center" value="http://www.dashboard.tm/signup/{{user.aff_id}}">
                <button data-toggle="modal" href=".referModal" class="btn btn-primary" id="referlink" onmouseover="copy2('#referlink','#refertext')">Click to Copy</button>
              </div>
              <div class="clearfix"></div>
            </div>  
            
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
      </div>
      <div class="notifbox arrow_box2" id="notif" ng-hide="clicked">
        Click here to show the sidebar! <a class="btn btn-success" ng-click="clicked=true;">Got It!</a>
        
      </div>
    </header>
    <div class="wrapper">
        <div class="sidebar" ng-controller="SidebarController" id="side-id">

            <div class="panel-group" id="accordion" >
                <div class="panel featured-box" ng-repeat="g in hgames">
                    <div class="title" >
                        <a ng-click="thawOthers();g.burn = !g.burn; agburn = false;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$index}}" >
                            <div>
                                <span class='pull-left' tooltip-placement="right" tooltip="{{g.genre_name}}">
                                    <i class="glyphicon glyphicon-fire {{{true:'burn',false:''}[g.burn]}}"></i>
                                    &nbsp;{{g.genre_initials}} 
                                </span>
                                <span class='pull-right burn'>
                                    {{g.games.length}}
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                    <div id="collapse{{$index}}" class=" panel-collapse collapse featured-box-content ">
                        <a href="#/game/{{game.game_fid}}" ng-repeat="game in g.games" ng-click="closeOthers();game.active = !game.active;" ng-class="{true:'hotgame-item-active',false:'hotgame-item'}[game.active]"> &nbsp; {{game.game_name}}<BR></a>
                    </div>
                </div>
                <div class="panel featured-box" >
                    <div class="title">
                        <a ng-click="thawOthers();agburn = !agburn" id="allgames" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse00" >
                            <div tooltip-placement="top" tooltip="{{g.genre_name}}">
                                <span class='pull-left'>
                                    <i class="glyphicon glyphicon-fire {{{true:'burn',false:''}[agburn]}}"></i>&nbsp; All Games
                                </span>
                                <span class='pull-right burn'>
                                  {{games.length}}
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                    <div id="collapse00" class="panel-collapse collapse in featured-box-content all-games midget" style="top:{{(hgames.length+1)*24+50}}px;">
                            <a href="#/game/{{game.fid}}" ng-repeat="game in games | orderBy:'name':false;" ng-click="declickOthers(game);game.clicked = true;"
                             class="hotgame-item" ng-class="{true:'hotgame-item-active',false:'hotgame-item'}[game.clicked]"> {{game.name}}</a>
                        
                    </div>
                </div>
            </div>

        </div>

        <div class="content-wrapper" ng-view id="mainview">
        </div>

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://timeago.yarp.com/jquery.timeago.js"></script>
<script src="js/human.js"></script>
<script type="text/javascript" src="js/jquery.zclip.min.js"></script>

</html>