<?php

namespace App\Http\Controllers;

use DB; // add
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SearchSummonerItemBuildController extends Controller
{
    public function championDisplay(Request $request){
      $summonerName = $request->input('summonerName');
      $summonerNameKey = $this->editSummonerName($summonerName);
      $endMillSeconds = microtime(true) * 1000;

      // 1000 millseconds * 60 seconds * 60 miniutes * 24 hours * 14 days
      $startMillSeconds = $endMillSeconds - (1000 * 60 * 60 * 24 * 14);

      $champions = DB::select("select distinct c.ChampionName, " .
                              "c.ChampionKey " .
                            "from MatchPlayerInfo mpi " .
                            "inner join MatchList ml " .
                              "on mpi.MatchId = ml.MatchId " .
                            "inner join MatchPlayerSetting mps " .
                              "on mpi.MatchId = mps.MatchId " .
                              "and mpi.ParticipantId = mps.ParticipantId " .
                            "inner join Champion c " .
                              "on mps.ChampionId = c.ChampionId " .
                            "where mpi.SummonerNameKey = '" . $summonerNameKey . "' " .
                            "and ml.TimeStamp between " . $startMillSeconds . " and " . $endMillSeconds);

      if(count($champions) === 0){
        return view('researchForm')
                ->with('errorMessage', "<p>Summoner name you inputted is not registerd.</p>");
      }else{

        $contents = $this->getChampionDivTag($champions, $summonerName);

        return view('toppage')
                ->with('contents', $contents);
      }
    }

    public function itemBuildDisplay(Request $request, $championKey, $language){
      $summonerName = $request->input('summonerName');
      $summonerNameKey = $this->editSummonerName($summonerName);

      $endMillSeconds = microtime(true) * 1000;

      // 1000 millseconds * 60 seconds * 60 miniutes * 24 hours * 14 days
      $startMillSeconds = $endMillSeconds - (1000 * 60 * 60 * 24 * 14);

      $championItemLog = DB::select("select tmp.ChampionName, " .
                                            "tmp.ChampionKey, " .
                                            "i.ItemName, " .
                                            "i.ItemImage, " .
                                            "i.ItemDescription, " .
                                            "i.ItemGoldTotal, " .
                                            "count(i.ItemId) NumberOfTimes, " .
                                            "truncate(avg(tmp.MinPurchaseMilliseconds) / 1000, 0) AvgMinPurchaseSeconds, " .
                                            'sum(case when id.ItemDerivationId is null then 0 else 1 end) DerivationNum ' .
                                    "from " .
                                    "( " .
                                      "select ml.MatchId, " .
                                              "c.ChampionName, " .
                                              "c.ChampionKey, " .
                                              "ibl.ItemId, " .
                                              "min(ibl.TimeStamp) MinPurchaseMilliseconds " .
                                      "from MatchPlayerInfo mpi " .
                                      "inner join MatchList ml " .
                                        "on mpi.MatchId = ml.MatchId " .
                                      "inner join ItemBuildLog ibl " .
                                        "on mpi.MatchId = ibl.MatchId " .
                                        "and mpi.ParticipantId = ibl.BuyerId " .
                                      "inner join Champion c " .
                                        "on ibl.ChampionId = c.ChampionId " .
                                      "where mpi.SummonerNameKey = 'jungIerguy' " .
                                        "and ml.TimeStamp between " . $startMillSeconds . " and " . $endMillSeconds . " " .
                                        "and c.ChampionKey = 'Poppy' " .
                                      "group by ml.MatchId, " .
                                                "c.ChampionName, " .
                                                "c.ChampionKey, " .
                                                "ibl.ItemId " .
                                    ") tmp " .
                                    "inner join Item i " .
                                    "on tmp.ItemId = i.ItemId " .
                                    'left join ItemDerivation id ' .
                                      'on tmp.ItemId = id.ItemId ' .
                                    "group by tmp.ItemId " .
                                    "order by AvgMinPurchaseSeconds, " .
                                      "tmp.ItemId");

      if(count($championItemLog) === 0){
        return view('researchForm')
                ->with('errorMessage', '<p>Champion Item build you selected is not registered.</p>');

      }else{
        $contents = $this->getItemTableTag($championItemLog, $language);

        return view('toppage')
                ->with('contents', $contents);
      }
    }

    private function getChampionDivTag($champions, $summonerName){

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

        $tmpStr .= "<div class='eachChampion'>";
        $tmpStr .= "<img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/" .
                    $info->ChampionKey . ".png' />";

        $tmpStr .= "<p>";
        $tmpStr .= $info->ChampionName;
        $tmpStr .= "<br><a href='search/" . $info->ChampionKey . "/en?summonerName=" . $summonerName . "'>English</a>";
        $tmpStr .= "<br><a href='search/" . $info->ChampionKey . "/ja?summonerName=" . $summonerName . "'>Japanese</a>";;
        $tmpStr .= "</p>";
        $tmpStr .= "</div>";

        $displayNum += 1;
      }

      $tmpStr .= "</div>";

      return $tmpStr;

    }

    private function editSummonerName($summonerName){
      $tmp = htmlentities($summonerName, ENT_QUOTES, "UTF-8");
      $tmp = mb_convert_kana($tmp, 'as');
      $tmp = strtolower($tmp);
      $tmp = str_replace(" ", "", $tmp);

      return $tmp;
    }

    private function getItemTableTag($statistics, $language){

      $tmpStr = "";
      $displayChampion = "";
      $displayNum = 0;

      foreach($statistics as $info){
        // whether display champion is changed
        if($info->ChampionName !== $displayChampion){

          if($displayNum > 0){
              $tmpStr .= "</table>";
          }

          $displayNum = $displayNum + 1;
          $displayChampion = $info->ChampionName;

          $tmpStr .= "<p><img src='http://ddragon.leagueoflegends.com/cdn/5.24.1/img/champion/";
          $tmpStr .= $info->ChampionKey . ".png' />" . $info->ChampionName . "</p>";
          $tmpStr .= $this->getTableHeader($language);
        }

        $tmpStr .= $this->getItemLogRecord($info, $language);
      }

      // for last champion table
      $tmpStr .= "</table>";

      return $tmpStr;

    }

    private function getItemLogRecord($info, $language){
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
