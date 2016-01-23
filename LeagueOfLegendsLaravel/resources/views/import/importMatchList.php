<?php
//ini_set("display_errors", on);
define("MAX_IMPORT_AMOUNT", "50");
define("SLEEP_MICRO_SEC", "500");

try {
  $summonerCnt = DB::table('Summoner')->count();
  $targetTimeStamp = DB::table('MatchList')->max('TimeStamp');

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
  $summoners = DB::table('Summoner')
              ->select('SummonerId')
              ->get();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "couldn't get API Key.<br>";
  die();
}

$baseUrl = 'https://na.api.pvp.net/api/lol/na/v1.3/game/by-summoner/' .
            '[SummonerId]/recent?api_key=[APIKey]';

$baseUrl = str_replace('[APIKey]', $apiKey->myKey, $baseUrl);

$insertDataArr = null;

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

  if(!array_key_exists("games", $json)){
    continue;
  }

  foreach($json['games'] as $game){

    usleep(SLEEP_MICRO_SEC);

    // a summoner didn't play recently.
    if($targetTimeStamp > $game["createDate"]){
      break;
    }

    // Only ranked games are targeted.
    if($game["subType"] === "RANKED_SOLO_5x5" ||
        $game["subType"] === "RANKED_PREMADE_5x5" ||
        $game["subType"] === "RANKED_TEAM_5x5"){

       $insertDataArr[] = "(" . $game["gameId"] . "," .
                                "'na'" . ",'" .
                                mb_strtolower($game["subType"]) . "'," .
                                $game["createDate"] . ")";
    }
  }
}

if(isset($insertDataArr)){
  try{
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