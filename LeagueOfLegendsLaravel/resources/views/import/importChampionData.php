<?php
ini_set('display_errors', 'On');
$dsn = 'mysql:dbname=LoLResearch;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{
    $dbh = new PDO($dsn, $user, $password, $options);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "connected<br>";
}catch (PDOException $e){
    echo $e->getMessage();
    die();
}

try{
  $apiResults = $dbh->query('SELECT myKey FROM APIKey');
  $apiKeyArr = $apiResults->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
  echo $e->getMessage();
  die();
}

//In near future
//https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?locale=en_US&champData=tags&api_key=3f9dba7e-2190-4e51-ae0c-9171cf593500
$url = 'https://global.api.pvp.net/api/lol/static-data/' .
        'na/v1.2/champion?api_key=' . $apiKeyArr[0]["myKey"];

/*
$url = 'https://global.api.pvp.net/api/lol/static-data/' .
        'na/v1.2/champion?locale=ja_JP&api_key=' . $apiKeyArr[0]["myKey"];
*/
if(($resource = file_get_contents($url)) === FALSE){
  echo "url = ". $url;
  exit;
}

$insertSql = "insert ignore into Champion (LocalCode, ChampionId, ChampionKey, ChampionName) values ";
$insertChampionArr = null;

$json = json_decode($resource, true);

foreach($json["data"] as $info){
//  echo var_dump($info) . "<br>";
  $insertChampionArr[] = '("en_US",' . $info['id'] . ',"' . $info['key'] . '","' . $info['name'] . '")';
}

try{
  $insertSql .= implode(",", $insertChampionArr);

  //echo "sql = " . $insertSql;
  //$stmt = $dbh->query("set names utf8");
  $stmt = $dbh->prepare($insertSql);
  $stmt->execute();

}catch(PDOException $e){
  print('PDO Error: '. $e->getMessage() . "<br>");
  print('----------------------');
  print($insertSql . "<br>");
  print('----------------------');
}

echo "finished.";
