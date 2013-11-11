function HeadController($scope, $http, $location, gamesService,  $rootScope, userService){
    // $rootScope.clicked = false;


    $scope.loginmessage = [{message:'',cls:'logmess'}];
    $scope.find = function(game){
        $location.path('/game/'+game.alias);
    }
    $scope.init = function(){
        $('.wrapper').attr('style','padding-left: 320px;');
        $('#side-id').show();
       
        if($rootScope.games != null)
          return;


        $rootScope.user = [{email:'',id:'', password:'', aff_id:'', admin:false}];
        userService.getUser().then (function(data){
            if(data=='false'){
              $rootScope.user.email = '';
            }
            else{
              $rootScope.user = data;
            }
        });
        
        gamesService.getDefaultGames().then(function(data){
            $rootScope.games = data;
        });

        gamesService.getSavedGames().then(function(data){
            
            $rootScope.carousel =[];
            for(var i=0; i < data.length;i=i+5){
              arr = [];
              if(data[i]!=null)
                arr['game1'] = data[i];
              if(data[i+1]!=null)
                arr['game2'] = data[i+1];
              if(data[i+2]!=null)
                arr['game3'] = data[i+2];
              if(data[i+3]!=null)
                arr['game4'] = data[i+3];
              if(data[i+4]!=null)
                arr['game5'] = data[i+4];
              if(data[i+5]!=null)
                arr['game6'] = data[i+5];
              if(arr != null)
              $rootScope.carousel.push(arr);
            }
            track('carousel', $rootScope.carousel)
            $rootScope.games = data;

            if(data==null){
              gamesService.refreshSavedGames().then(function(data){
                  $rootScope.games = data;
                  $('#games').show();
                  track('gamesonhead', $rootScope.games);
              });
            }
            track('gamesonhead', $rootScope.games);
            $('#gamesPager').show();
            gamesService.loadHotGames().then(function(data){ 
              track('hotgames', data);
              $rootScope.hgames = data;
            });

        });

        
        $('#loginform').show();
        $('#waitform').hide();
        $('#gamesPager').hide();

    }
    $scope.Signout = function(){

        userService.signOut().then(function(data){
            $rootScope.user = [{email:'',id:'', password:'', aff_id:'', admin:false}];
          track('user signout', $rootScope.user);
          $scope.user.email = "";
          $('#loginform').show();
          $('#waitform').hide();
          // $location.path('/');
        });
    }
    $scope.Login1 = function () {
        var email = $('#hemail').val();
        var password = $('#hpassword').val();
        $scope.loginmessage.message ='';
        $scope.loginmessage.cls = 'logmess';
        $('#loginbtn1').button('loading');
        var myuser = {'email':'', 'password':''};
        myuser['email'] = email;
        myuser['password'] = password;
        userService.signIn(myuser).then(function(data){
            if(data=="0"){
                $scope.loginmessage.message = "User does not exists. Please try again.";
                $scope.loginmessage.cls = "alert-danger logmess-v";
                $('#loginform').show();
                $('#waitform').show();
            }
            else { 
                $rootScope.user = data;
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

    $scope.isShown = true;
    $scope.showSide = function(){
      $scope.isShown = !$scope.isShown;
      if($scope.isShown){
        $('.wrapper').attr('style','padding-left: 320px;'); 
        $('#side-id').show();
      }
      else{
        $('.wrapper').attr('style','padding-left: 0;');
        $('#side-id').hide();
      }
    }
}
function SidebarController($scope, $http, gamesService, $location, $rootScope){
  $scope.agburn=true;
  $scope.order = [{sortname: 'Video Name +',sorttext:'name',reverse:false, currentclass: 'title'}];
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
  // $('.wrapper').attr('style','padding-left: 0px;');
  // $('#side-id').hide();
  $scope.myInterval = 5000;
  $scope.isHidden = true;

    $scope.sideshow = function(){ 
      $(".wrapper").toggleClass("active");
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


    $scope.sortBy = [ {sortname: 'Popularity - Descending',sorttext:'stat.clicks',reverse:true, currentclass: 'click'}
                      ,{sortname: 'Popularity - Ascending',sorttext:'stat.clicks',reverse:false, currentclass: 'click'}
                      ,{sortname: 'Views - Descending',sorttext:'statistics.viewCount',reverse:true, currentclass: 'view'}
                      ,{sortname: 'Views - Ascending',sorttext:'statistics.viewCount',reverse:false, currentclass: 'view'}
                      ,{sortname: 'Video Name - Descending',sorttext:'snippet.title',reverse:false, currentclass: 'title'}
                      ,{sortname: 'Video Name - Ascending',sorttext:'snippet.title',reverse:true, currentclass: 'title'}
                      ,{sortname: 'Published At - Descending',sorttext:'snippet.publishedAt',reverse:true, currentclass: 'date'}
                      ,{sortname: 'Published At - Ascending',sorttext:'snippet.publishedAt',reverse:false, currentclass: 'date'}  
                     ];

    // MY VIDEOS
    $scope.mvCurrentSort = $scope.sortBy[0];
    $scope.myvideos = [];
    $scope.currentPage3 = 0;
    $scope.pageSize3 = 10;
    $scope.num3 = 0;
    $scope.numberOfPages3=function(){ return Math.ceil($scope.myvideos.length/$scope.pageSize3); }
    
    $scope.LoadMyVideos = function(){
      $scope.myvideos = [];
      $('#nvLoader').hide();
      $('#nvPager').hide();
      $('#tvLoader').hide();
      $('#tvPager').hide();
      $('#mvLoader').show();
      $('#mvPager').hide();
      track('user',$scope.user)
      if($scope.user.email!=''){
        videoService.getVideosOfUser($scope.user.affiliate_id).then(function(data){
                $('#mvLoader').hide();
                $scope.myvideos = data;
                if(data=='false'){
                  
                  $('#mvPager').hide();
                }
                else{
                  $('#mvPager').show();
                  track('myvideos', data);
                  for(var v in $scope.myvideos){
                    $scope.myvideos[v].statistics.viewCount = parseInt($scope.myvideos[v].statistics.viewCount);
                  }
                  
                }
            });
      }
    }

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
        $('#mvLoader').hide();
        $('#mvPager').hide();
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
        $('#mvLoader').hide();
        $('#mvPager').hide();
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
    // videoService.getNewestVideosO(d).then(function(data) {
    //         $('#nvLoader').hide();
    //         $('#nvPager').show();
    //         $scope.videos = data;
    //         $scope.loaded = 1;

    //         for(var v in $scope.videos){
    //           $scope.videos[v].statistics.viewCount = parseInt($scope.videos[v].statistics.viewCount);
    //         }
    //         track('latestvideosO', data);
    //     });
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
    maintainService.loadSiteUsers().then(function(data) {
      
      $scope.siteUsers = data;
    });
  }
  $scope.loadUsers();


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
  $scope.deleteGenre = function(g, idx){
    maintainService.deleteGenre(g).then(function(data) {
        $scope.hgames.splice(idx, 1);
    });
  }
  $scope.deleteFeaturedGame = function (game, idx){
    // alert(game.id.$id)
    maintainService.deleteFeaturedGame(game._id.$id, $scope.hgames[idx]._id.$id).then(function(data){
        $scope.hgames[idx].games.splice($scope.hgames[idx].games.indexOf(game), 1);
        track('hgames', $scope.hgames[idx].games); 
    });      
  }
  $scope.addFeaturedGame = function(game, genre){
    track(game,genre);
    maintainService.addFeaturedGame(game, genre._id.$id).then(function(data){
      
            genre.games = data.games;
            track('addedfeat',data.games)
    });

  }
  $scope.addGenre = function(){
    dupli  =  {genre_name:$scope.toadd.genre_name, genre_initials:$scope.toadd.genre_initials};
    maintainService.addGenre(dupli).then(function(data) {
          $scope.hgames.push(data);
          track('after', $scope.hgames);
    });
  }
  var old2 = -1;
  $scope.setActive = function(g){
    track('activegame',g)
    g.clicked = !g.clicked;
    $scope.cg = g;
    if(old2 != -1)
      $scope.games[old2].clicked = false;
    var idx = $scope.games.indexOf(g);
    old2 = idx;
    $scope.currentpic = g.pic;
    $scope.currentalias = g.alias;
    $http({method: 'POST', url: 'upload/'+ $scope.currentpic}).
    error(function(data, status, headers, config) {
        $('#gameslogo').show();
        $('#logos').attr('src','http://placehold.it/175X150/131313/EEEEEE/&text=NO+LOGO');
    }).
    success(function(data, status, headers, config) {
        $('#gameslogo').show();
        $('#logos').attr('src','/upload/'+ $scope.currentpic);
    });
  }

  $scope.deleteUser = function (user){
    maintainService.deleteSiteUsers(user).then(function(data) {
      $scope.siteUsers.splice($scope.siteUsers.indexOf(user),1);
    });
  }
  $scope.newuser = "";
  $scope.addAff = function(){
    $('#addSiteUser').button('loading');
    maintainService.addSiteUsers($scope.newuser).then(function(data) {
      if(parseInt(data)==0)
        alert("invalid affiliate ID.");
      else{

      $scope.loadUsers();
      }
      $('#addSiteUser').button('reset');
    });
  }
  $scope.refreshGames = function(){
      $('#refreshGames1').button('loading');
      $('#games').hide();

      gamesService.refreshSavedGames().then(function(data){
          $rootScope.games = data;
          $('#games').show();
          track('gamesonhead', $rootScope.games);
          gamesService.loadHotGames().then(function(data){ 
            track('hotgames', data);
            $('#refreshGames1').button('reset');
            $rootScope.hgames = data;
          });
      });

    }
  $scope.removeLogo = function(game){
    maintainService.removeLogo(game).then(function (data){
      if(data==""){
        $('#logos').attr('src','http://placehold.it/175X150/131313/EEEEEE/&text=NO+LOGO');
        $scope.cg.pic = "";
        track('game', $scope.cg)
      }
    });
  }
}
function ReferralController($scope, $rootScope, userService){
  $scope.loadingReferrals = true;
  userService.getMyReferrals($rootScope.user.aff_id).then(function(data){
    $scope.referrals =data;
    
    // track('us', $scope.referrals)
    // data = (array) data;
    var array = [];
    for(var key in data){
        if(!data.hasOwnProperty(key)){
            continue;
        }
        array.push(data[key])
    }

    data  = array;
    size = Math.ceil(data.length/3);
    $scope.referrals1 = data.slice(0,size);
    $scope.referrals2 = data.slice(size,size*2 );
    $scope.referrals3 = data.slice(size*2,size*data.length);

    $scope.loadingReferrals = false;
    // track('size', size);
    // track('myrefs', $scope.referrals2);
  });
}