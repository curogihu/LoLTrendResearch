<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

if(!isset($_SESSION['item'])){
    $_SESSION['item'] = 
      DB::select('select tmp.ChampionName, ' .
                          'tmp.ChampionKey, ' .
                          'tmp.ItemName, ' .
                          'tmp.ItemImage, ' .
                          'tmp.ItemDescription, ' .
                          'truncate(avg(tmp.MinimumPurchaseTime) / 1000, 0) as AvgMinPurchaseTime, ' .
                          'count(tmp.ItemName) as Number ' .
                  'from ' .
                  '(' .
                  'select ibl.MatchId, ' .
                          'ibl.BuyerId, ' .
                          'mps.Championid, ' .
                          'c.ChampionName, ' .
                          'c.ChampionKey, ' .
                          'i.ItemName, ' .
                          'i.ItemImage, ' .
                          'ItemDescription, ' .
                          'min(ibl.TimeStamp) as MinimumPurchaseTime ' .
                  'from ItemBuildLog as ibl ' .

                  'inner join MatchPlayerSetting as mps ' .
                  'on ibl.MatchId = mps.MatchId ' .
                  'and ibl.BuyerId = mps.ParticipantId ' .

                  'inner join Champion as c ' .
                  'on c. ChampionId = mps.ChampionId ' .

                  'inner join Item as i on ibl.ItemId = i.ItemId ' .

                  'group by ibl.MatchId, ' .
                            'ibl.BuyerId, ' .
                             'mps.Championid, ' .
                             'c.ChampionName, ' .
                             'c.ChampionKey, ' .
                             'i.ItemName, ' .
                             'i.ItemImage, ' .
                             'ItemDescription' .
                  ') as tmp ' .
                  'group by tmp.ChampionName, ' .
                            'tmp.ChampionKey, ' .
                            'tmp.ItemName, ' .
                            'tmp.ItemImage, ' .
                            'tmp.ItemDescription ' .
                  'order by tmp.ChampionName, '.
                            'truncate(avg(tmp.MinimumPurchaseTime) / 1000, 0)');
}

function getItemTableTag($statistics){
  $tmpStr = "<table border='1'>";
  $tmpStr .= "<tr>";
  $tmpStr .= "<th>ChampionName</th>";
  $tmpStr .= "<th>ItemName</th>";
  //$tmpStr .= "<th>ItemDescription</th>";
  $tmpStr .= "<th>AvgMinPurchaseTime</th>";
  $tmpStr .= "<th>Number</th>";
  $tmpStr .= "</tr>";

  foreach($statistics as $info){
    $tmpTime = $info->AvgMinPurchaseTime;

    $tmpStr .= "<tr>";

    // will convert html string to blade template
    $tmpStr .= "<td>" . "<img src='images/champion/" . $info->ChampionKey . ".png' />" . $info->ChampionName . "</td>";
    $tmpStr .= "<td>" . "<img src='images/item/" . $info->ItemImage . "' />" . $info->ItemName . "</td>";
    $tmpStr .= "<td>" . floor($tmpTime / 60) . "min " . ($tmpTime % 60) . "sec"  . "</td>";
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
    <?php 
      echo getItemTableTag($_SESSION['item']);
    ?>
  </div>
</body>
</html>