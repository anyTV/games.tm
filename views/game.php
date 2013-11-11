  <div id="ViewMode" ng-controller="VideoController" ng-init="showOnLoad()">

          <div class="gamename">
              <section class='pull-left heads'>
              {{currentgame.name}} &nbsp;
              </section>
              <section class="pull-left">
                <a id = 'login' data-toggle="modal" href=".myModal" class="btn btn-danger loginlink" ng-click="getNewUrl()"  >
                Get your Play Now link! </a> 
                <a class="btn btn-success website-btn" href="{{currentgame.website}}" target="_blank">
                 See the official website </a> 
              </section>
              <div class="clearfix"></div>
          </div>
<!--    
           <div class="piccontainer" ng-show = "carou">
             <div class="photo">
                <carousel interval="myInterval">
                  <slide ng-repeat="slide in carou" active="slide.cls">
                    <img delayedsrc="http://www.gameplay.tm/re/server/php/files/{{slide.img}}-logo.jpg" class = "picture" onerror="http://www.gameplay.tm/re/server/php/files/default1.jpg">
                  </slide>
                </carousel>
             </div>
             <div class="gameintro">
                <div class="gamedetail" >
                  {{currentgamedetails.description}}
                </div>
                <div>
                  See the game in action <a href='http://www.gameplay.tm/re/game.php?preview={{currentgame.pic}}'> here!</a>
                </div>
               <div class="playnow">   
                 <a id = 'login' data-toggle="modal" href=".myModal" class="btn btn-danger loginlink" ng-click="getNewUrl()"  >
                  Get your Play Now link! </a> 
                  <a class="btn btn-danger" href="{{currentgame.website}}" target="_blank">
                   Official Website </a> 
               </div>
             </div >
           </div> -->

           <div class="gametitle">
               <div class="pull-left">
                   <form class= "pull-left form-inline search-form"> 
                       <input type="text" class='form-control search-video search-control' ng-model="search.snippet.channelTitle"  size="30" placeholder="Search video here.">
                       Sort by: <select class="form-control gamesort2 search-control" ng-model="currentSort" ng-options="item as item.sortname for item in sortBy"></select>
         
                   </form>
               </div>
               <div class="pull-right paid-countries">
                 <h4 class="panel-title">
                    <!-- <button class="btn btn-default" ng-click="isCollapsed = !isCollapsed" ng-hide="currentgame.realstate == 'paused' ">Paid Countries</button> -->
                    <button class="btn btn-default" ng-click="isCollapsed = !isCollapsed" >Paid Countries</button>
                    <button id = 'refreshVideo' class="btn  btn-default" ng-click="refreshVideo()" data-loading-text="Updating..."> Reload Videos </button>

                 </h4>
               </div>
               <div style="clear:both"></div>
           </div>
           <div ng-show="isCollapsed">
              <div class="country-container">
                <table class="countrylist table">
                   <tr ng-repeat="country in countries">

                       <td><div ng-repeat="cou in country.1"><div ng-repeat="c in cou">{{c.name}}</div></div></td>
                       <td>$ {{country.0}} </td>
                   </tr>
               </table>
              </div> 
           </div>


           <div class="no-videos">No videos for this game yet.</div>

           <div class='loader-videos'><img src="img/ajax-loader.gif"></div>
           
           <div class="video-container">
               <div class="video" ng-repeat="video in ((currentgame.videos | filter:search.snippet.channelTitle) | orderBy:currentSort.sorttext:currentSort.reverse | startFrom:currentPage*pageSize | limitTo:pageSize) " >
                 <div class="video-thumb pull-left">
                    <a href="http://www.youtube.com/watch?v={{video.id}}" target="_blank"> <img ng-src="{{video.snippet.thumbnails.default.url}}" /></a></div>
                 <div class="video-detail pull-left">
                   <ul class="list-unstyled video-list">
                     <li><span ng-class="{true:'badge',false:''}[currentSort.currentclass=='title']"><a target="_blank" href="http://www.youtube.com/watch?v={{video.id}}">{{video.snippet.title}}</a></span></li>
                     <li>by: <a href="http://www.youtube.com/user/{{video.snippet.channelTitle}}">{{video.snippet.channelTitle}}</a> -
                      <span ng-class="{true:'badge',false:''}[currentSort.currentclass=='view']">{{formatMoney(video.statistics.viewCount)}} YouTube views - </span>
                      <span ng-class="{true:'badge',false:''}[currentSort.currentclass=='click']" > {{video.stat.clicks}} Popularity </span>
                     </li>
                     <li>Uploaded: <span ng-class="{true:'badge',false:''}[currentSort.currentclass=='date']">{{timeago(video.snippet.publishedAt)}}</span></li>
                   </ul>
                 </div>
                 <div style="clear:both"></div>
               </div>
           </div>
           <div style="clear:both"></div>
           <section class="pagers" id="gamesPager">
             <input type="button" class="btn btn-default" ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1" value="Previous"/>
             {{currentPage+1}}/{{numberOfPages()}}
             <input type="button" class="btn btn-default" ng-disabled="currentPage >= games.length/pageSize - 1" ng-click="currentPage=currentPage+1" value="Next"/>

           </section>
       <div class="modal fade modal-login myModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
         <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header" >
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   <h3><img src="https://media.go2app.org/user_content/brand/logos/mmotm/logo_1362130084.png"> </h3>
               </div>
             <div class="modal-body">

               <div role="form" id="loginformp">
                 <form>
                   <div class="form-group">
                     <label for="exampleInputEmail1">Email address</label>

                     <input value="" type="text" ng-model = "useremail" class="form-control" id="exampleInputEmail1" ng-model ="useremail" placeholder="Enter email">
                   </div>
                   <div class="form-group">
                     <label for="exampleInputPassword1">Password </label>
                     <input value="" type="password" ng-model = "userpassword" class="form-control" id="exampleInputPassword1" placeholder="Password">
                   </div>
                   <button data-loading-text="Signing in..." id="loginbtnp" class="btn btn-primary" ng-click="Login(useremail, userpassword)">Sign in</button>
                   <span class="pull-right">
                       New to any.tv? <a class="btn btn-danger" href="http://www.dashboard.tm/signup"> Create an account</a>
                                     </span>
                 </form>
               </div>

               <div id="waitformp">
                  <div id="loginheader">
                      <div class="alert logmess {{loginmessage.cls}} " id="loginmessage"> {{loginmessage.message}} </div>
                  </div>
               </div>

               <div id="linkformp" class="link-container" >
                 <span>Here is your link for the game:</span>
                 <div><input class="form-control text-center" type="text" id="linktext"></div>
                 <div class="linkdiv">
                 <a id = 'copylink' class="btn btn-primary" onmouseover="copy('#copylink','#linktext')"><i class="icon-copy"></i> Click to Copy</a></div>
               </div>


             </div>
           </div><!-- /.modal-content -->
         </div><!-- /.modal-dialog -->
        </div>

  
  </div>