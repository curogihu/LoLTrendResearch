<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

$champion = DB::table('Champion')
            ->select('ChampionName')
            ->orderBy('Champion.ChampionName', 'asc')
            ->get();

if(!isset($_SESSION['championLane'])){
  $_SESSION['championLane'] =
    DB::table('MatchPlayerSetting')
       ->select(DB::raw('Champion.ChampionName, ' .
                      'sum(case when MatchPlayerSetting.Lane = "TOP" then 1 else 0 end) + 0 as TOP, ' .
                      'sum(case when MatchPlayerSetting.Lane = "MIDDLE" then 1 else 0 end) + 0 as MID, ' .
                      'sum(case when MatchPlayerSetting.Lane = "JUNGLE" then 1 else 0 end) + 0 as JG, ' .
                      'sum(case when MatchPlayerSetting.Role = "DUO_CARRY" then 1 else 0 end) + 0 as ADC, ' .
                      'sum(case when MatchPlayerSetting.Role = "DUO_SUPPORT" then 1 else 0 end) + 0 as SUP '))
      ->join('Champion', function($join)
      {
        $join->on('MatchPlayerSetting.ChampionId', '=', 'Champion.ChampionId');
      })
      ->groupby('Champion.ChampionName')
      ->orderBy('Champion.ChampionName', 'asc')
      ->get();
}

// laravel framework version
function getLanguageSelectTag($languages){
  $tmpStr = '<select name="language" id="languages">';

  foreach($languages as $language){
    $tmpStr .= '<option value="' . $language . '">' . $language . '</option>';
  }

  return $tmpStr . '</select>';
}

function json_safe_encode($data){
  return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function getChampionLaneTag($championLanes){
  $cnt = 0;

  echo "<table border='1'>";
  echo "<tr>";
  echo "<th>Champion</th>";
  echo "<th>TOP</th>";
  echo "<th>MIDDLE</th>";
  echo "<th>AD CARRY</th>";
  echo "<th>SUPPORT</th>";
  echo "<th>JUNGLE</th>";
  echo "</tr>";

  foreach($championLanes as $championLane){
    echo "<tr>";
    echo "<td>". $championLane->ChampionName . "</td>";
    echo "<td>". $championLane->TOP . "</td>";
    echo "<td>". $championLane->MID . "</td>";
    echo "<td>". $championLane->ADC . "</td>";
    echo "<td>". $championLane->SUP . "</td>";
    echo "<td>". $championLane->JG . "</td>";
    echo "</tr>";

    $cnt++;

    if($cnt % 20 === 0){
      echo "<tr>";
      echo "<th>Champion</th>";
      echo "<th>TOP</th>";
      echo "<th>MIDDLE</th>";
      echo "<th>AD CARRY</th>";
      echo "<th>SUPPORT</th>";
      echo "<th>JUNGLE</th>";
      echo "</tr>";
    }
  }

  echo "</table>";
}
?>

<!DOCTYPE html>
<script>
  $(document).ready(function() {
    var targetJson = <?php echo json_safe_encode($_SESSION['championLane']); ?>;

    $('#columns').columns({
      data:targetJson
    });
  });
</script>

<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>LoL Trend Research</title>
  <!--
  <script src="js/jquery-2.1.4.min.js"></script>
  <script src="js/jquery.columns.min.js"></script>
  <link rel="stylesheet" href="css/classic.css">

  <script src="{{ URL::asset('js/jquery-2.1.4.min.js') }}"></script>
  <script src="{{ URL::asset('js/jquery.columns.min.js') }}"></script>
  <link rel="stylesheet" href="{{ URL::asset('css/classic.css') }}">
  -->
</head>
<body>
  <div>
    <h1>LoL Trend Research</h1>
    <?php echo getLanguageSelectTag($_SESSION['language']); ?>
  </div>

  <div>
    <h2>Role Statistics</h2>
    <?php echo getChampionLaneTag($_SESSION['championLane']); ?>
  </div>
<!--
  <div id="columns">
  </div>
-->
</body>
</html>