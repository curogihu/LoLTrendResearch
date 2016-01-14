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
  <title>LoL Trend Research</title>
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="css/default.css" type="text/css">
</head>
<body>
  <?php include_once("analytics/analyticstracking.php") ?>
  <div id="container">

    <div id="header" class="middleContentItem">
      <a href='http://loltrendresearch.xyz'> LoL Trend Research</a>
    </div>

    <div id="left"></div>

    <div id="middle">
      <div id="menu" class="middleContentItem">
        <a href="/whenbuy" class="menuItem">When buy</a>
        <a href="/whenkilled" class="menuItem">When killed</a>
        <a href="/wherelane" class="menuItem">Where lane</a>
        <a href="/howmanycs" class="menuItem">How many CS</a>
        <a href="/form" class="menuItem">Search</a>
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