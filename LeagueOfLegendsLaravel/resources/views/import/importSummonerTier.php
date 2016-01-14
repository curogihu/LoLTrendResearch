<?php
/*
ini_set("display_errors", 1);
ini_set('display_errors', 'On');
*/
$summonerIdArr = null;

try{
  DB::table('SummonerDetailTier')->delete();
  $apiKey = DB::table('APIKey')->select('myKey')->first();

  $summonerIdResults = DB::table('MatchPlayerInfo')
                          ->select('SummonerId', 'SummonerName')
                          ->distinct()
                          ->whereNull('Tier')
                          ->get();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "couldn't get API Key.<br>";
  die();
}

if(count($summonerIdResults) === 0){
  echo "all summoner tier had aleady seted.";
  exit(0);
}

foreach($summonerIdResults as $info){
  $summonerInfoArr[$info->SummonerId] = $info->SummonerName;
  $summonerIdArr[] = $info->SummonerId;
}

$cnt = count($summonerIdArr);

$tmpArr = null;
$targetArr = null;
$insertArr = null;

//echo "cnt = " . $cnt . "<br>";

for($i = 0; $i * 10 < $cnt; $i++){

  // setting maximum 10 values with comma[,]
  for($j = 0; $j < 10 && $i * 10 + $j < $cnt; $j++){
    $tmpArr[] = $summonerIdArr[$i * 10 + $j];
  }

  $url = 'https://na.api.pvp.net/api/lol/na/v2.5/league/by-summoner/' .
          implode(",", $tmpArr) . '?api_key=' . $apiKey->myKey;

  $resource = file_get_contents($url);

  preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
  $status_code = $matches[1];

  switch($status_code){
    // no trobule
    case '200':
      break;

    // retry file_get_contents
    case '429':
      $nessearyStopSec = 0;
      $rateLimitType = "";

      foreach($http_response_header as $value){
        if(!strstr($value, RATE_LIMIT_TYPE)){
          $rateLimitType = trim(substr($value, strlen(RATE_LIMIT_TYPE) + 1));
        }

        if(!strstr($value, RETRY_AFTER)){
          $rateLimitType = intval(trim(substr($value, strlen(RETRY_AFTER) + 1)));
        }
      }

      if($rateLimitType === "" && $nessearyStopSec === 0){
        sleep(3);
      }else{
        sleep($nessearyStopSec + 1);
      }

      $resource = file_get_contents($url, false, $context);

      break;

    // skip
    default:
      continue;
  }

  if($resource === false){
    continue;
  }

  $json = json_decode($resource, true);

  foreach ($json as $summonerId => $summmonerDetailArr) {
    foreach($summmonerDetailArr as $summonerDetail){
      //echo "tier: " . $summonerDetail["tier"] . "<br>";

      foreach ($summonerDetail['entries'] as $playerInfo) {
        //sleep(1);
          //echo "p = " . $playerInfo["playerOrTeamName"] . " s = " . $summonerInfoArr[$summonerId] . "<br>";
        if($playerInfo["playerOrTeamName"] === $summonerInfoArr[$summonerId]){
          $insertArr[] = '("na",' .
                          $summonerId . ',"' .
                          $playerInfo["playerOrTeamName"] . '","' .
                          $summonerDetail["tier"] . '","' .
                          $playerInfo["division"] . '")';
        }
      }
    }
  }

  try{
    DB::insert('insert ignore into SummonerDetailTier (RegionId, SummonerId, SummonerName, Tier, Division) values ' . implode(",", $insertArr));


  }catch(Exception $e){
    echo "Error Message: " . $e . "<br>";
    echo "objective data is the following:<br>";
    echo var_dump($insertArr);
  }

  $insertArr = null;
  $tmpArr = null;
}

try{
  DB::update('UPDATE MatchPlayerInfo as mpi, SummonerDetailTier as sdt ' .
              'SET mpi.Tier = sdt.Tier, mpi.Division=sdt.Division ' .
              'WHERE mpi.Tier is null and ' .
                    'mpi.Division is null and ' .
                    'mpi.SummonerId = sdt.SummonerId and ' .
                    'mpi.SummonerName = sdt.SummonerName');
  // forgot its existance
  DB::commit();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "objective data is the following:<br>";
  echo var_dump($insertArr);
}

//echo "<br>finished";
date_default_timezone_set('Asia/Tokyo');
Log::info('Finishing importing SummonerTier, date: ' . date("F j, Y, g:i a"));
