<?php
//ini_set("display_errors", on);
define("MAX_IMPORT_AMOUNT", "10");

//error_reporting(E_ALL);
try {
  $summonerCnt = DB::table('Summoner')->count();
  $targetTimeStamp = DB::table('MatchList')->max('TimeStamp');

  // delete unnecessary summoner records
  if($summonerCnt > 50){
    $necessaryDeleteCnt = $summonerCnt - 50;

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
              ->orderBy(DB::raw('RAND()'))
              ->take(MAX_IMPORT_AMOUNT)
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

$baseYear = '2016';
$insertDataArr = array();
$matchArr = array();      // this array is used for duplication check.

foreach ($summoners as $summoner) {

  $url = str_replace('[SummonerId]', $summoner->SummonerId, $baseUrl);

  sleep(5);

  if(($resource = file_get_contents($url)) === FALSE){
    echo "url = ". $url;
    break;
  }

  $json = json_decode($resource, true);

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
}

try{
  DB::table('MatchList')->insert($insertDataArr);

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "objective data is the following:<br>";
  echo var_dump($insertDataArr);
  die();
}

echo "finished";