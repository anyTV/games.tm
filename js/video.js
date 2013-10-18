function VideoController($scope, $http, $routeParams, gamesService, userService, videoService, $rootScope){
    $('.loader-videos').show();
    $('.no-videos').hide();
    $('#linkformp').hide();
    $('#waitformp').hide();
    $('#loginformp').show();
    $('.wrapper').attr('style','padding-left: 320px;'); 
    $('#side-id').show();
    $('#mainview').attr('style','width: 100%;');
    $('#side-id').show();
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

    $scope.Login = function (email, password) {
            $scope.loginmessage.message ='';
            $scope.loginmessage.cls = 'logmess';
            $('#loginbtnp').button('loading');
            userService.checkUser(email, password).then(function(data){
                    if(data==""){
                        $scope.loginmessage.message = "User does not exists. Please try again.";
                        $scope.loginmessage.cls = "alert-danger logmess-v";
                        $('#loginformp').show();
                        $('#linkformp').hide();
                        $('#waitformp').show();
                    }
                    else { 
                        userService.setUser(email, password, data);
                        $rootScope.user.email = email;
                        $rootScope.user.password = password;
                        $rootScope.user.id = data;
                        userService.getReferralLink(data).then(function(data){
                          $rootScope.user.aff_id = data;
                        });
                        $rootScope.usermode = 1;
                        $scope.loginmessage.message = "Login successful. Please wait while we generate your Play Now link...";
                        $scope.loginmessage.cls = "alert-success logmess-v";
                        $('#loginformp').hide();
                        $('#linkformp').hide();
                        $('#waitformp').show();
                        var str = $scope.currentgame.id.split(',');
                        var real_offer = 0;
                        if($scope.currentgame.redirect_offer_id == 0)
                            real_offer = str[0].substr(0,str[0].length);
                        else
                            real_offer = $scope.currentgame.redirect_offer_id;
                        userService.getPlayNowLink($scope.currentuser.id, real_offer).then(function(data){
                                track('link',data);      
                                $('#linktext').val(data);
                                $('#loginformp').hide();
                                $('#linkformp').show();
                                $('#waitformp').hide();
                        });
                    }

                    $('#loginbtnp').button('reset');
                });

    };

    $scope.currentPage = 0;
    $scope.pageSize = 10;
    $scope.num = 0;
    $scope.numberOfPages=function(){ if($scope.currentgame == null) return 0; else return Math.ceil($scope.currentgame.videos.length/$scope.pageSize); }

    $scope.carou = [];
    $scope.countries = [];  
    $scope.currentgamedetails = [];
    $scope.search="";
    $scope.loginmessage = [{message:'',cls:'logmess'}];

    $scope.isCollapsed = false;
    $scope.currentgame = null;
    $scope.myInterval = 5000;
    $scope.sortBy = [ {sortname: 'Popularity - Descending',sorttext:'stat.clicks',reverse:true, currentclass: 'click'}
                      ,{sortname: 'Popularity - Ascending',sorttext:'stat.clicks',reverse:false, currentclass: 'click'}
                      ,{sortname: 'Views - Descending',sorttext:'statistics.viewCount',reverse:true, currentclass: 'view'}
                      ,{sortname: 'Views - Ascending',sorttext:'statistics.viewCount',reverse:false, currentclass: 'view'}
                      ,{sortname: 'Video Name - Descending',sorttext:'snippet.title',reverse:false, currentclass: 'title'}
                      ,{sortname: 'Video Name - Ascending',sorttext:'snippet.title',reverse:true, currentclass: 'title'}
                      ,{sortname: 'Published At - Descending',sorttext:'snippet.publishedAt',reverse:true, currentclass: 'date'}
                      ,{sortname: 'Published At - Ascending',sorttext:'snippet.publishedAt',reverse:false, currentclass: 'date'}
                     ];

    $scope.currentSort = [ {sortname: 'Clicks +',sorttext:'stat.clicks',reverse:true}];

    $scope.arrangeCountries = function(countries){
        pay = [];
        myarray = [];
        angular.forEach(countries, function(value, key){
            if(value.state == "active"){
              if(!isInArray(value.pay,pay))
                pay.push(value.pay);
            }
        });
        
        for (var p in pay) {
            current = [];
            states ="";
            angular.forEach(countries, function(value1, key1){
                if(value1.state=="active")
                  if(pay[p]==value1.pay){
                    current.push(value1.country);
                    states = value1.state;
                    // alert(pay);
                  }

            });
            myarray.push([pay[p],current,states]);
        };
        return myarray;
    }
    gamesService.getCurrentGame($routeParams.gameid).then(function(data){
                $('.pagers').hide();
                $rootScope.currentgame = $scope.currentgame = data;
                $scope.currentgame.pay = parseFloat($scope.currentgame.pay);
                var ctri, ctra = 0;
                if($scope.currentgame.state =='paused'){ ctri++; $scope.currentgame.realstate = "paused" }
                else if($scope.currentgame.pay != 0){ ctra++; $scope.currentgame.realstate = "active" }
                else{ $scope.currentgame.realstate = "paused" }  

                $scope.countries = $scope.currentgame.country;
                $scope.countries = $scope.arrangeCountries($scope.currentgame.country);
                track('currentgame:', $scope.currentgame);
                track("countries", $scope.countries);
                if($scope.currentgame.videos.length == 0){
                    videoService.getVideosOfGame($scope.currentgame.id,$scope.currentgame._id.$id).then(function(data){
                        $scope.currentgame.videos = data;
                        $scope.currentSort = $scope.sortBy[0];
                        if($scope.currentgame.videos.length == 0){
                            $('.no-videos').show();
                            $('.pagers').hide();
                        }
                        else{
                            $('.no-videos').hide();
                            $('.pagers').show(); 
                        }
                        $('.loader-videos').hide();
                        track('novideos: php/local-savevideos.php?g='+$scope.currentgame.id+'&gamemongoid='+$scope.currentgame._id.$id , data);
                    });
                }
                else{
                    $scope.currentgame.videos = $scope.currentgame.videos;
                    if($scope.currentgame.videos.length == 0){
                        $('.no-videos').show();
                        $('.pagers').hide();
                        track('show', $scope.currentgame.videos)
                    }
                    else{
                        $('.pagers').show();
                        $('.no-videos').hide();
                    }
                    $('.loader-videos').hide();
                    track('hasvideos', $scope.currentgame.videos)
                    $scope.currentSort = $scope.sortBy[0];
                }
            gamesService.getGameDetails($routeParams.gameid).then(function(data){
                        $scope.currentgamedetails = data[0];
                        track('details',data[0])
                    });
            gamesService.getGameScreens($scope.currentgame.name.toLowerCase()).then(function(data){
                        $scope.carou = data;
                        track('carou', $scope.carou);
                    });
            
     });
    $scope.refreshVideo = function(){
        $scope.currentgame.videos = [];
        $('.pagers').hide();
        $('#refreshVideo').button('loading');
        videoService.getVideosOfGame($scope.currentgame.id,$scope.currentgame._id.$id).then(function(data){
                    $scope.currentgame.videos = [];
                    $scope.currentPage = 0;
                    $scope.currentgame.videos = data;

                    for(var v in $scope.currentgame.videos){
                        $scope.currentgame.videos[v].statistics.viewCount = parseInt($scope.currentgame.videos[v].statistics.viewCount);
                    }
                     $scope.num = Math.ceil($scope.currentgame.videos.length/$scope.pageSize);
                     if($scope.num==0){
                        $('.no-videos').show();
                        $('.pagers').hide();
                     }
                     else{
                        $('.no-videos').hide();
                        $('.pagers').show();
                     }
                    // console.log($scope.currentgame.videos);
                    $('#refreshVideo').button('reset')
                    $('.loader-videos').hide();
                    $scope.currentSort = $scope.sortBy[0];

            });

    }
    
    $scope.getNewUrl = function(){
        $scope.currentuser = $scope.user;
        track('user', $scope.currentuser);
        if($scope.currentuser.email!=''){
            $scope.loginmessage.message = "Generating your Play Now link...";
            $scope.loginmessage.cls = "alert-success logmess-v";
            $('#loginformp').hide();
            $('#linkformp').hide();
            $('#waitformp').show();
            var str = $scope.currentgame.id.split(',');
            $('#linktext').val('');
            // $('#linktext').attr('disabled', 'true');
            var real_offer = 0;
            if($scope.currentgame.redirect_offer_id == 0)
                real_offer = str[0].substr(0,str[0].length);
            else
                real_offer = $scope.currentgame.redirect_offer_id;
            userService.getPlayNowLink($scope.currentuser.id, real_offer).then(function(data){
                track('link', data)  
                $('#loginformp').hide();
                $('#linkformp').show();
                $('#waitformp').hide();
                // $('#loginform').hide();
                // $('#loginheader').hide();
                $('#linktext').val(data);
                // $('#linktext').attr('disabled', 'false');
                return;
            });
        }
    }
// LOADVIDEOS
  
    
}