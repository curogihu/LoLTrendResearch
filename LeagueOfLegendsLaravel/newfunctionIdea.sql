// base sql
select mps.MatchId, mps.ParticipantId, mps.Role, mps.Lane, c.ChampionKey, c.ChampionName
from MatchPlayerSetting mps
inner join MatchPlayerInfo mpi
on mps.MatchId = mpi.MatchId
and mps.ParticipantId = mpi.ParticipantId
inner join Champion c
on mps.ChampionId = c.ChampionId


// extract my role
select mps.MatchId, mps.ParticipantId, mps.Role, mps.Lane, c.ChampionKey, c.ChampionName
from MatchPlayerSetting mps
inner join MatchPlayerInfo mpi
on mps.MatchId = mpi.MatchId
and mps.ParticipantId = mpi.ParticipantId
inner join Champion c
on mps.ChampionId = c.ChampionId
where mpi.SummonerNameKey = '[InputKey]'

// my opponent side

from [baseSql result]
inner join [myRole result]
on base.MatchId = myRole.MatchId
and base.Role = MyRole.Role
and base.Lane = myRole.Lane
and base.ParticipantId <> myRole.ParticipantId