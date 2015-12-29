<?php

namespace App\Http\Controllers;

use DB; // add
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EachChampionController extends Controller
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
    public function show($championKey, $language)
    {
      $languageId = "";

      if($language !== "en" && $language !== "ja"){
        return view('toppage')->with('contents', "<p>this language isn't supported.</p>");
      }

      switch ($language) {
        case "en":
          $languageId = "en_US";
          break;

        case "ja":
          $languageId = "ja_JP";
          break;
      }

      $championItemLog = DB::select('select c.ChampionName, ' .
                                            'c.ChampionKey, ' .
                                            'i.ItemName, ' .
                                            'i.ItemImage, ' .
                                            'i.ItemDescription, ' .
                                            'i.ItemGoldTotal, ' .
                                            'ibls.AvgMinPurchaseSeconds, ' .
                                            'ibls.NumberOfTimes, ' .
                                            'sum(case when id.ItemDerivationId is null then 0 else 1 end) DerivationNum ' .
                                    'from ItemBuildLogSummary ibls ' .
                                    'inner join Champion c ' .
                                      'on ibls.ChampionId = c.ChampionId ' .
                                    'inner join Item i ' .
                                      'on ibls.ItemId = i.ItemId ' .
                                    'left join ItemDerivation id ' .
                                      'on ibls.ItemId = id.ItemId ' .
                                    'where c.ChampionKey = "' . $championKey . '" ' .
                                    'and i.LanguageId = "' . $languageId . '" ' .
                                    'group by c.ChampionName, ' .
                                              'c.ChampionKey, ' .
                                              'i.ItemName, ' .
                                              'i.ItemImage, ' .
                                              'i.ItemDescription, ' .
                                              'ibls.AvgMinPurchaseSeconds, ' .
                                              'ibls.NumberOfTimes ' .
                                    'order by c.ChampionKey, ' .
                                              'ibls.AvgMinPurchaseSeconds');


      $contents = $this->getItemTableTag($championItemLog, $language);
      // self:: or $this->
       // echo $this->getAAA();

      return view('toppage')->with('contents', $contents);

      //return view('toppage');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    public function getAAA(){
      return "AAA";
    }

    private function getItemTableTag($statistics, $language){

      $tmpStr = "";
      $displayChampion = "";
      $displayNum = 0;

      foreach($statistics as $info){
        if($info->ChampionName !== $displayChampion){

          if($displayNum > 0){
              $tmpStr .= "</table>";
          }

          $displayNum = $displayNum + 1;
          $displayChampion = $info->ChampionName;

          $tmpStr .= "<p><img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/" . $info->ChampionKey . ".png' />" . $info->ChampionName . "</p>";
/*
          $tmpStr .= "<table border='1' align='center'>";
          $tmpStr .= "<tr>";
          $tmpStr .= "<th>image</th>";
          $tmpStr .= "<th>ItemName</th>";
          $tmpStr .= "<th>AvgMinPurchaseTime</th>";
          $tmpStr .= "<th>Frequent</th>";
          $tmpStr .= "<th>Derivation</th>";
          $tmpStr .= "</tr>";
*/
          $tmpStr .= $this->getTableHeader($language);
        }

        $tmpStr .= $this->getItemLogRecord($info, $language);
      }

      $tmpStr .= "</table>";

      return $tmpStr;

    }

    private function getItemLogRecord($info, $language){
      //echo "itemName : " . $info->ItemName . "Derivation: " . $info->DerivationNum . "<br>";
      $priceItem = "";

      $tmpStr = "";
      $tmpTime = $info->AvgMinPurchaseSeconds;

      $tmpStr .= "<tr>";
      $tmpStr .= "<td>";
      $tmpStr .= "<div class='Itemtooltip'>";
      $tmpStr .= "<img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/item/" . $info->ItemImage . "' />";

      $tmpStr .= "<span>";

      switch ($language) {
        case "en":
          $priceItem = "Price: ";
          break;

        case "ja":
          $priceItem = "価格: ";
          break;
      }

      $tmpStr .= $priceItem . $info->ItemGoldTotal . "<br><br>";
      $tmpStr .= $info->ItemDescription;
      $tmpStr .= "</span>";
      $tmpStr .= "</div>";
      $tmpStr .= "</td>";
      $tmpStr .= "<td>" . $info->ItemName . "</td>";
      //$tmpStr .= "<td>" . floor($tmpTime / 60) . "min " . ($tmpTime % 60) . "sec"  . "</td>";
      $tmpStr .= "<td>" . sprintf("%02d", floor($tmpTime / 60)) . ":" . sprintf("%02d", ($tmpTime % 60)) . "</td>";
      $tmpStr .= "<td>" . $info->NumberOfTimes . "</td>";

      if(intval($info->DerivationNum) === 0){
        $tmpStr .= "<td>-</td>";
      }else{
        $tmpStr .= "<td>○</td>";
      }

      $tmpStr .= "</tr>";

      return $tmpStr;
    }

    private function getTableHeader($language){
      $tmpStr = "";

      switch ($language) {
        case "en":
          $tmpStr .= "<table border='1' align='center'>";
          $tmpStr .= "<tr>";
          $tmpStr .= "<th>image</th>";
          $tmpStr .= "<th>ItemName</th>";
          $tmpStr .= "<th>AvgMinPurchaseTime</th>";
          $tmpStr .= "<th>Frequent</th>";
          $tmpStr .= "<th>Derivation</th>";
          $tmpStr .= "</tr>";
          break;

        case "ja":
          $tmpStr .= "<table border='1' align='center'>";
          $tmpStr .= "<tr>";
          $tmpStr .= "<th>画像</th>";
          $tmpStr .= "<th>アイテム名</th>";
          $tmpStr .= "<th>平均最短購入時間</th>";
          $tmpStr .= "<th>回数</th>";
          $tmpStr .= "<th>派生有無</th>";
          $tmpStr .= "</tr>";
          break;
      }

      return $tmpStr;
    }
}
