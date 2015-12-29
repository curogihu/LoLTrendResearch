<?php
define("MAX_IMPORT_AMOUNT", "500");
define("SLEEP_MICRO_SEC", "500");

try{
  $matchIdArr = DB::table('MatchList')
                    ->select('MatchList.RegionId', 'MatchList.MatchId')
                    ->leftjoin('MatchPlayerSetting',
                                'MatchList.MatchId',
                                '=',
                                'MatchPlayerSetting.MatchId')
                    ->whereNull('MatchPlayerSetting.MatchId')
//                    ->orderBy(DB::raw('RAND()'))
                    ->orderBy('MatchList.TimeStamp')
                    ->distinct()
                    ->take(MAX_IMPORT_AMOUNT)
                    ->get();

  $apiKey = DB::table('APIKey')->select('myKey')->first();

}catch(Exception $e){
  echo $e->getMessage();
  die();
}

$cnt = 0;
$baseUrl = 'https://na.api.pvp.net/api/lol/'.
            'na/v2.2/match/'.
            '[MatchId]?includeTimeline=true&' .
            'api_key=[APIKey]';

foreach($matchIdArr as $info){
  usleep(SLEEP_MICRO_SEC);

  $responseHeaders = apache_response_headers();

  if(array_key_exists("Retry-After", $responseHeaders)){
    $addSleepSec = $responseHeaders["Retry-After"];

    sleep($addSleepSec + 3);
  }

  $matchId = $info->MatchId;
  $regionId = $info->RegionId;

  $url = getMatchDetailUrl($baseUrl, $matchId, $apiKey->myKey);

  if(($resource = file_get_contents($url)) === FALSE){
    echo "url = ". $url;
    break;
  }

  //$json = json_decode(file_get_contents($url), true);
  $json = json_decode($resource, true);

  usleep(SLEEP_MICRO_SEC);
  $insertMatchPlayerSetting = getMatchPlayerSetting($json, $matchId);

  usleep(SLEEP_MICRO_SEC);
  $insertMatchPlayerInfo = getMatchPlayerInfo($json, $matchId);

  usleep(SLEEP_MICRO_SEC);
  $insertItemBuildLog = getItemBuildLog($json, $matchId);

  usleep(SLEEP_MICRO_SEC);
  $insertEliteMonsterKillLog =  getEliteMonsterKillLog($json, $matchId);

  usleep(SLEEP_MICRO_SEC);
  $insertWardPlacedLog = getWardPlacedLog($json, $matchId);

  usleep(SLEEP_MICRO_SEC);
  $insertWardKillLog = getWardKillLog($json, $matchId);

  usleep(SLEEP_MICRO_SEC);
  $insertSummoner = getSummoner($json, $matchId);

  try{
    // match data

    DB::table('MatchPlayerSetting')->insert($insertMatchPlayerSetting);
    DB::table('MatchPlayerInfo')->insert($insertMatchPlayerInfo);
    DB::table('ItemBuildLog')->insert($insertItemBuildLog);

    // more detail data
    DB::table('EliteMonsterKillLog')->insert($insertEliteMonsterKillLog);
    DB::table('WardPlacedLog')->insert($insertWardPlacedLog);
    DB::table('WardKillLog')->insert($insertWardKillLog);

    //DB::table('Summoner')->insert($insertSummoner);
  //  $tmpStr = implode(",", $insertSummoner);

   // echo $tmpStr;
    DB::insert('insert ignore into Summoner (RegionId, SummonerId, SummonerNameKey, SummonerName) values ' .
                implode(",", $insertSummoner));

  }catch(Exception $e){
    echo "Error Message: " . $e . "<br>";
    //echo "objective data is the following:<br>";
    // var_dump($insertDataArr);
    //die();
  }

  $insertMatchPlayerSetting = null;
  $insertMatchPlayerInfo = null;
  $insertItemBuildLog = null;
  $insertEliteMonsterKillLog = null;
  $insertWardPlacedLog = null;
  $insertWardKillLog = null;
  $insertSummoner = null;

  $cnt++;
/*
  if($cnt > MAX_IMPORT_AMOUNT){
    break;
  }
  */
}

echo "cnt = " . $cnt;
echo "finished.";

function getMatchDetailUrl($baseUrl, $matchId, $apiKey){
  $tmpUrl = str_replace("[MatchId]", $matchId, $baseUrl);
  return str_replace("[APIKey]", $apiKey, $tmpUrl);
}


function insertDataToTable($dbh, $baseSql, $insertDataArr){
  try{
    $insertSql = $baseSql . implode(",", $insertDataArr);
    $insertSql = str_replace(',,', ',', $insertSql);  // in case of not happen events

//    echo $insertSql . "<br>";

    $stmt = $dbh->prepare($insertSql);
    $stmt->execute();

  }catch(PDOException $e){
    print('PDO Error: '. $e->getMessage() . "<br>");
    print('----------------------');
    print($insertSql . "<br>");
    print('----------------------');
  }
}

function getSummoner($json, $matchId){
  $tmpArr = null;

  // for recursive in order to research
  foreach($json["participantIdentities"] as $info){
    $tmpName = $info["player"]["summonerName"];
/*
    $tmpArr[] = array('RegionId' => 'na',
                      'SummonerId' => $info["player"]["summonerId"],
                      'SummonerNameKey' => str_replace(' ', '', mb_strtolower($tmpName)),
                      'SummonerName' => $tmpName);
*/
   $tmpArr[] = '("na",' .
                $info["player"]["summonerId"] . ',"' .
                str_replace(' ', '', mb_strtolower($tmpName)) . '","' .
                $tmpName . '")';
  }

  //echo var_dump($tmpArr);

  return $tmpArr;
}

function getMatchPlayerSetting($json, $matchId){

  $tmpArr = null;

  foreach($json["participants"] as $info){
    $tmpArr[] = array('MatchId' => $matchId,
                      'ParticipantId' => $info["participantId"],
                      'ChampionId' => $info["championId"],
                      'Role' => $info["timeline"]["role"],
                      'Lane' => $info["timeline"]["lane"],
                      'Spell1Id' => $info["spell1Id"],
                      'Spell2Id' => $info["spell2Id"],
                      'MinionsKilled' => $info["stats"]["minionsKilled"]);
  }

  return $tmpArr;
}

function getMatchPlayerInfo($json, $matchId){
  $tmpArr = null;

  // for recursive in order to research
  foreach($json["participantIdentities"] as $info){
    $tmpName = $info["player"]["summonerName"];

    $tmpArr[] = array('MatchId' => $matchId,
                      'ParticipantId' => $info["participantId"],
                      'SummonerId' => $info["player"]["summonerId"],
                      'SummonerNameKey' => str_replace(' ', '', mb_strtolower($tmpName)),
                      'SummonerName' => $tmpName);
  }

  return $tmpArr;
}

function getItemBuildLog($json, $matchId){
  $tmpArr = null;

  foreach ($json["timeline"]["frames"] as $info) {
    if(array_key_exists("events", $info)){
      foreach($info["events"] as $infoDetail){
        if($infoDetail["eventType"] === "ITEM_PURCHASED"){
          $tmpArr[] = array('MatchId' => $matchId,
                            'BuyerId' => $infoDetail["participantId"],
                            'ItemId' => $infoDetail["itemId"],
                            'TimeStamp' => $infoDetail["timestamp"]);
        }
      }
    }
  }

  return $tmpArr;
}

function getEliteMonsterKillLog($json, $matchId){
  $tmpArr = null;

  foreach ($json["timeline"]["frames"] as $info) {
    if(array_key_exists("events", $info)){
      foreach($info["events"] as $infoDetail){
        if($infoDetail["eventType"] === "ELITE_MONSTER_KILL" &&
            ($infoDetail["monsterType"] !== "BLUE_GOLEM" ||
              $infoDetail["monsterType"] !== "RED_LIZARD")){
          $tmpArr[] = array('MatchId' => $matchId,
                            'KillerId' => $infoDetail["killerId"],
                            'MonsterType' => $infoDetail["monsterType"],
                            'TimeStamp' => $infoDetail["timestamp"]);
        }
      }
    }
  }

  return $tmpArr;
}

function getWardPlacedLog($json, $matchId){
  $tmpArr = null;

  foreach ($json["timeline"]["frames"] as $info) {
    if(array_key_exists("events", $info)){
      foreach($info["events"] as $infoDetail){
        if($infoDetail["eventType"] === "WARD_PLACED" &&
            ($infoDetail["wardType"] === "SIGHT_WARD" ||
              $infoDetail["wardType"] === "VISION_WARD" ||
              $infoDetail["wardType"] === "YELLOW_TRINKET" ||
              $infoDetail["wardType"] === "YELLOW_TRINKET_UPGRADE")){

          $tmpArr[] = array('MatchId' => $matchId,
                            'CreatorId' => $infoDetail["creatorId"],
                            'WardType' => $infoDetail["wardType"],
                            'TimeStamp' => $infoDetail["timestamp"]);
        }
      }
    }
  }

  return $tmpArr;
}

function getWardKillLog($json, $matchId){
  $tmpArr = null;

  foreach ($json["timeline"]["frames"] as $info) {
    if(array_key_exists("events", $info)){
      foreach($info["events"] as $infoDetail){
        // warning: wardType = UNDEFINED
        if($infoDetail["eventType"] === "WARD_KILL" &&
            ($infoDetail["wardType"] === "VISION_WARD" ||
              ($infoDetail["wardType"] === "YELLOW_TRINKET"))){
          $tmpArr[] = array('MatchId' => $matchId,
                            'KillerId' => $infoDetail["killerId"],
                            'WardType' => $infoDetail["wardType"],
                            'TimeStamp' => $infoDetail["timestamp"]);
        }
      }
    }
  }

  return $tmpArr;
}