<?php
$summonerArr = array('Liquid fabbbyyy');

try{
  $apiKey = DB::table('APIKey')->select('myKey')->first();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "couldn't get API Key.<br>";
  die();
}

$url = 'https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/' .
        convertToQueryStr($summonerArr) .
        '?api_key=' . $apiKey->myKey;

$obj = json_decode(file_get_contents($url), true);

foreach ($obj as $key => $info) {
  //var_dump($obj);
  //sleep(3);
  $insertDataArr[] = Array('RegionId' => 'na',
                          'SummonerId' => $info['id'],
                          'SummonerNameKey' => $key,
                          'SummonerName' => $info['name']);
}

try{
  DB::table('Summoner')->insert($insertDataArr);

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "objective data is the following:<br>";
  echo var_dump($insertDataArr);
  die();
}

//echo "finished";
date_default_timezone_set('Asia/Tokyo');
Log::info('Finishing importing Summoner, date: ' . date("F j, Y, g:i a"));

function convertToQueryStr($arr){
  $output = "";

  foreach ($arr as $value) {
    $output .= str_replace(' ', '', mb_strtolower($value)) . ",";

  }

  return substr($output, 0, strlen($output) - 1);
}