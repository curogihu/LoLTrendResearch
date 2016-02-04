<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>LoL Trend Research</title>
<!--
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="css/default.css" type="text/css">
-->

  <link rel="stylesheet" href="{{{asset('/css/bootstrap.css')}}}" type="text/css">
  <link rel="stylesheet" href="{{{asset('/css/default.css')}}}" type="text/css">

<!--
  HTML::script
-->
  <!-- HTML::style('css/default.css'); -->
</head>
<body>
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
        <a href="{{{asset('/wordpress')}}}" class="menuItem">Blog</a>
      </div>

      <div id="contents" class="middleContentItem">
<!--
        {!! Form::open(array('url' => 'register', 'method' => 'POST')) !!}
        <div class="form-group">
          <p>Regsiter</p>
          {!! Form::label('registerSummonerNameLabel', 'Summoner Name:') !!}
          {!! Form::text('registerSummonerName', null) !!}
          {!! Form::submit('Register') !!}
        </div>

        {!! Form::close() !!}
-->

        {!! Form::open(array('url' => 'search', 'method' => 'GET')) !!}
        <div class="form-group">
          <p>Summoners in only NA server are targeted.</p>
          {!! $errorMessage !!}
          {!! Form::label('summonerNameLabel', 'Summoner Name') !!}
          {!! Form::text('summonerName', null) !!}
          {!! Form::submit('Search') !!}
        </div>

      {!! Form::close() !!}
      </div>

    </div>

    <div id="right">
      @yield('advertisement2')
    </div>

    <div id="footer" class="middleContentItem"><p>&copy; 2015 LoLTrendResearch</p></div>
  </div>
</body>
</html>