var Game = angular.module('Game', ['ui.bootstrap.tpls', 'ui.bootstrap']  )
  .config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
     $routeProvider.
         when("/", {templateUrl:'views/home.php'}).
         when("/maintainance", {templateUrl:'views/maintain.php'}).
         when("/maintainance/:message", {templateUrl:'views/maintain.php'}).
         when("/settings/:shit", {templateUrl:'settings.html'}).
         when("/game/:gameid", {templateUrl:'views/game.php'}).
         when("/referrals/", {templateUrl:'views/referral.php'});
  }]);

function track(label, data){ console.log(label); console.log(data); }
function isInArray(value, array) {  return array.indexOf(value) > -1 ? true : false; }
function dateDashes(d, dec){
	var curr_date = d.getDate()-dec;
	var curr_month = d.getMonth() + 1; 
	var curr_year = d.getFullYear();
	return curr_year + "-" + curr_month + "-" + curr_date; 
}
var ini = 0;
function copy(id, tocopy){
    if(ini ==0){
    ini = 1;
        $(id).zclip({
           path: 'js/ZeroClipboard.swf',
           copy: $(tocopy).val(),
           beforeCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-warning');
           },
           afterCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-success');
            $(tocopy).val($(tocopy).val()+' - Copied');
           }
        });
      }
}
var ini2 =0;
function copy2(id, tocopy){
    if(ini2 ==0){
      ini2 = 1;
        $(id).zclip({
           path: 'js/ZeroClipboard.swf',
           copy: $(tocopy).val(),
           beforeCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-warning');
           },
           afterCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-success');
            $(tocopy).val($(tocopy).val()+' - Copied');
           }
        });
      }
}


Game.filter('startFrom', function() {
    return function(input, start) {
        if(input!=null){

        start = +start; //parse to int
        return input.slice(start);
        }
    }
});
Game.factory('gamesService', function ($q, $http, $rootScope) {
    return {

        getDefaultGames:function(){
            var deferred = $q.defer();
            track('request','php/default-games.php');
            $http({ method: 'POST', url: 'php/default-games.php'}).success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getGames:function () {
            var deferred = $q.defer();
            track('request','php/local-fetchgames.php');
            $http({ method: 'POST', url: 'php/local-fetchgames.php',}).
            success(function(data, status, headers, config) {
        	    if (data.length <1){
        		    $http({ method: 'POST', url: 'php/local-savegames.php?target=Offer&method=findAll',}).
                    success(function(data, status, headers, config) {
        				  deferred.resolve(data);
        			  });
          		}
          		else {
          			deferred.resolve(data);
                }
        	});
        	return deferred.promise;
        },
        getFreshGames:function(){
            var deferred = $q.defer();
            track('request','php/local-savegames.php?target=Offer&method=findAll');
            $http({ method: 'POST', url: 'php/local-savegames.php?target=Offer&method=findAll',}).
            success(function(data, status, headers, config) {
              deferred.resolve(data);
            });
            return deferred.promise;
        },
        getCurrentGame: function(fid){
            var deferred = $q.defer();
            track('request','php/local-fetchcurrent.php?g='+fid);
            $http({ method: 'POST', url: 'php/local-fetchcurrent.php?g='+fid}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getGameDetails:function(game){
            var deferred = $q.defer();
            track('request','php/mysql_getgamedetails.php?g='+game);
            $http({ method: 'POST', url: 'php/mysql_getgamedetails.php?g='+game}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getGameScreens:function(game){
            var deferred = $q.defer();
            track('request','php/mysql_listofss.php?g='+game);
            $http({ method: 'POST', url: 'php/mysql_listofss.php?g='+game}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getHotGames:function(){
            var deferred = $q.defer();
            track('request','php/mysql_hotgames.php');
            $http({ method: 'POST', url: 'php/mysql_hotgames.php'}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        LoadSiteUsers: function(){
          var deferred = $q.defer();
          $http({method: 'POST', url: 'php/maintain.php?mode=user&type=load'}).
          success(function(data, status, headers, config) {
                    deferred.resolve(data);
                    track('Users',data);
          });
          return deferred.promise;
        }
    }
});
Game.factory('videoService', function ($q, $http) {
    return {
        getVideosOfGame:function(gameid, gamemongoid){
            var deferred = $q.defer();
            track('request','php/local-savevideos.php?g='+gameid+'&gamemongoid='+gamemongoid);
            $http({method: 'POST',url: 'php/local-savevideos.php?g='+gameid+'&gamemongoid='+gamemongoid}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getTrendingVideos:function(d) {
            var deferred = $q.defer();
            track('request:', 'php/ho-getTrafficByDate.php?g='+d);
            $http({ method: 'POST', url: 'php/ho-getTrafficByDate.php?g='+d,}).
            success(function(data, status, headers, config) {
              deferred.resolve(data);
            });
            return deferred.promise;
        },
        getNewestVideos:function(d){
            var deferred = $q.defer();
            track('request:', 'php/ho-getLatest.php?g='+d);
            $http({ method: 'POST', url: 'php/ho-getLatest.php?g='+d,}).
            success(function(data, status, headers, config) {
              deferred.resolve(data);
            });
            return deferred.promise;
        }
      }
});
Game.factory('userService', function ($q, $http) {
    return {
        getUser:function () {
          var deferred = $q.defer();
          track('request','php/local-user.php?type=get');
          $http({ method: 'POST', url: 'php/local-user.php?type=get',}).
          success(function(data, status, headers, config) {
                user = data;
                track('user in session', user);
                deferred.resolve(data);
           });
           return deferred.promise;                
        },
        checkUser:function(email,password){
          var deferred = $q.defer();
          track('request','php/ho-checkUser.php?email='+email+"&password="+password);
          $http({ method: 'POST', url: 'php/ho-checkUser.php?email='+email+"&password="+password,}).
          success(function(data, status, headers, config) {
                deferred.resolve(data);
              });
          return deferred.promise;

        },
        setUser:function(email1,password1, id1, aff_id1){
          var deferred = $q.defer();
          track('saving user', 'php/local-user.php?type=set&email='+email1+'&id='+id1+'&password='+password1+'&aff_id'+aff_id1);
          $http({ method: 'POST', url: 'php/local-user.php?type=set&email='+email1+'&id='+id1+'&password='+password1+'&aff_id'+aff_id1,}).
          success(function(data, status, headers, config) {
                deferred.resolve(data);
                user = [{email:""+email1, id:""+id1, password:""+password1, aff_id:aff_id1 }];
                return user;
          });
          return deferred.promise;
        },
        getPlayNowLink:function(affiliate,offer){
          var deferred = $q.defer();
          track('request','php/ho-playnow.php?offer='+offer+"&affiliate="+affiliate);
          $http({method: 'POST', url: 'php/ho-playnow.php?offer='+offer+"&affiliate="+affiliate}).
          success(function(data, status, headers, config) {
                    deferred.resolve(data);
          });
          return deferred.promise;
        },
        getReferralLink: function(user_id){
          var deferred = $q.defer();
          track('request','php/ho-referrallink.php?id='+user_id);
          $http({method: 'POST', url: 'php/ho-referrallink.php?id='+user_id}).
          success(function(data, status, headers, config) {
                    deferred.resolve(data);
          });
          return deferred.promise;
        }
    }      
});
Game.factory('maintainService', function ($q, $http) {
  return {
    deleteGenre:function (id) {
      var deferred = $q.defer();
      $http({method: 'POST', url: 'php/maintain.php?mode=genre&type=delete&id='+id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;
    },
    addGenre:function(game){
      var deferred = $q.defer();
      track('addgenre',game)
      $http({method: 'POST', url: 'php/maintain.php?mode=genre&type=add&name='+game.genre_name+'&ini='+game.genre_initials}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;
    },
    addFeaturedGame: function(game, genre){
      var deferred = $q.defer();
      track('addfeat','php/maintain.php?mode=featured&type=add&name='+game.name+'&genre_id='+genre.genre_id)
      $http({method: 'POST', url: 'php/maintain.php?mode=featured&type=add&name='+game.name+'&genre_id='+genre.genre_id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;

    },
    deleteFeaturedGame: function(game_id){
      var deferred = $q.defer();
      track('deletefeat','php/maintain.php?mode=featured&type=delete&game_id='+game_id)
      $http({method: 'POST', url: 'php/maintain.php?mode=featured&type=delete&game_id='+game_id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;

    },
    setGamesToGenre: function(genre_name, games){
      var deferred = $q.defer();
      track('setgames','php/maintain.php?mode=games&type=set&name='+genre_name+"&games[]="+JSON.stringify(games))
      $http({method: 'POST', url: 'php/maintain.php?mode=games&type=set&name='+genre_name+"&games[]="+JSON.stringify(games)}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;
    },
    LoadSiteUsers: function(){
      var deferred = $q.defer();
      $http({method: 'POST', url: 'php/maintain.php?mode=user&type=load'}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
                track('Users',data);
      });
      return deferred.promise;
    },
    deleteSiteUsers: function(aff_id){
      var deferred = $q.defer();
      $http({method: 'POST', url: 'php/maintain.php?mode=user&type=delete&id='+aff_id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
                track('Users',data);
      });
      return deferred.promise;
    },
    addSiteUsers: function(id){
      var deferred = $q.defer();

      $http({method: 'POST', url: 'php/maintain.php?mode=user&type=add&id='+id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
                 track('php/maintain.php?mode=user&type=add&id='+id, data);
      });
      return deferred.promise;
    }

  }  

});
Game.filter('paginate', function() {
  return function(input, currentPage, numPerPage) {
  var begin = ((currentPage - 1) * numPerPage)
  , end = begin + numPerPage;
  if(input)
   return input.slice(begin, end);
  };
});

Game.directive('whenScrolled', function() {
    return function(scope, elm, attr) {
        var raw = elm[0];
        
        elm.bind('scroll', function() {
            if (raw.scrollTop + raw.offsetHeight >= raw.scrollHeight) {
                scope.$apply(attr.whenScrolled);
            }
        });
    };
});
Game.directive("scroll", function ($window) {
        return function(scope, element, attrs) {
            var windowEl = angular.element($window);

                windowEl.bind('scroll', function() {
                  scope.$apply(function() {
                    // track('scroll', $window)
                    if($window.scrollY == 0)
                    scope[attrs.scroll] = false;
                    else{
                    scope[attrs.scroll] = true;

                    }
                  });
                });

            // element.bind("scroll", function() {
            //   scope[attrs.scroll] = true;
            //     scope.$apply();
            // });
        };
    });


