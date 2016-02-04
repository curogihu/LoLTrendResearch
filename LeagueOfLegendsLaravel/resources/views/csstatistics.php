<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

//ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

if(!isset($_SESSION['cs'])){
    $_SESSION['cs'] =
      DB::select('select mpi.Tier, ' .
                        'mpi.Division, ' .
                        'count(mpi.SummonerId) SummonerCnt, ' .
                        'truncate(avg(mps.MinionsKilled) , 0) AvgMinionsKilled ' .
                  'from MatchPlayerInfo mpi ' .
                  'inner join MatchPlayerSetting mps ' .
                    'on mpi.MatchId = mps.MatchId ' .
                    'and mpi.ParticipantId = mps.ParticipantId ' .

                  'inner join RankSort rs ' .
                    'on mpi.Tier = rs.Tier ' .
                    'and mpi.Division = rs.Division ' .

                  'group by mpi.Tier, mpi.Division ' .
                  'order by rs.OrderId desc');
}

function getCsTableTag($statistics){
  $tmpStr = "<table border='1' align='center'>";
  $tmpStr .= "<tr>";
  $tmpStr .= "<th>Tier</th>";
  $tmpStr .= "<th>Division</th>";
  $tmpStr .= "<th>SummonerCnt</th>";
  $tmpStr .= "<th>AvgMinionsKilled</th>";
  $tmpStr .= "</tr>";

  foreach($statistics as $info){
    $tmpStr .= "<tr>";

    // will convert html string to blade template
    $tmpStr .= "<td>" . $info->Tier . "</td>";
    $tmpStr .= "<td>" . $info->Division . "</td>";
    $tmpStr .= "<td>" . $info->SummonerCnt . "</td>";
    $tmpStr .= "<td>" . $info->AvgMinionsKilled . "</td>";
    $tmpStr .= "</tr>";
  }

  return $tmpStr . "</table>";
}

// laravel framework version
function getLanguageSelectTag($languages){
  $tmpStr = '<select name="language" id="languages">';

  foreach($languages as $language){
    $tmpStr .= '<option value="' . $language . '">' . $language . '</option>';
  }

  return $tmpStr . '</select>';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="description" contents="ゲーム「リーグオブレジェンド」において、各チャンピオンの試合時間に適したアイテムが分かるサイトです。 On League of lengeds, You can know when and what should I buy items each champion.">
  <meta name="keywords" content="リーグオブレジェンド, アイテム, 初心者, チャンピオン, League of Legends, LoL, lol, Champion, Item">
  <title>LoL Trend Research</title>
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="css/default.css" type="text/css">
</head>
<body>
  <div id="container">

    <div id="header" class="middleContentItem">
      <h1><a href='http://loltrendresearch.xyz'>LoL Trend Research</a></h1>
    </div>

    <div id="left"></div>

    <div id="middle">
      <div id="menu" class="middleContentItem">
        <h2><a href="/whenbuy" class="menuItem">When buy</a></h2>
        <h2><a href="/whenkilled" class="menuItem">When killed</a></h2>
        <h2><a href="/wherelane" class="menuItem">Where lane</a></h2>
        <h2><a href="/howmanycs" class="menuItem">How many CS</a></h2>
        <h2><a href="/form" class="menuItem">Search</a></h2>
      </div>

      <div id="contents" class="middleContentItem">
        <?php
          echo getCsTableTag($_SESSION['cs']);
        ?>
      </div>
    </div>

    <div id="right"></div>

    <div id="footer" class="middleContentItem"><p>&copy; 2015 LoLTrendResearch</p></div>
  </div>
</body>
</html>