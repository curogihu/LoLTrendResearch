<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/main', function () {
    return view('main');
});

Route::get('/objective', function () {
    return view('objective');
});
*/
/*
Route::get('/item', function () {
    return view('item');
});
*/

/*
Route::get('/whenbuy', function () {
    return view('itemstatistics');
});
*/

Route::get('/whenbuy/', 'AllChampionController@show');

Route::get('/whenbuy/{championKey}', 'EachChampionController@show');

Route::get('/whenkilled', function () {
    return view('monsterstatistics');
});

Route::get('/wherelane', function () {
    return view('lanestatistics');
});

Route::get('/howmanycs', function () {
    return view('csstatistics');
});

Route::get('/', function() {
    return view('toppage');
});
//Route::get('/riot.txt', 'TextController@riot');
/*
Route::get('/championLane', function () {
    return view('championLane');
});

Route::get('/championLane', function () {
    return view('championLane');
});

Route::get('/top', function() {
    return view('toppage');
});
*/



// these links for importing each summoner data
// convert to Laravel version, ok
Route::get('/import/summoner', function () {
    return view('import.importSummoner');
});

// ok
Route::get('/import/matchlist', function () {
    return view('import.importMatchList');
});

Route::get('/import/matchDetail', function () {
    return view('import.importMatchDetail');
});

Route::get('/import/summonerTier', function () {
    return view('import.importSummonerTier');
});

// these links for to be easily unchanged LoL data
Route::get('/import/champion', function () {
    return view('import.importChampionData');
});

Route::get('/import/item', function () {
    return view('import.importItemData');
});

Route::get('/import/language', function () {
    return view('import.importLanguage');
});

Route::get('main/riot.txt', function(){
  return view('riot.riot');
});







//上記は、プロジェクトの作成後のデフォルト、’/’をWelcomeControllerのindexメソッドにマッピングしている
//Route::get('/', 'WelcomeController@index');

//また、コントローラー全体をパスにマッピングすることも可能 
//以下のようにRoute::controllerメソッドを使用する
//Route::controller('/', 'WelcomeController');
