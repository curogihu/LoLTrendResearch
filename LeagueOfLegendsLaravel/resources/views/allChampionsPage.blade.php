<!DOCTYPE html>
<html lang="ja" ng-app="itemBuildStatisticsApp">
<head>
  <meta charset="UTF-8">
  <meta name="description" contents="ゲーム「リーグオブレジェンド」において、各チャンピオンの試合時間に適したアイテムが分かるサイトです。 On League of lengeds, You can know when and what should I buy items each champion.">
  <meta name="keywords" content="リーグオブレジェンド, アイテム, 初心者, チャンピオン, League of Legends, LoL, lol, Champion, Item">
  <title>LoL Trend Research</title>
  <link rel="stylesheet" href="{{{asset('/css/bootstrap.css')}}}" type="text/css">
  <link rel="stylesheet" href="{{{asset('/css/default.css')}}}" type="text/css">
</head>

<body ng-controller="ChampionsController as ChampionsCtrl">
  <?php include_once("analytics/analyticstracking.php") ?>
  <script src="{{{asset('/js/angular.js')}}}"></script>

  <div id="container">

    <div id="header" class="middleContentItem">
      <h1>{!! link_to('http://loltrendresearch.xyz', 'LoL Trend Research') !!}</h1>
    </div>

    <div id="left">
      @yield('advertisement1')
    </div>

    <div id="middle">
      <div id="menu" class="middleContentItem">
        <h2><a href="/whenbuy" class="menuItem">When buy</a></h2>
        <h2><a href="/whenkilled" class="menuItem">When killed</a></h2>
        <h2><a href="/wherelane" class="menuItem">Where lane</a></h2>
        <h2><a href="/howmanycs" class="menuItem">How many CS</a></h2>
        <h2><a href="/form" class="menuItem">Search</a></h2>
      </div>

      <div id="contents" class="middleContentItem">

        <div ng-repeat="champion in ChampionsCtrl.champions" class='eachChampion' style="margin-top: 30px;">
          <img ng-src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/<%champion.ChampionKey%>.png' />
          <p>
            <%champion.ChampionName%><br>
            <a ng-href="whenbuy/<%champion.ChampionKey%>/en">English</a><br>
            <a ng-href="whenbuy/<%champion.ChampionKey%>/ja">Japanese</a><br>
          </p>
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
    //var json = $http.get('champions');

    var app = angular.module('itemBuildStatisticsApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    });

    app.controller('ChampionsController', function($scope, $http){
      //json = $http.get('champions');
      //json = sessionStorage.getItem('champions');

      this.champions = angular.fromJson(json);

    });

  </script>


</body>
</html>