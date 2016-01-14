<?php
define("MAX_IMPORT_AMOUNT", "500");
define("SLEEP_MICRO_SEC", "500");

define("RATE_LIMIT_TYPE", "X-Rate-Limit-Type:");
define("RETRY_AFTER", "Retry-After:");
/*
$fp = fopen('tmp.txt', 'w+');
flock($fp, LOCK_SH);
*/

$context = stream_context_create(array(
    'http' => array('ignore_errors' => true)
));

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
    /*
    $addSleepSec = $responseHeaders["Retry-After"];
    sleep($addSleepSec + 3);
    */
    break;
  }

  $matchId = $info->MatchId;
  $regionId = $info->RegionId;

  $url = getMatchDetailUrl($baseUrl, $matchId, $apiKey->myKey);
  $resource = file_get_contents($url, false, $context);

/*
  foreach($http_response_header as $key => $value){
    fwrite($fp, $key . ":" . $value . "\n");
  }

  fwrite($fp, "\n\n");
*/
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

  if($resource === FALSE){
    continue;
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
/*
    DB::table('MatchPlayerSetting')->insert($insertMatchPlayerSetting);
    DB::table('MatchPlayerInfo')->insert($insertMatchPlayerInfo);
    DB::table('ItemBuildLog')->insert($insertItemBuildLog);

    // more detail data
    DB::table('EliteMonsterKillLog')->insert($insertEliteMonsterKillLog);
    DB::table('WardPlacedLog')->insert($insertWardPlacedLog);
    DB::table('WardKillLog')->insert($insertWardKillLog);
*/
    //DB::table('Summoner')->insert($insertSummoner);
  //  $tmpStr = implode(",", $insertSummoner);

   // echo $tmpStr;
  DB::insert('insert ignore into MatchPlayerSetting (' .
                                  'MatchId,' .
                                  'ParticipantId,' .
                                  'ChampionId,' .
                                  'Role,'.
                                  'Lane,'.
                                  'Spell1Id,'.
                                  'Spell2Id,'.
                                  'MinionsKilled) values ' . implode(",", $insertMatchPlayerSetting));

  DB::insert('insert ignore into MatchPlayerInfo (' .
                                  'MatchId, ' .
                                  'ParticipantId, ' .
                                  'SummonerId, ' .
                                  'SummonerNameKey, ' .
                                  'SummonerName) values ' . implode(",", $insertMatchPlayerInfo));

  if(!empty($insertItemBuildLog)){
    DB::insert('insert ignore into ItemBuildLog(MatchId, BuyerId, ItemId, TimeStamp) values ' . implode(",", $insertItemBuildLog));
  }

  if(!empty($insertEliteMonsterKillLog)){
    DB::insert('insert ignore into EliteMonsterKillLog values ' . implode(",", $insertEliteMonsterKillLog));
  }

  if(!empty($insertWardPlacedLog)){
    DB::insert('insert ignore into WardPlacedLog values ' . implode(",", $insertWardPlacedLog));
  }

  if(!empty($insertWardKillLog)){
    DB::insert('insert ignore into WardKillLog values ' . implode(",", $insertWardKillLog));
  }

  if(!empty($insertSummoner)){
    DB::insert('insert ignore into Summoner values ' . implode(",", $insertSummoner));
  }


 }catch(Exception $e){
    echo "Error Message: " . $e . "<br>";
    echo "objective data is the following:<br>";

    die();
  }

  $insertMatchPlayerSetting = null;
  $insertMatchPlayerInfo = null;
  $insertItemBuildLog = null;
  $insertEliteMonsterKillLog = null;
  $insertWardPlacedLog = null;
  $insertWardKillLog = null;
  $insertSummoner = null;
}

try{
  // create temporary table for displaying whenbuy page
  DB::table('ItemBuildLogSummary')->delete();

  // set championid
  DB::update('UPDATE ItemBuildLog as ibl, MatchPlayerSetting as mps ' .
              'set ibl.ChampionId = mps.ChampionId ' .
              'WHERE ibl.ChampionId is null and ' .
                    'ibl.MatchId = mps.MatchId and ' .
                    'ibl.BuyerId = mps.ParticipantId');

    // forgot its existance
  DB::commit();

  db::insert('insert into ItemBuildLogSummary ' .
              'select distinct tmp.ChampionId, ' .
                      'tmp.ItemId, ' .
                      'truncate(avg(MinimumPurchaseTime) / 1000, 0) AvgMinPurchaseSeconds, ' .
                      'count(tmp.ItemId) as Number ' .
              'from( ' .
                'select ibl.ChampionId, ibl.ItemId, min(TimeStamp) MinimumPurchaseTime ' .
                'from ItemBuildLog ibl ' .
                'group by ibl.MatchId, ibl.ChampionId, ibl.ItemId ' .
              ') tmp ' .
              'group by tmp.ChampionId, tmp.ItemId');

}catch(Exception $e){
  echo $e->getMessage();
  die();
}

//fclose($fp);
//echo "finished.";
date_default_timezone_set('Asia/Tokyo');
Log::info('Finishing importing MatchDetail, date: ' . date("F j, Y, g:i a"));

function getMatchDetailUrl($baseUrl, $matchId, $apiKey){
  $tmpUrl = str_replace("[MatchId]", $matchId, $baseUrl);
  return str_replace("[APIKey]", $apiKey, $tmpUrl);
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

  try{
    foreach($json["participants"] as $info){
      $tmpArr[] = "(" . $matchId . "," .
                        $info["participantId"] . "," .
                        $info["championId"] . ",'" .
                        $info["timeline"]["role"] . "','" .
                        $info["timeline"]["lane"] . "'," .
                        $info["spell1Id"] . "," .
                        $info["spell2Id"] . "," .
                        $info["stats"]["minionsKilled"] . ")";
    }

  }catch(Exception $e){
    echo "Unexpected error occurs. <br>";
    echo "-----------------------------<br>";
    echo var_dump($json);
    echo "<br>-----------------------------<br>";

    die();
  }

  return $tmpArr;
}

function getMatchPlayerInfo($json, $matchId){
  $tmpArr = null;

  // for recursive in order to research
  foreach($json["participantIdentities"] as $info){
    $tmpName = $info["player"]["summonerName"];
/*
    $tmpArr[] = array('MatchId' => $matchId,
                      'ParticipantId' => $info["participantId"],
                      'SummonerId' => $info["player"]["summonerId"],
                      'SummonerNameKey' => str_replace(' ', '', mb_strtolower($tmpName)),
                      'SummonerName' => $tmpName);
                      */
    $tmpArr[] = "(" . $matchId . "," .
                      $info["participantId"] . "," .
                      $info["player"]["summonerId"] . ",'" .
                      str_replace(' ', '', mb_strtolower($tmpName)) . "','" .
                      $tmpName . "')";
  }

  return $tmpArr;
}

function getItemBuildLog($json, $matchId){
  $tmpArr = null;

  foreach ($json["timeline"]["frames"] as $info) {
    if(array_key_exists("events", $info)){
      foreach($info["events"] as $infoDetail){
        if($infoDetail["eventType"] === "ITEM_PURCHASED"){
          $tmpArr[] = "(" . $matchId . "," .
                            $infoDetail["participantId"] . "," .
                            $infoDetail["itemId"] . "," .
                            $infoDetail["timestamp"] . ")";
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
          /*
          $tmpArr[] = array('MatchId' => $matchId,
                            'KillerId' => $infoDetail["killerId"],
                            'MonsterType' => $infoDetail["monsterType"],
                            'TimeStamp' => $infoDetail["timestamp"]);
*/
          $tmpArr[] = "(" . $matchId . "," .
                    $infoDetail["killerId"] . ",'" .
                    $infoDetail["monsterType"] . "'," .
                    $infoDetail["timestamp"] . ")";
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
/*
          $tmpArr[] = array('MatchId' => $matchId,
                            'CreatorId' => $infoDetail["creatorId"],
                            'WardType' => $infoDetail["wardType"],
                            'TimeStamp' => $infoDetail["timestamp"]);
                            */
          $tmpArr[] = "(" . $matchId . "," .
                    $infoDetail["creatorId"] . ",'" .
                    $infoDetail["wardType"] . "'," .
                    $infoDetail["timestamp"] . ")";
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
          /*
          $tmpArr[] = array('MatchId' => $matchId,
                            'KillerId' => $infoDetail["killerId"],
                            'WardType' => $infoDetail["wardType"],
                            'TimeStamp' => $infoDetail["timestamp"]);
                            */
          $tmpArr[] = "(" . $matchId . "," .
                    $infoDetail["killerId"] . ",'" .
                    $infoDetail["wardType"] . "'," .
                    $infoDetail["timestamp"] . ")";
        }
      }
    }
  }

  return $tmpArr;
}