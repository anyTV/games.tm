function HeadController($scope, $http, $location, gamesService,  $rootScope, userService){
    // var started = 0; 
    // $('#notif').fadeOut(10000);
    $scope.showMaintainance = false;
    $rootScope.clicked = false;
    $scope.init = function(){
        $('.wrapper').attr('style','padding-left: 320px;');
        $('#side-id').show();
        // $('#mainview').addClass('main-container');
        $rootScope.user = [{email:'',id:'', password:'', aff_id:''}];
        $rootScope.usermode = 0;
        $scope.find = function(game){
            $location.path('/game/'+game.fid);
        }
        gamesService.LoadSiteUsers().then(function(data) {
          
          $rootScope.siteUsers = data;
          track('siteUsers', data);
        });
        
        $scope.showMaintainance = $scope.checkIfValidUser($rootScope.user[0]);
        gamesService.getDefaultGames().then(function(data){
            $rootScope.games = data;
            track('defaultgames', $rootScope.games);
        });
        gamesService.getGames().then(function(data){
             $rootScope.games = data;
             ctra = 0; ctri=0;
             for(var g in $rootScope.games){
                 if($rootScope.games[g].name.toLowerCase().indexOf("createtest") != -1){
                      $rootScope.games.splice(g, 1); continue;
                 }
                 if($rootScope.games[g].state =='paused'){ ctri++; $rootScope.games[g].realstate = "paused" }
                 else if($rootScope.games[g].pay != 0){ ctra++; $rootScope.games[g].realstate = "active" }
                 else{ ctri++; $rootScope.games[g].realstate = "paused" }     
             }
             $rootScope.currentView = ctra;
             $rootScope.active = ctra;
             $rootScope.pause = ctri;
             $('#gamesPager').show();
             track('gamesonhead', $rootScope.games);
             gamesService.getHotGames().then(function(data){ 
               $scope.hotgames1 = data;
               for(var y in $scope.games){ 
                 for( var i in $scope.hotgames1){

                   for(var x in $scope.hotgames1[i].games){
                         if($scope.hotgames1[i].games[x].game_name == $scope.games[y].name){

                            $scope.hotgames1[i].games[x].game_fid = $scope.games[y].fid;
                         }
                   }
                 }
               }
               $rootScope.hgames = $scope.hotgames1;
                
             });
       });

        userService.getUser().then (function(data){
            $rootScope.user = data;
            $rootScope.usermode = data.id;

        });

        $('#loginform').show();
        $('#waitform').hide();
        $('#gamesPager').hide();
        $scope.loginmessage = [{message:'',cls:'logmess'}];
    }

    $scope.Signout = function(){
        userService.setUser('', '', 0,0);
        $rootScope.usermode = 0;
    }
    $scope.checkIfValidUser =function(id){
        if(id==4)
          return true;
        angular.forEach($rootScope.siteUsers, function(value, key){
          console.log(value);
          if($rootScope.siteUsers[key].aff_id==id)
            return true;
        });
        return false;
    }
    $scope.Login1 = function () {
            var email = $('#hemail').val();
            var password = $('#hpassword').val();
            $scope.loginmessage.message ='';
            $scope.loginmessage.cls = 'logmess';
            $('#loginbtn1').button('loading');
            userService.checkUser(email, password).then(function(data){
                if(data==""){
                    $scope.loginmessage.message = "User does not exists. Please try again.";
                    $scope.loginmessage.cls = "alert-danger logmess-v";
                    $('#loginform').show();
                    $('#waitform').show();
                }
                else { 
                    $rootScope.user.email = email;
                    $rootScope.user.password = password;
                    $rootScope.user.id = data;
                    userService.getReferralLink(data).then(function(data){
                      $rootScope.user.aff_id = data;
                    });
                    userService.setUser(email, password, data, $rootScope.user.aff_id);
                    $scope.showMaintainance = $scope.checkIfValidUser($rootScope.user.id);
                    track('maintainance', $scope.showMaintainance);
                    $rootScope.usermode = 1;
                    track('user mode',$rootScope.usermode);
                    track('user in',$rootScope.user);
                    $scope.loginmessage.message = "Login successful.";
                    $scope.loginmessage.cls = "alert-success logmess-v";
                    $('#loginform').hide();
                    $('#waitform').show();
                    $('.myModal1').modal('hide')
                    // $scope.user = $scope.currentuser;
                }
                $('#loginbtn1').button('reset');
            });
    };
  $scope.isHidden = true;
  $scope.navTop = false;
  $scope.showSide = function(){
    $scope.isHidden = !$scope.isHidden;
    if($scope.isHidden){
      $('.wrapper').attr('style','padding-left: 0;');
      $('#side-id').hide();
    }
    else{
      $('.wrapper').attr('style','padding-left: 320px;'); 
      $('#side-id').show();
    }
  }
}
function SidebarController($scope, $http, gamesService, $location, $rootScope){
  $scope.agburn=true;
  $scope.order = [{sortname: 'Video Name +',sorttext:'name',reverse:false, currentclass: 'title'}];

  $scope.viewMore = function() {

    $scope.listview = !$scope.listview;
  }
  $scope.closeOthers = function(){
    for( var i in $scope.hgames){
      for(var x in $scope.hgames[i].games){

          $scope.hgames[i].games[x].active = false;
      }
    }
  }
  var old2 = 0;
  $scope.declickOthers = function(g){
    $scope.games[old2].clicked = false;
    var idx = $scope.games.indexOf(g);
    old2 = idx;
  }
  $scope.thawOthers = function (){
    for( var i in $scope.hgames){
      $scope.hgames[i].burn = false;
    }
     $scope.agburn = false;
  }
}
function HomeController($scope, $http, gamesService, videoService, $rootScope, $location, $timeout){
  $('.wrapper').attr('style','padding-left: 0px;');
  $('#side-id').hide();
  $scope.isHidden = true;
    $scope.pageprev = function(){
      // alert($scope.currentPage2)
      if($scope.currentPage2 > 0) {

        $scope.currentPage2=$scope.currentPage2-1;
        $timeout.cancel($scope.stop)
      }
    }
    $scope.pagenext = function(){
      // alert($scope.currentPage2)
      if($scope.currentPage2 <= $scope.games.length/$scope.pageSize2 - 1) {

        $scope.currentPage2=$scope.currentPage2+1
        $timeout.cancel($scope.stop)
      }
    }
      var direction = true;
    $scope.stop = 0;
    $scope.roulette = function(){

      $scope.stop = $timeout(function() {
        // alert('test')
        // $scope.currentPage2=$scope.currentPage2+1;
        // track(direction,$scope.currentPage2);
        if($scope.currentPage2 <= $scope.games.length/$scope.pageSize2 - 1 && direction) {

          $scope.currentPage2=$scope.currentPage2+1
          if($scope.currentPage2 == $scope.numberOfPages2()-1)
            direction = false;
        }
        else if( $scope.currentPage2 > 0 && !direction){
          $scope.currentPage2=$scope.currentPage2-1;
          if($scope.currentPage2 == 1)
            direction = true;
        }
        // if($scope.currentPage2 >= 1)
          // $scope.pagenext();


        $scope.roulette();
      }, 5000);
    }
    // $scope.roulette();
    $scope.sideshow = function(){ 
      $(".wrapper").toggleClass("active");
      // alert($('.all-games').attr('class'))
    }

    $scope.timeago = function(dated){
        var date = humanized_time_span(dated);
        return date;

    }
    $scope.formatMoney = function(n){
        var rx=  /(\d+)(\d{3})/;
        return String(n).replace(/^\d+/, function(w){
            while(rx.test(w)){
                w= w.replace(rx, '$1,$2');
            }
            return w;
        });
    };


    $rootScope.items = [];
    ctr = 0;

    // 

    $scope.currentPage2 = 0;
    $scope.pageSize2 = 6;
    $scope.num2 = 0;
    
    $scope.numberOfPages2=function(){  if($scope.games == null) return 0; else return Math.ceil($scope.games.length/$scope.pageSize2); } 
    $scope.test = function(){
        $('#refreshGames1').button('loading');
    }

    $scope.sortBy = [ {sortname: 'Popularity - Descending',sorttext:'stat.clicks',reverse:true, currentclass: 'click'}
                      ,{sortname: 'Popularity - Ascending',sorttext:'stat.clicks',reverse:false, currentclass: 'click'}
                      ,{sortname: 'Views - Descending',sorttext:'statistics.viewCount',reverse:true, currentclass: 'view'}
                      ,{sortname: 'Views - Ascending',sorttext:'statistics.viewCount',reverse:false, currentclass: 'view'}
                      ,{sortname: 'Video Name - Descending',sorttext:'snippet.title',reverse:false, currentclass: 'title'}
                      ,{sortname: 'Video Name - Ascending',sorttext:'snippet.title',reverse:true, currentclass: 'title'}
                      ,{sortname: 'Published At - Descending',sorttext:'snippet.publishedAt',reverse:true, currentclass: 'date'}
                      ,{sortname: 'Published At - Ascending',sorttext:'snippet.publishedAt',reverse:false, currentclass: 'date'}  
                     ];
    // TREND

    $scope.tvCurrentSort =  $scope.sortBy[0];

        
    $scope.videos1 = [];  
    $scope.currentPage = 0;
    $scope.pageSize = 10;
    $scope.num = 0;
    $scope.numberOfPages=function(){ return Math.ceil($scope.videos.length/$scope.pageSize); }


    $scope.changeDate = function(left){
        $scope.videos1 = [];
        $('#nvLoader').hide();
        $('#nvPager').hide();
        $('#tvLoader').show();
        $('#tvPager').hide();
        var r = "";
        var f = $scope.dt1;
        var t = $scope.dt2;

        var y = new Date();
        track(f,t)
        f = dateDashes(f, 0); 
        t = dateDashes(t, 0); 
        // y = y.getDate()-1;
        d = f + ","+t;
        videoService.getTrendingVideos(d).then(function(data){
                $('#tvLoader').hide();
                $('#tvPager').show();
                if(data=='false')
                  $('#tvPager').hide();
                $scope.videos1 = data;
                track('trending', data);
                for(var v in $scope.videos){
                  $scope.videos[v].statistics.viewCount = parseInt($scope.videos[v].statistics.viewCount);
                }
            });

    }

    $scope.find = function(game){
        $location.path('/game/'+game.fid);
    }
    $scope.dt2 = new Date();
    $scope.dt1 = new Date();
    $scope.limit1 = new Date();
    $scope.limit2 = new Date();
    // var y = new Date($scope.dt1);
    // $scope.dt2 = $scope.dt1 = y.setDate(y.getDate() - 1);

    $scope.opened1 = false;
    $scope.opened2 = false;

    $scope.open1 = function() {
        $timeout(function() {
          $scope.opened1 = true;
        });
    };
    $scope.open2 = function() {
        $timeout(function() {
          $scope.opened2 = true;
        });
    };

    $scope.trendOver = "";
    $scope.breakdown = function (sources) {
      var retstring ="";
      var ctr =0;
      for(var i =0; i<sources.Names.length; i++){
        retstring += sources.Names[i] + ' - ' + sources.clicks[i] + '<br>';
        ctr += parseInt(sources.clicks[i]);
      }
      $scope.trendOver = retstring + "Total:<strong>"+ctr+"</strong>";
    }

    // NEW VIDEOS
    $scope.videos = []; 
    $scope.nvCurrentSort =  $scope.sortBy[6];
    $scope.currentPage1 = 0;
    $scope.pageSize1 = 10;
    $scope.num1 = 0;
    $scope.numberOfPages1=function(){ return Math.ceil($scope.videos.length/$scope.pageSize1); }

    $('#nvLoader').show();
    $('#nvPager').hide();
    $scope.LoadNewVideos = function(){
      if($scope.loaded == 0){
        $('#nvLoader').show();
        $('#nvPager').hide();
        $('#tvLoader').hide();
        $('#tvPager').hide();
      }
      else{
        $('#nvLoader').hide();
        $('#nvPager').show();
      }
    }
    $scope.loaded = 0;
    var today = new Date();
    var y = new Date();
    $('#nvLoader').show();
    $('#nvPager').hide();
    $('#tvLoader').hide();
    $('#tvPager').hide();
    $scope.videos = [];  
    // track('lastfive',new Date(today.setDate(today.getDate()-5)) )
    $lastfive = dateDashes(new Date(today.setDate(today.getDate()-5)),0);
    $today = dateDashes(y, 0);

    d = $lastfive + ","+$today;
    videoService.getNewestVideos(d).then(function(data) {
            $('#nvLoader').hide();
            $('#nvPager').show();
            $scope.videos = data;
            $scope.loaded = 1;

            for(var v in $scope.videos){
              $scope.videos[v].statistics.viewCount = parseInt($scope.videos[v].statistics.viewCount);
            }
            track('latestvideos', data);
        });
    $('#gamepic').attr('src',  $('#gamepic').attr('dsrc'));
}
function MaintainController($scope, $http, maintainService, gamesService, $rootScope, $routeParams){
  $('#side-id').hide();
  // $('#mainview').hide();
  // $('#mainview').attr('style','width: 100%;');
  $('.wrapper').attr('style','padding-left: 0px;');
  // $('#mainview').addClass('main-container-maintain');
  $scope.assigned = null;  $scope.toadd = {genre_id: 0, genre_name:'', genre_initials:'', games:[]};
  $scope.siteUsers = [];
  $scope.changed = $routeParams.message;
  $scope.cid = 0;
  $scope.loadUsers = function(){
    maintainService.LoadSiteUsers().then(function(data) {
      
      $scope.siteUsers = data;
    });
  }
  $scope.loadUsers();

  $scope.closeOthers = function(){
    for( var i in $scope.games){
          $scope.games[i].clicked = false;

    }
    $scope.actives = [];
  }
  var old2 = 0;
  $scope.declickOthers = function(g){
    $scope.games[old2].clicked = false;
    var idx = $scope.games.indexOf(g);
    old2 = idx;
    if(g.clicked){
      $scope.actives.push(g);
      $scope.cg = g.pic;
      $scope.cid = g.fid;
      $http({method: 'POST', url: 'img/'+$scope.cg+'.png'}).
      error(function(data, status, headers, config) {
          $('#gameslogo').show();
          $('#logos').attr('src','http://placehold.it/175X150/131313/EEEEEE/&text=NO+LOGO');
      }).
      success(function(data, status, headers, config) {
          $('#gameslogo').show();
          $('#logos').attr('src','/img/'+$scope.cg+'.png');
      });
    }
    else{
      $scope.actives.splice($scope.actives.indexOf(g), 1);
    }
  }
  $scope.deleteGenre = function(id, idx){
    maintainService.deleteGenre(id).then(function(data) {
      if(data=="1"){
        $scope.hgames.splice(idx, 1);
      }

    });
  }
  $scope.deleteFeaturedGame = function (game, idx){
    maintainService.deleteFeaturedGame(game.game_id).then(function(data){
      if(data=="1"){
        $scope.hgames[idx].games.splice($scope.hgames[idx].games.indexOf(game), 1);
        track('hgames', $scope.hgames[idx].games); 
      }
    });      
  }
  $scope.addFeaturedGame = function(game, genre){
    maintainService.addFeaturedGame(game, genre).then(function(data){
      if(data=="1"){
        for (var i in  $scope.hgames ) {
          if($scope.hgames[i].genre_id == genre.genre_id){
            featgame = { active:false, game_name:game.name, game_fid:game.fid }
            $scope.hgames[i].games.push(featgame);

            track('addedfeat', $scope.hgames[i].games);
            break;
          }

        };
       
      }
    });

  }
  $scope.addGenre = function(){
    $lastid = parseInt($scope.hgames[$scope.hgames.length-1].genre_id);
    dupli  =  {genre_id:$scope.toadd.genre_id,genre_name:$scope.toadd.genre_name, genre_initials:$scope.toadd.genre_initials, games:[]};
    maintainService.addGenre(dupli).then(function(data) {
        if(data=="1"){
          dupli.genre_id = ++$lastid;
          $scope.hgames.push(dupli);
          track('after', $scope.hgames);
        }

    });
  }
  $scope.actives = [];
  $scope.cg = "";
  $scope.setActive = function(g){
    g.clicked = !g.clicked;
    $scope.games[old2].clicked = false;
    var idx = $scope.games.indexOf(g);
    old2 = idx;
    if(g.clicked){
      $scope.actives.push(g);
      $scope.cg = g.pic;
      $scope.cid = g.fid;
      $http({method: 'POST', url: 'upload/'+$scope.cg}).
      error(function(data, status, headers, config) {
          $('#gameslogo').show();
          $('#logos').attr('src','http://placehold.it/175X150/131313/EEEEEE/&text=NO+LOGO');
      }).
      success(function(data, status, headers, config) {
          $('#gameslogo').show();
          $('#logos').attr('src','/upload/'+$scope.cg);
      });
    }
    else{
      $scope.actives.splice($scope.actives.indexOf(g), 1);
    }
  }
  $scope.setGames = function(genre_name){
    track('actives', $scope.actives)
    if(genre_name == null) return;

    fids = [];
    for(var i in $scope.actives){
      fids.push($scope.actives[i].fid);
    }

    maintainService.setGamesToGenre(genre_name, fids).then(function(data) {
        // console.log(data);
        if(data=="1"){
          alert('celebrate')
          for( var i in $scope.games ){
            for(var x in $scope.actives){
              track('cond', $scope.games[i].fid == $scope.actives[x].fid);
              if($scope.games[i].fid == $scope.actives[x].fid){
                $scope.games[i].genre = genre_name; alert(genre_name);
              }
            }
          }
        }
    });
  }
  $scope.deleteUser = function (user){
    maintainService.deleteSiteUsers(user).then(function(data) {
      $scope.siteUsers.splice($scope.siteUsers.indexOf(user),1);
    });
  }
  $scope.newuser = "";
  $scope.addAff = function(){
    maintainService.addSiteUsers($scope.newuser).then(function(data) {
      $scope.loadUsers();
    });
  }
  $scope.refreshGames = function(){
      $('#refreshGames1').button('loading');
      $('#games').hide();

      gamesService.getFreshGames().then(function(data){
           $rootScope.games = [];
           $rootScope.games = data;
           ctra = 0; ctri=0;
           track("fresh games2",$rootScope.games);
           for(var g in $rootScope.games){
               if($rootScope.games[g].name.toLowerCase().indexOf("createtest") != -1){
                    $rootScope.games.splice(g, 1); continue;
               }
               if($rootScope.games[g].state =='paused'){ ctri++; $rootScope.games[g].realstate = "paused" }
               else if($rootScope.games[g].pay != 0){ ctra++; $rootScope.games[g].realstate = "active" }
               else{ ctri++; $rootScope.games[g].realstate = "paused" }     
           }
           $rootScope.currentView = ctra;
           $rootScope.active = ctra;
           $rootScope.pause = ctri;
           $('#games').show();
           
           gamesService.getHotGames().then(function(data){ 
             $scope.hotgames1 = data;
             for(var y in $scope.games){ 
               for( var i in $scope.hotgames1){

                 for(var x in $scope.hotgames1[i].games){
                       if($scope.hotgames1[i].games[x].game_name == $scope.games[y].name){

                          $scope.hotgames1[i].games[x].game_fid = $scope.games[y].fid;
                       }
                 }
               }
             }
             $rootScope.hgames = $scope.hotgames1;
              
           });
        $('#refreshGames1').button('reset');
       });
    }
}
