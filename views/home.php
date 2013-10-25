<div class="home" id="Home" ng-controller="HomeController">
<!--   <article class="info-message" id="info-message">
    <div class="jumbotron">
        <h1>Welcome to Games Team!</h1>
        <p class="lead">Earn about $1.00 every time someone new plays a game after watching your video or livestream!
            Here you can view our active games and their videos on YouTube! Try selecting one to see them.
        </p>
        <div class="text-center" >
        <embed width="560" height="315" src="http://www.youtube.com/v/1BZmecGM1Ag" type="application/x-shockwave-flash"></embed>
        </div>
    </div>
      
  </article> -->
  <tabset>
    <tab heading ="Games">
      <article id = "boxlists"  >
          <!-- ng-repeat="g in (games | filter:sort | orderBy: 'name':false | startFrom:currentPage2*pageSize2 | limitTo:pageSize2)"  -->
          <section class="info-message">
            <p class="lead">Earn about $1.00 every time someone new plays a game after watching your video or livestream!<br>
              Play Now links pay you $3.00 CPM on top of your YouTube CPM, on average! See this <a class="reportlink" href="https://docs.google.com/spreadsheet/ccc?key=0AjrNKskJC13udG1jQmhQQUNWWlNEVERfNEplTVlzMGc">report.</a>
              <a id="menu-toggle" ng-click="sideshow()" class="btn btn-default">
                <i class="glyphicon glyphicon-list"></i>
              </a></p>
          </section>
            <div class="jumbotron">
              <div class="container"> 
                <div class="welcome-message ">
                  <div class="logopic">
                    <div class="row">
                      <div class="col-md-4 col-md-offset-4 logohome"><img src="/img/anymed.png" alt="anytv"></div>
                    </div>
                    <h1> A new kind of YouTube network. <a class="btn btn-danger btn-join" href="http://www.any.tv/join" target="_blank">Join Us</button></a>
                  </div>
                </div>
                  <div class="col-lg-4">
                    <span class="subtitle">What is Games.tm?</span>
                    Games.tm provides you with a list of games supported by any.tv with their  Play Now link which
                    you can copy to your videos so you can start earning revenue. 
                    Games.tm also provides the status of your videos even if you have multiple Play Now links on your videos.
                  </div>
                  <div class="col-lg-4">
                    <span class="subtitle">Why join <img src="http://www.any.tv/wp-content/uploads/2013/03/anytv-logo-1600x1600-to-84x32.png">?</span>

                    We pay you recommendation revenue in addition to YouTube ad revenue, and we have a fully-functioning dashboard that reports your earnings the moment they happen.
                    Also, we pay you a lifetime 10% bonus for recommending us to your friends!
                  </div>
                  <div class="col-lg-4">
                    <span class="subtitle">Powered by AngularJS</span>
                    This tool is powered by the most super-heroic of all super-heroic framework. Angularjs provides this app with the realtime searches you need all the while maintaining ease of use and user friendliness. Thanks AngularJs! you're my hero!
                  </div>
                
              </div>
            </div>
          <div class="gamelistcontainer">
            <carousel interval="myInterval" ng-init="myInterval = 3000;">
              <slide ng-repeat="g in carousel" active="slide.active">
                <!-- <div class='gameimgbox ' ng-repeat="g in (games | orderBy: 'name':false | startFrom:currentPage2*pageSize2 | limitTo:pageSize2)" > -->
                <section class="pull-left" >
                  <div class='gameimgbox2 ' ng-hide="g.game1==null">
                    <a href="/#/game/{{g.game1.fid}}" class="carougame">
                      <div class="photo" ng-switch="g.game1.pic.indexOf('.')==-1"> 
                        <img id="gamepic" ng-switch-when="true" src="http://placehold.it/256x256/131313/EEEEEE/&text=N/A" >
                        <img id="gamepic" ng-switch-when="false" ng-src="/upload/{{g.game1.pic}}">
                      </div>
                      <p class="boxgamename">{{g.game1.name}}</p>
                    </a>
                  </div>
                  <div class='gameimgbox2 ' ng-hide="g.game2==null">
                    <a href="/#/game/{{g.game2.fid}}" class="carougame">
                      <div class="photo" ng-switch="g.game2.pic.indexOf('.')==-1"> 
                        <img id="gamepic" ng-switch-when="true" src="http://placehold.it/256x256/131313/EEEEEE/&text=N/A" >
                        <img id="gamepic" ng-switch-when="false" ng-src="/upload/{{g.game2.pic}}">
                       </div>
                      <p class="boxgamename">{{g.game2.name}}</p>
                    </a>
                  </div>
                  <div class='gameimgbox2 ' ng-hide="g.game3==null">
                    <a href="/#/game/{{g.game3.fid}}" class="carougame">
                      <div class="photo" ng-switch="g.game3.pic.indexOf('.')==-1"> 
                        <img id="gamepic" ng-switch-when="true" src="http://placehold.it/256x256/131313/EEEEEE/&text=N/A" >
                        <img id="gamepic" ng-switch-when="false" ng-src="/upload/{{g.game3.pic}}">
                       </div>
                       <p class="boxgamename">{{g.game3.name}}</p>
                    </a>
                  </div>
                  <div class='gameimgbox2 ' ng-hide="g.game4==null">
                    <a href="/#/game/{{g.game4.fid}}" class="carougame">
                      <div class="photo" ng-switch="g.game4.pic.indexOf('.')==-1"> 
                        <img id="gamepic" ng-switch-when="true" src="http://placehold.it/256x256/131313/EEEEEE/&text=N/A" >
                        <img id="gamepic" ng-switch-when="false" ng-src="/upload/{{g.game4.pic}}">
                       </div>
                       <p class="boxgamename">{{g.game4.name}}</p>
                    </a>
                  </div>
                  <div class='gameimgbox2 ' ng-hide="g.game5==null">
                    <a href="/#/game/{{g.game5.fid}}" class="carougame">
                      <div class="photo" ng-switch="g.game5.pic.indexOf('.')==-1"> 
                        <img id="gamepic" ng-switch-when="true" src="http://placehold.it/256x256/131313/EEEEEE/&text=N/A" >
                        <img id="gamepic" ng-switch-when="false" ng-src="/upload/{{g.game5.pic}}">
                       </div>
                       <p class="boxgamename">{{g.game5.name}}</p>
                    </a>
                  </div>
                  <div class='gameimgbox2 ' ng-hide="g.game6==null">
                    <a href="/#/game/{{g.game6.fid}}" class="carougame">
                      <div class="photo" ng-switch="g.game6.pic.indexOf('.')==-1"> 
                        <img id="gamepic" ng-switch-when="true" src="http://placehold.it/256x256/131313/EEEEEE/&text=N/A" >
                        <img id="gamepic" ng-switch-when="false" ng-src="/upload/{{g.game6.pic}}">
                       </div>
                       <p class="boxgamename">{{g.game6.name}}</p>
                    </a>
                  </div>
                  <div class="clearfix"></div>
                </section>
                <!-- </div> -->
              </slide>
            </carousel>
          </div>

      </article>
    </tab>

    <tab select='changeDate(true);' tooltip-placement="top" tooltip-html-unsafe="Videos getting clicks on their Play Now links.">
      <tab-heading>Trending Videos &nbsp;<span class='glyphicon glyphicon-info-sign'></span> </tab-heading>
      <article class="panel-success" id="datesearch"  >
          <div class="gametitle panel-success">
            <form class= "pull-left form-inline search-form"> 
                <input type="text" class='form-control search-video search-control' ng-model="search.snippet.channelTitle"  size="30" placeholder="Search video here.">
                  From:
                    <input type="text" ng-change="changeDate(false)" class="form-control datepicker search-control" show-weeks="false" datepicker-popup="yyyy-MMMM-dd" ng-model="dt1" is-open="opened1" min="minDate" max="limit1" datepicker-options="dateOptions" date-disabled="disabled(limit1, mode)"/>
                  To:
                    <input type="text" ng-change="changeDate(true)" class="form-control datepicker search-control" show-weeks="false" datepicker-popup="yyyy-MMMM-dd" ng-model="dt2" is-open="opened2" min="minDate" max="limit2" datepicker-options="dateOptions" date-disabled="disabled(limit2, mode)"/>
               Sort: <select class="form-control gamesort2" ng-model="tvCurrentSort" ng-options="item as item.sortname for item in sortBy"></select>
            </form>

            <div style="clear:both"></div>
          </div>

          <div class='loader-videos' id='tvLoader'><img src="img/ajax-loader.gif"></div>
          <div class='no-videos' id='ntvLoader' ng-show="videos1=='false'">No videos found on the date you selected.</div>

          <div class="video-container" ng-show="videos1">
            <div class="video" ng-repeat="video in ((videos1 | filter:search.snippet.channelTitle | orderBy:tvCurrentSort.sorttext:tvCurrentSort.reverse | startFrom:currentPage*pageSize | limitTo:pageSize)) " >
              <div class="video-thumb pull-left">
                 <a href="http://www.youtube.com/watch?v={{video.id}}" target="_blank"> <img ng-src="{{video.snippet.thumbnails.default.url}}" /></a></div>
              <div class="video-detail pull-left">
                <ul class="list-unstyled video-list">
                  <li><span ng-class="{true:'badge',false:''}[tvCurrentSort.currentclass=='title']"><a target="_blank" href="http://www.youtube.com/watch?v={{video.id}}">{{video.snippet.title}}</a></span></li>
                  <li>by: <a href="http://www.youtube.com/user/{{video.snippet.channelTitle}}">{{video.snippet.channelTitle}}</a> - 
                    <span ng-class="{true:'badge',false:''}[tvCurrentSort.currentclass=='view']">{{formatMoney(video.statistics.viewCount)}} YouTube views - </span> 
                    <span ng-class="{true:'badge',false:''}[tvCurrentSort.currentclass=='click']" tooltip-placement="right" tooltip-html-unsafe="{{trendOver}}" ng-mouseover="breakdown(video.stat.Sources)"> {{video.stat.clicks}} Popularity <span class='glyphicon glyphicon-info-sign'></span> </span>
                    <!-- {{trendOver}} -->
                  </li>
                  <!-- <li>Game: <strong ng-repeat="s in video.stat.Sources">{{s | json}}</strong></li> -->
                  <li>Uploaded: <span ng-class="{true:'badge',false:''}[tvCurrentSort.currentclass=='date']">{{timeago(video.snippet.publishedAt)}}</span></li>
                </ul>
              </div>
              <div style="clear:both"></div>
            </div>
          </div>

          <div class='pagers  ' id="tvPager"  >
              <input type="button" class="btn btn-default" ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1" value="Previous"/>
              {{currentPage+1}}/{{numberOfPages()}}
              <input type="button" class="btn btn-default" ng-disabled="currentPage >= videos.length/pageSize - 1" ng-click="currentPage=currentPage+1" value="Next"/>
          </div>
      </article>
    </tab>
    <tab select="LoadNewVideos()" tooltip-placement="top" tooltip="New Videos getting clicks on their Play Now links.">
      <tab-heading>Newest Videos &nbsp;<span class='glyphicon glyphicon-info-sign'></span> </tab-heading>
      
      <article class="panel-success" id="datesearch">
          <div class="gametitle panel-success">
            <form class= "pull-left form-inline search-form"> 
                <input type="text" class='form-control search-video search-control' ng-model="search.snippet.channelTitle"  size="30" placeholder="Search video here.">
                Sort: <select class="form-control gamesort2" ng-model="nvCurrentSort" ng-options="item as item.sortname for item in sortBy"></select>
            </form>

            <div style="clear:both"></div>
          </div>
          <div class='loader-videos' id='nvLoader'><img src="img/ajax-loader.gif"></div>
          <div class="video-container">
            <div class="video" ng-repeat="video in ((videos | filter:search.snippet.channelTitle | orderBy:nvCurrentSort.sorttext:nvCurrentSort.reverse )) " >
              <div class="video-thumb pull-left">
                 <a href="http://www.youtube.com/watch?v={{video.id}}" target="_blank"> <img ng-src="{{video.snippet.thumbnails.default.url}}" /></a></div>
              <div class="video-detail pull-left">
                <ul class="list-unstyled video-list">
                  <li><span ng-class="{true:'badge',false:''}[nvCurrentSort.currentclass=='title']"><a target="_blank" href="http://www.youtube.com/watch?v={{video.id}}">{{video.snippet.title}}</a></span></li>

                  <li>by: <a href="http://www.youtube.com/user/{{video.snippet.channelTitle}}">{{video.snippet.channelTitle}}</a>
                   - <span ng-class="{true:'badge',false:''}[nvCurrentSort.currentclass=='view']">{{formatMoney(video.statistics.viewCount)}} YouTube views - </span> 
                   <span ng-class="{true:'badge',false:''}[nvCurrentSort.currentclass=='click']" tooltip-placement="right" tooltip-html-unsafe="{{trendOver}}" ng-mouseover="breakdown(video.stat.Sources)"> {{video.stat.clicks}} Popularity <span class='glyphicon glyphicon-info-sign'></span></span>
                  </li>
                  <li>Uploaded: <span ng-class="{true:'badge',false:''}[nvCurrentSort.currentclass=='date']">{{timeago(video.snippet.publishedAt)}}</span></li>
                </ul>
              </div>
              <div style="clear:both"></div>
            </div>
          </div>
      </article>
    </tab>
  </tabset>

</div>