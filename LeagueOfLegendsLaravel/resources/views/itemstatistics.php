<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

//ini_set('display_errors', 'On');

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

if(!isset($_SESSION['item'])){
    $_SESSION['item'] =
      DB::select('select c.ChampionName, ' .
                          'c.ChampionKey, ' .
                          'i.ItemName, ' .
                          'i.ItemImage, ' .
                          'i.ItemDescription, ' .
                          'ibls.AvgMinPurchaseSeconds, ' .
                          'ibls.NumberOfTimes, ' .
                          'sum(case when id.ItemDerivationId is null then 0 else 1 end) DerivationNum ' .
                  'from ItemBuildLogSummary ibls ' .
                  'inner join Champion c ' .
                    'on ibls.ChampionId = c.ChampionId ' .
                  'inner join Item i ' .
                    'on ibls.ItemId = i.ItemId ' .
                  'left join ItemDerivation id ' .
                    'on ibls.ItemId = id.ItemId ' .
                    'group by c.ChampionName, ' .
                              'c.ChampionKey, ' .
                              'i.ItemName, ' .
                              'i.ItemImage, ' .
                              'i.ItemDescription, ' .
                              'ibls.AvgMinPurchaseSeconds, ' .
                              'ibls.NumberOfTimes ' .
                  'order by c.ChampionKey, ibls.AvgMinPurchaseSeconds');

}

function getItemTableTag($statistics){

  $tmpStr = "";
  $displayChampion = "";
  $displayNum = 0;

  foreach($statistics as $info){
    $tmpTime = $info->AvgMinPurchaseSeconds;

    if($info->ChampionName !== $displayChampion){

      if($displayNum > 0){
          $tmpStr .= "</table>";
      }

      $displayNum = $displayNum + 1;
      $displayChampion = $info->ChampionName;

      $tmpStr .= "<p><img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/" . $info->ChampionKey . ".png' />" . $info->ChampionName . "</p>";
      $tmpStr .= "<table border='1' align='center'>";
      $tmpStr .= "<tr>";
      $tmpStr .= "<th>image</th>";
      $tmpStr .= "<th>ItemName</th>";
      $tmpStr .= "<th>AvgMinPurchaseTime</th>";
      $tmpStr .= "<th>Frequent</th>";
      $tmpStr .= "<th>Derivation</th>";
      $tmpStr .= "</tr>";
    }

    $tmpStr .= getItemLogRecord($info);
  }

  $tmpStr .= "</table>";

  return $tmpStr;

}

function getItemLogRecord($info){
  $tmpStr = "";

  $tmpStr .= "<tr>";
  $tmpStr .= "<td><img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/item/" . $info->ItemImage . "' /></td>";
  $tmpStr .= "<td>" . $info->ItemName . "</td>";
  $tmpStr .= "<td>" . floor($tmpTime / 60) . "min " . ($tmpTime % 60) . "sec"  . "</td>";
  $tmpStr .= "<td>" . $info->NumberOfTimes . "</td>";

  echo $info->DerivationNum;

  if($info->DerivationNum === 0){
    $tmpStr .= "<td>-</td>";
  }else{
    $tmpStr .= "<td>○</td>";
  }
  $tmpStr .= "</tr>";

  return $tmpStr;
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
      <h1><a href='http://loltrendresearch.xyz'> LoL Trend Research</a></h1>
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
          echo getItemTableTag($_SESSION['item']);
        ?>
      </div>
    </div>

    <div id="right"></div>

    <div id="footer" class="middleContentItem"><p>&copy; 2015 LoLTrendResearch</p></div>
  </div>
</body>
</html>