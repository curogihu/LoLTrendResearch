<?php
//ini_set("display_errors", on);
define("MAX_IMPORT_AMOUNT", "50");
define("SLEEP_MICRO_SEC", "500");

//error_reporting(E_ALL);
try {
  $summonerCnt = DB::table('Summoner')->count();
  $targetTimeStamp = DB::table('MatchList')->max('TimeStamp');

//  echo "timeStamp = " . $targetTimeStamp;

  // delete unnecessary summoner records
  if($summonerCnt > MAX_IMPORT_AMOUNT){
    $necessaryDeleteCnt = $summonerCnt - MAX_IMPORT_AMOUNT;

    $deleteSummoners = DB::table("Summoner")
                        ->select("SummonerId")
                        ->take($necessaryDeleteCnt)
                        ->orderBy(DB::raw('RAND()'))
                        ->get();

    foreach($deleteSummoners as $deleteSummoner){
      DB::table('Summoner')->where('SummonerId', '=', $deleteSummoner->SummonerId)->delete();
    }

  }

}catch(Exception $e){
  die("Couldn't get initial data.");
}

try{
  $apiKey = DB::table('APIKey')->select('myKey')->first();
  //$results = $dbh->query('SELECT SummonerId FROM Summoner ORDER BY RAND() LIMIT 0, ' . MAX_IMPORT_AMOUNT);
  $summoners = DB::table('Summoner')
              ->select('SummonerId')
  //            ->orderBy(DB::raw('RAND()'))
  //            ->take(MAX_IMPORT_AMOUNT)
              ->get();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "couldn't get API Key.<br>";
  die();
}

/*
$baseUrl = 'https://na.api.pvp.net/api/lol/na/v2.2/matchlist/by-summoner/' .
            '[SummonerId]?rankedQueues=RANKED_SOLO_5x5,RANKED_TEAM_5x5&api_key=[APIKey]';
*/

$baseUrl = 'https://na.api.pvp.net/api/lol/na/v1.3/game/by-summoner/' .
            '[SummonerId]/recent?api_key=[APIKey]';

$baseUrl = str_replace('[APIKey]', $apiKey->myKey, $baseUrl);

//$baseYear = '2016';
$insertDataArr = null;
//$matchArr = array();      // this array is used for duplication check.

foreach ($summoners as $summoner) {

  $url = str_replace('[SummonerId]', $summoner->SummonerId, $baseUrl);

  usleep(SLEEP_MICRO_SEC);

  $responseHeaders = apache_response_headers();

  if(array_key_exists("Retry-After", $responseHeaders)){
    echo "test" . "<br>";
    $addSleepSec = $responseHeaders["Retry-After"];

    sleep($addSleepSec + 3);
  }

  if(($resource = file_get_contents($url)) === FALSE){
    echo "url = ". $url;
    break;
  }

  $json = json_decode($resource, true);
/*
  $jsonFp = fopen('tmp.json', 'w');
  flock($jsonFp, LOCK_SH);
  fwrite($jsonFp, $resource);
  fclose($jsonFp);
*/

  if(!array_key_exists("games", $json)){
    continue;
  }

  foreach($json['games'] as $game){
    //echo $game["subType"] . "<br><br>";

    usleep(SLEEP_MICRO_SEC);

    // a summoner didn't play recently.
    if($targetTimeStamp > $game["createDate"]){
      break;
    }

    // Only ranked games are targeted.
    if($game["subType"] === "RANKED_SOLO_5x5" ||
        $game["subType"] === "RANKED_PREMADE_5x5" ||
        $game["subType"] === "RANKED_TEAM_5x5"){
/*
       $insertDataArr[] = array('MatchId' => $game["gameId"],
                        'RegionId' => 'na',
                        'Queue' => mb_strtolower($game["subType"]),
                        'TimeStamp' => $game["createDate"]);
                        */
       $insertDataArr[] = "(" . $game["gameId"] . "," .
                                "'na'" . ",'" .
                                mb_strtolower($game["subType"]) . "'," .
                                $game["createDate"] . ")";
    }
  }
/*
  if(array_key_exists("matches", $json)){

    // Did each player play rank match?
    foreach($json['matches'] as $matchInfo){
      sleep(5);

      if($targetTimeStamp > $matchInfo["timestamp"]){
        break;
      }

      if(strstr($matchInfo["season"], $baseYear) &&
          !in_array($matchInfo["matchId"], $matchArr)){

        $matchArr[] = $matchInfo["matchId"];
        $tmpArr[] = array('MatchId' => $matchInfo["matchId"],
                                  'RegionId' => 'na',
                                  'Queue' => mb_strtolower($matchInfo["queue"]),
                                  'Season' => mb_strtolower($matchInfo["season"]),
                                  'PlatformId' => mb_strtolower($matchInfo["platformId"]),
                                  'TimeStamp' => $matchInfo["timestamp"]);
      }
    }
  }
  */
}

if(isset($insertDataArr)){
  try{
   // DB::table('MatchList')->insert($insertDataArr);
    //echo "insert ignore into MatchList values " . implode(",", $insertDataArr);
    DB::insert("insert ignore into MatchList values " . implode(",", $insertDataArr));

  }catch(Exception $e){
    echo "Error Message: " . $e . "<br>";
    echo "objective data is the following:<br>";
    echo var_dump($insertDataArr);
    die();
  }
}

//echo "finished";
date_default_timezone_set('Asia/Tokyo');
Log::info('Finishing importing MatchList, date: ' . date("F j, Y, g:i a"));