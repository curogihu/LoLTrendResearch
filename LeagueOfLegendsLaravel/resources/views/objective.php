<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

if(!isset($_SESSION['objective'])){
  $_SESSION['objective'] =
    DB::table('MatchPlayerInfo')
      ->select(DB::raw('MatchPlayerInfo.Tier, MatchPlayerInfo.Division, count(MatchPlayerInfo.SummonerName) as Number'))
      ->join('RankSort', function($join)
      {
        $join->on('MatchPlayerInfo.Tier', '=', 'RankSort.Tier');
        $join->on('MatchPlayerInfo.Division', '=', 'RankSort.Division');
      })
      ->groupby('MatchPlayerInfo.Tier', 'MatchPlayerInfo.Division')
      ->orderBy('RankSort.OrderId', 'desc')
      ->get();
}

function getRankDistributionTag($statistics){
  $tmpStr = "<table border='1'>";
  $tmpStr .= "<tr>";
  $tmpStr .= "<th>Tier</th><th>Division</th><th>Number</th>";
  $tmpStr .= "</tr>";

  foreach($statistics as $info){
    $tmpStr .= "<tr>";
    $tmpStr .= "<td>" . $info->Tier . "</td>";
    $tmpStr .= "<td>" . $info->Division . "</td>";
    $tmpStr .= "<td>" . $info->Number . "</td>";
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
</head>
<body>
  <div>
      <h1>LoL Trend Research</h1>
    <?php echo getLanguageSelectTag($_SESSION['language']); ?>
  </div>

  <div>
    <h2>Objective data</h2>
    <?php echo getRankDistributionTag($championRankData); ?>
  </div>
</body>
</html>