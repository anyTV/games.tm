<div class="referrals" ng-controller="ReferralController">
    <div class="referralLoader" ng-show="loadingReferrals">
        <img src="img/ajax-loader.gif">
    </div>

    <div ng-show="!loadingReferrals">
        <div class="jumbotron marbottom">
            <h1>My Referrals</h1>
            <!-- </hr> -->
        </div>
            <div class="col-lg-4">
                <p class="" ng-repeat="r in referrals1">{{r.company}}</p>
            </div>
            <div class="col-lg-4">
                <p class="" ng-repeat="r in referrals2">{{r.company}}</p>
            </div>
            <div class="col-lg-4">
                <p class="" ng-repeat="r in referrals3">{{r.company}}</p>
            </div>

    </div>
</div>