<?php

// ja_JP or en_US
$languageId = "ja_JP";

try{
  $apiKey = DB::table('APIKey')->select('myKey')->first();

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "couldn't get API Key.<br>";
  die();
}


// English version
$url = 'https://global.api.pvp.net/api/lol/static-data/na/v1.2/' .
        'item?locale=' . $languageId  . '&itemListData=all&api_key='. $apiKey->myKey;

// Japanese version
/*
$url = 'https://global.api.pvp.net/api/lol/static-data/na/v1.2/' .
        'item?locale=ja_JP&itemListData=all&api_key='. $apiKey->myKey;
*/

if(($resource = file_get_contents($url)) === FALSE){
  echo "url = ". $url;
  exit(-1);
}

$json = json_decode($resource, true);

foreach($json["data"] as $info){
  $itemId = $info["id"];

  $insertItemArr[] = array('LanguageId' => $languageId,
                            'ItemId' => $itemId,
                            'ItemName' => $info["name"],
                            'ItemDescription' => $info["description"],
                            'ItemImage' => $info["image"]["full"],
                            'ItemGoldBase' => $info["gold"]["base"],
                            'ItemGoldTotal' => $info["gold"]["total"],
                            'ItemGoldSell' => $info["gold"]["sell"]);

  if(array_key_exists("into", $info)){

    foreach($info["into"] as $derivationItemId){
      $insertItemDerivationArr[] = array('ItemId' => $itemId,
                                          'ItemDerivationId' => $derivationItemId);
    }
  }

  if(array_key_exists("tags", $info)){

    foreach($info["tags"] as $itemTag){
      $insertItemTagArr[] = array('ItemId' => $itemId,
                                  'TagName' => $itemTag);
    }
  }

}

try{
  DB::table('Item')->insert($insertItemArr);
  DB::table('ItemDerivation')->insert($insertItemDerivationArr);
  DB::table('ItemTag')->insert($insertItemTagArr);

}catch(Exception $e){
  echo "Error Message: " . $e . "<br>";
  echo "objective data is the following:<br>";
  echo var_dump($insertItemTagArr);
  die();
}

echo "finish.";
