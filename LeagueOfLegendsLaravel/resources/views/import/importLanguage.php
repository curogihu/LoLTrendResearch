<?php

try{
  $apiKey = DB::table('APIKey')->select('myKey')->first();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "couldn't get API Key.<br>";
  die();
}

$url = 'https://global.api.pvp.net/api/lol/static-data/na/v1.2/' .
            'languages?api_key='. $apiKey->myKey;

if(($resource = file_get_contents($url)) === FALSE){
  echo "url = ". $url;
  exit(-1);
}

$json = json_decode($resource, true);

foreach($json as $info){
  $insertDataArr[] = array('LanaguageId' => $info);
}

try{
  DB::table('Language')->insert($insertDataArr);

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "objective data is the following:<br>";
  echo var_dump($insertDataArr);
  die();
}

echo "finished";