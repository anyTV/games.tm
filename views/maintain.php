<div ng-controller="MaintainController" class="maintain-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="featured-games">

              <section class="box-header">
                  <aside class="pull-left add-padding" ng-init="hideMenu = true;">FEATURED GAMES</aside>
                  <aside  class="pull-right add-padding"  >
                    <a ng-click="hideMenu = !hideMenu;" class="btn btn-default">{{{true:'Add Genre',false:'Hide'}[hideMenu]}}</a>
                     <!-- ng-class="{true:'glyphicon glyphicon-plus-sign white',false:'glyphicon glyphicon-minus-sign white'}[hideMenu]" -->
                  </aside>
                  <div class="clearfix"></div>
              </section>
              <section ng-hide="hideMenu" class="form-container">
                  <form ng-submit="addGenre(toadd); toadd.genre_initials = ''; toadd.genre_name = '';" >
                      <table>
                          <tr>
                              <td><label for="genreIni" class="pull-left">Genre</label></td>
                              <td><input type="text" ng-model="toadd.genre_initials" class="col-md-8 form-control genretext" id="genreIni" placeholder="Enter name of genre"></td>
                          </tr>
                          <tr>
                              <td><label for="genreDesc" >Description</label></td>
                              <td><input type="text" ng-model="toadd.genre_name" class="col-md-8 form-control genretext" id="genreDesc" placeholder="Enter description"></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td><input type="submit" class="btn btn-success pull-right" value="ADD GENRE"></input></td>
                          </tr>
                      </table>
                  </form>
              </section>
              <section class="overflow">
                  <div class="panel-group" id="accordion">
                    <div class="" ng-repeat="g in hgames">
                      <div class="title" >
                          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapses{{$index}}" >
                            <div>
                              <span class='pull-left' tooltip-placement="right" tooltip="{{g.genre_name}}" >
                                 {{g.genre_initials}} [{{g.games.length}}] 
                              </span>
                              <span class='pull-right'>
                                  <span class="glyphicon glyphicon-trash" ng-click="deleteGenre(g.genre_id, $index)" tooltip-placement="right" tooltip="Delete {{g.genre_initials}}"></span>
                                  
                              </span>
                              <div class="clearfix"></div>
                            </div>
                          </a>
                      </div>
                      <div id="collapses{{$index}}" class=" panel-collapse collapse featured-box-content " ng-init="hgamesIdx = $index;">
                          <aside  ng-repeat="game in g.games">
                            <a href="#/game/{{game.game_fid}}" ng-click="closeOthers();game.active = !game.active;" ng-class="{true:'hotgame-item-active',false:'hotgame-item'}[game.active]">
                              <span class="pull-left">
                                &nbsp; {{game.game_name}}
                              </span>
                              <span class="pull-right add-padding">
                                <span class="glyphicon glyphicon-remove red" ng-click="deleteFeaturedGame(game, hgamesIdx)"></span>
                              </span>
                              <div class="clearfix"></div>
                            </a>
                          </aside>
                          <input type="text" placeholder="Add game featured.." class="form-control add-input" ng-model="featGame"
                          typeahead-editable="false" typeahead-on-select='addFeaturedGame($item, g); featGame=""' 
                          typeahead="game.name for game in games | filter:$viewValue | limitTo:8">
                       </div>
                       
                    </div>
                  </div> <!-- end acc -->
              </section>
            </div>
            <div class="featured-games">
              <section class="box-header" ng-show="user.id==4">
                 <aside class="pull-left add-padding"  ng-init="hideMenuGamesGenre = true;">SITE ACCESS</aside>
                 <div class="clearfix"></div>
              </section>
              <section class="form-container">
                <form ng-submit="addAff()">
                  <aside class="pull-left add-padding">
                       <input type="text" class="search-aff " ng-model="newuser" placeholder="Add affiliate ID.." > 
                       
                  </aside>
                  <aside class="pull-right  add-padding">
                      <button class="btn btn-success add-padding">Add</button>
                  </aside>
                  <div class="clearfix"></div>
                </form>
              </section>
              <section class="overflow">
                      <section ng-repeat="u in ((siteUsers | filter:searchtext.name) | orderBy:'name':false) ">
                          <a class='hotgame-item'>
                            <aside class='pull-left'>
                              &nbsp;{{u.aff_id}} - {{u.name}}
                            </aside>
                            <aside class="pull-right add-padding">
                              <span class="glyphicon glyphicon-trash" ng-click="deleteUser(u.aff_id)" tooltip-placement="right" tooltip="Delete"></span>
                            </aside>
                            <div class="clearfix"></div>
                          </a>
                        </section>
              </section>
            </div>
        </div>

        <div class="col-lg-6">
          <div class="featured-games">
            <section class="box-header">
               <aside class="pull-left add-padding" ng-init="hideMenuGamesGenre = true;" ng-switch="changed!=null">
                <span ng-switch-when = "true">CHANGE GAMES LOGOS - Logo successfully changed.</span>
                <span ng-switch-when = "false">CHANGE GAMES LOGOS </span>
              </aside>
               <aside  class="pull-right add-padding"  ng-hide="!hideMenuGamesGenre" >
                <button id = 'refreshGames1' class="btn btn-default reload-btn" ng-click = "refreshGames();" data-loading-text="Updating..." ><span class="glyphicon glyphicon-refresh"></span> &nbsp;Update Games </button>
              </aside>
               <aside  class="pull-right add-padding"  ng-hide="hideMenuGamesGenre" >
                  <a ng-click="hideMenuGamesGenre = !hideMenuGamesGenre;" class="btn btn-default"> Hide </a>
               </aside>
               <div class="clearfix"></div>
            </section>
            <section ng-class="{true:'overflow-reduced',false:'overflow-normal'}[!hideMenuGamesGenre]"  style="height:{{hideMenuGamesGenre}}" id="games">
               
                    <section ng-repeat="g in ((games | filter:searchtext.name) | orderBy:'name':false) ">
                        <a ng-class="{true:'hotgame-item-active add-padding',false:'hotgame-item add-padding'}[g.clicked]" ng-click = "setActive(g)" >
                            <aside class="pull-left">
                              &nbsp;{{g.name}} <span class="green" ng-switch="changed==g.fid">
                                <span ng-switch-when="true"> - Logo successfully changed.</span>
                               </span> 
                            </aside>
                            <aside class="pull-right genre-right">
                              <i ng-class="{true:'glyphicon glyphicon-remove',false:'glyphicon glyphicon-ok'}[g.pic.indexOf('.')==-1]"></i>
                            </aside>
                            <div class="clearfix"></div>
                        </a></li>
            </section>
            </div>
        </div>
        <div class="col-lg-3"   id="gameslogo" ng-show="hideLogo">
          <div class="featured-games">
            <section class="box-header">
              <aside class="pull-left add-padding" ng-init="hideMenuGamesGenre = true;">GAME LOGO</aside>
              <div class="clearfix"></div>
            </section>
            <section class="window-body" >
              <div class="logo-box">
                <img id="logos" ng-src="upload/{{cg}}.png">
                <form method="POST" action="/php/maintain.php?mode=logo&game={{cg}}" enctype="multipart/form-data" id="photoForm">
                  <input type="hidden" name="game" value="{{cg}}">
                  <input type="hidden" name="gameid" value="{{cid}}">
                  <input class="changeBtn" type="file" name="file">
                  Ideal image size is  256 x 256.
                  <button  type="submit" class="btn btn-success saveBtn col-md-5">Save</button>
                  <button  type="button" class="btn btn-danger saveBtn col-md-5"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                  <div class="clearfix"></div>
                </form>
              </div>
            </section>
          </div>
        </div>


    </div>
<!-- 
    <div class="copyright">
      <aside class="pull-left">
        Copyright 2013 any.TV Limited | All Rights Reserved
      </aside>
      <aside class="pull-right">
          <a href="http://www.facebook.com/anyTVnetwork"><img src="img/facebook.png"></a>
          <a href="http://www.twitter.com/anyTVnetwork"><img src="img/twitter.png"></a>
          <a href="http://www.youtube.com/anyTVnetwork"><img src="img/youtube.png"></a>
      </aside>
    </div> -->
    <!-- <div class="clearfix"></div> -->

</div>