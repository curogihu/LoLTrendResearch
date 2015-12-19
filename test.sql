SELECT mps.ChampionId, ibl.ItemId, ibl.TimeStamp 
from ItemBuildLog ibl 
inner join MatchPlayerSetting mps 
on ibl.MatchId = mps.MatchId and ibl.BuyerId = mps.ParticipantId

update ItemBuildLog ibl
inner join MatchPlayerSetting mps
on ibl.MatchId = mps.MatchId and ibl.BuyerId = mps.ParticipantId
set ibl.ChampionId = mps.ChampionId
where ibl.ChampionId is null


Item - ItemId, ItemName, ItemDescription, ItemImage, ItemGoldTotal
Champion - ChampionId, ChampionName


ItemBuildLog - ChampionId, ItemId, TimeStamp


// for displaying data
select c.ChampionName, c.ChampionKey, i.ItemName, i.ItemImage, i.ItemDescription,
        ibls.AvgMinPurchaseSeconds, ibls.NumberOfTimes
from ItemBuildLogSummary ibls
inner join Champion c on ibls.ChampionId = c.ChampionId
inner join Item i on ibls.ItemId = i.ItemId


// ItemBuildLogSummary
select tmp.ChampionId, tmp.ItemId, truncate(avg(MinimumPurchaseTime) / 1000, 0) AvgMinPurchaseSeconds, count(tmp.ItemId) as Number
from(
select ibl.ChampionId, ibl.ItemId, min(TimeStamp) MinimumPurchaseTime
from ItemBuildLog ibl
group by ibl.MatchId, ibl.ChampionId, ibl.ItemId
) tmp
group by tmp.ChampionId, tmp.ItemId



create table ItemBuildLogSummary(
  ChampionId int(11),
  ItemId int(11),
  AvgMinPurchaseSeconds int(11),
  NumberOfTimes int(11)
)


select c.ChampionName, c.ChampionKey, i.ItemName, i.ItemImage, i.ItemDescription, ibls.AvgMinPurchaseSeconds, ibls.NumberOfTimes
from ItemBuildLogSummary ibls
inner join Champion c on ibls.ChampionId = c.ChampionId
inner join Item i on ibls.ItemId = i.ItemId
order by c.ChampionKey, ibls.AvgMinPurchaseSeconds