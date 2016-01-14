<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
  Import various League of Legends data via LoL JSON
*/
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

/*
  Display page based on various data in MySQL
*/
Route::get('/', function() {
    return view('toppage')->with('contents', "");
});

Route::get('/whenbuy', 'AllChampionController@show');

Route::get('/whenbuy/{championKey}/{language}', 'EachChampionController@show');

Route::get('/whenkilled', function () {
    return view('monsterstatistics');
});

Route::get('/wherelane', function () {
    return view('lanestatistics');
});

Route::get('/howmanycs', function () {
    return view('csstatistics');
});


Route::get('/form', function () {
    //return view('welcome');
  return view('researchForm')->with('errorMessage', "");
});

Route::post('/register', 'RegisterSummonerController@register');

Route::get('/search', 'SearchSummonerItemBuildController@championDisplay');
Route::get('/search/{championKey}/{language}', 'SearchSummonerItemBuildController@itemBuildDisplay');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
/*
Route::group(['middleware' => ['web']], function () {
    //
});
*/