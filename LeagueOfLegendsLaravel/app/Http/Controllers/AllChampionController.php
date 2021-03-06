<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
//use Session;
use Cookie;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class AllChampionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        $champions = DB::table('Champion')
            ->select('ChampionName', 'ChampionKey')
            ->orderBy('ChampionKey')
            ->get();

        //$response = new \Illuminate\Http\Response(
         //               view('allChampionsPage')->with('contents', json_encode($champions)))

        //$response->withCookie(cookie('referrer', $request->referrer, 45000));

        //Session::put('champions', json_encode($champions));
        //echo Session::get('champions');
        //Cookie::queue('champions', $champions);
        return view('allChampionsPage')->with('contents', json_encode($champions));
        //return response()->view('allChampionsPage')->json($champions);
    }

/*  old version
    public function show()
    {
        $champions = DB::table('Champion')
            ->select('ChampionName', 'ChampionKey')
            ->orderBy('ChampionKey')
            ->get();

        $contents = $this->getChampionDivTag($champions);
        // self:: or $this->
         // echo $this->getAAA();

        return view('toppage')->with('contents', $contents);
    }
*/
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /* Unneeded function
    private function getChampionDivTag($champions){

      if(count($champions) === 0){
        return "";
      }

      $tmpStr = "<p><div class='championIcon'>";
      $displayNum = 0;

      foreach($champions as $info){

        if($displayNum > 0 && $displayNum % 4 === 0){
            $tmpStr .= "</div></p>";
            $tmpStr .= "<p><div class='championIcon'>";
        }

        $tmpStr .= "<div class='eachChampion col-md-3'>";
        $tmpStr .= "<img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/" .
                    $info->ChampionKey . ".png' />";

        $tmpStr .= "<p>";
        $tmpStr .= $info->ChampionName;
        $tmpStr .= "<br><a href='whenbuy/" . $info->ChampionKey . "/en'>English</a>";
        $tmpStr .= "<br><a href='whenbuy/" . $info->ChampionKey . "/ja'>Japanese</a>";
        $tmpStr .= "</p>";
        $tmpStr .= "</div>";

        $displayNum += 1;
      }

      $tmpStr .= "</div>";

      return $tmpStr;

    }
    */
}
