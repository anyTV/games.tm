var Game = angular.module('Game', ['ui.bootstrap.tpls', 'ui.bootstrap']  )
  .config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
     $routeProvider.
         when("/", {templateUrl:'views/home.php'}).
         when("/maintainance", {templateUrl:'views/maintain.php'}).
         when("/maintainance/:message", {templateUrl:'views/maintain.php'}).
         when("/settings/:shit", {templateUrl:'settings.html'}).
         when("/game/:alias", {templateUrl:'views/game.php'}).
         when("/game/:alias/:popup", {templateUrl:'views/game.php'}).
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
    // if(ini ==0){
    // ini = 1;
        $(id).zclip({
           path: 'js/ZeroClipboard.swf',
           copy: $(tocopy).val(),
           beforeCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-warning');
           },
           afterCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-success');
           }
        });
      // }
}
var ini2 =0;
function copy2(id, tocopy){
    // if(ini2 ==0){
      // ini2 = 1;
        $(id).zclip({
           path: 'js/ZeroClipboard.swf',
           copy: $(tocopy).val(),
           beforeCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-warning');
           },
           afterCopy: function(){
            $(tocopy).attr('class','form-control text-center btn-success');
           }
        });
      // }
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
            track('request','php/Controller.php?type=Game&action=loadDefaultGames');
            $http({ method: 'POST', url: 'php/Controller.php?type=Game&action=loadDefaultGames'}).success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getSavedGames:function () {
            var deferred = $q.defer();
            track('request','php/Controller.php?type=Game&action=loadSavedGames');
            $http({ method: 'POST', url: 'php/Controller.php?type=Game&action=loadSavedGames',}).
            success(function(data, status, headers, config) {
          			deferred.resolve(data);

        	  });
        	 return deferred.promise;
        },
        refreshSavedGames:function(){
            var deferred = $q.defer();
            track('request','php/Controller.php?type=Game&action=refreshSavedGames');
            $http({ method: 'POST', url: 'php/Controller.php?type=Game&action=refreshSavedGames',}).
            success(function(data, status, headers, config) {
              deferred.resolve(data);
            });
            return deferred.promise;
        },
        getCurrentGame: function(alias){
            var deferred = $q.defer();
            track('request','php/Controller.php?type=Game&action=getCurrentGame&alias='+alias);
            $http({ method: 'POST', url: 'php/Controller.php?type=Game&action=getCurrentGame&alias='+alias}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        loadHotGames:function(){
            var deferred = $q.defer();
            track('request','php/Controller.php?type=Game&action=loadHotGames');
            $http({ method: 'POST', url: 'php/Controller.php?type=Game&action=loadHotGames'}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
    }
});
Game.factory('videoService', function ($q, $http) {
    return {
        getVideosOfGame:function(gameid, gamemongoid){
            var deferred = $q.defer();
            track('request','php/Controller.php?type=Video&action=getVideosOfGame&ids='+gameid+'&gamemongoid='+gamemongoid);
            $http({method: 'POST',url: 'php/Controller.php?type=Video&action=getVideosOfGame&ids='+gameid+'&gamemongoid='+gamemongoid}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            });
            return deferred.promise;
        },
        getTrendingVideos:function(d) {
            var deferred = $q.defer();
            track('request:', 'php/Controller.php?type=Video&action=getTrendingVideos&dates='+d);
            $http({ method: 'POST', url: 'php/Controller.php?type=Video&action=getTrendingVideos&dates='+d,}).
            success(function(data, status, headers, config) {
              deferred.resolve(data);
            });
            return deferred.promise;
        },
        getVideosOfUser:function(affiliate_id){
            var deferred = $q.defer();
            track('request:', 'php/Controller.php?type=Video&action=getVideosOfUser&affiliate_id='+affiliate_id);
            $http({ method: 'POST', url: 'php/Controller.php?type=Video&action=getVideosOfUser&affiliate_id='+affiliate_id,}).
            success(function(data, status, headers, config) {
              deferred.resolve(data);
            });
            return deferred.promise;
        },
        getNewestVideos:function(d){
            var deferred = $q.defer();
            track('request:', 'php/Controller.php?type=Video&action=getLatestVideos&dates='+d);
            $http({ method: 'POST', url: 'php/Controller.php?type=Video&action=getLatestVideos&dates='+d,}).
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
          track('request','php/Controller.php?type=User&action=getUser');
          $http({ method: 'POST', url: 'php/Controller.php?type=User&action=getUser',}).
          success(function(data, status, headers, config) {
                user = data;
                track('user in session', user);
                deferred.resolve(data);
           });
           return deferred.promise;                
        },
        signIn:function(user){
          var deferred = $q.defer();
          track('php/Controller.php?type=User&action=signIn&user='+JSON.stringify(user),user);
          $http({ method: 'GET', url: 'php/Controller.php?type=User&action=signIn&user='+JSON.stringify(user)}).
          success(function(data, status, headers, config) {
                deferred.resolve(data);
          });
          return deferred.promise;

        },
        signOut:function(){
          var deferred = $q.defer();
          $http({ method: 'GET', url: 'php/Controller.php?type=User&action=signOut'}).
          success(function(data, status, headers, config) {
                deferred.resolve(data);
          });
          return deferred.promise;
        },
        getPlayNowLink:function(offer){
          var deferred = $q.defer();
          track('request','php/Controller.php?type=User&action=getPlayNowLink&offer_id='+offer);
          $http({method: 'POST', url: 'php/Controller.php?type=User&action=getPlayNowLink&offer_id='+offer}).
          success(function(data, status, headers, config) {
                    deferred.resolve(data);
          });
          return deferred.promise;
        },
        getMyReferrals: function(user_id){
          var deferred = $q.defer();
          track('request','php/Controller.php?type=User&action=getReferrals');
          $http({method: 'POST', url: 'php/Controller.php?type=User&action=getReferrals'}).
          success(function(data, status, headers, config) {
                    deferred.resolve(data);
          });
          return deferred.promise;
        }
    }      
});
Game.factory('maintainService', function ($q, $http) {
  return {
    deleteGenre:function (g) {
      var deferred = $q.defer();
      track('deleteGenre', 'php/Controller.php?type=Maintain&action=genre&subaction=delete&id='+g._id.$id )
      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=genre&subaction=delete&id='+g._id.$id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;
    },
    addGenre:function(game){
      var deferred = $q.defer();
      track('addgenre','php/Controller.php?type=Maintain&action=genre&subaction=add&genre_name='+game.genre_name+'&genre_initials='+game.genre_initials)
      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=genre&subaction=add&genre_name='+game.genre_name+'&genre_initials='+game.genre_initials}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;
    },
    addFeaturedGame: function(game, genre_id){
      var deferred = $q.defer();
      track('addfeat','php/Controller.php?type=Maintain&action=featured&subaction=add&name='+game.name+'&alias='+game.alias+'&genre_id='+genre_id)
      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=featured&subaction=add&name='+game.name+'&alias='+game.alias+'&genre_id='+genre_id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;

    },
    deleteFeaturedGame: function(game_id, genre_id){
      var deferred = $q.defer();
      track('deletefeat','php/Controller.php?type=Maintain&action=featured&subaction=delete&game_id='+game_id+'&genre_id='+genre_id)
      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=featured&subaction=delete&game_id='+game_id+'&genre_id='+genre_id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;

    },
    deleteSiteUsers: function(user){
      var deferred = $q.defer();
      track('php/Controller.php?type=Maintain&action=user&subaction=delete&id='+user.affiliate_id);
      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=user&subaction=delete&id='+user.affiliate_id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
      });
      return deferred.promise;
    },
    addSiteUsers: function(id){
      var deferred = $q.defer();

      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=user&subaction=add&id='+id}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
                 track('php/Controller.php?type=Maintain&action=user&subaction=add&id='+id, data);
      });
      return deferred.promise;
    },
    loadSiteUsers: function(){
      var deferred = $q.defer();

      $http({method: 'POST', url: 'php/Controller.php?type=Maintain&action=user&subaction=load'}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
                 track('php/Controller.php?type=Maintain&action=user&subaction=load', data);
      });
      return deferred.promise;
    },
    removeLogo: function(game){
      var deferred = $q.defer();

      $http({method: 'GET', url: 'php/Controller.php?type=Maintain&action=removeLogo&game='+game}).
      success(function(data, status, headers, config) {
                deferred.resolve(data);
                 track('php/Controller.php?type=Maintain&action=removeLogo&game='+game, data);
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
Game.directive('focusMe', function($timeout, $parse) {
  return {
    //scope: true,   // optionally create a child scope
    link: function(scope, element, attrs) {
      var model = $parse(attrs.focusMe);
      scope.$watch(model, function(value) {
        console.log('value=',value);
        if(value === true) { 
          $timeout(function() {
            element[0].focus(); 
          });
        }
      });
      // to address @blesh's comment, set attribute value to 'false'
      // on blur event:
      element.bind('blur', function() {
         // console.log('blur');
         scope.$apply(model.assign(scope, false));
      });
    }
  };
});


