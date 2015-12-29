<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

//ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

if(!isset($_SESSION['monster'])){
    $_SESSION['monster'] =
      DB::select('select tmp.MonsterType, ' .
                          'tmp.KilledMin, ' .
                          'count(tmp.KilledMin) KilledCnt ' .
                  'from( ' .
                    'SELECT MonsterType, ' .
                            'truncate(TimeStamp / 1000 / 60, 0) + ' .
                          'case ' .
                            'when mod(truncate(TimeStamp / 1000, 0), 60) > 0 then 1 else 0 ' .
                          'end KilledMin ' .
                    'FROM EliteMonsterKillLog ' .
                  ') tmp ' .
                  'group by tmp.MonsterType, tmp.KilledMin ' .
                  'order by tmp.MonsterType, tmp.KilledMin ');
}

function getMonsterTableTag($statistics){
  $tmpStr = "<table border='1' align='center'>";
  $tmpStr .= "<tr>";
  $tmpStr .= "<th>MonsterType</th>";
  $tmpStr .= "<th>KilledMin</th>";
  $tmpStr .= "<th>KilledCnt</th>";
  $tmpStr .= "</tr>";

  foreach($statistics as $info){

    $tmpStr .= "<tr>";

    // will convert html string to blade template
    $tmpStr .= "<td>" . $info->MonsterType . "</td>";
    $tmpStr .= "<td>" . $info->KilledMin . "</td>";
    $tmpStr .= "<td>" . $info->KilledCnt . "</td>";
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
      <h1>LoL Trend Research</h1>
    </div>

    <div id="left"></div>

    <div id="middle">
      <div id="menu" class="middleContentItem">
        <a href="/whenbuy" class="menuItem">When buy</a>
        <a href="/whenkilled" class="menuItem">When killed</a>
        <a href="/wherelane" class="menuItem">Where lane</a>
        <a href="/howmanycs" class="menuItem">How many CS</a>
      </div>

      <div id="contents" class="middleContentItem">
        <?php
          echo getMonsterTableTag($_SESSION['monster']);
        ?>
      </div>
    </div>

    <div id="right"></div>

    <div id="footer" class="middleContentItem"><p>&copy; 2015 LoLTrendResearch</p></div>
  </div>
</body>
</html>