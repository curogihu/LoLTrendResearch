<!DOCTYPE html>
<html lang="ja" ng-app="itemBuildStatisticsApp">
<head>
  <meta charset="UTF-8">
  <title>LoL Trend Research</title>
  <link rel="stylesheet" href="{{{asset('/css/bootstrap.css')}}}" type="text/css">
  <link rel="stylesheet" href="{{{asset('/css/default.css')}}}" type="text/css">
</head>

<body ng-controller="ChampionsController as ChampionsCtrl">
  <?php include_once("analytics/analyticstracking.php") ?>
  <script src="{{{asset('/js/angular.js')}}}"></script>

  <div id="container">

    <div id="header" class="middleContentItem">
      {!! link_to('http://loltrendresearch.xyz', 'LoL Trend Research') !!}
    </div>

    <div id="left">
      @yield('advertisement1')
    </div>

    <div id="middle">
      <div id="menu" class="middleContentItem">
        <a href="/whenbuy" class="menuItem">When buy</a>
        <a href="/whenkilled" class="menuItem">When killed</a>
        <a href="/wherelane" class="menuItem">Where lane</a>
        <a href="/howmanycs" class="menuItem">How many CS</a>
        <a href="/form" class="menuItem">Search</a>
      </div>

      <div id="contents" class="middleContentItem">

        <div ng-repeat="champion in ChampionsCtrl.champions">
          <div class='eachChampion col-md-3' style="margin-top: 30px;">

            <img ng-src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/<%champion.ChampionKey%>.png' />
            <p>
              <%champion.ChampionName%><br>
              <a ng-href="whenbuy/<%champion.ChampionKey%>/en">English</a><br>
              <a ng-href="whenbuy/<%champion.ChampionKey%>/ja">Japanese</a><br>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div id="right">
      @yield('advertisement2')
    </div>

    <div id="footer" class="middleContentItem"><p>&copy; 2015 LoLTrendResearch</p></div>
  </div>

  <script type="text/javascript">
    var json = {!! $contents !!};

    var app = angular.module('itemBuildStatisticsApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    });

    app.controller('ChampionsController', function(){
      this.champions = angular.fromJson(json);
    });

  </script>


</body>
</html>