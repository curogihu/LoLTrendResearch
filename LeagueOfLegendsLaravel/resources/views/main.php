<?php
// http://localhost:1025/LoLTrendResearch/phpFolder/LoLLaravel/public/main

ini_set('display_errors', 'On');

/*
$dsn = 'mysql:dbname=LoLResearch;host=localhost;charset=utf8';
$user = 'root';
$password = 'root';

if(!isset($_SESSION['language'])){
  try{
      $dbh = new PDO($dsn, $user, $password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $_SESSION['language'] = $dbh->query('SELECT LanguageId FROM Language');

  }catch (PDOException $e){
      echo $e->getMessage();
      die();
  }
}
*/

if(!isset($_SESSION['language'])){
  $_SESSION['language'] = DB::table('Language')->lists("LanguageId");
}

//$tests = DB::table('Language')->get();

/*
foreach ($tests as $test) {
  // $test["LanguageId"] is NG.
  //echo $test->LanguageId . "<br>";
  echo var_dump($test) . "<br>";
}
*/

/*
no framework version
function getLanguageSelectTag($languages){
  $tmpStr = '<select name="language" id="languages">';

  foreach($languages as $language){
    $tmpStr .= '<option value="' . $language["LanguageId"] . '">' . $language["LanguageId"] . '</option>';
  }

  return $tmpStr . '</select>';
}
*/

// laravel framework version
function getLanguageSelectTag($languages){
  $tmpStr = '<select name="language" id="languages">';

  foreach($languages as $language){
    $tmpStr .= '<option value="' . $language . '">' . $language . '</option>';
  }

  return $tmpStr . '</select>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>LoL Trend Research</title>
</head>
<body>
  <div>
      <h1>LoL Trend Research</h1>
    <?php echo getLanguageSelectTag($_SESSION['language']); ?>
  </div>

  <div>
    <h2>Ward Placed</h2>

    <p>Placed Average: 3</p>
    <table border="1">
      <tr>
        <th>Rank</th>
        <th>Amount per Match</th>
      </tr>

      <tr>
        <td>BRONZE</td>
        <td>1</td>
      </tr>
      <tr>
        <td>SILVER</td>
        <td>2</td>
      </tr>
      <tr>
        <td>GOLD</td>
        <td>3</td>
      </tr>
      <tr>
        <td>PLATINUM</td>
        <td>4</td>
      </tr>
      <tr>
        <td>DIAMOND</td>
        <td>5</td>
      </tr>
      <tr>
        <td>MASTER</td>
        <td>6</td>
      </tr>
      <tr>
        <td>CHALLENGER</td>
        <td>7</td>
      </tr>
    </table>

    <h2>Ward Break</h2>

    <p>Break Average: 3</p>
    <table border="1">
      <tr>
        <th>Rank</th>
        <th>Amount per Match</th>
      </tr>

      <tr>
        <td>BRONZE</td>
        <td>1</td>
      </tr>
      <tr>
        <td>SILVER</td>
        <td>2</td>
      </tr>
      <tr>
        <td>GOLD</td>
        <td>3</td>
      </tr>
      <tr>
        <td>PLATINUM</td>
        <td>4</td>
      </tr>
      <tr>
        <td>DIAMOND</td>
        <td>5</td>
      </tr>
      <tr>
        <td>MASTER</td>
        <td>6</td>
      </tr>
      <tr>
        <td>CHALLENGER</td>
        <td>7</td>
      </tr>
    </table>
  </div>  
</body>
</html>