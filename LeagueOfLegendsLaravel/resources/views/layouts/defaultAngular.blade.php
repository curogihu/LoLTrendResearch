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
        <a href="/whenbuy" class="menuItem">@yield('menuItem1')</a>
        <a href="/whenkilled" class="menuItem">@yield('menuItem2')</a>
        <a href="/wherelane" class="menuItem">@yield('menuItem3')</a>
        <a href="/howmanycs" class="menuItem">@yield('menuItem4')</a>
        <a href="/form" class="menuItem">@yield('menuItem5')</a>
      </div>

      <div id="contents" class="middleContentItem">
        <ul ng-repeat="champion in ChampionsCtrl.champions">
          <li><%champion.ChampionKey + ', ' + champion.ChampionName%></li>
        </ul>
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