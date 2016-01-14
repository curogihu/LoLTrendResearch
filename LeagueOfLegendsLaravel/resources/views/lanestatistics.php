<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

//ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

$champion = DB::table('Champion')
            ->select('ChampionName')
            ->orderBy('Champion.ChampionName', 'asc')
            ->get();

if(!isset($_SESSION['championLane'])){

  $tmpOutputArr = null;
  $tmpArr = DB::table('MatchPlayerSetting')
             ->select(DB::raw('Champion.ChampionName, ' .
                            'sum(case when MatchPlayerSetting.Lane = "TOP" then 1 else 0 end) as TOP, ' .
                            'sum(case when MatchPlayerSetting.Lane = "MIDDLE" then 1 else 0 end) as MID, ' .
                            'sum(case when MatchPlayerSetting.Lane = "JUNGLE" then 1 else 0 end) as JG, ' .
                            'sum(case when MatchPlayerSetting.Role = "DUO_CARRY" then 1 else 0 end) ADC, ' .
                            'sum(case when MatchPlayerSetting.Role = "DUO_SUPPORT" then 1 else 0 end) as SUP '))
            ->join('Champion', function($join)
            {
              $join->on('MatchPlayerSetting.ChampionId', '=', 'Champion.ChampionId');
            })
            ->groupby('Champion.ChampionName')
            ->orderBy('Champion.ChampionName', 'asc')
            ->get();

  /*
  After converting value to integer, adding each champion data to an array.
  I tried converting string to interger when executing select statement, but didn't it.
  */
  foreach ($tmpArr as $info) {
    $tmpOutputArr[] = array("ChampionName" => $info->ChampionName,
                          "TOP" => intval($info->TOP),
                          "MID" => intval($info->MID),
                          "JG" => intval($info->JG),
                          "ADC" => intval($info->ADC),
                          "SUP" => intval($info->SUP));
  }

  $_SESSION['championLane'] = json_safe_encode($tmpOutputArr);
}

// laravel framework version
/*
function getLanguageSelectTag($languages){
  $tmpStr = '<select name="language" id="languages">';

  foreach($languages as $language){
    $tmpStr .= '<option value="' . $language . '">' . $language . '</option>';
  }

  return $tmpStr . '</select>';
}
*/
function json_safe_encode($data){
  return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/*
function getChampionLaneTag($championLanes){
  $cnt = 0;

  echo "<table border='1' align='center'>";
  echo "<tr>";
  echo "<th>Champion</th>";
  echo "<th>TOP</th>";
  echo "<th>MID</th>";
  echo "<th>ADC</th>";
  echo "<th>SUP</th>";
  echo "<th>JUG</th>";
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
      echo "<th>MID</th>";
      echo "<th>ADC</th>";
      echo "<th>SUP</th>";
      echo "<th>JUG</th>";
      echo "</tr>";
    }
  }

  echo "</table>";
}
*/
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>LoL Trend Research</title>
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="css/default.css" type="text/css">
  <link rel="stylesheet" href="css/classic.css" type="text/css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery.columns.min.js"></script>
</head>

<script>
  $(document).ready(function() {
    var json = <?php echo $_SESSION['championLane']; ?>;

    $('#columns').columns({
      data:json,
      sortBy: 'ChampionName'
    });
  });
</script>

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
        <div id="columns"></div>
      </div>
    </div>

    <div id="right"></div>

    <div id="footer" class="middleContentItem"><p>&copy; 2015 LoLTrendResearch</p></div>
  </div>
</body>
</html>